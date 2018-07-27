<?php

namespace ES\OperatoriBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Ephp\WsBundle\Controller\WsInvokerController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Richiesta controller.
 *
 * @Route("/richieste-di-registrazione")
 */
class AdminAngaController extends WsInvokerController {

    /**
     * Lists all Richiesta entities.
     *
     * @Route("/list", name="anga_list", defaults={"_format"="json"})
     * @Method("POST")
     */
    public function listAction() {
        $request = $this->getRequest();

        $this->call = $this->createCall('anga_list');
        $params = array(
            'denominazione' => $request->get('denominazione'),
            'codice_fiscale' => $request->get('codice_fiscale', null),
            'partita_iva' => $request->get('partita_iva', null),
            'cap' => $request->get('cap', null),
        );
        try {
            $response = $this->invokeService('BringOut', 'Cerca', $this->call, $params);
            $json = $response->getXml();
        } catch (\Exception $e) {
            if ($this->call) {
                $this->completeCall($this->call, $params, $this->getErrorMessage($e));
            }
            echo $e->getMessage();
            throw $e;
        }
        $this->completeCall($this->call, $params, $json);
        return new \Symfony\Component\HttpFoundation\Response($json);
    }
    
    /**
     * Lists all Richiesta entities.
     *
     * @Route("/detail", name="anga_detail", defaults={"_format"="json"})
     * @Method("POST")
     */
    public function detailAction() {
        $request = $this->getRequest();

        $this->call = $this->createCall('anga_detail');
        $params = array(
            'id' => $request->get('id'),
        );
        try {
            $response = $this->invokeService('BringOut', 'Dettagli', $this->call, $params);
            $json = $response->getXml();
        } catch (\Exception $e) {
            if ($this->call) {
                $this->completeCall($this->call, $params, $this->getErrorMessage($e));
            }
            echo $e->getMessage();
            throw $e;
        }
        $this->completeCall($this->call, $params, $json);
        return new \Symfony\Component\HttpFoundation\Response($json);
    }
    
    public function next() {
        $this->call = $this->createCall('anga_next');
        $params = array();
        try {
            $response = $this->invokeService('BringOut', 'NextAnga', $this->call, $params);
            $json = $response->getXml();
        } catch (\Exception $e) {
            if ($this->call) {
                $this->completeCall($this->call, $params, $this->getErrorMessage($e));
            }
            echo $e->getMessage();
            throw $e;
        }
        $this->completeCall($this->call, $params, $json);
        return json_decode($json);
    }

}
