<?php

namespace ES\CerMapBundle\EventListener;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\Routing\RouterInterface;
use Presta\SitemapBundle\Service\SitemapListenerInterface;
use Presta\SitemapBundle\Event\SitemapPopulateEvent;
use Presta\SitemapBundle\Sitemap\Url\UrlConcrete;

class SitemapListener implements SitemapListenerInterface {

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @var string
     */
    private $env;

    /**
     * @var integer
     */
    private $n;

    public function __construct(RouterInterface $router, Registry $doctrine, \AppKernel $kernel) {
        $this->router = $router;
        $this->em = $doctrine->getEntityManager();
        $this->env = $kernel->getEnvironment();
        $this->n = $kernel->getContainer()->get('request')->get('n', null);
    }

    public function populateSitemap(SitemapPopulateEvent $event) {
        $section = $event->getSection();
        if (in_array($this->env, array('dev', 'prod')) && (is_null($section) || $section == 'cer')) {
//            if (is_null($section)) {
//                $url = $this->router->generate('homepage', array(), true);
//                $event->getGenerator()->addUrl(
//                        new UrlConcrete(
//                        $url, new \DateTime(), UrlConcrete::CHANGEFREQ_YEARLY, 1
//                        ), 'cer'
//                );
//            } else {
            $_cer = $this->em->getRepository('ESCerMapBundle:Cer\Cer');
            //root cer
            $url = $this->router->generate('es_cer_classi', array(), true);
            $event->getGenerator()->addUrl(
                    new UrlConcrete(
                    $url, new \DateTime(), UrlConcrete::CHANGEFREQ_YEARLY, 1
                    ), 'cer'
            );

            $classi = $_cer->findBy(array('sottoclasse' => '00'), array('classe' => 'ASC'));
            foreach ($classi as $classe) {
                //classi cer
                $url = $this->router->generate('es_cer_sottoclassi', array('slug' => $classe->getSlug()), true);
                $event->getGenerator()->addUrl(
                        new UrlConcrete(
                        $url, new \DateTime(), UrlConcrete::CHANGEFREQ_YEARLY, 1
                        ), 'cer'
                );

                $sottoclassi = $_cer->findBy(array('classe' => $classe->getClasse(), 'categoria' => '00'), array('categoria' => 'ASC'));
                foreach ($sottoclassi as $sottoclasse) {
                    //sottoclassi cer
                    if ($sottoclasse->getSottoclasse() != '00') {
                        $url = $this->router->generate('es_cer_categorie', array('slug' => $sottoclasse->getSlug()), true);
                        $event->getGenerator()->addUrl(
                                new UrlConcrete(
                                $url, new \DateTime(), UrlConcrete::CHANGEFREQ_YEARLY, 1
                                ), 'cer'
                        );
                        $categorie = $_cer->findBy(array('classe' => $sottoclasse->getClasse(), 'sottoclasse' => $sottoclasse->getSottoclasse()), array('categoria' => 'ASC'));
                        foreach ($categorie as $categoria) {
                            //sottoclassi cer
                            if ($categoria->getCategoria() != '00') {
                                $url = $this->router->generate('es_cer_cer', array('slug' => $categoria->getSlug()), true);
                                $event->getGenerator()->addUrl(
                                        new UrlConcrete(
                                        $url, new \DateTime(), UrlConcrete::CHANGEFREQ_YEARLY, 1
                                        ), 'cer'
                                );
                            }
                        }
                    }
                }
//                }
            }
        }
        if (in_array($this->env, array('dev', 'prod')) && (is_null($section) || $section == 'recuperabili')) {
//            if (is_null($section)) {
//                $url = $this->router->generate('homepage', array(), true);
//                $event->getGenerator()->addUrl(
//                        new UrlConcrete(
//                        $url, new \DateTime(), UrlConcrete::CHANGEFREQ_YEARLY, 1
//                        ), 'recuperabili'
//                );
//            } else {
            $_cat = $this->em->getRepository('ESCerMapBundle:Recuperabili\Categoria');
            //root recuperabili
            $url = $this->router->generate('es_recnp', array(), true);
            $event->getGenerator()->addUrl(
                    new UrlConcrete(
                    $url, new \DateTime(), UrlConcrete::CHANGEFREQ_YEARLY, 1
                    ), 'recuperabili'
            );
            $url = $this->router->generate('es_recp', array(), true);
            $event->getGenerator()->addUrl(
                    new UrlConcrete(
                    $url, new \DateTime(), UrlConcrete::CHANGEFREQ_YEARLY, 1
                    ), 'recuperabili'
            );
            $categorie = $_cat->findAll();
            foreach ($categorie as $categoria) {
                /* @var $categoria \ES\CerMapBundle\Entity\Recuperabili\Categoria */
                $pericoloso = $categoria->getPericoloso();
                $url = $this->router->generate($pericoloso ? 'es_recp_categoria' : 'es_recnp_categoria', array('slug' => $categoria->getSlug()), true);
                $event->getGenerator()->addUrl(
                        new UrlConcrete(
                        $url, new \DateTime(), UrlConcrete::CHANGEFREQ_YEARLY, 1
                        ), 'recuperabili'
                );
                foreach ($categoria->getRifiuti() as $rifiuto) {
                    /* @var $rifiuto \ES\CerMapBundle\Entity\Recuperabili\Rifiuto */
                    $url = $this->router->generate($pericoloso ? 'es_recp_rifiuto' : 'es_recnp_rifiuto', array('slug' => $rifiuto->getSlug()), true);
                    $event->getGenerator()->addUrl(
                            new UrlConcrete(
                            $url, new \DateTime(), UrlConcrete::CHANGEFREQ_YEARLY, 1
                            ), 'recuperabili'
                    );
                }
            }
//            }
        }
        if ($this->env == 'csr' && (is_null($section) || $section == 'coordinamento')) {
            if (!$this->n) {
                $url = $this->router->generate('homepage', array(), true);
                $event->getGenerator()->addUrl(
                        new UrlConcrete(
                        $url, new \DateTime(), UrlConcrete::CHANGEFREQ_YEARLY, 1
                        ), 'coordinamento'
                );
            }
            $_cer = $this->em->getRepository('ESCerMapBundle:Cer\Cer');
            $_geo = $this->em->getRepository('EphpGeoBundle:GeoNames');
            set_time_limit(7200);
            $cers = array();
            $classi = $_cer->findBy(array('sottoclasse' => '00'), array('classe' => 'ASC'));
            foreach ($classi as $classe) {
                //classi cer
                $sottoclassi = $_cer->findBy(array('classe' => $classe->getClasse(), 'categoria' => '00'), array('categoria' => 'ASC'));
                foreach ($sottoclassi as $sottoclasse) {
                    //sottoclassi cer
                    if ($sottoclasse->getSottoclasse() != '00') {
                        $categorie = $_cer->findBy(array('classe' => $sottoclasse->getClasse(), 'sottoclasse' => $sottoclasse->getSottoclasse()), array('categoria' => 'ASC'));
                        foreach ($categorie as $categoria) {
                            //sottoclassi cer
                            if ($categoria->getCategoria() != '00') {
                                if (!$this->n) {
                                    $url = $this->router->generate($categoria->getPericoloso() ? 'centro_coordinamento_cer_rifiuti_pericolosi' : 'centro_coordinamento_cer_rifiuti', array('slug' => $categoria->getSlug()), true);
                                    $event->getGenerator()->addUrl(
                                            new UrlConcrete(
                                            $url, new \DateTime(), UrlConcrete::CHANGEFREQ_YEARLY, 1
                                            ), 'coordinamento'
                                    );
                                }
                                $cers[] = array('slug' => $categoria->getSlug(), 'pericoloso' => $categoria->getPericoloso());
                            }
                        }
                    }
                }
            }
            if ($this->n) {
                $comuni = $_geo->findBy(array('country_code' => 'IT', 'feature_code' => 'ADM3', 'timezone' => 'Europe/Rome'), array(), 1, $this->n);
                foreach ($comuni as $comune) {
                    set_time_limit(3600);
                    /* @var $comune \Ephp\GeoBundle\Entity\GeoNames */
                    $url = $this->router->generate('centro_coordinamento_geo_rifiuti', array('comune' => \Gedmo\Sluggable\Util\Urlizer::urlize($comune->getAsciiname()), 'provincia' => $comune->getAdmin2Code()), true);
                    $event->getGenerator()->addUrl(
                            new UrlConcrete(
                            $url, new \DateTime(), UrlConcrete::CHANGEFREQ_YEARLY, 1
                            ), 'coordinamento'
                    );
                    foreach ($cers as $cer) {
                        $url = $this->router->generate($cer['pericoloso'] ? 'centro_coordinamento_geocer_rifiuti_pericolosi' : 'centro_coordinamento_geocer_rifiuti', array('slug' => $cer['slug'], 'comune' => \Gedmo\Sluggable\Util\Urlizer::urlize($comune->getAsciiname()), 'provincia' => $comune->getAdmin2Code()), true);
                        $event->getGenerator()->addUrl(
                                new UrlConcrete(
                                $url, new \DateTime(), UrlConcrete::CHANGEFREQ_YEARLY, 1
                                ), 'coordinamento'
                        );
                    }
                }
            }
        }
        if ($this->env == 'csr' && (is_null($section) || $section == 'coordinamento_geo')) {
            $url = $this->router->generate('centro_coordinamento_regioni', array(), true);
            $event->getGenerator()->addUrl(
                    new UrlConcrete(
                    $url, new \DateTime(), UrlConcrete::CHANGEFREQ_YEARLY, 1
                    ), 'coordinamento_geo'
            );
            $_geo = $this->em->getRepository('EphpGeoBundle:GeoNames');
            set_time_limit(7200);
            $regioni = $_geo->findBy(array('country_code' => 'IT', 'feature_code' => 'ADM1', 'timezone' => 'Europe/Rome'));
            foreach ($regioni as $regione) {
                set_time_limit(3600);
                /* @var $regione \Ephp\GeoBundle\Entity\GeoNames */
                $url = $this->router->generate('centro_coordinamento_province', array('slug' => \Gedmo\Sluggable\Util\Urlizer::urlize($regione->getAsciiname())), true);
                $event->getGenerator()->addUrl(
                        new UrlConcrete(
                        $url, new \DateTime(), UrlConcrete::CHANGEFREQ_YEARLY, 1
                        ), 'coordinamento_geo'
                );
            }
            $province = $_geo->findBy(array('country_code' => 'IT', 'feature_code' => 'ADM2', 'timezone' => 'Europe/Rome'));
            foreach ($province as $provincia) {
                set_time_limit(3600);
                /* @var $provincia \Ephp\GeoBundle\Entity\GeoNames */
                $url = $this->router->generate('centro_coordinamento_comuni', array('slug' => \Gedmo\Sluggable\Util\Urlizer::urlize($provincia->getAsciiname())), true);
                $event->getGenerator()->addUrl(
                        new UrlConcrete(
                        $url, new \DateTime(), UrlConcrete::CHANGEFREQ_YEARLY, 1
                        ), 'coordinamento_geo'
                );
            }
            $comuni = $_geo->findBy(array('country_code' => 'IT', 'feature_code' => 'ADM3', 'timezone' => 'Europe/Rome'));
            foreach ($comuni as $comune) {
                set_time_limit(3600);
                /* @var $comune \Ephp\GeoBundle\Entity\GeoNames */
                $url = $this->router->generate('centro_coordinamento_comune', array('slug' => \Gedmo\Sluggable\Util\Urlizer::urlize($comune->getAsciiname()) . '_' . $comune->getAdmin2Code()), true);
                $event->getGenerator()->addUrl(
                        new UrlConcrete(
                        $url, new \DateTime(), UrlConcrete::CHANGEFREQ_YEARLY, 1
                        ), 'coordinamento_geo'
                );
            }
        }
        // MANCA MPS
    }

}