<?php

namespace ES\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/corporate")
 */
class CorporateController extends Controller {

    use \Ephp\UtilityBundle\Controller\Traits\BaseController,
        \Ephp\GeoBundle\Controller\Traits\BaseGeoController,
        \ES\CerMapBundle\Controller\Traits\BaseCerMapController;
    
     /**
     * @Route("/servizi-ebusiness", name="index", options={"sitemap" = true})
     * @Template()
     */
    public function indexAction() {
        return array();
    }
    
    /**
     * @Route("/vision-e-mission", name="vision", options={"sitemap" = true})
     * @Template()
     */
    public function visionMissionAction() {
        return array();
    }

    /**
     * @Route("/persone-e-collaboratori", name="collaboratori", options={"sitemap" = true})
     * @Template()
     */
    public function collaboratoriAction() {
        return array();
    }

    /**
     * @Route("/partners-e-fornitori", name="partners", options={"sitemap" = true})
     * @Template()
     */
    public function partnersAction() {
        return array();
    }

    /**
     * @Route("/dove-siamo", name="dove_siamo", options={"sitemap" = true})
     * @Template()
     */
    public function doveSiamoAction() {
        return array();
    }
    /**
     * @Route("/collabora-con-noi", name="collabora_con_noi", options={"sitemap" = true})
     * @Template()
     */
    public function collaborazioniAction() {
        return array();
    }
    
    /**
     * @Route("/salva-collaborazione", name="salva_collaborazione")
     */
    public function salvaCollaborazioneAction() {
        $em = $this->getEm();
        $request = $this->get('request');
        //if ($request->isXmlHttpRequest()) {
        //$request->get('NAME DEL CAMPO');        

        $em = $this->getEm();
        try {
            $em->beginTransaction();
            $this->sendemail($request);
            $out_json = array(
                'status' => "OK",
            );
//            $em->persist(XXXXX);
//            $em->flush();
//            $em->commit();
        } catch (\Exception $e) {
            $out_json = array(
                'status' => "KO",
                 //'error' => $e->getMessage(),
            );
            $em->rollback();
        }
        
        
        
        
        return new \Symfony\Component\HttpFoundation\Response(json_encode($out_json));
    }
    
    /**
     * @param Contact $contact
     * @return type 1 (true) if send successfully, 0 (false) otherwise 
     */
    private function sendEmail($richiesta) {
        $message = \Swift_Message::newInstance()
            ->setSubject('Nuova richiesta Ecoseekr')
            ->setFrom($richiesta->get('email3'))
            ->setTo('ecoseekr.italia@gmail.com')
            ->setBody($this->renderView('ESWebBundle:Corporate:collaborazioni_email.txt.twig'),'text/html')
        ;
        return $this->get('mailer')->send($message);
    }
    

    /**
     * @Route("/info-bancarie-e-societarie", name="info", options={"sitemap" = true})
     * @Template()
     */
    public function infoAction() {
        return array();
    }

    /**
     * @Route("/diventa-rivenditore", name="rivenditore", options={"sitemap" = true})
     * @Template()
     */
    public function rivenditoreAction() {
        return array();
    }

    /**
     * @Route("/invia-il-tuo-curriculum", name="curriculum", options={"sitemap" = true})
     * @Template()
     */
    public function curriculumAction() {
        return array();
    }

    /**
     * @Route("/informativa-sulla-privacy", name="privacy", options={"sitemap" = true})
     * @Template()
     */
    public function privacyAction() {
        return array();
    }

    /**
     * @Route("/termini-e-condizioni-generali", name="terms_cond", options={"sitemap" = true})
     * @Template()
     */
    public function termsAction() {
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
