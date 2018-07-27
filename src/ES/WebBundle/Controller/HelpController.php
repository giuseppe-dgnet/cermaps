<?php

namespace ES\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/aiuto")
 */
class HelpController extends Controller {
    
    use \Ephp\UtilityBundle\Controller\Traits\BaseController,
        \Ephp\GeoBundle\Controller\Traits\BaseGeoController,
        \ES\CerMapBundle\Controller\Traits\BaseCerMapController;
    
    /**
     * @Route("/come-funziona", name="come_funziona", options={"sitemap" = true})
     * @Template()
     */
    public function comeFunzionaAction() {
        return array();
    }
    /**
     * @Route("/scegliere-ecoseekr", name="scegliere_ecoseekr", options={"sitemap" = true})
     * @Template()
     */
    public function scegliereEcoseekrAction() {
        return array();
    }
    /**
     * @Route("/costi-e-funzionalita", name="costi_funzionalita", options={"sitemap" = true})
     * @Template()
     */
    public function costiFunzionalitaAction() {
        return array();
    }
    
     /**
     * @Route("/statistiche", name="statistiche", options={"sitemap" = true})
     * @Template()
     */
    public function statisticheAction() {
        $map = $this->getSimpleMap();
        $map->setStylesheetOption('width', '100%');
        $map->setStylesheetOption('height', '350px');
        return array(
            'map' => $map
        );
    }

}
