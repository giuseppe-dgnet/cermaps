<?php

namespace ES\OperatoriBundle\Controller\Ajax;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ES\OperatoriBundle\Form\Showroom\DescrizioneBreveType;
use ES\OperatoriBundle\Form\Showroom\DescrizioneLungaType;

/**
 * @Route("/preventivi")
 */
class PreventiviController extends \ES\OperatoriBundle\Controller\ShowroomController {

    use \Ephp\UtilityBundle\Controller\Traits\BaseController;

    /**
     * @Route("/cer", name="op_sr_cer_edit")
     * @Template()
     */
    public function modificaCerAction() {
        try {
            $showroom = $this->myShowroom();
        } catch (\Exception $e) {
            throw $this->createNotFoundException($e->getMessage(), $e); //($e->getMessage());
        }
        $cer_provenienza = $this->findBy('ESCerMapBundle:Cer\Cer', array('sottoclasse' => '00'), array('classe' => 'ASC'));

        return array(
            'showroom' => $showroom,
            'cer_provenienza' => $cer_provenienza,
            'cer_massimi' => 1000,
            'slug' => $showroom->getSlug(),
            'modificabile' => true
        );
    }

    /**
     * @Route("/cer/salva", name="op_sr_cer_save")
     * @Template()
     */
    public function salvaCerAction() {
        $em = $this->getEm();
        $showroom = $this->myShowroom();

        $_cer = $this->getRepository('ESCerMapBundle:Cer\Cer');
        $cers = json_decode($this->getParam('cer', '[]'));

        try {
            $em->beginTransaction();
            $showroom->getCer()->clear();
            if ($cers && count($cers) > 0) {
                foreach ($cers as $cer_id) {
                    $cer = $_cer->find($cer_id);
                    $showroom->addCer($cer);
                }
            }
            $em->persist($showroom);
            $em->flush();
            $em->commit();
        } catch (\Exception $e) {
            $em->rollback();
            throw $this->createNotFoundException($e->getMessage(), $e);
        }

        return $this->redirect($this->generateUrl('op_sr_cer', array('slug' => $showroom->getSlug())));
    }
    
    /**
     * Finds and displays a Professione entity.
     *
     * @Route("/ajax/cer", name="op_sr_cer_ajax")
     * @Template("ESOperatoriBundle:Ajax:Preventivi/cer/elencoAjax.html.twig")
     */
    public function elencoCerAction() {
        $classe = $this->getParam('classe');
        $codici_cers = json_decode($this->getParam('cers', array()));

        $cers = $this->findBy('ESCerMapBundle:Cer\Cer', array('classe' => $classe), array('codice' => 'ASC'));

        foreach ($cers as $cer) {
            /* @var $cer ES\CerMapBundle\Entity\Cer\Cer */
            if (in_array($cer->getId(), $codici_cers)) {
                $cer->setAvaible(true);
            }
        }

        return array('cers' => $cers, 'classe' => $classe);
    }
    
    /**
     * @Route("/servizi", name="op_sr_servizi_edit")
     * @Template()
     */
    public function modificaServiziAction() {
        try {
            $showroom = $this->myShowroom();
        } catch (\Exception $e) {
            throw $this->createNotFoundException($e->getMessage(), $e); //($e->getMessage());
        }
        
        $categorie = $this->findBy('ESOperatoriBundle:Servizi\Categoria', array(), array('categoria' => 'ASC'));

        return array(
            'showroom' => $showroom,
            'categorie' => $categorie,
            'servizi_massimi' => 1000,
            'slug' => $showroom->getSlug(),
            'modificabile' => true
        );
    }

    /**
     * @Route("/servizi/salva", name="op_sr_servizi_save")
     * @Template()
     */
    public function salvaServiziAction() {
        $em = $this->getEm();
        $showroom = $this->myShowroom();

        $_servizio = $this->getRepository('ESOperatoriBundle:Servizi\servizio');
        $cers = json_decode($this->getParam('servizi', '[]'));

        try {
            $em->beginTransaction();
            $showroom->getServiziSr()->clear();
            if ($cers && count($cers) > 0) {
                foreach ($cers as $cer_id) {
                    $cer = $_servizio->find($cer_id);
                    $showroom->addServiziSr($cer);
                }
            }
            $em->persist($showroom);
            $em->flush();
            $em->commit();
        } catch (\Exception $e) {
            $em->rollback();
            throw $this->createNotFoundException($e->getMessage(), $e);
        }

        return $this->redirect($this->generateUrl('op_sr_my', array('slug' => $showroom->getSlug())));
    }
    
}
