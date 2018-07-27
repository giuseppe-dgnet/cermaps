<?php

namespace ES\OperatoriBundle\Controller\Servizi;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use ES\OperatoriBundle\Entity\Servizi\Servizio;
use ES\OperatoriBundle\Form\Servizi\ServizioType;

/**
 * Servizi\Servizio controller.
 *
 * @Route("/servizi")
 */
class ServizioController extends Controller
{
    /**
     * Lists all Servizi\Servizio entities.
     *
     * @Route("/", name="admin_servizi")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ESOperatoriBundle:Servizi\Servizio')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a Servizi\Servizio entity.
     *
     * @Route("/{id}/show", name="admin_servizi_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ESOperatoriBundle:Servizi\Servizio')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Servizi\Servizio entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Servizi\Servizio entity.
     *
     * @Route("/new", name="admin_servizi_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Servizio();
        $form   = $this->createForm(new ServizioType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Servizi\Servizio entity.
     *
     * @Route("/create", name="admin_servizi_create")
     * @Method("POST")
     * @Template("ESOperatoriBundle:Servizi\Servizio:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Servizio();
        $form = $this->createForm(new ServizioType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_servizi_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Servizi\Servizio entity.
     *
     * @Route("/{id}/edit", name="admin_servizi_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ESOperatoriBundle:Servizi\Servizio')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Servizi\Servizio entity.');
        }

        $editForm = $this->createForm(new ServizioType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Servizi\Servizio entity.
     *
     * @Route("/{id}/update", name="admin_servizi_update")
     * @Method("POST")
     * @Template("ESOperatoriBundle:Servizi\Servizio:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ESOperatoriBundle:Servizi\Servizio')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Servizi\Servizio entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new ServizioType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_servizi_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Servizi\Servizio entity.
     *
     * @Route("/{id}/delete", name="admin_servizi_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ESOperatoriBundle:Servizi\Servizio')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Servizi\Servizio entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_servizi'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
