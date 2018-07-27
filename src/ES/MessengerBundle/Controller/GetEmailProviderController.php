<?php

namespace ES\MessengerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


/**
 * @Route("/get_email")
 */
class GetEmailProviderController extends \Ephp\WsBundle\Controller\WsInvokerController {

   /**
     * @Route("/",name="centro_provider")
     * @Template()
     */
    public function indexAction() {
        $utente = $this->getUtente();

        return array(
        );
    }
    
     /**
     * @Route("/aggiungiProviderEmail", name="aggiungiProviderEmail")
     */
    public function aggiungiProviderEmailAction() {
        $request = $this->getRequest();
        
        $data = $request->get('email_provider');
        
        //print_r($data);
        $this->call = $this->createCall('ciuccia_mail');        
        $params = array('provider' => $data["provider"], 'username' => $data["username"],'password' => $data["password"]);
        $esito = null;
        try {
            $response = $this->invokeService('CiucciaMail', 'google_yahoo', $this->call, $params);
            
            $json = json_decode($response->getXml());
            //\ES\WebBundle\Functions\Funzioni::pr($json);
            if ($response->getStatusCode() == 200) {
                $esito = "OK";
            } else {
                $esito = "KO";
            }
            $this->completeCall($this->call, $params, $json);
        } catch (\Exception $e) {
            if ($this->call) {
                $this->completeCall($this->call, $params, $json);
            }
            $esito = $e->getMessage();
        }
         $em = $this->getEm();
        $_user = $em->getRepository('ES\ACLBundle\Entity\User');

        
        
        foreach ($json as $email) {
            $user = $_user->findOneBy(array('email' => $email));
            
            
            if ($user) {
                $utente_provider[] = array(
                    'email' => $json,
                    'i' => $user->getId(),
                    'n' => $user->getNome() ? : '',
                    'c' => $user->getCognome() ? : '',
                    'v' => $user->getEmailVerificata() ? '1' : '',
                    't' => $user->getAzienda() ? 'a' : ($user->getProfessionista() ? 'p' : ''),
                    'u' => $user->getUser() ? 'r' : '',
                    'ac' => $user->getNomeAzienda() ? $user->getNomeAzienda() : '',
                );
            }else{
                $utente_provider[] = array(
                    'email' => $json,
                    'i' => '',
                    'n' => '',
                    'c' => '',
                    'v' => '',
                    't' => '',
                    'u' => '',
                    'ac' => '',
                );
            }
        }
       // return array('esito' => $response);
        $out = array(
            'emails' => $utente_provider,
        );
        
        echo json_encode($out);
        exit;
    }


}

?>
