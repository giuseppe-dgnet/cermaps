<?php

namespace ES\CerMapBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class CoordinamentoController extends Controller {

    use \Ephp\UtilityBundle\Controller\Traits\BaseController,
        \Ephp\GeoBundle\Controller\Traits\BaseGeoController,
        Traits\BaseCerMapController;

    /**
     * @Route("/", name="homepage")
     * @Route("/rifiuti-speciali-in/italia", name="centro_coordinamento_rifiuti")
     * @Template("ESCerMapBundle:Coordinamento:form.html.twig")
     */
    public function indexAction() {
        $this->getRequest()->getSession()->set('cermap.data', 0);
        $rdo = new \ES\MessengerBundle\Entity\RDO\Cer();

        $user = $this->getUser();

        if (is_object($user)) {
            /* @var $user \ES\UserBundle\Entity\User */
            $rdo->setFromUtente($user, true);
        }

        $rdo->setCerList('[]');

        $form = $this->createForm(new \ES\MessengerBundle\Form\RDO\CerType(false), $rdo);

        /*$map = $this->geCertMap(null, null, array(
            'map' => array(
                'width' => '250px',
                'height' => '250px',
                'zoomController' => array(
                    'enable' => false,
                    'zoom' => 5,
                ),
            ),
        ));*/
        
        $map = false;

        return array(
            'form' => $form->createView(),
            'map' => $map,
            'dist' => 25,
            'fb' => false,
            'geo' => false,
            'cer' => false,
            'tag' => false,
            'luogo' => false,
            'lat' => false,
            'lon' => false,
            'home' => true,
        );
    }

    /**
     * @Route("/rifiuti-speciali/{slug}", name="centro_coordinamento_cer_rifiuti")
     * @Route("/rifiuti-speciali-pericolosi/{slug}", name="centro_coordinamento_cer_rifiuti_pericolosi")
     * @Template("ESCerMapBundle:Coordinamento:form.html.twig")
     */
    public function cerAction($slug) {
        $cer = $this->findOneBy('ESCerMapBundle:Cer\Cer', array('slug' => $slug));
        /* @var $cer \ES\CerMapBundle\Entity\Cer\Cer */
        $tag = $this->findOneBy('EphpTagBundle:Tag', array('tag' => $cer->getCodice(), 'favicon' => 'tag_cer'));

        $this->getRequest()->getSession()->set('cermap.data', 0);
        $rdo = new \ES\MessengerBundle\Entity\RDO\Cer();

        $user = $this->getUser();

        if (is_object($user)) {
            /* @var $user \ES\UserBundle\Entity\User */
            $rdo->setFromUtente($user, true);
        }

        $rdo->setCerList('[' . $cer->getId() . ']');

        $form = $this->createForm(new \ES\MessengerBundle\Form\RDO\CerType(false), $rdo);

        /*$map = $this->geCertMap(null, null, array(
            'map' => array(
                'width' => '250px',
                'height' => '250px',
                'zoomController' => array(
                    'enable' => false,
                    'zoom' => 5,
                ),
            ),
        ));*/
        
        $map = false;

        return array(
            'form' => $form->createView(),
            'map' => $map,
            'dist' => 25,
            'fb' => false,
            'geo' => false,
            'cer' => $cer,
            'tag' => $tag,
            'luogo' => false,
            'lat' => false,
            'lon' => false,
        );
    }

    /**
     * @Route("/rifiuti-speciali-a/{comune}_{provincia}", name="centro_coordinamento_geo_rifiuti")
     * @Template("ESCerMapBundle:Coordinamento:form.html.twig")
     */
    public function geoAction($comune, $provincia) {
        if(strpos($provincia, '/') !== false) {
            $tmp = explode('/', $provincia);
            return $this->geoCerAction($comune, $tmp[0], $tmp[1]);
        }
        $_geo = $this->getRepository('EphpGeoBundle:GeoNames');
        /* @var $geo \Ephp\GeoBundle\Entity\GeoNamesRepository */
        $geo = $_geo->ricercaComune(str_replace('-', '%', $comune), $provincia, null, 'IT');
        /* @var $geo \Ephp\GeoBundle\Entity\GeoNames */

        $geo_session = array(
            'luogo' => $geo->getAsciiname() . ' (' . $geo->getAdmin2Code() . ')',
            'lat' => $geo->getLatitude(),
            'lon' => $geo->getLongitude(),
        );
        $this->getRequest()->getSession()->set('cermap.data', 0);
        $rdo = new \ES\MessengerBundle\Entity\RDO\Cer();

        $user = $this->getUser();

        if (is_object($user)) {
            /* @var $user \ES\UserBundle\Entity\User */
            $rdo->setFromUtente($user, true);
        }

        $rdo->setComune($geo);
        $rdo->setCerList('[]');

        $form = $this->createForm(new \ES\MessengerBundle\Form\RDO\CerType(false), $rdo);

        /*$map = $this->geCertMap($geo_session['lat'], $geo_session['lon'], array(
            'map' => array(
                'width' => '250px',
                'height' => '250px',
                'zoomController' => array(
                    'enable' => false,
                    'zoom' => 5,
                ),
            ),
        ));*/
        
        $map = false;
        
        return array(
            'form' => $form->createView(),
            'map' => $map,
            'dist' => 25,
            'fb' => false,
            'geo' => $geo,
            'cer' => false,
            'tag' => false,
            'luogo' => $geo_session['luogo'],
            'lat' => $geo_session['lat'],
            'lon' => $geo_session['lon'],
        );
    }

    /**
     * @Route("/rifiuti-speciali-a/{comune}_{provincia}/{slug}", name="centro_coordinamento_geocer_rifiuti")
     * @Route("/rifiuti-speciali-pericolosi-a/{comune}_{provincia}/{slug}", name="centro_coordinamento_geocer_rifiuti_pericolosi")
     * @Template("ESCerMapBundle:Coordinamento:form.html.twig")
     */
    public function geoCerAction($comune, $provincia, $slug) {
        $_geo = $this->getRepository('EphpGeoBundle:GeoNames');
        /* @var $geo \Ephp\GeoBundle\Entity\GeoNamesRepository */
        $geo = $_geo->ricercaComune(str_replace('-', '%', $comune), $provincia, null, 'IT');
        /* @var $geo \Ephp\GeoBundle\Entity\GeoNames */
        $cer = $this->findOneBy('ESCerMapBundle:Cer\Cer', array('slug' => $slug));
        /* @var $cer \ES\CerMapBundle\Entity\Cer\Cer */
        $tag = $this->findOneBy('EphpTagBundle:Tag', array('tag' => $cer->getCodice(), 'favicon' => 'tag_cer'));

        $geo_session = array(
            'luogo' => $geo->getAsciiname() . ' (' . $geo->getAdmin2Code() . ')',
            'lat' => $geo->getLatitude(),
            'lon' => $geo->getLongitude(),
        );
        $this->getRequest()->getSession()->set('cermap.data', 0);
        $rdo = new \ES\MessengerBundle\Entity\RDO\Cer();

        $user = $this->getUser();

        if (is_object($user)) {
            /* @var $user \ES\UserBundle\Entity\User */
            $rdo->setFromUtente($user, true);
        }

        $rdo->setComune($geo);
        $rdo->setCerList('[' . $cer->getId() . ']');

        $form = $this->createForm(new \ES\MessengerBundle\Form\RDO\CerType(false), $rdo);

        /*$map = $this->geCertMap($geo_session['lat'], $geo_session['lon'], array(
            'map' => array(
                'width' => '250px',
                'height' => '250px',
                'zoomController' => array(
                    'enable' => false,
                    'zoom' => 5,
                ),
            ),
        ));*/
        
        $map = false;
        
        return array(
            'form' => $form->createView(),
            'map' => $map,
            'dist' => 25,
            'fb' => false,
            'geo' => $geo,
            'cer' => $cer,
            'tag' => $tag,
            'luogo' => $geo_session['luogo'],
            'lat' => $geo_session['lat'],
            'lon' => $geo_session['lon'],
        );
    }

    /**
     * @Route("/regioni", name="centro_coordinamento_regioni")
     * @Template("ESCerMapBundle:Coordinamento:box.html.twig")
     */
    public function regioniAction() {
        $regioni = $this->findBy('EphpGeoBundle:GeoNames', array('country_code' => 'IT', 'timezone' => 'Europe/Rome', 'feature_code' => 'ADM1'), array('admin1_code' => 'asc'));
        return array(
            'geos' => $this->sluggaGeos($regioni),
            'next_route' => 'centro_coordinamento_province',
            'action' => 'Scegli la regione',
            'titolo_h1' => 'Elenco regioni Centro - Smaltimento Rifiuti Speciali',
            'seoTitle' => 'Elenco regioni Centro - Smaltimento Rifiuti Speciali',
            'seoDescription' => 'Elenco regioni - Centro Smaltimento Rifiuti Speciali',
            'seoKeywords' => 'Regioni, Centro Smaltimento Rifiuti Speciali, Centro Smaltimento Rifiuti Pericoloso',
        );
    }

    /**
     * @Route("/province/{slug}", name="centro_coordinamento_province")
     * @Template("ESCerMapBundle:Coordinamento:box.html.twig")
     */
    public function provinceAction($slug) {
        $_geo = $this->getRepository('EphpGeoBundle:GeoNames');
        /* @var $geo \Ephp\GeoBundle\Entity\GeoNamesRepository */
        $regione = $_geo->ricercaRegione(str_replace('-', '%', $slug), 'IT');
        $province = $this->findBy('EphpGeoBundle:GeoNames', array('country_code' => 'IT', 'timezone' => 'Europe/Rome', 'feature_code' => 'ADM2', 'admin1_code' => $regione->getAdmin1Code()), array('asciiname' => 'asc'));
        return array(
            'geos' => $this->sluggaGeos($province),
            'regione' => $this->sluggaGeo($regione),
            'action' => 'Scegli la provincia',
            'next_route' => 'centro_coordinamento_comuni',
            'titolo_h1' => 'Elenco province della '.$regione->getName().' - Centro Smaltimento Rifiuti Speciali',
            'seoTitle' => 'Elenco province della '.$regione->getName().' - Centro Smaltimento Rifiuti Speciali',
            'seoDescription' => 'Elenco province della '.$regione->getName().' - Centro Smaltimento Rifiuti Speciali',
            'seoKeywords' => 'Regioni, Centro Smaltimento Rifiuti Speciali, Centro Smaltimento Rifiuti Pericoloso, '.$regione->getName(),
        );
    }

    /**
     * @Route("/comuni/{slug}", name="centro_coordinamento_comuni")
     * @Template("ESCerMapBundle:Coordinamento:box.html.twig")
     */
    public function comuniAction($slug) {
        $_geo = $this->getRepository('EphpGeoBundle:GeoNames');
        /* @var $geo \Ephp\GeoBundle\Entity\GeoNamesRepository */
        $provincia = $_geo->ricercaProvincia(str_replace('-', '%', $slug), null, 'IT');
        $regione = $this->findOneBy('EphpGeoBundle:GeoNames', array('country_code' => 'IT', 'timezone' => 'Europe/Rome', 'feature_code' => 'ADM1', 'admin1_code' => $provincia->getAdmin1Code()));
        $comuni = $this->findBy('EphpGeoBundle:GeoNames', array('country_code' => 'IT', 'timezone' => 'Europe/Rome', 'feature_code' => 'ADM3', 'admin1_code' => $regione->getAdmin1Code(), 'admin2_code' => $provincia->getAdmin2Code()), array('asciiname' => 'asc'));
        return array(
            'geos' => $this->sluggaGeos($comuni, true),
            'regione' => $this->sluggaGeo($regione),
            'provincia' => $this->sluggaGeo($provincia),
            'action' => 'Scegli il comune',
            'next_route' => 'centro_coordinamento_comune',
            'titolo_h1' => 'Elenco comuni della '.$provincia->getName().' - Centro Smaltimento Rifiuti Speciali',
            'seoTitle' => 'Elenco comuni della '.$provincia->getName().' - Centro Smaltimento Rifiuti Speciali',
            'seoDescription' => 'Elenco comuni della '.$provincia->getName().' - Centro Smaltimento Rifiuti Speciali',
            'seoKeywords' => 'Regioni, Centro Smaltimento Rifiuti Speciali, Centro Smaltimento Rifiuti Pericoloso, '.$provincia->getName(),
        );
    }

    /**
     * @Route("/comune/{slug}", name="centro_coordinamento_comune")
     * @Template("ESCerMapBundle:Coordinamento:cer.html.twig")
     */
    public function comuneAction($slug) {
        $slug = explode('_', $slug);
        $_geo = $this->getRepository('EphpGeoBundle:GeoNames');
        /* @var $geo \Ephp\GeoBundle\Entity\GeoNamesRepository */
        $provincia = $this->findOneBy('EphpGeoBundle:GeoNames', array('country_code' => 'IT', 'timezone' => 'Europe/Rome', 'feature_code' => 'ADM2', 'admin2_code' => $slug[1]));
        $regione = $this->findOneBy('EphpGeoBundle:GeoNames', array('country_code' => 'IT', 'timezone' => 'Europe/Rome', 'feature_code' => 'ADM1', 'admin1_code' => $provincia->getAdmin1Code()));
        $comune = $_geo->ricercaComune(str_replace('-', '%', $slug[0]), $provincia->getAdmin2Code(), null, 'IT');
        $cers = array(
            'classi' => $this->findBy('ESCerMapBundle:Cer\Cer', array('sottoclasse' => '00')),
            'sottoclassi' => array(),
            'categorie' => array(),
        );
        foreach($cers['classi'] as $classe) {
            /* @var $classe \ES\CerMapBundle\Entity\Cer\Cer */
            $cers['sottoclassi'][$classe->getClasse()] = $this->findBy('ESCerMapBundle:Cer\Cer', array('classe' => $classe->getClasse(), 'categoria' => '00'));
            foreach($cers['sottoclassi'][$classe->getClasse()] as $sottoclasse) {
                /* @var $sottoclasse \ES\CerMapBundle\Entity\Cer\Cer */
                $cers['categorie'][$classe->getClasse()][$sottoclasse->getSottoclasse()] = $this->findBy('ESCerMapBundle:Cer\Cer', array('classe' => $classe->getClasse(), 'sottoclasse' => $sottoclasse->getSottoclasse()));
            }
        }
        
        return array(
            'cers' => $cers,
            'comune' => $this->sluggaGeo($comune),
            'regione' => $this->sluggaGeo($regione),
            'provincia' => $this->sluggaGeo($provincia),
            'provincia_sigla' => $provincia->getAdmin2Code(),
            'action' => 'Scegli il comune',
            'next_route' => 'centro_coordinamento_comuni',
            'titolo_h1' => 'Elenco comuni della '.$provincia->getName().' - Centro Smaltimento Rifiuti Speciali',
            'seoTitle' => 'Elenco comuni della '.$provincia->getName().' - Centro Smaltimento Rifiuti Speciali',
            'seoDescription' => 'Elenco comuni della '.$provincia->getName().' - Centro Smaltimento Rifiuti Speciali',
            'seoKeywords' => 'Regioni, Centro Smaltimento Rifiuti Speciali, Centro Smaltimento Rifiuti Pericoloso, '.$provincia->getName(),
        );
    }
    
    private function sluggaGeos($geos, $provincia = false) {
        $out = array();
        foreach ($geos as $geo) {
            $out[] = $this->sluggaGeo($geo, $provincia);
        }
        return $out;
    }
    private function sluggaGeo(\Ephp\GeoBundle\Entity\GeoNames $geo, $provincia = false) {
         return array('nome' => $geo->getName(), 'slug' => \Gedmo\Sluggable\Util\Urlizer::urlize($geo->getAsciiname()).($provincia ? '_'.$geo->getAdmin2Code() : ''));
    }
}
