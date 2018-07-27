<?php

namespace ES\CerMapBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/cermap/tag/")
 */
class TagController extends \Ephp\TagBundle\Controller\DefaultController {

    use \Ephp\UtilityBundle\Controller\Traits\BaseController;

    /**
     * q = termine cercato
     * o = cerca nel/nei guppo/i (es: azienda|professionista)
     * e = non cerca nel/nei guppo/i (es: azienda|professionista)
     * d = cerca anche nella secrizione di tag
     * n = permette la creazione di nuovi tag
     * c = associa css ai tag creati
     * 
     * @Route("/cer", name="tag_cer_cerca", defaults={"_format"="json"}, options={"expose"=true})
     */
    public function cercaCerAction() {
        return $this->cerca($this->getParam('q', ''), 'cer2007no00', '', true, false, false);
    }
}
