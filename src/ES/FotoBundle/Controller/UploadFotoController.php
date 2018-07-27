<?php

namespace ES\FotoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Description of UploadFotoController
 *
 * @author Corrado
 * 
 * @Route("/upload")
 */
class UploadFotoController extends Controller {

    /**
     * 
     * Genera un numero unico randomico per id_cartella_upload
     * Perche il bundle delle immaigni pretende delle cartelle uniche
     * anche per lo stesso utente
     * 
     * @Route("/{didascalia}/{redirect}", name="upload_foto")
     * @Template("ESFotoBundle:CaricaFoto:upload_foto.html.twig")
     */
    public function indexAction($didascalia = false, $redirect = null) {

        $user = $this->get('security.context')->getToken()->getUser();
        $percorsoFile = $user->getShowroom()->getSlug() . '--' . sha1(uniqid($user->getEmail(), true));

        return array(
            'id_cartella_upload' => $percorsoFile,
            'profilo' => $user->getShowroom(),
            'didascalia' => $didascalia,
            'redirect' => $redirect
        );
    }
    
    /**
     * viene chiamata tramite Js e salva le foto nella cartella PERMANENTE (cambiato il comportamento, non esiste piu Temp
     * Ecco $immagine ovvero cosa restituisce il servizio
     * var_dump(json_decode($immagine));
     * <b>array</b> <i>(size=1)</i>
      0 <font color='#888a85'>=&gt;</font>
      <b>object</b>(<i>stdClass�K�c���</i>)[<i>1009</i>]
      <i>public</i> 'name' <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'4177ff6ec8eaf58b9382787daec26bce8ab17a57.png'</font> <i>(length=44)</i>
      <i>public</i> 'size' <font color='#888a85'>=&gt;</font> <small>int</small> <font color='#4e9a06'>418453</font>
      <i>public</i> 'type' <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'image/png'</font> <i>(length=9)</i>
      <i>public</i> 'url' <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'/uploads/attachments/solangione--c14704c810363ca96228f4bf9fd8c98143b4c475/originals/4177ff6ec8eaf58b9382787daec26bce8ab17a57.png'</font> <i>(length=128)</i>
      <i>public</i> 'thumbnail_url' <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'/uploads/attachments/solangione--c14704c810363ca96228f4bf9fd8c98143b4c475/thumbnails/4177ff6ec8eaf58b9382787daec26bce8ab17a57.png'</font> <i>(length=129)</i>
      <i>public</i> 'profilo_url' <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'/uploads/attachments/solangione--c14704c810363ca96228f4bf9fd8c98143b4c475/profilo/4177ff6ec8eaf58b9382787daec26bce8ab17a57.png'</font> <i>(length=126)</i>
      <i>public</i> 'small_url' <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'/uploads/attachments/solangione--c14704c810363ca96228f4bf9fd8c98143b4c475/small/4177ff6ec8eaf58b9382787daec26bce8ab17a57.png'</font> <i>(length=124)</i>
      <i>public</i> 'medium_url' <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'/uploads/attachments/solangione--c14704c810363ca96228f4bf9fd8c98143b4c475/medium/4177ff6ec8eaf58b9382787daec26bce8ab17a57.png'</font> <i>(length=125)</i>
      <i>public</i> 'large_url' <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'/uploads/attachments/solangione--c14704c810363ca96228f4bf9fd8c98143b4c475/large/4177ff6ec8eaf58b9382787daec26bce8ab17a57.png'</font> <i>(length=124)</i>
      <i>public</i> 'delete_url' <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'http://sn/app_dev.php/upload-upload-img?editId=solangione--c14704c810363ca96228f4bf9fd8c98143b4c475?file=4177ff6ec8eaf58b9382787daec26bce8ab17a57.png'</font> <i>(length=149)</i>
      <i>public</i> 'delete_type' <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'DELETE'</font> <i>(length=6)</i>
      </pre>
     * @Route("-upload-img", name="upload_img")
     * @Template("ESFotoBundle:CaricaFoto:upload_foto.html.twig")
     */
    public function caricaAction() {

        if ($this->get('request')->isXmlHttpRequest()) {
            $editId = $this->getRequest()->get('editId'); //ID CARTELLA
            $user = $this->get('security.context')->getToken()->getUser();
            //Se vengo da una cancellazione di un'immagine appena uppata, non entra nel classico servizio, ma entro nel servizio
            //che mi sono creato io, perche voglio tenere traccia almeno di un'immagine
            if ($this->get('request')->getMethod() !== "DELETE") {
                $immagine = $this->get('punk_ave.file_uploader')->handleFileUpload(array('folder' => 'attachments/' . $editId));
            } else {
                $em = $this->getEm();
                $fotos = $em->getRepository('\ES\FotoBundle\Entity\Foto')->findOneBy(array('nome' => $this->getRequest()->get('file'), 'showroom' => $user->getShowroom()));

                //Elimino le foto Fisicamente ma tengo sempre la originals per pararci il culetto
                foreach ($this->get('gestioneImmagini')->getSizes() as $version => $options) {
                    $file = $fotos->getUploadRootDir() . "/" . $fotos->getPath() . "/" . $options["folder"] . "/" . $fotos->getNome();
                    unlink($file);
                }

                $fotos->setFotoEliminata(true);

                try {
                    $em->beginTransaction();
                    $em->persist($fotos);
                    $em->flush();
                    $em->commit();

                    $out_json = array(
                        'status' => "OK",
                    );
                } catch (\Exception $e) {
                    $out_json = array(
                        'status' => "KO",
                    );
                }

                return new \Symfony\Component\HttpFoundation\Response(json_encode($out_json));
            }


            $em = $this->getEm();
            $em->beginTransaction();
            try {
                foreach (json_decode($immagine) as $key => $value) {

                    if (!isset($value->error)) { //Controllo che sia un File che deve essere salvato nel DB
                        $foto = new \ES\FotoBundle\Entity\Foto();
                        $foto->setNome($value->name); //oggetto stdClass, vedi sui commenti come è fatto
                        $foto->setPath($editId);
                        $foto->setShowroom($user->getShowroom());
                        $foto->getShowroom()->setLogo($value->profilo_url);
                        $em->persist($foto);
                        $em->flush();
                    } else {
                        exit(0);
                        //return;
                    }
                }

                $em->commit();
            } catch (\Exception $e) {
                
            }
            exit(0); //Perche restituisce un Json
            //return new \Symfony\Component\HttpFoundation\Response(json_encode($out_json));
        } else {
            //non è una chiamata Ajax
            return new RedirectResponse($this->container->get('router')->generate('homepage'));
        }
    }

    /**
     * Salva in blocco i form delle nuove foto caricate
     * 
     * mi arriva un array da una funzione ajax che si trova in 
     * FotoBundle/Resources/public/js/upload_immagine.js
     * mi arrivano piu array arrayNomeFoto,arrayTitoloFoto, arrayDescrizioneFotoarrayProfiloFoto
     * 
     * 
     * @todo Sanitize sui titoli
     * 
     * @Route("-salva-blocco-foto-nuove", name="salva_blocco_foto_nuove")
     */
    public function salvaBloccoFotoNuoveAction() {
        $em = $this->getEm();
        $user = $this->get('security.context')->getToken()->getUser();
        $request = $this->get('request');

        if ($request->isXmlHttpRequest()) {

            try {

                /**
                 * Il problema che lui non associa l'id dell'array delle opzioni messe nel mio form
                 * all'id delle foto 
                 */
                $em->beginTransaction();

                foreach ($this->getRequest()->get('arrayNomeFoto') as $key => $nomeFoto) {
                    
                    $foto = $em->getRepository('\ES\FotoBundle\Entity\Foto')->findOneBy(array('nome' => $nomeFoto));

                    $foto->setNome($nomeFoto);
                    $foto->setTitolo($this->getRequest()->get('arrayTitoloFoto')[$key]);
                    $foto->setDescrizione($this->getRequest()->get('arrayDescrizioneFoto')[$key]);

                    //metto come stringa perche il data dell'ajax me lo converte stringa...'rtacci sua
                    if ($this->getRequest()->get('arrayProfiloFoto')[$key] == 'true') {
                        $foto_profilo = $em->getRepository('\ES\FotoBundle\Entity\Foto')->getFotoProfilo($this->get('security.context')->getToken()->getUser()->getShowroom()->getId());

                        if ($foto_profilo != null) {
                            $foto_profilo->setFotoProfilo(false);
                            $em->persist($foto_profilo);
                            $em->flush();
                        }

                        $foto->setFotoProfilo(true);
                    }
                    $foto->setFotoPrivata($this->getRequest()->get('arrayPrivataFoto')[$key] == 'true' ? 1 : 0);

                    $foto->setProfilo($user->getShowroom());
                    $em->persist($foto);
                    $em->flush();
                }

                $out_json = array(
                    'status' => "OK",
                );

                $em->commit();
            } catch (\Exception $e) {
                $out_json = array(
                    'status' => "KO"
                    //'e' => $e->getMessage()
                );
                $em->rollback();
            }

            return new \Symfony\Component\HttpFoundation\Response(json_encode($out_json));
        } else {

            //return new RedirectResponse($this->container->get('router')->generate('modifica_profilo'));
        }
    }

    /**
     * @return \Doctrine\ORM\EntityManager 
     */
    private function getEm() {
        return $this->getDoctrine()->getEntityManager();
    }

    /* ---------------------------------- 
     * 
     * Il Crop Viene chiamato se selezioniamo foto profilo dalle foto che uppiamo
     * Una volta salvato chiamo il servizio passando l'array delle coordinate
     * Il servizio guarda se ci sono le coordinate, quindi crea un'immagine secondo tali coordinate
     * e l'immagine viene salvata all'interno della cartella profilo all'interno delle altre cartelle dove 
     * si trovano le foto provvisorie ( origianls, small, thumbnails,PROFILO, large)
     * 
     * controllo se nel percorso della cartella esiste la stringe tmp/ questo vuol dire che vengo da delle foto
     * temporanea, ovvero da foto appena caricate, se invece questa stringa non si trova all'interno di tale percorso
     * vuol dire che sto modificando delle foto che ho gia caricato in precedenza
     * 
     * ---------------------------------- */

    /**
     * @Route("-croppa_foto", name="croppa_foto")
     */
    public function croppaFotoAction() {

        $request = $this->get('request');
        if ($request->isXmlHttpRequest()) {

            $em = $this->getEm();

            //coordinate per il crop che passo poi al servizio
            $arrayCoo = [
                "w" => $this->getRequest()->get("w"),
                "h" => $this->getRequest()->get("h"),
                "x" => $this->getRequest()->get("x"),
                "y" => $this->getRequest()->get("y"),
            ];

            $user = $this->get('security.context')->getToken()->getUser();

            //Percorso della cartella 
            $percorso_cartella = $this->getRequest()->get("percorso");

            //verifico se sono nuove foto oppure vengo da delle foto caricate in precedenza
            $prima_occorrenza = strpos($percorso_cartella, "tmp/");

            $src = $this->getRequest()->get("name_originale");

            //butta il contenuto dell'immagine dentro a una variabile (senno si incazza)
            file_put_contents($src, file_get_contents(__DIR__ . '/../../../../web' . $this->getRequest()->get("immagine_originale")));

            if ($prima_occorrenza === false) {
                $percoso = '';
            } else {
                $percoso = 'tmp';
            }

            $this->get('gestioneImmagini')->handleFileUpload($src, array('folder' => $percoso . '/attachments/' . $percorso_cartella, 'coordinate' => $arrayCoo));

            //Azzero la foto profilo
            $foto_profilo = $em->getRepository('\ES\FotoBundle\Entity\Foto')->getFotoProfilo($this->get('security.context')->getToken()->getUser()->getShowroom()->getId());
            $nuova_foto_profilo = $em->getRepository('\ES\FotoBundle\Entity\Foto')->findOneBy(array('nome' => $src));

            try {
                $em->beginTransaction();
                if ($foto_profilo != null) {
                    $foto_profilo->setFotoProfilo(false);
                    $em->persist($foto_profilo);
                    $em->flush();
                }
                $nuova_foto_profilo->setFotoProfilo(true);
                $em->persist($nuova_foto_profilo);
                $em->flush();
                $em->commit();
            } catch (\Exception $e) {
                $em->rollback();                
                $out_json = array(
                    'status' => "KO",                    
                );
                return new \Symfony\Component\HttpFoundation\Response(json_encode($out_json));
            }

            $out_json = array(
                'status' => "OK",
                'croppata' => '/uploads/' . $percoso . '/attachments/' . $percorso_cartella . '/profilo/' . $src //=> Questo è il percorso se la vogliamo far vedere una volta croppata
            );
            return new \Symfony\Component\HttpFoundation\Response(json_encode($out_json));
        }
        exit;
    }
    
    


}
