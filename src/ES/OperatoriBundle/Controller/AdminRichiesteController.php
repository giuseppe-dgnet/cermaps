<?php

namespace ES\OperatoriBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use ES\OperatoriBundle\Entity\Richiesta;
use ES\OperatoriBundle\Form\RichiestaType;

/**
 * Richiesta controller.
 *
 * @Route("/richieste-di-registrazione")
 */
class AdminRichiesteController extends Controller {

    use \Ephp\UtilityBundle\Controller\Traits\BaseController;

    /**
     * Lists all Richiesta entities.
     *
     * @Route("/", name="richiesta")
     * @Template()
     */
    public function indexAction() {
        $entities = $this->getRepository('ESOperatoriBundle:Richiesta')->filtro($this->getParam('filtro'));

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a Richiesta entity.
     *
     * @Route("/{id}/show", name="richiesta_show")
     * @Template()
     */
    public function showAction($id) {
        $entity = $this->find('ESOperatoriBundle:Richiesta', $id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Richiesta entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * @Route("-nuovo-showroom", name="new_showroom")
     * @Template("ESOperatoriBundle:AdminRichieste\newShowroom:new_showroom.html.twig")
     */
    public function newShowroomAction() {
        $entity = new \ES\OperatoriBundle\Entity\Showroom();
        $editForm = $this->createForm(new \ES\OperatoriBundle\Form\ShowroomType(), $entity);
        //$deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
                //'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Richiesta entity.
     *
     * @Route("/{id}/edit", name="richiesta_edit")
     * @Template()
     */
    public function editAction($id) {

        //$em = $this->getDoctrine()->getManager(); //OLD
        //$richiesta = $em->getRepository('ESOperatoriBundle:Richiesta')->find($id); //OLD
        $richiesta = $this->find('ESOperatoriBundle:Richiesta', $id);
        /* @var $richiesta \ES\OperatoriBundle\Entity\Richiesta */
        //var_dump($richiesta);
        $entity = new \ES\OperatoriBundle\Entity\Showroom();

        $entity->setRagioneSociale($richiesta->getRagioneSociale());
        $entity->setCodiceFiscale($richiesta->getCodiceFiscaleAzienda());
        $entity->setAttivitaPrincipale($richiesta->getAttivitaPrincipale());
        //$entity->setUser($richiesta->getReferente());
        $entity->setPartitaIva($richiesta->getPartitaIva());
        $entity->setTelefono($richiesta->getTelefono());
        $entity->setEmail($richiesta->getEmail());
        $entity->setEmailPec($richiesta->getEmailPec());
        $entity->setSito($richiesta->getSitoWeb());
        $entity->setFax($richiesta->getFax());
        $entity->setIndirizzo($richiesta->getIndirizzo());
        $entity->setImpianto($richiesta->getImpianto());
        $entity->setDiscarica($richiesta->getDiscarica());
        $entity->setRaccoglitore($richiesta->getRaccoglitore());
        $entity->setTrasportatore($richiesta->getTrasportatore());
        $entity->setLaboratorio($richiesta->getLaboratorio());
        $entity->setServizi($richiesta->getServizi());
        $entity->setDemolizioni($richiesta->getDemolizioni());
        $entity->setSpurghi($richiesta->getSpurghi());
        $entity->setBonifiche($richiesta->getBonifiche());
        $entity->setRottamazione($richiesta->getRottamazione());
        $entity->setRaee($richiesta->getRaee());
        $entity->setOlioMinerale($richiesta->getOlioMinerale());
        $entity->setOlioVegetale($richiesta->getOlioVegetale());

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Richiesta entity.');
        }

        $editForm = $this->createForm(new \ES\OperatoriBundle\Form\ShowroomType(), $entity);
        //$deleteForm = $this->createDeleteForm($id);

        return array(
            'id' => $id,
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
                //'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * @Route("-salva-utenza-showroom/{id}", name="salva_utenza_showroom")
     */
    public function SalvaUtenzaShowroomAction($id) {
        $em = $this->getEm();
        $request = $this->getRequest();
        $form_data = $request->get('form_crea_showroom');
        $_sr = $this->getRepository('ESOperatoriBundle:Showroom');
        /* @var $_rif \ES\OperatoriBundle\Entity\ShowroomRepository */
        $entity = $_sr->findOneBy(array('codice_fiscale' => $form_data["codice_fiscale"]));
        if(!$entity) {
            $entity = new \ES\OperatoriBundle\Entity\Showroom();
        } else {
            $entity->setAnga(null);
            $entity->getCer()->clear();
            $entity->getCerCp()->clear();
            $entity->getcerTrattati()->clear();
            $entity->getServiziSr()->clear();
            $entity->getTipologie()->clear();
            $entity->getRd()->clear();
            $entity->getMps()->clear();
            $entity->getCategorie()->clear();
        }
        $editForm = $this->createForm(new \ES\OperatoriBundle\Form\ShowroomType(), $entity);
        $_geo = $this->getRepository('EphpGeoBundle:GeoNames');
        /* @var $_geo \Ephp\GeoBundle\Entity\GeoNamesRepository */
        $_cer = $this->getRepository('ESCerMapBundle:Cer\Cer');
        /* @var $_cer \ES\CerMapBundle\Entity\Cer\CerRepository */
        $_rif = $this->getRepository('ESCerMapBundle:Recuperabili\Rifiuto');
        /* @var $_rif \ES\CerMapBundle\Entity\Recuperabili\RifiutoRepository */
        $_cat = $this->getRepository('ESOperatoriBundle:ShowroomCategoria');
        /* @var $_rif \ES\OperatoriBundle\Entity\ShowroomCategoriaRepository */
        $_ric = $this->getRepository('ESOperatoriBundle:Richiesta');
        /* @var $_rif \ES\OperatoriBundle\Entity\RichiestaRepository */

        $tmp = $form_data;
        $cer_trattati = $tmp['elenco_cer'];
        if ($request->isMethod('POST')) {
            $editForm->bind($request);
            if ($editForm->isValid()) {
                try {
                    $em->beginTransaction();

                    if ($id == 'nuovo') {
                        $nuova_richiesta = $_ric->findOneBy(array('codice_fiscale_azienda' => $form_data["codice_fiscale"]));
                        if(!$nuova_richiesta) {
                            $nuova_richiesta = new Richiesta();
                        }
                        $nuova_richiesta->setRagioneSociale($form_data["ragione_sociale"]);
                        $nuova_richiesta->setCodiceFiscaleAzienda($form_data["codice_fiscale"]);
                        $nuova_richiesta->setReferente("inserito da agenzia");
                        $nuova_richiesta->setPartitaIva(isset($form_data["partita_iva"]) ? $form_data["partita_iva"] : "inserito da agenzia");
                        $nuova_richiesta->setIndirizzo($form_data["indirizzo"]);
                        $nuova_richiesta->setAttivitaPrincipale($form_data["attivita_principale"]);


                        $nuova_richiesta->setEmail($form_data["email"]);
                        $nuova_richiesta->setTelefono($form_data["telefono"]);

                        $nuova_richiesta->setImpianto(isset($form_data["impianto"]) ? 1 : 0);
                        $nuova_richiesta->setDiscarica(isset($form_data["discarica"]) ? 1 : 0);
                        $nuova_richiesta->setRaccoglitore(isset($form_data["raccoglitore"]) ? 1 : 0);
                        $nuova_richiesta->setTrasportatore(isset($form_data["trasportatore"]) ? 1 : 0);
                        $nuova_richiesta->setLaboratorio(isset($form_data["laboratorio"]) ? 1 : 0);
                        $nuova_richiesta->setServizi(isset($form_data["servizi"]) ? 1 : 0);
                        $nuova_richiesta->setDemolizioni(isset($form_data["demolizioni"]) ? 1 : 0);
                        $nuova_richiesta->setSpurghi(isset($form_data["spurghi"]) ? 1 : 0);
                        $nuova_richiesta->setBonifiche(isset($form_data["bonifiche"]) ? 1 : 0);
                        $nuova_richiesta->setRottamazione(isset($form_data["rottamazione"]) ? 1 : 0);
                        $nuova_richiesta->setRaee(isset($form_data["raee"]) ? 1 : 0);
                        $nuova_richiesta->setOlioMinerale(isset($form_data["olio_minerale"]) ? 1 : 0);
                        $nuova_richiesta->setOlioVegetale(isset($form_data["olio_vegetale"]) ? 1 : 0);


                        $em->persist($nuova_richiesta);
                        $em->flush();
                        $id = $nuova_richiesta->getId();
                    }

                    $detail = json_decode($entity->getAnga());
                    if (isset($detail->anga)) {
                        $anga = $detail->anga;
                        $entity->setListUpdateAt(new \DateTime(date("c", $anga->list_update)));
                        $entity->setDetailUpdateAt(new \DateTime(date("c", $anga->detail_update)));
                        $entity->setHasCer($anga->has_cer);
                        $entity->setHasCerCp($anga->has_cer_cp);
                        $entity->setHasTipologie($anga->has_tipologie);
                        $comune = $_geo->ricercaComune($detail->sede->comune, $detail->sede->provincia, $detail->sede->regione, 'IT');
                        $entity->setComune($comune);
                        if (!$entity->getLatitudine() || !$entity->getLongitudine()) {
                            $geo = $this->geoCode($entity->getIndirizzo(), $comune);
                            $entity->setLatitudine($geo['lat']);
                            $entity->setLongitudine($geo['lon']);
                        }
                        if ($anga->cer && $anga->cer{0} != '<') {
                            $cers = explode('-', $anga->cer);
                            $codici = array();
                            foreach ($cers as $cer) {
                                $__cer__ = $_cer->findOneBy(array('codice' => trim(str_replace('.', '', $cer))));
                                if(!in_array($__cer__->getCodice(), $codici)) {
                                    $codici[] = $__cer__->getCodice();
                                    $entity->addCer($__cer__);
                                }
                            }
                        }
                        if ($anga->cercp && $anga->cercp{0} != '<') {
                            $cerscp = explode('-', $anga->cercp);
                            $codici = array();
                            foreach ($cerscp as $cer) {
                                $__cer__ = $_cer->findOneBy(array('codice' => trim(str_replace('.', '', $cer))));
                                if(!in_array($__cer__->getCodice(), $codici)) {
                                    $codici[] = $__cer__->getCodice();
                                    $entity->addCerCp($__cer__);
                                }
                            }
                        }
                        if ($anga->tipologie && $anga->tipologie{0} != '<') {
                            $tipologie = explode('-', $anga->tipologie);
                            foreach ($tipologie as $tipologia) {
                                $tipologia = $_rif->ricercaDaSigla(trim($tipologia));
                                if ($tipologia) {
                                    /* @var $tipologia \ES\CerMapBundle\Entity\Recuperabili\Rifiuto */
                                    $entity->addTipologie($tipologia);
                                    foreach ($tipologia->getAttivitaRecupero() as $attivita) {
                                        /* @var $attivita \ES\CerMapBundle\Entity\Recuperabili\AttivitaRecupero */
                                        foreach ($attivita->getMps() as $mps) {
                                            $entity->addMps($mps);
                                        }
                                        foreach ($attivita->getRecupero() as $rd) {
                                            $entity->addRd($rd);
                                        }
                                    }
                                }
                            }
                        }
                        $categorie = $anga->lista_categorie;
                        foreach ($categorie as $categoria) {
                            $entity->addCategorie($_cat->ricercaDaDati($categoria));
                        }
                    } else {
                        $entity->setListUpdateAt(new \DateTime());
                        $entity->setDetailUpdateAt(new \DateTime());
                        $localita = $entity->getComuneTestuale();
                        $comune = $_geo->ricercaComune($this->separaComune($localita), $this->separaProvincia($localita), null, 'IT');
                        /* @var $comune \Ephp\GeoBundle\Entity\GeoNames */
                        if(!$comune) {
                            $comune = $_geo->find(3175395);
                        }
                        $entity->setComune($comune);

                        $geo = $this->geoCode($entity->getIndirizzo(), $localita);
                        if(isset($geo['lat']) && $geo['lat']) {
                            $entity->setLatitudine($geo['lat']);
                            $entity->setLongitudine($geo['lon']);
                        } else {
                            $entity->setLatitudine($comune->getLatitude());
                            $entity->setLongitudine($comune->getLongitude());
                        }
                    }

                    if ($cer_trattati != '') {
                        $cerstrattati = explode(',', $cer_trattati);
                        $codici = array();
                        foreach ($cerstrattati as $cer) {
                            $__cer__ = $_cer->findOneBy(array('codice' => trim(str_replace('.', '', $cer))));
                            if($__cer__) {
                                if(!in_array($__cer__->getCodice(), $codici)) {
                                    $codici[] = $__cer__->getCodice();
                                    $entity->addcerTrattati($__cer__);
                                    $entity->setHasCerTrattati(true);
                                }
                            }
                        }
                    }
                    $entity->setSeo(new \Ephp\UtilityBundle\Seo\Seo($em));
                    $entity->generateSeo();
                    $em->persist($entity);
                    $em->flush();
                    foreach ($entity->getCategorie() as $categoria) {
                        /* @var $categoria \ES\OperatoriBundle\Entity\ShowroomCategoria */
                        $categoria->setShowroom($entity);
                        $em->persist($categoria);
                        $em->flush();
                    }

                    $richiesta = $this->find('ESOperatoriBundle:Richiesta', $id);
                    /* @var $richiesta \ES\OperatoriBundle\Entity\Richiesta */
                    $richiesta->setShowroom($entity);
                    $em->persist($richiesta);
                    $em->flush();
                    $em->commit();
                } catch (\Exception $e) {
                    $em->rollback();
                    throw $e;
                }
                return $this->redirect($this->generateUrl('crea_utente', array('id' => $id)));
            }
        }
    }

    private function separaComune($localita) {
        return trim(substr($localita, 0, strpos($localita, '(')));
    }

    private function separaProvincia($localita) {
        return trim(substr($localita, strpos($localita, '(') + 1, 2));
    }

    /**
     * Displays a form to edit an existing Richiesta entity.
     *
     * @Route("/crea-utente/{id}", name="crea_utente")
     * @Template("ESOperatoriBundle:CreaUtente:crea_utente.html.twig")
     */
    public function creaUtenteAction($id) {
        $request = $this->get('request');
        $richiesta = $this->find('ESOperatoriBundle:Richiesta', $id);

//        var_dump($richiesta);
//        exit;

        return array(
            'email' => $richiesta->getEmail(),
            'id_richiesta' => $richiesta->getId(),
            'id_showroom' => $richiesta->getShowroom()->getId(),
            'referente' => $richiesta->getReferente(),
            'ruolo' => $richiesta->getRuolo()
        );
//        $user = new \ES\UserBundle\Entity\User();
//        
//        $form = $this->createForm(new \ES\UserBundle\Form\Type\RegistrationFormType(), $user);
//        //$form = $this->createForm(new \ES\UserBundle\Form\UserType(), $user);
//        return array(
//            'form' => $form->createView(),
//        );
    }

    /**
     * Edits an existing Richiesta entity.
     *
     * @Route("/{id}/update", name="richiesta_update")
     * @Method("POST")
     * @Template("ESOperatoriBundle:Richiesta:edit.html.twig")
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ESOperatoriBundle:Richiesta')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Richiesta entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new RichiestaType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('richiesta_edit', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Richiesta entity.
     *
     * @Route("/{id}/update-note", name="richiesta_update_note")
     * @Method("POST")
     * @Template("ESOperatoriBundle:Richiesta:edit.html.twig")
     */
    public function updateNoteAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ESOperatoriBundle:Richiesta')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Richiesta entity.');
        }

        $entity->setNote($request->get('note'));
        $em->persist($entity);
        $em->flush();

        return new \Symfony\Component\HttpFoundation\Response('ok');
    }

    /**
     * Deletes a Richiesta entity.
     *
     * @Route("/{id}/delete", name="richiesta_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ESOperatoriBundle:Richiesta')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Richiesta entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('richiesta'));
    }

    private function createDeleteForm($id) {
        return $this->createFormBuilder(array('id' => $id))
                        ->add('id', 'hidden')
                        ->getForm()
        ;
    }

}
