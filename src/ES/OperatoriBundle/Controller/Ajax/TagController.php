<?php

namespace ES\OperatoriBundle\Controller\Ajax;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/tag")
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
     * @Route("/cer/{slug}", name="tag_cer_sr_cerca", defaults={"_format"="json"}, options={"expose"=true})
     */
    public function cercaCerAction($slug) {
        $q = $this->getParam('q', '');
        $out = array();
        if (strlen($q) >= 2) {
            $sr = $this->findOneBy('ESOperatoriBundle:Showroom', array('slug' => $slug));
            $conn = $this->getEm()->getConnection();
            $sql = "
select t.*
  from tag_tags t
 inner join op_showroom_tags st on t.id = st.tag_id
 where st.showroom_id = :sr
   and st.campo = 'cer'
   and (t.descrizione LIKE :q OR t.tag LIKE :q)
";
         $tags = $conn->executeQuery($sql, array('sr' => $sr->getId(), 'q' => "%{$q}%"))->fetchAll();
            if (count($tags) > 0) {
                foreach ($tags as $tag) {
                    $out[] = array(
                        'id' => $tag['id'],
                        'name' => $tag['tag'],
                        'css' => $tag['favicon'] ? : 'tag_default',
                        'descrizione' => $tag['descrizione'],
                    );
                }
            }
        }
        
        return new \Symfony\Component\HttpFoundation\Response(json_encode($out));
    }
    /**
     * q = termine cercato
     * o = cerca nel/nei guppo/i (es: azienda|professionista)
     * e = non cerca nel/nei guppo/i (es: azienda|professionista)
     * d = cerca anche nella secrizione di tag
     * n = permette la creazione di nuovi tag
     * c = associa css ai tag creati
     * 
     * @Route("/mps/{slug}", name="tag_mps_sr_cerca", defaults={"_format"="json"}, options={"expose"=true})
     */
    public function cercaMpsAction($slug) {
        $q = $this->getParam('q', '');
        $out = array();
        if (strlen($q) >= 2) {
            $sr = $this->findOneBy('ESOperatoriBundle:Showroom', array('slug' => $slug));
            $conn = $this->getEm()->getConnection();
            $sql = "
select t.*
  from tag_tags t
 inner join op_showroom_tags st on t.id = st.tag_id
 where st.showroom_id = :sr
   and st.campo = 'mps'
   and (t.descrizione LIKE :q OR t.tag LIKE :q)
";
         $tags = $conn->executeQuery($sql, array('sr' => $sr->getId(), 'q' => "%{$q}%"))->fetchAll();
            if (count($tags) > 0) {
                foreach ($tags as $tag) {
                    $out[] = array(
                        'id' => $tag['id'],
                        'name' => $tag['tag'],
                        'css' => $tag['favicon'] ? : 'tag_default',
                        'descrizione' => $tag['descrizione'],
                    );
                }
            }
        }
        
        return new \Symfony\Component\HttpFoundation\Response(json_encode($out));
    }

}
