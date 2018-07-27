<?php

namespace ES\OperatoriBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

//use EcoSeekr\Bundle\CERBundle\Manager\CERManager;

/**
 * @Route("/operatori")
 */
class AdminController extends Controller {

    use \Ephp\UtilityBundle\Controller\Traits\BaseController;

    /**
     * Importa CERIndex da un file CSV
     *
     * @Route("/import/servizi", name="es_op_admin_servizi", defaults={"_format": "json"})
     */
    public function importRifiutiAction() {
        set_time_limit(3600);
        $em = $this->getEm();
        $_categoria = $this->getRepository('ESOperatoriBundle:Servizi\Categoria');
        $_servizio = $this->getRepository('ESOperatoriBundle:Servizi\Servizio');
        $conn = $em->getConnection();
        $i = $j = 0;
        if ($handle = fopen(__DIR__ . "/../Resources/fixtures/categorie.csv", 'r')) {
            while ($dati = fgetcsv($handle, 0, ',')) {
                try {
                    $conn->beginTransaction();
                    $cat = $_categoria->findOneBy(array('sigla' => $dati[0]));
                    if(!$cat) {
                        $cat = new \ES\OperatoriBundle\Entity\Servizi\Categoria();
                        $cat->setSigla($dati[0]);
                    }
                    $cat->setCategoria($dati[1]);
                    $cat->setDescrizione($dati[2]);
                    $cat->setSeo(new \Ephp\UtilityBundle\Seo\Seo($em));
                    $cat->generateSeo();
                    $em->persist($cat);
                    $em->flush();
                    $i++;
                    $conn->commit();
                } catch (\Exception $e) {
                    $conn->rollback();
                    throw $e;
                }
            }
        }
        if ($handle = fopen(__DIR__ . "/../Resources/fixtures/servizi.csv", 'r')) {
            while ($dati = fgetcsv($handle, 0, ',')) {
                try {
                    $conn->beginTransaction();
                    $cat = $_categoria->findOneBy(array('sigla' => $dati[0]));
                    $ser = $_servizio->findOneBy(array('categoria' => $cat->getId(), 'servizio' => $dati[1]));
                    if(!$ser) {
                        $ser = new \ES\OperatoriBundle\Entity\Servizi\Servizio();
                        $ser->setCategoria($cat);
                        $ser->setServizio($dati[1]);
                    }
                    $ser->setDescrizione($dati[2]);
                    $ser->setSeo(new \Ephp\UtilityBundle\Seo\Seo($em));
                    $ser->generateSeo();
                    $em->persist($ser);
                    $em->flush();
                    $j++;
                    $conn->commit();
                } catch (\Exception $e) {
                    $conn->rollback();
                    throw $e;
                }
            }
        }

        return new \Symfony\Component\HttpFoundation\Response(json_encode(array('categorie' => $i, 'servizi' => $j)));
    }

}
