<?php

namespace ES\OperatoriBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Description of AdminRdoController
 *
 * @author Corrado
 * 
 * @Route("/gestione-rdo")
 */
class AdminRdoController extends Controller {

    use \Ephp\UtilityBundle\Controller\Traits\BaseController;

    /**
     * @Route("/", name="gestione_rdo")
     * @Template("ESOperatoriBundle:AdminRichieste/gestioneRDO:index_gestione_rdo.html.twig")
     */
    public function indexAction() {

        $rdo_cers = $this->getRepository('ESMessengerBundle:RDO\Cer')->getGestioneRdoCer();
        $rdo_mps = $this->getRepository('ESMessengerBundle:RDO\Mps')->getGestioneRdoMps();

        $out = array();
        $numeroForm = 0;
        foreach ($rdo_cers as $key => $rdo_cer) {
            if ($rdo_cer->getFromShowroom()) {
                $out[$rdo_cer->getFromShowroom()->getId()]["azienda"] = $rdo_cer->getFromShowroom()->getRagioneSociale();
                $out[$rdo_cer->getFromShowroom()->getId()]["email"] = $rdo_cer->getFromShowroom()->getEmail();
                $out[$rdo_cer->getFromShowroom()->getId()]["telefono"] = $rdo_cer->getFromShowroom()->getTelefono();
                $out[$rdo_cer->getFromShowroom()->getId()]["comune"] = $rdo_cer->getFromShowroom()->getComuneTestuale();
                $out[$rdo_cer->getFromShowroom()->getId()]["sito"] = $rdo_cer->getFromShowroom()->getSito();
                $out[$rdo_cer->getFromShowroom()->getId()]["codice_fiscale"] = $rdo_cer->getFromShowroom()->getCodiceFiscale();
                $out[$rdo_cer->getFromShowroom()->getId()]["rdo"][$key] = array(
                    "rdo" => $rdo_cer,
                    "form" => $this->createForm(new \ES\MessengerBundle\Form\Admin\CerType($rdo_cer->getId()), $rdo_cers[$key])->createView(),
                    $numeroForm++
                );
            } else {
                $out[0]["azienda"] = "";
                $out[0]["email"] = "";
                $out[0]["telefono"] = "";
                $out[0]["comune"] = "";
                $out[0]["rdo"] = "";
                $out[0]["sito"] = "";
                $out[0]["codice_fiscale"] = "";
            }
        }

        return array(
            'out' => $out,
            'numeroForm' => $numeroForm,
            'rdo_cer' => $rdo_cer,
            'rdo_mps' => $rdo_mps
        );
    }
    /**
     * @Route("/csr", name="gestione_csr")
     * @Template("ESOperatoriBundle:AdminRichieste/gestioneRDO:index_csr_rdo.html.twig")
     */
    public function csrAction() {

        $rdo_cers = $this->getRepository('ESMessengerBundle:RDO\Cer')->getGestioneRdoCerCentroSmaltimento();
//        $rdo_mps = $this->getRepository('ESMessengerBundle:RDO\Mps')->getGestioneRdoMps();

        $out = array();
        $numeroForm = 0;
        foreach ($rdo_cers as $key => $rdo_cer) {
                $out[$key] = array(
                    "rdo" => $rdo_cer,
                    "form" => $this->createForm(new \ES\MessengerBundle\Form\Admin\CerType($rdo_cer->getId()), $rdo_cers[$key])->createView(),
                    $numeroForm++
                );
        }

        return array(
            'out' => $out,
            'numeroForm' => $numeroForm,
            'rdo_cer' => $rdo_cer,
        );
    }

    /**
     * @Route("/load-cer", name="load_cer_admin_form",options={"expose"=true})
     * @Template("ESOperatoriBundle:AdminRichieste:gestioneRDO/cerDetail.html.twig")
     */
    public function laodCerDetailAction() {
        $id = $this->getParam('id');
        $rdo_cer = $this->find('\ES\MessengerBundle\Entity\RDO\Cer', $id);
        return array(
            "rdo" => $rdo_cer,
            "form" => $this->createForm(new \ES\MessengerBundle\Form\Admin\CerType($rdo_cer->getId()), $rdo_cer)->createView(),
        );
    }

    /**
     * @Route("/salva-rdo-admin-form/{id}", name="salva_rdo_admin_form", defaults={"_format": "json"}, options={"expose"=true})
     */
    public function salvaRdoAdminAction($id) {
        $em = $this->getEm();
        $request = $this->get('request');
        if ($request->isXmlHttpRequest()) {
            $em = $this->getEm();

            $rdo_cer = $this->find('\ES\MessengerBundle\Entity\RDO\Cer', $id);
            /* @var $rdo_cer \ES\MessengerBundle\Entity\RDO\Cer */

            $form = $this->createForm(new \ES\MessengerBundle\Form\Admin\CerType($id), $rdo_cer);
            $form->bind($request);

            try {
                $user = $this->getUser();
                /* @var $user \Symfony\Component\Security\Core\User\User */
                $em->beginTransaction();

                if ($form->isValid()) {
                    if(!$rdo_cer->getStato()) {
                        $rdo_cer->setStato($this->findOneBy('ESMessengerBundle:RDO\Stato', array('stato' => 'Preso in carico')));
                    }
                    $rdo_cer->setAdmin($user->getUsername());
                    if($rdo_cer->getStato()->getStato() == 'Elimina') {
                        $em->remove($rdo_cer);
                    } else {
                        $em->persist($rdo_cer);
                    }
                    $em->flush();
                    $em->commit();
                    $out_json = array(
                        'status' => "OK",
                        'id' => $id,
                        'admin' => $user->getUsername(),
                        'stato' => $rdo_cer->getStato()->getStato(),
                    );
                        
                } else {
                    $out_json = array(
                        'status' => "KO",
                        'error' => $form->getErrorsAsString(),
                    );
                }
            } catch (\Exception $e) {
                $out_json = array(
                    'status' => "KO",
                    'error' => $e->getMessage(),
                );
                $em->rollback();
            }

            return new \Symfony\Component\HttpFoundation\Response(json_encode($out_json));
        }
    }

}