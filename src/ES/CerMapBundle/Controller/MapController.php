<?php

namespace ES\CerMapBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Cookie;

/**
 * @Route("/")
 */
class MapController extends Controller {

    use \Ephp\UtilityBundle\Controller\Traits\BaseController,
        \Ephp\GeoBundle\Controller\Traits\BaseGeoController,
        Traits\BaseCerMapController;

    /**
     * @Route("/", name="cermap")
     * @Template()
     */
    public function indexAction() {
        $geo_session = $this->getRequest()->getSession()->get('posizione', array());
        $this->getRequest()->getSession()->set('cermap.data', 0);
        $dist = 100;
        
        $request = $this->get('request');
        $value = $request->cookies->get('trip_completo'); //$media_id è il nome del cookies

        return array(
            'map' => isset($geo_session['lat']) ? $this->getCerMap($geo_session['lat'], $geo_session['lon']) : $this->getCerMap(),
            'dist' => $dist,
            'geo' => !isset($geo_session['luogo']),
            'luogo' => isset($geo_session['luogo']) ? $geo_session['luogo'] : 'Italia',
            'lat' => isset($geo_session['lat']) ? $geo_session['lat'] : 41.87194,
            'lon' => isset($geo_session['lon']) ? $geo_session['lon'] : 12.56738,
            'trip_completo' => $value
        );
    }

}
