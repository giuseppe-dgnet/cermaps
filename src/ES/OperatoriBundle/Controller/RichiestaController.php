<?php

namespace ES\OperatoriBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use ES\OperatoriBundle\Entity\Richiesta;
use ES\OperatoriBundle\Form\RichiestaType;

/**
 * Richiesta controller.
 *
 * @Route("/richiesta-di-registrazione")
 */
class RichiestaController extends Controller {

    use \Ephp\UtilityBundle\Controller\Traits\BaseController;

    /**
     * Displays a form to create a new Richiesta entity.
     *
     * @Route("/new", name="richiesta_new")
     * @Template()
     */
    public function newAction() {
        $entity = new Richiesta();
        $form = $this->createForm(new RichiestaType(), $entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a new Richiesta entity.
     *
     * @Route("/create", name="richiesta_create")
     * @Template("ESOperatoriBundle:Richiesta:new.html.twig")
     */
    public function createAction(Request $request) {
        $request = $this->get('request');
        //form_crea_showroom == nome del Form
//ragione_sociale == nome del campo

        if ($request->isXmlHttpRequest()) { //se non è ajax, redirect alla homepage
            $entity = new Richiesta();
            $form = $this->createForm(new RichiestaType(), $entity);
            $form->bind($request);

            if ($form->isValid()) {
                $this->sendEmail($entity);
                $em = $this->getEm();
                $em->persist($entity);
                $em->flush();

                $out_json = array(
                    'status' => "OK",
                );



                return new \Symfony\Component\HttpFoundation\Response(json_encode($out_json));
                //return $this->redirect($this->generateUrl('richiesta_show', array('id' => $entity->getId())));
            }

            $out_json = array(
                'status' => "KO",
            );

            return new \Symfony\Component\HttpFoundation\Response(json_encode($out_json));
//        return array(
//            'entity' => $entity,
//            'form' => $form->createView(),
//        );
        }
        return $this->redirect($this->generateUrl('homepage'));
    }
    
    
    /**
     * @param Contact $contact
     * @return type 1 (true) if send successfully, 0 (false) otherwise
     */
    private function sendEmail($richiesta) {
        $message = \Swift_Message::newInstance()
            ->setSubject('Nuova richiesta Ecoseekr')
            ->setFrom($richiesta->getEmail())
            ->setTo('ecoseekr.italia@gmail.com')
            ->setBody($this->renderView('ESOperatoriBundle:Richiesta:email.txt.twig', array('richiesta' => $richiesta)),'text/html')
        ;
        return $this->get('mailer')->send($message);
    }

}
