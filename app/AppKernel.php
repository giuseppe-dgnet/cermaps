<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new JMS\AopBundle\JMSAopBundle(),
            new JMS\DiExtraBundle\JMSDiExtraBundle($this),
            new JMS\SecurityExtraBundle\JMSSecurityExtraBundle(),

            new APY\JsFormValidationBundle\APYJsFormValidationBundle(),
            new Bazinga\ExposeTranslationBundle\BazingaExposeTranslationBundle(),
            new Ivory\GoogleMapBundle\IvoryGoogleMapBundle(),
            new PunkAve\FileUploaderBundle\PunkAveFileUploaderBundle(),
            new Presta\SitemapBundle\PrestaSitemapBundle(),
            
            new FOS\UserBundle\FOSUserBundle(),
            new FOS\FacebookBundle\FOSFacebookBundle(),
            new FOS\JsRoutingBundle\FOSJsRoutingBundle(),
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
            new Ephp\ACLBundle\EphpACLBundle(),
            new Ephp\GeoBundle\EphpGeoBundle(),
            new Ephp\TagBundle\EphpTagBundle(),
            new Ephp\UtilityBundle\EphpUtilityBundle(),
            new Ephp\WsBundle\EphpWsBundle(),
            new Ephp\StatsBundle\EphpStatsBundle(),
            
            new ES\WebBundle\ESWebBundle(),
            new ES\UserBundle\ESUserBundle(),
            new ES\CerMapBundle\ESCerMapBundle(),
            new ES\OperatoriBundle\ESOperatoriBundle(),
            new ES\UploadFotoBundle\ESUploadFotoBundle(),
            new ES\FotoBundle\ESFotoBundle(),
            new ES\MessengerBundle\ESMessengerBundle(),
            new ES\NewsBundle\ESNewsBundle(),
            );

        if (in_array($this->getEnvironment(), array('dev', 'test', 'admin'))) {
            $bundles[] = new Acme\DemoBundle\AcmeDemoBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }
}
