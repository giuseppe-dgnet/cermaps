<?php

namespace ES\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use ES\CerMapBundle\Controller\MapController;

/**
 * @Route("/")
 * @Template()
 */
class AdminController extends Controller {

    use \Ephp\UtilityBundle\Controller\Traits\BaseController;
    
    /**
     * @Route("/", name="homepage")
     * @Route("/", name="admin")
     * @Template()
     */
    public function indexAction() {
        return array();
    }
}
