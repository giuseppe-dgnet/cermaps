<?php

namespace ES\OperatoriBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use ES\OperatoriBundle\Entity\Richiesta;
use ES\OperatoriBundle\Form\RichiestaType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Richiesta controller.
 *
 * @Route("/showroom")
 */
class ShowroomController extends Controller {

    use \Ephp\UtilityBundle\Controller\Traits\BaseController;

    /**
     * Displays a form to create a new Richiesta entity.
     *
     * @Route("/", name="op_sr_my")
     * @Template()
     */
    public function indexAction() {
        $sr = $this->myShowroom();

        return $this->redirect($this->generateUrl('op_sr_open', array('slug' => $sr->getSlug())));
    }

    /**
     * Displays a form to create a new Richiesta entity.
     *
     * @Route("-redirect", name="op_sr_redirect")
     * @Template()
     */
    public function redirectAction() {
        $sr = $this->myShowroom();

        return array('url' => $this->generateUrl('op_sr_open', array('slug' => $sr->getSlug())));
    }

    /**
     * Displays a form to create a new Richiesta entity.
     *
     * @Route("/{slug}", name="op_sr_open", options={"stats":{"area": {"showroom"}, "entity":{"entity":"ESOperatoriBundle:Showroom", "column":"slug", "param":"slug", "prefix":"showroom", "output":"id"}}})
     * @Route("/{slug}/home", name="op_sr_home", options={"stats":{"area": {"showroom"}, "entity":{"entity":"ESOperatoriBundle:Showroom", "column":"slug", "param":"slug", "prefix":"showroom", "output":"id"}}})
     * @Template()
     */
    public function homeAction($slug) {
        $sr = $this->findOneBy('ESOperatoriBundle:Showroom', array('slug' => $slug));

        try {
            $_foto = $this->getRepository('\ES\FotoBundle\Entity\Foto')->ultimaFoto($sr->getId());
            $foto = $_foto[0]->getWebUrl('profilo');
        } catch (\Doctrine\Orm\NoResultException $e) {
            $foto = null;
        }

        return array(
            'showroom' => $sr,
            'foto_profilo' => $foto,
            'modificabile' => $sr->getUser() && ($this->getUser() && $this->getUser() != 'anon.') && $sr->getUser()->getId() == $this->getUser()->getId(),
        );
    }

    /**
     * Displays a form to create a new Richiesta entity.
     *
     * @Route("/{slug}/cer", name="op_sr_cer", options={"stats":{"area": {"showroom", "rdo", "rdo-cer"}, "entity":{"entity":"ESOperatoriBundle:Showroom", "column":"slug", "param":"slug", "prefix":"showroom", "output":"id"}}}))
     * @Template()
     */
    public function cerAction($slug) {
        $sr = $this->findOneBy('ESOperatoriBundle:Showroom', array('slug' => $slug));

        return array(
            'showroom' => $sr,
            'modificabile' => $sr->getUser() && ($this->getUser() && $this->getUser() != 'anon.') && $sr->getUser()->getId() == $this->getUser()->getId(),
        );
    }
    
    /**
     * Displays a form to create a new Richiesta entity.
     *
     * @Route("/{slug}/mps", name="op_sr_mps", options={"stats":{"area": {"showroom", "rdo", "rdo-mps"}, "entity":{"entity":"ESOperatoriBundle:Showroom", "column":"slug", "param":"slug", "prefix":"showroom", "output":"id"}}})))
     * @Template()
     */
    public function mpsAction($slug) {
        $sr = $this->findOneBy('ESOperatoriBundle:Showroom', array('slug' => $slug));

        return array(
            'showroom' => $sr,
            'modificabile' => $sr->getUser() && ($this->getUser() && $this->getUser() != 'anon.') && $sr->getUser()->getId() == $this->getUser()->getId(),
        );
    }
    
    /**
     * Displays a form to create a new Richiesta entity.
     *
     * @Route("/{slug}/servizi", name="op_sr_servizi", options={"stats":{"area": {"showroom", "rdo", "rdo-servizi"}, "entity":{"entity":"ESOperatoriBundle:Showroom", "column":"slug", "param":"slug", "prefix":"showroom", "output":"id"}}})))
     * @Template()
     */
    public function serviziAction($slug) {
        $sr = $this->findOneBy('ESOperatoriBundle:Showroom', array('slug' => $slug));

        return array(
            'showroom' => $sr,
            'modificabile' => $sr->getUser() && ($this->getUser() && $this->getUser() != 'anon.') && $sr->getUser()->getId() == $this->getUser()->getId(),
        );
    }

    /**
     * @Route("/reimposta-password", name="reimposta_password")
     * @Template("ESOperatoriBundle:CreaUtente/reimpostaPassword:reimposta_password.html.twig")
     */
    public function impostaPasswordAction() {

        /** FORM PASSWORD * */
        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $user = $this->getUser();
        $formFactory = $this->container->get('fos_user.change_password.form.factory');

        $form_password = $formFactory->createForm();
        $form_password->setData($user);


        return array(
            'form_password' => $form_password->createView(),
        );
    }

    /**
     * 
     * Cambio password 
     * 
     * @Route("/profilo-password", name="salva_password_profilo")
     */
    public function salvaPasowordAction() {
        $request = $this->get('request');
        if ($request->isXmlHttpRequest()) {
            $user = $this->container->get('security.context')->getToken()->getUser();
            if (!is_object($user)) {
                throw new AccessDeniedException('This user does not have access to this section.');
            }

            /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
            $dispatcher = $this->container->get('event_dispatcher');

            $event = new GetResponseUserEvent($user, $request);
            $dispatcher->dispatch(FOSUserEvents::CHANGE_PASSWORD_INITIALIZE, $event);

            if (null !== $event->getResponse()) {
                return $event->getResponse();
            }

            /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
            $formFactory = $this->container->get('fos_user.change_password.form.factory');

            $form = $formFactory->createForm();
            $form->setData($user);

            if ($request->isMethod('POST')) {
                $form->bind($request);

                if ($form->isValid()) {
                    /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
                    $userManager = $this->container->get('fos_user.user_manager');

                    $event = new FormEvent($form, $request);
                    $dispatcher->dispatch(FOSUserEvents::CHANGE_PASSWORD_SUCCESS, $event);

                    $userManager->updateUser($user);

                    if (null === $response = $event->getResponse()) {
                        $url = $this->container->get('router')->generate('fos_user_profile_show');
                        $response = new RedirectResponse($url);
                    }

                    $out_json = array(
                        'status' => "OK",
                    );


                    $dispatcher->dispatch(FOSUserEvents::CHANGE_PASSWORD_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

                    return new \Symfony\Component\HttpFoundation\Response(json_encode($out_json));

                    //return $response;
                }
            }
            $out_json = array(
                'status' => "passwordErr",
            );

            return new \Symfony\Component\HttpFoundation\Response(json_encode($out_json));
        }
        return new RedirectResponse($this->container->get('router')->generate('reimposta_password'));
    }

    /**
     * 
     * @return \ES\OperatoriBundle\Entity\Showroom
     * @throws \Exception
     */
    protected function myShowroom() {
        $user = $this->getUser();
        if ($user && $user != 'anon.') {
            /* @var $user \ES\UserBundle\Entity\User */
            if ($user->getShowroom()) {
                return $user->getShowroom();
            }
            throw new \Exception('Utente senza Showroom operatore');
        }
        throw new \Exception('Utente non autenticato');
    }

    /**
     * @Route("/tipologia-categoria", name="tipologia_categoria", options={"expose"=true})
     * @Template("ESOperatoriBundle:Showroom/home:lista_tipologie.html.twig")
     */
    public function tipologiaCategoriaAction() {
        $request = $this->get('request');

        if ($request->isXmlHttpRequest()) {
            $sr = $this->myShowroom();
            $tipologia = $sr->getTipologieCategoria($request->get('id_categoria'));
            return array(
                'tipologia' => $tipologia
            );
        }
    }

    /**
     * Funzione che incrementa le statistiche del click per visualizzare le informazioni
     * Funzione chiamata via ajax da OperatoriBundle/Resources/public/js/showroom.js
     * Funzione mostrainformazioni(target) => js
     * 
     * @Route("informazioniShowroom", name="mostra_informazioni_showroom", options={"expose"=true})
     */
    public function informazioniShowroomAction() {
        $em = $this->getEm();
        $request = $this->get('request');
        if ($request->isXmlHttpRequest()) {

            $em = $this->getEm();
            try {

                $sr = $this->findOneBy('ESOperatoriBundle:Showroom', array('id' => $request->get('id_showroom')));
                $em->beginTransaction();

                $getter = \Doctrine\Common\Util\Inflector::camelize('get_' . $request->get('informazione_richiesta'));
                $getter_stat = \Doctrine\Common\Util\Inflector::camelize('get_click_' . $request->get('informazione_richiesta'));
                $setter = \Doctrine\Common\Util\Inflector::camelize('set_click_' . $request->get('informazione_richiesta'));

                $sr->$setter($sr->$getter_stat() + 1);

                $em->persist($sr);
                $em->flush();
                $em->commit();
                
                $out_json = array(
                    'status' => "OK",
                    'result' => $sr->$getter()
                );
            } catch (\Exception $e) {
                $out_json = array(
                    'status' => "KO",
                );
                $em->rollback();
            }

            return new \Symfony\Component\HttpFoundation\Response(json_encode($out_json));
        }
    }

}
