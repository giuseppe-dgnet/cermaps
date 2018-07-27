<?php

namespace ES\OperatoriBundle\EventListener;

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

    public function __construct(RouterInterface $router, Registry $doctrine, \AppKernel $kernel) {
        $this->router = $router;
        $this->em = $doctrine->getEntityManager();
        $this->env = $kernel->getEnvironment();
    }

    public function populateSitemap(SitemapPopulateEvent $event) {
        $section = $event->getSection();
        if (in_array($this->env, array('dev', 'prod')) && (is_null($section) || $section == 'showroom')) {
            $_cer = $this->em->getRepository('ESOperatoriBundle:Showroom');
            $pag = 0;
//            if (!is_null($section)) {
//                $n = 0;
//                $sm = 0;
//                $url = $this->router->generate('homepage', array(), true);
//                $event->getGenerator()->addUrl(
//                        new UrlConcrete(
//                        $url, new \DateTime(), UrlConcrete::CHANGEFREQ_YEARLY, 1
//                        ), 'showroom'
//                );
//                do {
//                    set_time_limit(3600);
//                    $showrooms = $_cer->findBy(array(), array(), 100, $pag * 100);
//                    foreach ($showrooms as $showroom) {
//                        /* @var $showroom \ES\OperatoriBundle\Entity\Showroom */
//                        $n++;
//                        if($n == \Presta\SitemapBundle\Sitemap\XmlConstraint::LIMIT_ITEMS) {
//                            $event->getGenerator()->addUrl(
//                                    new UrlConcrete(
//                                    $url, new \DateTime(), UrlConcrete::CHANGEFREQ_YEARLY, 1
//                                    ), 'showroom_'.$sm
//                            );
//                            $sm++;
//                            $n = 0;
//                        }
//                        if ($showroom->getHasCer()) {
//                            $n++;
//                            if($n == \Presta\SitemapBundle\Sitemap\XmlConstraint::LIMIT_ITEMS) {
//                                $event->getGenerator()->addUrl(
//                                        new UrlConcrete(
//                                        $url, new \DateTime(), UrlConcrete::CHANGEFREQ_YEARLY, 1
//                                        ), 'showroom_'.$sm
//                                );
//                                $sm++;
//                                $n = 0;
//                            }
//                        }
//                        if ($showroom->getHasTipologie()) {
//                            $n++;
//                            if($n == \Presta\SitemapBundle\Sitemap\XmlConstraint::LIMIT_ITEMS) {
//                                $event->getGenerator()->addUrl(
//                                        new UrlConcrete(
//                                        $url, new \DateTime(), UrlConcrete::CHANGEFREQ_YEARLY, 1
//                                        ), 'showroom_'.$sm
//                                );
//                                $sm++;
//                                $n = 0;
//                            }
//                        }
//                        if (count($showroom->getServiziSr()) > 0) {
//                            $n++;
//                            if($n == \Presta\SitemapBundle\Sitemap\XmlConstraint::LIMIT_ITEMS) {
//                                $event->getGenerator()->addUrl(
//                                        new UrlConcrete(
//                                        $url, new \DateTime(), UrlConcrete::CHANGEFREQ_YEARLY, 1
//                                        ), 'showroom_'.$sm
//                                );
//                                $sm++;
//                                $n = 0;
//                            }
//                        }
//                    }
//                    $pag++;
//                } while (count($showrooms) == 100);
//            } else {
                do {
                    set_time_limit(3600);
                    $showrooms = $_cer->findBy(array(), array(), 100, $pag * 100);
                    foreach ($showrooms as $showroom) {
                        /* @var $showroom \ES\OperatoriBundle\Entity\Showroom */
                        //classi cer
                        $url = $this->router->generate('op_sr_open', array('slug' => $showroom->getSlug()), true);
                        $event->getGenerator()->addUrl(
                                new UrlConcrete(
                                $url, new \DateTime(), UrlConcrete::CHANGEFREQ_DAILY, 1
                                ), 'showroom'
                        );
                        if ($showroom->getHasCer()) {
                            $url = $this->router->generate('op_sr_cer', array('slug' => $showroom->getSlug()), true);
                            $event->getGenerator()->addUrl(
                                    new UrlConcrete(
                                    $url, new \DateTime(), UrlConcrete::CHANGEFREQ_DAILY, 1
                                    ), 'showroom'
                            );
                        }
                        if ($showroom->getHasTipologie()) {
                            $url = $this->router->generate('op_sr_mps', array('slug' => $showroom->getSlug()), true);
                            $event->getGenerator()->addUrl(
                                    new UrlConcrete(
                                    $url, new \DateTime(), UrlConcrete::CHANGEFREQ_DAILY, 1
                                    ), 'showroom'
                            );
                        }
                        if (count($showroom->getServiziSr()) > 0) {
                            $url = $this->router->generate('op_sr_servizi', array('slug' => $showroom->getSlug()), true);
                            $event->getGenerator()->addUrl(
                                    new UrlConcrete(
                                    $url, new \DateTime(), UrlConcrete::CHANGEFREQ_DAILY, 1
                                    ), 'showroom'
                            );
                        }
                    }
                    $pag++;
                } while (count($showrooms) == 100);
//            }
        }
    }

}