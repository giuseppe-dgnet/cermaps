<?php

namespace ES\NewsBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Ephp\WsBundle\Controller\WsInvokerController;

/**
 * @Route("/news")
 * @Template()
 */
class DefaultController extends WsInvokerController {

    /**
     * @Route("/read")
     * @Template()
     */
    public function readAction() {
        $params = array();
        $this->call = $this->createCall('theenq_read');
        try {
            $response = $this->invokeService('Theenq', 'read', $this->call, $params);
            $json = json_decode($response->getXml());
            foreach($json as $jalert) {
                $alert = new Alert();
                foreach($jalert as $key => $value) {
                }
            }
        } catch (\Exception $e) {
            if ($this->call) {
                $this->completeCall($this->call, $params, $this->getErrorMessage($e));
            }
            echo $e->getMessage();
            throw $e;
        }
        $this->completeCall($this->call, $params, $json);
    }

}
