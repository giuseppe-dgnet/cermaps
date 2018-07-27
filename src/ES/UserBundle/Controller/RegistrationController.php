<?php

namespace ES\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Event\UserEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\UserBundle\Model\UserInterface;
use Ephp\ACLBundle\Controller\RegistrationController as BaseController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Description of RegistrationController
 *
 * @author Corrado
 * 
 */
class RegistrationController extends BaseController {

    public function registerAction(Request $request) {
        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->container->get('fos_user.registration.form.factory');
        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->container->get('fos_user.user_manager');
        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->container->get('event_dispatcher');

        $user = $userManager->createUser();
        $user->setEnabled(true);
        $user->setOperatore(true);

        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, new UserEvent($user, $request));

        $form = $formFactory->createForm();
        $form->setData($user);

        if ('POST' === $request->getMethod()) {
            $form->bind($request);

            if ($form->isValid()) {
                $event = new FormEvent($form, $request);
                $dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);

                $userManager->updateUser($user);

                if (null === $response = $event->getResponse()) {
                    $url = $this->container->get('router')->generate('fos_user_registration_confirmed');
                    $response = new RedirectResponse($url);
                }

                try {
                    $em = $this->getEm();
                    $em->beginTransaction();
                    $showroom = $em->getRepository('\ES\OperatoriBundle\Entity\Showroom')->findOneBy(array('id' => $request->request->get('id_showroom')));
                    $showroom->setUser($user);
                    $em->persist($showroom);
                    $em->flush();

                    $richieste = $em->getRepository('\ES\OperatoriBundle\Entity\Richiesta')->findOneBy(array('id' => $request->request->get('id_richiesta')));
                    $richieste->setUser($user); //$em->commit();
                    $em->persist($richieste);
                    $em->flush();
                    $em->commit();
                } catch (\Exception $e) {
                    $em->rollback();
                    throw $e;
                }

                $dispatcher->dispatch(FOSUserEvents::REGISTRATION_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

                return $response;
            }
        }

        return $this->container->get('templating')->renderResponse('FOSUserBundle:Registration:register.html.' . $this->getEngine(), array(
                    'form' => $form->createView(),
        ));
    }

    /* ---------------------------------- 
     * 
     * Redirect dopo aver cliccato sulla conferma tramite email
     * 
     * ---------------------------------- */

    public function confirmedAction() {
        return new RedirectResponse($this->container->get('router')->generate('reimposta_password', array('first' => 1)));
    }

    /* ---------------------------------- 
     * 
     * Sovrascrivo questa Funziona per far rimanere l'email in sessione che mi serve per 
     * rimandare l invito tramite ajax con la funzione resendEmailAction()
     * 
     * ---------------------------------- */

    public function checkEmailAction() {
        $email = $this->container->get('session')->get('fos_user_send_confirmation_email/email');
        $this->container->get('session')->remove('fos_user_send_confirmation_email/email');
        $user = $this->container->get('fos_user.user_manager')->findUserByEmail($email);

        if (null === $user) {
            throw new NotFoundHttpException(sprintf('The user with email "%s" does not exist', $email));
        }

        return $this->container->get('templating')->renderResponse('FOSUserBundle:Registration:checkEmail.html.' . $this->getEngine(), array(
                    'user' => $user,
        ));
    }

    /* ---------------------------------- 
     * 
     * Funzione Ajax che rimanda il token di registrazione
     * Forse si potrebbe fare meglio richiamando direttamente 
     * il FOS user Bundle ma mi sono rotto il cazzo di provare :)
     * 
     * La funzione nel Js si chiama resendAttivazione();
     * 
     * ---------------------------------- */

    /**
     * @Route("-resend-attivazione", name="resend_attivazione")
     */
    public function resendEmailAction(Request $request) {

        //echo $request->request->get('email');
        //$username = $this->container->get('security.context')->getToken()->getUser();
        //$email = $this->container->get('session')->get('fos_user_send_confirmation_email/email');
        $user = $this->container->get('fos_user.user_manager')->findUserByUsernameOrEmail($request->request->get('email'));

        $messaggio = \Swift_Message::newInstance()
                ->setSubject('Rimando avviso') //come faccio a prendere i parametri da dentro il config.yml?
                ->setFrom($this->container->getParameter('app_email'))
                ->setTo($user->getEmail())
                ->setContentType("text/html") // <= Permette l'Html
                ->setBody($this->container->get('templating')->render('ESUserBundle:Email:resend_email.txt.twig', array('user' => $user, 'confirmationUrl' => $this->container->get('router')->generate('fos_user_registration_confirm', array('token' => $user->getConfirmationToken()), true))))
        ;
        $this->container->get('mailer')->send($messaggio);

        $out_json = array(
            'status' => "OK",
        );

        return new \Symfony\Component\HttpFoundation\Response(json_encode($out_json));


        //return $this->render(...);
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEm() {

        return $this->container->get('doctrine')->getEntityManager();
    }
    
    
     /**
     * Restituisce il template che poi attacco nel Js
     * 
     * @Route("/password-dimenticata", name="password_dimenticata", options={"expose"=true})
     * @Template("ESUserBundle:ChangePassword:changePassword.html.twig")
     */
    public function passwordDimentacataAction() {
        $request = $this->get('request');

        if ($request->isXmlHttpRequest()) {
            $tipologia = $sr->getTipologieCategoria($request->get('id_categoria'));
            return array(
                
            );
        }
    }

    /* ---------------------------------- 
     * 
     *  Chiamata al funzione valida form che verifica
     *  se esiste gia l'email
     * 
     *  TODO potremmo fare...sei tu? allora mandami l'email o ricordami
     *  (lo so che non mi sono spiegato ma io mi sono capito)
     * 
     * ---------------------------------- */

    /**
     * Verifica che ci sia l'email all'interno del Nostro Database, in modo da capire se mandare o meno l'email di conferma
     * 
     * @Route("verifica-invio-password-email", name="verifica_invio_password_email",options={"expose"=true})
     */
    public function verifcaInvioPasswordEmailAction(Request $request) {
        $em = $this->getEm();
        $em->beginTransaction();
        $user = $em->getRepository('\ES\UserBundle\Entity\User')->findOneBy(array('email' => $request->request->get('email')));
        if ($user) { //FALSE non fa partire il form
            $valore = "true";
        } else {
            $valore = "false";
        }
        return new \Symfony\Component\HttpFoundation\Response($valore);
    }
    
    /**
     * @Route("-verifica_email", name="verifica_email",options={"expose"=true})
     */
    public function verifcaEmailAction(Request $request) {
        $em = $this->getEm();
        $em->beginTransaction();
        $user = $em->getRepository('\ES\UserBundle\Entity\User')->findOneBy(array('email' => $request->request->get('email')));
        if ($user) {
            $valore = "false"; //FALSE non fa partire il form
        } else {
            $valore = "true";
        }
        return new \Symfony\Component\HttpFoundation\Response($valore);
    }

}