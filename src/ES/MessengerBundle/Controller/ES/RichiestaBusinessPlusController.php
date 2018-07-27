<?php

namespace ES\MessengerBundle\Controller\ES;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use ES\MessengerBundle\Entity\ES\RichiestaBusinessPlus;
use ES\MessengerBundle\Form\ES\RichiestaBusinessPlusType;
use ES\MessengerBundle\Form\ES\GestioneRichiestaBusinessPlusType;

/**
 * ES\RichiestaBusinessPlus controller.
 *
 * @Route("/business_plus")
 */
class RichiestaBusinessPlusController extends Controller {

    /**
     * Lists all ES\RichiestaBusinessPlus entities.
     *
     * @Route("/admize", name="business_plus")
     * @Template()
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('EcoSeekrMessengerBundle:ES\RichiestaBusinessPlus')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a ES\RichiestaBusinessPlus entity.
     *
     * @Route("/admize/{id}/show", name="business_plus_show")
     * @Template()
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('EcoSeekrMessengerBundle:ES\RichiestaBusinessPlus')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ES\RichiestaBusinessPlus entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),);
    }

    /**
     * Displays a form to create a new ES\RichiestaBusinessPlus entity.
     *
     * @Route("/richiesta", name="business_plus_new")
     * @Template()
     */
    public function newAction() {
        $entity = new RichiestaBusinessPlus();
        if ($this->getRequest()->get('slug', false)) {
            $showroom = $this->getEm()->getRepository('ES\ShowRoomBundle\Entity\ShowRoom')->findOneBy(array('slug' => $this->getRequest()->get('slug', false)));
            if ($showroom) {
                $referente = $showroom->getReferente();
                $entity->setNome($referente->getNome());
                $entity->setCognome($referente->getCognome());
                if ($showroom->getAzienda()) {
                    $entity->setAzienda($showroom->getAzienda()->getDenominazioneCompleta());
                    $entity->setCodiceFiscale($showroom->getAzienda()->getCodiceFiscale());
                } else {
                    $entity->setCodiceFiscale($showroom->getProfessionista()->getCodiceFiscale());
                }
                $entity->setComune($showroom->getSedeLegale()->getComune());
                $entity->setEmail($referente->getEmail());
            }
        }
        $form = $this->createForm(new RichiestaBusinessPlusType(), $entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView()
        );
    }

    /**
     * Creates a new ES\RichiestaBusinessPlus entity.
     *
     * @Route("/invia/richiesta", name="business_plus_create")
     * @Method("post")
     */
    public function createAction() {
        $entity = new RichiestaBusinessPlus();
        $request = $this->getRequest();
        $form = $this->createForm(new RichiestaBusinessPlusType(), $entity);
        $form->bindRequest($request);

        $em = $this->getEm();
        $_geo = $em->getRepository('ES\GeoBundle\Entity\GeoNames');
        $_azi = $em->getRepository('ES\AziendaBundle\Entity\Azienda');
        $richiesta = $request->get('richiestabusinessplus');
        if ($form->isValid()) {
            if ($richiesta['comune']) {
                $comune = $_geo->find($richiesta['comune']);
                $entity->setComune($comune);
            }
            if ($richiesta['impresa']) {
                $impresa = $_azi->find($richiesta['impresa']);
                $entity->setImpresa($impresa);
            }
            $em->persist($entity);
            $em->flush();

            return new \Symfony\Component\HttpFoundation\Response('OK');
        }

        return new \Symfony\Component\HttpFoundation\Response('KO');
    }

    /**
     * Displays a form to edit an existing ES\RichiestaBusinessPlus entity.
     *
     * @Route("/admize/{id}/edit", name="business_plus_edit")
     * @Template()
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('EcoSeekrMessengerBundle:ES\RichiestaBusinessPlus')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ES\RichiestaBusinessPlus entity.');
        }

        $editForm = $this->createForm(new GestioneRichiestaBusinessPlusType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing ES\RichiestaBusinessPlus entity.
     *
     * @Route("/admize/{id}/update", name="business_plus_update")
     * @Method("post")
     * @Template("EcoSeekrMessengerBundle:ES\RichiestaBusinessPlus:edit.html.twig")
     */
    public function updateAction($id) {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('EcoSeekrMessengerBundle:ES\RichiestaBusinessPlus')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ES\RichiestaBusinessPlus entity.');
        }

        $editForm = $this->createForm(new GestioneRichiestaBusinessPlusType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('business_plus'));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a ES\RichiestaBusinessPlus entity.
     *
     * @Route("/admize/{id}/delete", name="business_plus_delete")
     * @Method("post")
     */
    public function deleteAction($id) {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('EcoSeekrMessengerBundle:ES\RichiestaBusinessPlus')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find ES\RichiestaBusinessPlus entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('business_plus'));
    }

    private function createDeleteForm($id) {
        return $this->createFormBuilder(array('id' => $id))
                        ->add('id', 'hidden')
                        ->getForm()
        ;
    }

    /**
     * @return \Doctrine\ORM\EntityManager 
     */
    public function getEm() {
        return $this->getDoctrine()->getEntityManager();
    }

}
