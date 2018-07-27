<?php

namespace ES\CerMapBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/codici-cer")
 */
class CerController extends Controller {

    use \Ephp\UtilityBundle\Controller\Traits\BaseController;

    /**
     * Mostra l'elenco delle classi CER
     * La pagina deve dare la possibilità di interazioni AJAX per vedere sotto classi e categorie
     * @Route("/", name="es_cer_classi")
     * @Template()
     */
    public function classiAction() {
        $_cer = $this->getRepository('ESCerMapBundle:Cer\Cer');
        $classi = $_cer->findBy(array('sottoclasse' => '00'), array('classe' => 'ASC'));
        return array(
            'classi' => $classi,
        );
    }

    /**
     * Mostra l'elenco delle classi CER
     * La pagina deve dare la possibilità di interazioni AJAX per vedere sotto classi e categorie
     * @Route("/ajax", name="es_cer_classi_ajax", options={"stats":{"area": {"cer-index"}, "area_from":{"param":"slug", "from":0, "chars":6}}})
     * @Template("ESCerMapBundle:Cer:classi/content.html.twig")
     */
    public function classiAjaxAction() {
        return $this->classiAction();
    }

    /**
     * Mostra l'elenco delle sottoclassi di una classe CER
     * @Route("/classe/{slug}", name="es_cer_sottoclassi", options={"stats":{"area": {"cer-index"}, "area_from":{"param":"slug", "from":0, "chars":6}}})
     * @Template()
     */
    public function sottoclassiAction($slug) {
        $_cer = $this->getRepository('ESCerMapBundle:Cer\Cer');
        $codice = $this->splitCodice($slug);
        $out = $this->classiAction();
        $out['sottoclassi'] = $_cer->findBy(array('classe' => $codice['classe'], 'categoria' => '00'), array('sottoclasse' => 'ASC'));
        $out['classe'] = $_cer->findOneBy(array('slug' => $slug));
        return $out;
    }

    /**
     * Mostra l'elenco delle sottoclassi di una classe CER
     * @Route("/ajax/classe/{slug}", name="es_cer_sottoclassi_ajax")
     * @Template("ESCerMapBundle:Cer:sottoclassi/content.html.twig")
     */
    public function sottoclassiAjaxAction($slug) {
        return $this->sottoclassiAction($slug);
    }

    /**
     * Mostra l'elenco delle categorie di una sottoclasse CER
     * @Route("/sottoclasse/{slug}", name="es_cer_categorie", options={"stats":{"area": {"cer-index"}, "area_from":{"param":"slug", "from":0, "chars":6}}})
     * @Template()
     */
    public function categorieAction($slug) {
        $_cer = $this->getRepository('ESCerMapBundle:Cer\Cer');
        $codice = $this->splitCodice($slug);
        $out = $this->classiAction();
        $out['categorie'] = $_cer->findBy(array('classe' => $codice['classe'], 'sottoclasse' => $codice['sottoclasse']), array('categoria' => 'ASC'));
        $out['sottoclasse'] = $_cer->findOneBy(array('slug' => $slug));
        $out['classe'] = $_cer->findOneBy(array('classe' => $codice['classe'], 'sottoclasse' => '00'));
        $out['sottoclassi'] = $_cer->findBy(array('classe' => $codice['classe'], 'categoria' => '00'), array('sottoclasse' => 'ASC'));
        return $out;
    }

    /**
     * Mostra l'elenco delle categorie di una sottoclasse CER
     * @Route("/ajax/sottoclasse/{slug}", name="es_cer_categorie_ajax")
     * @Template()
     */
    public function categorieAjaxAction($slug) {
        return $this->categorieAction($slug);
    }

    /**
     * Mostra il CER
     * @Route("/cer/{slug}", name="es_cer_cer", options={"stats":{"area": {"cer", "cer-index"}, "area_from":{"param":"slug", "from":0, "chars":6}}})
     * @Template("ESCerMapBundle:Map:index.html.twig")
     */
    public function cerAction($slug) {
        $mapController = new MapController();
        $mapController->setContainer($this->container);
        $out = $mapController->indexAction();

        $codice = $this->splitCodice($slug);

        $_cer = $this->getRepository('ESCerMapBundle:Cer\Cer');
        $out['categoria'] = $_cer->findOneBy(array('slug' => $slug));
        $out['sottoclasse'] = $_cer->findOneBy(array('classe' => $codice['classe'], 'sottoclasse' => $codice['sottoclasse'], 'categoria' => '00'));
        $out['classe'] = $_cer->findOneBy(array('classe' => $codice['classe'], 'sottoclasse' => '00'));
        $out['sottoclassi'] = $_cer->findBy(array('classe' => $codice['classe'], 'categoria' => '00'), array('sottoclasse' => 'ASC'));
        $out['categorie'] = $_cer->findBy(array('classe' => $codice['classe'], 'sottoclasse' => $codice['sottoclasse']), array('categoria' => 'ASC'));
        $out['twig'] = "ESCerMapBundle:Cer:cer.html.twig";
        $out['seoTitle'] = $out['categoria']->getCodice() . ' codice CER: ' . strtolower($out['categoria']->getDescrizione());
        $out['seoDescription'] = 'CerMaps - ' . $out['categoria']->getCodice() . ' codice CER: ' . str_replace($out['categoria']->getCodice(), '', $out['categoria']->getSeoDescription());
        $out['seoKeywords'] = 'CerMaps - Codici CER, ' . $out['categoria']->getSeoKeywords() . ', aziende, smaltimento, recupero';

        return $out;
    }

    /**
     * @Route("-cerca.{_format}", name="es_cer_cerca", defaults={"_format"="json"},requirements={"_format"="html|json"})
     * @Template()
     */
    public function cercaCerAction() {
        $request = $this->getRequest();
        $_sr = $this->getRepository('ESCerMapBundle:Cer\Cer');
        /* @var $_sr \ES\CerMapBundle\Entity\Cer\CerRepository */
        $out = $_sr->cerca($request->get('term'));
        $parola_ricercata = $request->get('term');
        switch ($this->getRequest()->get('_format')) {
            case 'html':
                if (count($out) > 0) {
                    return array('response' => $out, 'parola_ricercata' => $parola_ricercata);
                } else {
                    //return $this->createNotFoundException('Nessun cer trovato con questa chiave di ricerca');
                    return array('response' => $out, 'parola_ricercata' => $parola_ricercata);
//                    return $this->redirect($this->generateUrl('cerca_globale', array('parola_ricercata' => $parola_ricercata)));
                }
            default:
                return array(
                    'json' => json_encode($out)
                );
        }
    }

    private function splitCodice($codice) {
        if (strlen($codice) < 6) {
            throw new \Exception('La lunghezza del codice cER deve essere di 6 cifre');
        }
        return array(
            'classe' => substr($codice, 0, 2),
            'sottoclasse' => substr($codice, 2, 2),
            'categoria' => substr($codice, 4, 2),
            'completo' => substr($codice, 0, 6),
        );
    }

}
