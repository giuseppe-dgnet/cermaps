<?php

namespace ES\MessengerBundle\Controller;

use ES\ACLBundle\Entity\User;
use ES\MessengerBundle\Entity\RDO\Cer;
use ES\MessengerBundle\Entity\RDO\Mps;
use ES\MessengerBundle\Entity\RDO\Servizi;
use ES\MessengerBundle\Entity\Messaggio;
use ES\MessengerBundle\Entity\Post;
use ES\MessengerBundle\Entity\Destinatario;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use ES\ShowRoomBundle\Controller\ShowRoomControllerBase;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/invia/rdo")
 */
class MessengerRDOController extends \ES\OperatoriBundle\Controller\ShowroomController {

    use \Ephp\UtilityBundle\Controller\Traits\BaseController;

    public function notifica(\ES\UserBundle\Entity\User $destinatario = NULL, \ES\MessengerBundle\Entity\MessaggioBase $rdo, $invia_al_mittente) {
        if ($destinatario) {
            $rdo->cercaDestinatario($destinatario);
            $da = $rdo->getFromEmail();
        }
        // Email inviata a chi riceve la richiesta
        $message = \Swift_Message::newInstance()
                ->setSubject($rdo->getSubject())
                ->setFrom($this->container->getParameter('email_messenger'))
                ->setTo($destinatario ? $destinatario->getEmail() : 'ecoseekr.italia@gmail.com')
                ->setReplyTo($this->container->getParameter('email_messenger'), $rdo->getFromNome())/* ." ".$rdo->getFrom . ' - ' . $rdo->getConversazione()->getSlug()) */
                ->setBody($this->renderView('ESMessengerBundle:RDO:notificaRicezioneEmail.txt.twig', array('user' => $destinatario ? $destinatario : $rdo->getFromShowroom(), 'riepilogo' => $rdo->riepilogo(), 'esistente' => $destinatario ? true : false /* , 'destinatario' => $d */)))
        ;
        $message->getHeaders()->addTextHeader('X-Mailer', 'PHP v' . phpversion());
        $message->getHeaders()->get('Message-ID')->setId('ES-' . md5(time()) . rand(100, 999) . '@ecoseekr.com');
        $this->get('mailer')->send($message);
        if ($invia_al_mittente) {
            // Email inviata a chi fa la richiesta se la sua mail è verificata
            $message = \Swift_Message::newInstance()
                    ->setSubject('Invio richiesta effettuata')
                    ->setFrom($this->container->getParameter('email_messenger'))
                    ->setTo($rdo->getFromEmail())
                    ->setBody($this->renderView('ESMessengerBundle:RDO:notificaInvioEmail.txt.twig', array('riepilogo' => $rdo->riepilogo())))
            ;
            return $this->get('mailer')->send($message);


//            $message = \Swift_Message::newInstance()
//                    ->setSubject('Invio richiesta effettuata')
//                    ->setFrom($this->container->getParameter('email_messenger'))
//                    ->setTo($rdo->getFromEmail())
//                    ->setBody($this->renderView('ESMessengerBundle:RDO:notificaInvioEmail.txt.twig', array('riepilogo' => $rdo->riepilogo())), 'text/html')
//
//            ;
//            $message->getHeaders()->addTextHeader('X-Mailer', 'PHP v' . phpversion());
//            $message->getHeaders()->get('Message-ID')->setId('ES-' . md5(time()) . rand(100, 999) . '@ecoseekr.com');
//            $this->get('mailer')->send($message);
        }
    }

    /* ---------------------------------- 
     * 
     *  RDO
     * 
     * ---------------------------------- */

    /**
     * CER
     */

    /**
     * @Route("/richiesta-rdo-cer/cm/{slug}", name="op_cm_richiesta_rdo_cer", options={"stats":{"area": {"form-rdo-cm", "form-rdo-cm-cer"}}})
     * @Route("/richiesta-rdo-cer/sr/{slug}", name="op_sr_richiesta_rdo_cer", options={"stats":{"area": {"form-rdo-sr", "form-rdo-sr-cer"}}})
     * @Template("ESOperatoriBundle:Showroom:preventivi/rdo_cer.html.twig")
     */
    public function preventiviRdoCerAction($slug) {

        $rdo = new \ES\MessengerBundle\Entity\RDO\Cer();
        $sr = $this->findOneBy('ESOperatoriBundle:Showroom', array('slug' => $slug));

        $request = $this->get('request');

        //vediamo se è passato un parametro per il Fancybox
        $fb = $request->get('fb');

        $user = $this->getUser();

        if (is_object($user)) {
            /* @var $user \ES\UserBundle\Entity\User */
            $rdo->setFromUtente($user, true);
        }
        $rdo->setFromShowroom($sr, true);

        $form = $this->createForm(new \ES\MessengerBundle\Form\RDO\CerType(false), $rdo);

        return array(
            'form' => $form->createView(),
            'showroom' => $slug,
            'hasCer' => $sr->getHasCer(),
            'fb' => $fb
        );
    }

    /**
     * @Route("/invio/richiesta-form-rdo", name="invio_richiesta_form_rdo", options={"stats":{"area": {"form-rdo-send", "form-rdo-send-cer"}}})
     */
    public function InvioRichiestaFormRdoAction() {
        $em = $this->getEm();
        $request = $this->getRequest();
        $_rdo = $request->get('rdo');

        $sr = $this->findOneBy('ESOperatoriBundle:Showroom', array('slug' => $request->get('slug')));

        if ($request->isXmlHttpRequest()) {
            $rdo = new \ES\MessengerBundle\Entity\RDO\Cer();
//            if(!$_rdo['comune']) {
//                $_geo = $this->getRepository('EphpGeoBundle:GeoNames');
//                /* @var $_geo \Ephp\GeoBundle\Entity\GeoNamesRepository */
//                $geo = $_geo->ricercaComune($_rdo['geo'], null, null, 'IT');
//                if(!$geo) {
//                    if($request->getSession()->get('posizione')) {
//                        $posizione = $request->getSession()->get('posizione');
//                        if(isset($posizione['id'])) {
//                            $geo = $_geo->find($posizione['id']);
//                        }
//                    }
//                }
//                if(!$geo) {
//                    $geo = $_geo->find(3175395);
//                }
//                $_rdo['comune'] = $geo->getGeonameid();
//                $request->query->set('rdo', $_rdo);
//                $request->request->set('rdo', $_rdo);
//                $request->attributes->set('rdo', $_rdo);
//            }
            $form = $this->createForm(new \ES\MessengerBundle\Form\RDO\CerType(false), $rdo);
            $form->bind($request);
            //\Ephp\UtilityBundle\Utility\Debug::vd($rdo);
            try {
                if ($rdo->getCerList()) {
                    $ids = json_decode($rdo->getCerList());

                    foreach ($ids as $id) {
                        $tag = $this->find('EphpTagBundle:Tag', $id);

                        $rdo->addCer($this->findOneBy('ESCerMapBundle:Cer\Cer', array('codice' => $tag->getTag())));
                    }
                }
                if ($sr) {
                    $rdo->setFromShowroom($sr);
                }

                if ($_rdo['latitudine'] != "" && $_rdo['longitudine'] != "") {
                    $comune = $em->getRepository('\Ephp\GeoBundle\Entity\GeoNames')->getComune($_rdo['latitudine'], $_rdo['longitudine']);
                    $rdo->setComune($comune);
                }
                //$comune = $this->findOneBy('ESCerMapBundle:Cer\Cer', array('codice' => $tag->getTag()
//                ->add('comune', null, array(
//                        'query_builder' => function(\Ephp\GeoBundle\Entity\GeoNamesRepository $er) use ($comune_id) {
//                            return $er->createQueryBuilder('u')
//                                    ->where('u.geonameid = :id')
//                                    ->setParameter('id', $comune_id);
//                        },
//                    ))

                $em->persist($rdo);
                $em->flush();

                $out_json = array(
                    'status' => "OK",
                );

                $this->notifica($sr ? $sr->getUser() : null, $rdo, true);
            } catch (\Exception $e) {
                $out_json = array(
                    'e' => $e->getMessage(),
                    'status' => "KO",
                );
            }
            return new \Symfony\Component\HttpFoundation\Response(json_encode($out_json));
        }
    }

    /**
     * MPS
     */

    /**
     * @Route("/richiesta-rdo-mps/cm/{slug}", name="op_cm_richiesta_rdo_mps", options={"stats":{"area": {"form-rdo-cm", "form-rdo-cm-mps"}}})
     * @Route("/richiesta-rdo-mps/sr/{slug}", name="op_sr_richiesta_rdo_mps", options={"stats":{"area": {"form-rdo-sr", "form-rdo-sr-mps"}}})
     * @Template("ESOperatoriBundle:Showroom:preventivi/rdo_mps.html.twig")
     */
    public function preventiviRdoMpsAction($slug) {

        $mps = new \ES\MessengerBundle\Entity\RDO\Mps();
        $user = $this->getUser();
        $request = $this->get('request');

        $sr = $this->findOneBy('ESOperatoriBundle:Showroom', array('slug' => $slug));

        if (is_object($user)) {
            /* @var $user \ES\UserBundle\Entity\User */
            $mps->setFromUtente($user, true);
        }
        $mps->setFromShowroom($sr, true);
        //vediamo se è passato un parametro per il Fancybox
        $fb = $request->get('fb');

        $form = $this->createForm(new \ES\MessengerBundle\Form\RDO\MpsType(false), $mps);

        return array(
            'form' => $form->createView(),
            'showroom' => $slug,
            'fb' => $fb
        );
    }

    /**
     * @Route("/invio/richiesta-form-mps", name="invio_richiesta_form_mps", options={"stats":{"area": {"form-rdo-send", "form-rdo-send-mps"}}})
     */
    public function InvioRichiestaFormMpsAction() {
        $em = $this->getEm();
        $request = $this->get('request');
        $_mps = $request->get('mps');

        $sr = $this->findOneBy('ESOperatoriBundle:Showroom', array('slug' => $request->get('slug')));

        if ($request->isXmlHttpRequest()) {
            $mps = new \ES\MessengerBundle\Entity\RDO\Mps();
//            if (!$_mps['comune']) {
//                $_geo = $this->getRepository('EphpGeoBundle:GeoNames');
//                /* @var $_geo \Ephp\GeoBundle\Entity\GeoNamesRepository */
//                $geo = $_geo->ricercaComune($_mps['geo'], null, null, 'IT');
//                if (!$geo) {
//                    if ($request->getSession()->get('posizione')) {
//                        $posizione = $request->getSession()->get('posizione');
//                        if (isset($posizione['id'])) {
//                            $geo = $_geo->find($posizione['id']);
//                        }
//                    }
//                }
//                if (!$geo) {
//                    $geo = $_geo->find(3175395);
//                }
//                $_mps['comune'] = $geo->getGeonameid();
//                $request->query->set('mps', $_mps);
//                $request->request->set('mps', $_mps);
//                $request->attributes->set('mps', $_mps);
//            }
            $form = $this->createForm(new \ES\MessengerBundle\Form\RDO\MpsType(false), $mps);
            $form->bind($request);
            //\Ephp\UtilityBundle\Utility\Debug::vd($mps);

            try {
                if ($mps->getMpsList()) {
                    $ids = json_decode($mps->getMpsList());

                    foreach ($ids as $id) {
                        $tag = $this->find('EphpTagBundle:Tag', $id);

                        $mps->addMps($this->findOneBy('ESCerMapBundle:Mps\Mps', array('materia' => $tag->getTag(), 'descrizione' => $tag->getDescrizione())));
                    }
                }
                if ($sr) {
                    $mps->setFromShowroom($sr);
                }

                if ($_mps['latitudine'] != "" && $_mps['longitudine'] != "") {
                    $comune = $em->getRepository('\Ephp\GeoBundle\Entity\GeoNames')->getComune($_mps['latitudine'], $_mps['longitudine']);
                    $mps->setComune($comune);
                }
                $em->persist($mps);
                $em->flush();

                $out_json = array(
                    'status' => "OK",
                );

                $this->notifica($sr ? $sr->getUser() : null, $mps, true);
            } catch (\Exception $e) {
                $out_json = array(
                    //'e' => $e->getMessage(),
                    'status' => "KO",
                );
            }
            return new \Symfony\Component\HttpFoundation\Response(json_encode($out_json));
        }
    }

    /**
     * SERVIZI
     */

    /**
     * @Route("/richiesta-rdo-servizi/cm/{slug}", name="op_cm_richiesta_rdo_servizi", options={"stats":{"area": {"form-rdo-cm", "form-rdo-cm-servizi"}}})
     * @Route("/richiesta-rdo-servizi/sr/{slug}", name="op_sr_richiesta_rdo_servizi", options={"stats":{"area": {"form-rdo-sr", "form-rdo-sr-servizi"}}})
     * @Template("ESOperatoriBundle:Showroom:preventivi/rdo_servizi.html.twig")
     */
    public function preventiviRdoServiziAction($slug) {

        $servizi = new \ES\MessengerBundle\Entity\RDO\Servizi();
        $user = $this->getUser();

        if (is_object($user)) {
            /* @var $user \ES\UserBundle\Entity\User */
            $servizi->setFromUtente($user, true);
        }

        $form = $this->createForm(new \ES\MessengerBundle\Form\RDO\ServiziType(false), $servizi);

        return array(
            'form' => $form->createView(),
            'showroom' => $slug,
        );
    }

    /**
     * @Route("/invio/richiesta-form-servizi", name="invio_richiesta_form_servizi", options={"stats":{"area": {"form-rdo-send", "form-rdo-send-servizi"}}})
     */
    public function InvioRichiestaFormServiziAction() {
        $em = $this->getEm();
        $request = $this->get('request');
        $_servizi = $request->get('servizi');

        $sr = $this->findOneBy('ESOperatoriBundle:Showroom', array('slug' => $request->get('slug')));

        if ($request->isXmlHttpRequest()) {
            $servizi = new \ES\MessengerBundle\Entity\RDO\Servizi();
            $form = $this->createForm(new \ES\MessengerBundle\Form\RDO\ServiziType($_servizi['comune']), $servizi);
            if (!$_servizi['comune']) {
                $_geo = $this->getRepository('EphpGeoBundle:GeoNames');
                /* @var $_geo \Ephp\GeoBundle\Entity\GeoNamesRepository */
                $geo = $_geo->ricercaComune($_servizi['geo'], null, null, 'IT');
                if (!$geo) {
                    if ($request->getSession()->get('posizione')) {
                        $posizione = $request->getSession()->get('posizione');
                        if (isset($posizione['id'])) {
                            $geo = $_geo->find($posizione['id']);
                        }
                    }
                }
                if (!$geo) {
                    $geo = $_geo->find(3175395);
                }
                $_servizi['comune'] = $geo->getGeonameid();
                $request->query->set('servizi', $_servizi);
                $request->request->set('servizi', $_servizi);
                $request->attributes->set('servizi', $_servizi);
            }
            $form->bind($request);
            //\Ephp\UtilityBundle\Utility\Debug::vd($servizi);
            try {
                $ids = explode(',', $servizi->getServiziList());

                foreach ($ids as $id) {
                    $servizi->addServizi($this->find('ESOperatoriBundle:Servizi\Servizio', $id));
                }

                $em->persist($servizi);
                $em->flush();

                $out_json = array(
                    'status' => "OK",
                );

                $this->notifica($sr->getUser(), $servizi, true);
            } catch (\Exception $e) {
                $out_json = array(
                    'e' => $e->getMessage(),
                    'status' => "KO",
                );
            }
            return new \Symfony\Component\HttpFoundation\Response(json_encode($out_json));
        }
    }

}
