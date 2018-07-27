<?php

namespace ES\OperatoriBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * Richiesta controller.
 *
 * @Route("/showroom")
 */
class WsController extends Controller {

    use \Ephp\UtilityBundle\Controller\Traits\BaseController;

    /**
     * Displays a form to create a new Richiesta entity.
     *
     * @Route("/cf/{cf}", name="op_ws_sr_my", defaults={"_format":"json"})
     */
    public function cfAction($cf) {
        $sr = $this->findOneBy('ESOperatoriBundle:Showroom', array('codice_fiscale' => $cf));
        if($sr) {
            return new Response(json_encode(array('url' => $this->generateUrl('op_sr_open', array('slug' => $sr->getSlug()), true))));
        }
        return new Response(json_encode(array('error' => 'not found')));
    }

}
