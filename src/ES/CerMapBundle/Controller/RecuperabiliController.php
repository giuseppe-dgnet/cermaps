<?php

namespace ES\CerMapBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use ES\CerMapBundle\Entity\Recuperabili\Rifiuto;

//use EcoSeekr\Bundle\CERBundle\Manager\CERManager;

/**
 * @Route("/smaltimento-recupero-rifiuti")
 */
class RecuperabiliController extends Controller {

    use \Ephp\UtilityBundle\Controller\Traits\BaseController;

    /**
     * Pagina di accesso delle Landing Page sui rifiuti
     * @Route("-non-pericolosi/{tipo}", name="es_recnp", defaults={"tipo"="recnp"}, options={"stats":{"area": {"recuperabili", "recuperabili-np"}}})
     * @Route("-pericolosi/{tipo}", name="es_recp", defaults={"tipo"="recp"}, options={"stats":{"area": {"recuperabili", "recuperabili-per"}}})
     * @Template()
     */
    public function categorieAction($tipo) {
        $pericoloso = $tipo == 'recp';
        $_cat = $this->getRepository('ESCerMapBundle:Recuperabili\Categoria');
        $categorie = $_cat->findBy(array('pericoloso' => $pericoloso));

        return array(
            'categorie' => $categorie,
            'route' => $tipo,
            'tipo' => $tipo,
            'pericoloso' => $pericoloso
        );
    }

    /**
     * Pagina di accesso delle Landing Page sui rifiuti
     * @Route("-non-pericolosi/categoria/{slug}/{tipo}", name="es_recnp_categoria", defaults={"tipo"="recnp"}, options={"stats":{"area": {"recuperabili", "recuperabili-np"}}}))
     * @Route("-pericolosi/categoria/{slug}/{tipo}", name="es_recp_categoria", defaults={"tipo"="recp"}, options={"stats":{"area": {"recuperabili", "recuperabili-per"}}}))
     * @Template()
     */
    public function categoriaAction($slug, $tipo) {
        $pericoloso = $tipo == 'recp';
        $_cat = $this->getRepository('ESCerMapBundle:Recuperabili\Categoria');
        $categoria = $_cat->findOneBy(array('slug' => $slug));
        $categorie = $_cat->findBy(array('pericoloso' => $pericoloso));
        return array(
            'categoria' => $categoria,
            'categorie' => $categorie,
            'route' => $tipo,
            'tipo' => $tipo,
            'pericoloso' => $pericoloso
        );
    }

    /**
     * Pagina di accesso delle Landing Page sui rifiuti
     * @Route("-ajax-non-pericolosi/di/{slug}/{tipo}", name="es_ajax_recnp_rifiuto", defaults={"tipo"="recnp"},options={"expose"=true})
     * @Route("-ajax-pericolosi/di/{slug}/{tipo}", name="es_ajax_recp_rifiuto", defaults={"tipo"="recp"},options={"expose"=true})
     * @Template("ESCerMapBundle:Recuperabili:scheda/content.html.twig")
     */
    public function ajaxSchedaAction($slug, $tipo) {
        return array_merge($this->schedaAction($slug, $tipo), array('col_sx' => false));
    }

    /**
     * Pagina di accesso delle Landing Page sui rifiuti
     * @Route("-non-pericolosi/di/{slug}/{tipo}", name="es_recnp_rifiuto", defaults={"tipo"="recnp"}, options={"stats":{"area": {"recuperabili", "recuperabili-np"}}}))
     * @Route("-pericolosi/di/{slug}/{tipo}", name="es_recp_rifiuto", defaults={"tipo"="recp"}, options={"stats":{"area": {"recuperabili", "recuperabili-per"}}}))
     * @Template()
     */
    public function schedaAction($slug, $tipo) {
        $pericoloso = $tipo == 'recp';
        $_rif = $this->getRepository('ESCerMapBundle:Recuperabili\Rifiuto');
        $_cat = $this->getRepository('ESCerMapBundle:Recuperabili\Categoria');
        $rifiuto = $_rif->findOneBy(array('slug' => $slug));
        $categoria = $rifiuto->getCategoria();
        $rifiuti = $categoria->getRifiuti();
        $categorie = $_cat->findBy(array('pericoloso' => $pericoloso));
        return array(
            'rifiuto' => $rifiuto,
            'rifiuti' => $rifiuti,
            'categoria' => $categoria,
            'categorie' => $categorie,
            'route' => $tipo,
            'tipo' => $tipo,
            'pericoloso' => $pericoloso
        );
    }

    /**
     * Pagina di accesso delle Landing Page sui rifiuti
     * @Route("/cer/{slug}", name="web_lps_cer_rifiuti")
     * @Template()
     */
    public function cerAction($slug) {
        $geo = $this->getRequest()->getSession()->get('posizione', false);
        $output = $this->getScheda($slug);
        $link = $this->getPrevNextSlug($output['id'], $output['categoria']['id']);
        if (!$link['prev']) {
            $link['prev'] = array('slug' => $slug, 'titolo' => $output['titolo_lps']);
        }
        if (!$link['next']) {
            $link['next'] = array('slug' => $slug, 'titolo' => $output['titolo_lps']);
        }

        // Tiro fuori gli showroom
        $_sr = $this->em->getRepository('EcoSeekr\Bundle\ShowRoomBundle\Entity\ShowRoom');
        $term = array(
            'cer' => array(),
        );
        foreach ($output['cer'] as $codice => $info) {
            $term['cer'][] = $codice;
        }

        $out = $_sr->cerca($term, $geo, array('azienda' => true), array('cer' => true, 'comune' => true), array('cer' => true));

        return array(
            'rifiuto' => $output,
            'link' => $link,
            'results' => $out,
            'route' => 'lps',
            'tipo' => $this->tipo, 'pericoloso' => $this->pericoloso,
            'registra' => true,
            'messaggio_errore' => 'Nessun operatore smaltisce o recupera i CER selezionati',
        );
    }

    /**
     * Pagina di accesso delle Landing Page sui rifiuti
     * @Route("/mps/{slug}", name="web_lps_mps_rifiuti")
     * @Template()
     */
    public function mpsAction($slug) {
        $geo = $this->getRequest()->getSession()->get('posizione', false);
        $output = $this->getScheda($slug);
        $link = $this->getPrevNextSlug($output['id'], $output['categoria']['id']);
        if (!$link['prev']) {
            $link['prev'] = array('slug' => $slug, 'titolo' => $output['titolo_lps']);
        }
        if (!$link['next']) {
            $link['next'] = array('slug' => $slug, 'titolo' => $output['titolo_lps']);
        }

        // Tiro fuori gli showroom
        $_sr = $this->em->getRepository('EcoSeekr\Bundle\ShowRoomBundle\Entity\ShowRoom');
        $term = array(
            'mps' => array(),
        );
        foreach ($output['categorie_mps'] as $cat => $info) {
            foreach ($info['mps'] as $mps) {
                $term['mps'][] = $mps['id'];
            }
        }

        $out = $_sr->cerca($term, $geo, array('azienda' => true, 'professionista' => true), array('mps' => true, 'comune' => true), array('mps' => true));

        return array(
            'rifiuto' => $output,
            'link' => $link,
            'results' => $out,
            'route' => 'lps',
            'tipo' => $this->tipo, 'pericoloso' => $this->pericoloso,
            'registra' => true,
            'messaggio_errore' => 'Nessun operatore acquista o vende le Materie Prime Seconde selezionate',
        );
    }

    /**
     * @Route("-non-pericolosi-cerca.{_format}/{tipo}", name="es_recnp_cerca", defaults={"tipo"="recnp", "_format"="json"},requirements={"_format"="html|json"})
     * @Route("-pericolosi-cerca.{_format}/{tipo}", name="es_recp_cerca", defaults={"tipo"="recp", "_format"="json"},requirements={"_format"="html|json"})
     * @Template()
     */
    public function cercaRifiutiAction($tipo) {
        $pericoloso = $tipo == 'recp';
        $request = $this->getRequest();
        $_sr = $this->getRepository('ESCerMapBundle:Recuperabili\Rifiuto');
        /* @var $_sr \ES\CerMapBundle\Entity\Recuperabili\RifiutoRepository */
        $out = $_sr->cerca($request->get('term'), $pericoloso);
        $parola_ricercata = $request->get('term');
        switch ($this->getRequest()->get('_format')) {
            case 'html':
                if (count($out) > 0) {
                    return array(
                        'response' => $out,
                        'parola_ricercata' => $parola_ricercata,
                        'route' => $tipo,
                        'tipo' => $tipo,
                        'pericoloso' => $pericoloso
                    );
                } else {
                    return $this->redirect($this->generateUrl('cerca_globale', array('parola_ricercata' => $parola_ricercata)));
                }
            default:
                return array(
                    'json' => json_encode($out)
                );
        }
    }

    /**
     * Pagina di accesso delle Landing Page sui rifiuti
     * @Route("/bacheca", name="web_lps_bacheca")
     * @Template()
     */
    public function bachecaLpsAction() {
        return array();
    }

}
