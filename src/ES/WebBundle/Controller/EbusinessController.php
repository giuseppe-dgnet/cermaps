<?php

namespace ES\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/")
 */
class EbusinessController extends Controller {

    use \Ephp\UtilityBundle\Controller\Traits\BaseController,
        \Ephp\GeoBundle\Controller\Traits\BaseGeoController;
    
     /**
     * @Route("/", name="ebusiness", options={"sitemap" = true})
     * @Route("/", name="homepage", options={"sitemap" = true})
     * @Template()
     */
    public function indexAction() {
        return array();
    }
    
    
    // SEZIONE E-BUSINESS
    
     /**
     * @Route("/e-business/a-cosa-serve", name="ebusinessIndex", options={"sitemap" = true})
     * @Template("ESWebBundle:Ebusiness:ebusiness/index.html.twig")
     */
    public function ebusinessIndexAction() {
        return array();
    }
    
     /**
     * @Route("/e-business/introduzione-all-ebusiness", name="ebusinessIntro", options={"sitemap" = true})
     * @Template("ESWebBundle:Ebusiness:ebusiness/ebusinessIntro.html.twig")
     */
    public function ebusinessIntroAction() {
        return array();
    }
    
     /**
     * @Route("/e-business/quanto-investire-nell-ebusiness", name="quantoInvestire", options={"sitemap" = true})
     * @Template("ESWebBundle:Ebusiness:ebusiness/quantoInvestire.html.twig")
     */
    public function quantoInvestireAction() {
        return array();
    }
    
     /**
     * @Route("/e-business/e-business-pianificazione-strategica-costi-obiettivi", name="consulenza", options={"sitemap" = true})
     * @Template("ESWebBundle:Ebusiness:ebusiness/consulenza.html.twig")
     */
    public function consulenzaAction() {
        return array();
    }
    
     /**
     * @Route("/e-business/sviluppo-contesti-soluzioni-business-to-business-b2b", name="b2b", options={"sitemap" = true})
     * @Template("ESWebBundle:Ebusiness:ebusiness/b2b.html.twig")
     */
    public function b2bAction() {
        return array();
    }
    
     /**
     * @Route("/e-business/sviluppo-contesti-soluzioni-business-to-business-b2c", name="b2c", options={"sitemap" = true})
     * @Template("ESWebBundle:Ebusiness:ebusiness/b2c.html.twig")
     */
    public function b2cAction() {
        return array();
    }
    
    
    
    // SEZIONE WEB MARKETING
    
    /**
     * @Route("/web-marketing/a-cosa-serve", name="webMarketingIndex", options={"sitemap" = true})
     * @Template("ESWebBundle:Ebusiness:webmarketing/index.html.twig")
     */
    public function webMarketingIndexAction() {
        return array();
    }
    /**
     * @Route("/web-marketing/introduzione-al-webmarketing", name="webMarketingIntro", options={"sitemap" = true})
     * @Template("ESWebBundle:Ebusiness:webmarketing/webMarketingIntro.html.twig")
     */
    public function webMarketingIntroAction() {
        return array();
    }
    /**
     * @Route("/web-marketing/marketing-mix-per-il-web", name="marketingMix", options={"sitemap" = true})
     * @Template("ESWebBundle:Ebusiness:webmarketing/marketingMix.html.twig")
     */
    public function marketingMixAction() {
        return array();
    }
    /**
     * @Route("/web-marketing/search-engine-optimization", name="seo", options={"sitemap" = true})
     * @Template("ESWebBundle:Ebusiness:webmarketing/seo.html.twig")
     */
    public function seoAction() {
        return array();
    }
    /**
     * @Route("/web-marketing/search-engine-marketing", name="sem", options={"sitemap" = true})
     * @Template("ESWebBundle:Ebusiness:webmarketing/sem.html.twig")
     */
    public function semAction() {
        return array();
    }
    
    // SEZIONE WEB ADVERTISING
    
    /**
     * @Route("/web-advertising/a-cosa-serve", name="webAdvertisingIndex", options={"sitemap" = true})
     * @Template("ESWebBundle:Ebusiness:webadvertising/index.html.twig")
     */
    public function webAdvertisingIndexAction() {
        return array();
    }
    /**
     * @Route("/web-advertising/introduzione-al-webadvertising", name="webAdvertisingIntro", options={"sitemap" = true})
     * @Template("ESWebBundle:Ebusiness:webadvertising/webAdvertisingIntro.html.twig")
     */
    public function webAdvertisingIntroAction() {
        return array();
    }
    /**
     * @Route("/web-advertising/campagne-pubblicitarie-su-google-adwords", name="campagneGoogle", options={"sitemap" = true})
     * @Template("ESWebBundle:Ebusiness:webadvertising/campagneGoogle.html.twig")
     */
    public function campagneGoogleIntroAction() {
        return array();
    }
    /**
     * @Route("/web-advertising/campagne-pubblicitarie-online", name="campagneOnline", options={"sitemap" = true})
     * @Template("ESWebBundle:Ebusiness:webadvertising/campagneOnline.html.twig")
     */
    public function campagneOnlineIntroAction() {
        return array();
    }
    
    // SEZIONE WEB DESIGN
    
    /**
     * @Route("/web-design/a-cosa-serve", name="webDesignIndex", options={"sitemap" = true})
     * @Template("ESWebBundle:Ebusiness:webdesign/index.html.twig")
     */
    public function webDesignIndexAction() {
        return array();
    }
    
    /**
     * @Route("/web-design/introduzione-al-webdesign", name="webDesignIntro", options={"sitemap" = true})
     * @Template("ESWebBundle:Ebusiness:webdesign/webDesignIntro.html.twig")
     */
    public function webDesignIntroAction() {
        return array();
    }
    
    /**
     * @Route("/web-design/webdesign-in-dettaglio", name="webDesign", options={"sitemap" = true})
     * @Template("ESWebBundle:Ebusiness:webdesign/webDesign.html.twig")
     */
    public function webDesignAction() {
        return array();
    }
        
    /**
     * @Route("/web-design/flash-design", name="flashDesign", options={"sitemap" = true})
     * @Template("ESWebBundle:Ebusiness:webdesign/flashDesign.html.twig")
     */
    public function flashDesignAction() {
        return array();
    }
    
    /**
     * @Route("/web-design/display-advertising-e-banner-design", name="displayAdv", options={"sitemap" = true})
     * @Template("ESWebBundle:Ebusiness:webdesign/displayAdv.html.twig")
     */
    public function displayAdvAction() {
        return array();
    }
    
    /**
     * @Route("/web-design/display-advertising-e-banner-design", name="contentDev", options={"sitemap" = true})
     * @Template("ESWebBundle:Ebusiness:webdesign/contentDev.html.twig")
     */
    public function contentDevAction() {
        return array();
    }
    
    
    // OBIETTIVI CORRELATI
    
    /**
     * @Route("/obiettivi-correlati/programmare-il-lancio-del-sito", name="lancio", options={"sitemap" = true})
     * @Template("ESWebBundle:Ebusiness:obiettivi/lancio.html.twig")
     */
    public function lancioAction() {
        return array();
    }
    /**
     * @Route("/obiettivi-correlati/aumentare-il-numero-di-utenti", name="utenti", options={"sitemap" = true})
     * @Template("ESWebBundle:Ebusiness:obiettivi/utenti.html.twig")
     */
    public function utentiAction() {
        return array();
    }
    /**
     * @Route("/obiettivi-correlati/aumentare-il-rapporto-di-conversione", name="conversione", options={"sitemap" = true})
     * @Template("ESWebBundle:Ebusiness:obiettivi/conversione.html.twig")
     */
    public function conversioneAction() {
        return array();
    }
    /**
     * @Route("/obiettivi-correlati/posizionare-il-sito-web-nei-motori-di-ricerca", name="posizionare", options={"sitemap" = true})
     * @Template("ESWebBundle:Ebusiness:obiettivi/posizionare.html.twig")
     */
    public function posizionareAction() {
        return array();
    }
    /**
     * @Route("/obiettivi-correlati/primeggiare-sulla-concorrenza", name="primeggiare", options={"sitemap" = true})
     * @Template("ESWebBundle:Ebusiness:obiettivi/primeggiare.html.twig")
     */
    public function primeggiareAction() {
        return array();
    }
    
    // SEZIONE THEENQ
    
    /**
     * @Route("/servizi-theenq", name="theenq", options={"sitemap" = true})
     * @Template("ESWebBundle:Ebusiness:theenq/index.html.twig")
     */
    public function theenqAction() {
        return array();
    }
    /**
     * @Route("/servizi-theenq/alert", name="alert", options={"sitemap" = true})
     * @Template("ESWebBundle:Ebusiness:theenq/alert.html.twig")
     */
    public function alertAction() {
        return array();
    }
    /**
     * @Route("/servizi-theenq/one", name="one", options={"sitemap" = true})
     * @Template("ESWebBundle:Ebusiness:theenq/one.html.twig")
     */
    public function oneAction() {
        return array();
    }
    /**
     * @Route("/servizi-theenq/two", name="two", options={"sitemap" = true})
     * @Template("ESWebBundle:Ebusiness:theenq/two.html.twig")
     */
    public function twoAction() {
        return array();
    }
    
    
    // ALTRO
    
    /**
     * @Route("/informativa-sulla-privacy", name="privacy", options={"sitemap" = true})
     * @Template()
     */
    public function privacyAction() {
        return array();
    }

    /**
     * @Route("/termini-e-condizioni-generali", name="terms_cond", options={"sitemap" = true})
     * @Template()
     */
    public function termsAction() {
        return array();
    }

}
