<?php

namespace ES\CerMapBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/")
 */
class CerSiteController extends Controller {

    use \Ephp\UtilityBundle\Controller\Traits\BaseController;

    /**
     * Mostra l'elenco delle classi CER
     * La pagina deve dare la possibilità di interazioni AJAX per vedere sotto classi e categorie
     * @Route("/", name="homepage")
     * @Template()
     */
    public function indexAction() {
        return array(
            'cers' => $this->findBy('ESCerMapBundle:Cer\Cer', array(), array('id' => 'asc')),
        );
    }
    /**
     * Mostra l'elenco delle classi CER
     * La pagina deve dare la possibilità di interazioni AJAX per vedere sotto classi e categorie
     * @Route("/partial", name="partial")
     * @Template()
     */
    public function partialAction() {
        return array(
            'cers' => $this->findBy('ESCerMapBundle:Cer\Cer', array(), array('id' => 'asc')),
        );
    }

}
