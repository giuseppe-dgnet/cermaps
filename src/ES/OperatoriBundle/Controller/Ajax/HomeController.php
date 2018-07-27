<?php

namespace ES\OperatoriBundle\Controller\Ajax;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ES\OperatoriBundle\Form\Showroom\DescrizioneBreveType;
use ES\OperatoriBundle\Form\Showroom\DescrizioneLungaType;
use ES\OperatoriBundle\Form\Showroom\AttivitaType;
use ES\OperatoriBundle\Form\Showroom\DatiSocietariType;
use ES\OperatoriBundle\Form\Showroom\ContattiType;
/**
 * @Route("/home")
 */
class HomeController extends \ES\OperatoriBundle\Controller\ShowroomController {

    use \Ephp\UtilityBundle\Controller\Traits\BaseController;

    /**
     * @Route("/descrizione-attivita/form", name="op_sr_form_descrizione_breve")
     * @Template()
     */
    public function formDescrizioneBreveAction() {
        $entity = $this->myShowroom();
        $form = $this->createForm(new DescrizioneBreveType(), $entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a new Richiesta entity.
     *
     * @Route("/descrizione-attivita/salva", name="op_sr_save_descrizione_breve")
     * @Template("ESOperatoriBundle:Showroom:home/intestazione/intestazione.html.twig")
     */
    public function saveDescrizioneBreveAction(Request $request) {
        $entity = $this->myShowroom();
        $form = $this->createForm(new DescrizioneBreveType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getEm();
            $em->persist($entity);
            $em->flush();
        }

        return array(
            'showroom' => $entity,
            'modificabile' => true,
        );
    }
    
    
    
    /**
     * @Route("/descrizione/form", name="op_sr_form_descrizione_lunga")
     * @Template()
     */
    public function formDescrizioneLungaAction() {
        $entity = $this->myShowroom();
        $form = $this->createForm(new DescrizioneLungaType(), $entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a new Richiesta entity.
     *
     * @Route("/descrizione/salva", name="op_sr_save_descrizione_lunga")
     * @Template("ESOperatoriBundle:Showroom:home/presentazione/presentazione.html.twig")
     */
    public function saveDescrizioneLungaAction(Request $request) {
        $entity = $this->myShowroom();
        $form = $this->createForm(new DescrizioneLungaType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getEm();
            $em->persist($entity);
            $em->flush();
        }

        return array(
            'showroom' => $entity,
            'modificabile' => true,
        );
    }

    /**
     * @Route("/attivita/form", name="op_sr_form_attivita")
     * @Template()
     */
    public function formAttivitaAction() {
        $entity = $this->myShowroom();
        $form = $this->createForm(new AttivitaType(), $entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a new Richiesta entity.
     *
     * @Route("/attivita/salva", name="op_sr_save_attivita")
     * @Template("ESOperatoriBundle:Showroom:home/attivita/attivita.html.twig")
     */
    public function saveAttivitaAction(Request $request) {
        $entity = $this->myShowroom();
        $form = $this->createForm(new AttivitaType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getEm();
            $em->persist($entity);
            $em->flush();
        }

        return array(
            'showroom' => $entity,
            'modificabile' => true,
        );
    }
    
    
    /**
     * @Route("/dati_societari/form", name="op_sr_form_dati_societari")
     * @Template()
     */
    public function formDatiSocietariAction() {
        $entity = $this->myShowroom();
        $comune = $entity->getComune()->getNomeComune();
        $entity->setComuneId();
        $form = $this->createForm(new DatiSocietariType(false), $entity);

        return array(
            'entity' => $entity,
            'comune' => $comune,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a new Richiesta entity.
     *
     * @Route("/dati_societari/salva", name="op_sr_save_dati_societari")
     * @Template("ESOperatoriBundle:Showroom:home/sede/sede.html.twig")
     */
    public function saveDatiSocietariAction(Request $request) {
        $entity = $this->myShowroom();
        $_showroom = $request->get('showroom');
        $form = $this->createForm(new DatiSocietariType($_showroom['comune']), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getEm();
            $em->persist($entity);
            $em->flush();
        }

        return array(
            'showroom' => $entity,
            'modificabile' => true,
        );
    }
    /**
     * @Route("/contatti/form", name="op_sr_form_contatti")
     * @Template()
     */
    public function formContattiAction() {
        $entity = $this->myShowroom();
        $form = $this->createForm(new ContattiType(), $entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a new Richiesta entity.
     *
     * @Route("/contatti/salva", name="op_sr_save_contatti")
     * @Template("ESOperatoriBundle:Showroom:home/contatti/contatti.html.twig")
     */
    public function saveContattiAction(Request $request) {
        $entity = $this->myShowroom();
        $form = $this->createForm(new ContattiType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getEm();
            $em->persist($entity);
            $em->flush();
        }

        return array(
            'showroom' => $entity,
            'modificabile' => true,
        );
    }
    


}
