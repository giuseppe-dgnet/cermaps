<?php

namespace ES\OperatoriBundle\Controller\Servizi;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use ES\OperatoriBundle\Entity\Servizi\Categoria;
use ES\OperatoriBundle\Form\Servizi\CategoriaType;

/**
 * Servizi\Categoria controller.
 *
 * @Route("/categorie/servizi")
 */
class CategoriaController extends Controller
{
    /**
     * Lists all Servizi\Categoria entities.
     *
     * @Route("/", name="admin_categorie_servizi")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ESOperatoriBundle:Servizi\Categoria')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a Servizi\Categoria entity.
     *
     * @Route("/{id}/show", name="admin_categorie_servizi_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ESOperatoriBundle:Servizi\Categoria')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Servizi\Categoria entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Servizi\Categoria entity.
     *
     * @Route("/new", name="admin_categorie_servizi_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Categoria();
        $form   = $this->createForm(new CategoriaType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Servizi\Categoria entity.
     *
     * @Route("/create", name="admin_categorie_servizi_create")
     * @Method("POST")
     * @Template("ESOperatoriBundle:Servizi\Categoria:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Categoria();
        $form = $this->createForm(new CategoriaType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_categorie_servizi_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Servizi\Categoria entity.
     *
     * @Route("/{id}/edit", name="admin_categorie_servizi_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ESOperatoriBundle:Servizi\Categoria')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Servizi\Categoria entity.');
        }

        $editForm = $this->createForm(new CategoriaType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Servizi\Categoria entity.
     *
     * @Route("/{id}/update", name="admin_categorie_servizi_update")
     * @Method("POST")
     * @Template("ESOperatoriBundle:Servizi\Categoria:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ESOperatoriBundle:Servizi\Categoria')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Servizi\Categoria entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new CategoriaType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_categorie_servizi_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Servizi\Categoria entity.
     *
     * @Route("/{id}/delete", name="admin_categorie_servizi_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ESOperatoriBundle:Servizi\Categoria')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Servizi\Categoria entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_categorie_servizi'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
