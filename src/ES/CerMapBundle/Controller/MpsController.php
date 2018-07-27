<?php

namespace ES\CerMapBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use EcoSeekr\Bundle\WebBundle\Functions\Funzioni;

/**
 * @Route("/materie-prime-seconde")
 */
class MpsController extends Controller {

    use \Ephp\UtilityBundle\Controller\Traits\BaseController;
    
    /**
     * @Route("/", name="es_mps_categorie")
     * @Template()
     */
    public function categorieAction() {
        $_categorie = $this->getRepository('ESCerMapBundle:Mps\Categoria');
        $categorie = $_categorie->findBy(array(), array('nome' => 'ASC'));
        return array('categorie' => $categorie);
    }

    /**
     * @Route("/ajax", name="es_mps_categorie_ajax")
     * @Template()
     */
    public function indexAjaxAction() {
        return $this->indexAction();
    }

    /**
     * @Route("/per/{slug}", name="es_mps_categoria")
     * @Template()
     */
    public function categoriaAction($slug) {
        $_categorie = $this->getRepository('ESCerMapBundle:Mps\Categoria');
        $categoria = $_categorie->findOneBy(array('slug' => $slug));
        $categorie = $_categorie->findBy(array(), array('nome' => 'ASC'));
        return array(
            'categorie' => $categorie,
            'categoria' => $categoria,
        );
    }

    /**
     * @Route("/{slug}", name="es_mps")
     * @Template()
     */
    public function mpsAction($slug) {
//        $geo = $this->getRequest()->getSession()->get('posizione', false);

        $_materia = $this->getRepository('ESCerMapBundle:Mps\Mps');
        $_categorie = $this->getRepository('ESCerMapBundle:Mps\Categoria');
        $materia = $_materia->findOneBy(array('slug' => $slug));
        $categoria = $materia->getCategoria();
        $categorie = $_categorie->findBy(array(), array('nome' => 'ASC'));

        // Tiro fuori ggli showroom
//        $_sr = $em->getRepository('EcoSeekr\Bundle\ShowRoomBundle\Entity\ShowRoom');
//        $out = $_sr->cerca($materia->getMateria(), $geo, array('azienda' => true, 'professionista' => true), array('mps' => true, 'comune' => true), array('mps' => true));
        return array(
//            'results' => $out,
            'materia' => $materia,
            'categoria' => $categoria,
            'categorie' => $categorie,
            
            'registra' => true,
            'messaggio_errore' => "Nessun operatore acquista o vende {$materia->getMateria()}",
        );
    }

    /**
     * @Route("-cerca.{_format}", name="es_mps_cerca", defaults={"_format"="json"},requirements={"_format"="html|json"})
     * @Template()
     */
    public function cercaMpsAction() {
        $request = $this->getRequest();
        $_sr = $this->getRepository('ESCerMapBundle:Mps\Mps');
        /* @var $_sr \ES\CerMapBundle\Entity\Mps\MpsRepository */
        $out = $_sr->cerca($request->get('term'));
        $parola_ricercata = $request->get('term');
        switch ($this->getRequest()->get('_format')) {
            case 'html':
                if (count($out) > 0) {
                    return array('response' => $out, 'parola_ricercata' => $parola_ricercata);
                } else {
                    return $this->redirect($this->generateUrl('cerca_globale', array('parola_ricercata' => $parola_ricercata)));
                }
            default:
                return array(
                    'json' => json_encode($out)
                );
        }
    }
    
    /**
     * @Route("/cerca/ajax", name="es_mps_cerca_ajax")
     * @Template()
     */
    public function cercaAjaxAction() {
        $request = $this->getRequest();
        $nome = $request->get('term');
        $em = $this->getEm();
        $_mps = $em->getRepository('EcoSeekr\Bundle\MPSBundle\Entity\MateriaPrimaSeconda');
        $json = $_mps->cercaAutocomplete($nome);
        echo json_encode($json);
        exit;
    }

}
