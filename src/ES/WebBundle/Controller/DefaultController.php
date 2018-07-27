<?php

namespace ES\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use ES\CerMapBundle\Controller\MapController;
use Symfony\Component\HttpFoundation\Cookie;

class DefaultController extends Controller {

    use \Ephp\UtilityBundle\Controller\Traits\BaseController;

    /**
     * @Route("/", name="homepage", options={"sitemap" = true})
     * @Template("ESCerMapBundle:Map:index.html.twig")
     */
    public function indexAction() {
        $mapController = new MapController();
        $mapController->setContainer($this->container);
        $out = $mapController->indexAction();


        //$out['twig'] = "ESWebBundle:Default:richiesta.html.twig";

        return $out;
    }

    /**
     * @Route("/imposta-cookies-trip", name="imposta_cookies_trip", options={"expose"=true})
     */
    public function impostaCookiesTripAction() {

        try {
            $cookie = new Cookie('trip_completo', true, time() + 3600 * 24 * 7);
            $response = new \Symfony\Component\HttpFoundation\Response();
            $response->headers->setCookie($cookie);
            $response->send();

            //if ($request->isXmlHttpRequest()) {
            //$request->get('NAME DEL CAMPO');        

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

    /**
     * @Route("/richiesta", name="homepage_richiesta")
     * @Template("ESCerMapBundle:Map:index.html.twig")
     */
    public function richiestaAction() {
        $mapController = new MapController();
        $mapController->setContainer($this->container);
        $out = $mapController->indexAction();

        $out['twig'] = "ESWebBundle:Default:richiesta.html.twig";

        return $out;
    }

}
