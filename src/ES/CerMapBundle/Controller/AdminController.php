<?php

namespace ES\CerMapBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

//use EcoSeekr\Bundle\CERBundle\Manager\CERManager;

/**
 * @Route("/cermap")
 */
class AdminController extends Controller {

    use \Ephp\UtilityBundle\Controller\Traits\BaseController;

    /**
     * Importa CERIndex da un file CSV
     *
     * @Route("/import/rifiuti", name="es_cer_admin_rifiuti", defaults={"_format": "json"})
     */
    public function importRifiutiAction() {
        set_time_limit(3600);
        $em = $this->getEm();
        $_categoria = $em->getRepository('ESCerMapBundle:Recuperabili\Categoria');
        $_recupero = $em->getRepository('ESCerMapBundle:Recuperabili\SmaltimentoRecupero');
        $_categoria_mps = $em->getRepository('ESCerMapBundle:Mps\Categoria');
        $_mps = $em->getRepository('ESCerMapBundle:Mps\Mps');
        $_cer = $em->getRepository('ESCerMapBundle:Cer\Cer');
        $conn = $em->getConnection();
        $i = 0;
        if ($handle = fopen(__DIR__ . "/../Resources/fixtures/rifiuti.json", 'r')) {
            $json = json_decode(fread($handle, filesize(__DIR__ . "/../Resources/fixtures/rifiuti.json")));
            foreach ($json as $rifiuto) {
                try {
                    $conn->beginTransaction();
                    $rif = new \ES\CerMapBundle\Entity\Recuperabili\Rifiuto();
                    
                    $rif->setRifiuto($rifiuto->rifiuto);
                    $rif->setSottotitolo($rifiuto->sottotitolo);
                    $rif->setCaratteristiche($rifiuto->caratteristiche);
                    $indice = str_replace('*', '', $rifiuto->categoria);
                    $rif->setCategoria($_categoria->findOneBy(array('indice' => $indice, 'pericoloso' => $indice != $rifiuto->categoria)));
                    foreach($rifiuto->cer as $cer) {
                        $rif->addCer($_cer->findOneBy(array('codice' => $cer)));
                    }
                    $rif->setNumero($rifiuto->numero);
                    $rif->setProvenienza($rifiuto->provenienza);
                    $rif->setTipologia($rifiuto->tipologia);
                    
                    foreach ($rifiuto->attivita as $attivita) {
                        $att = new \ES\CerMapBundle\Entity\Recuperabili\AttivitaRecupero();
                        $att->setAttivita($attivita->attivita);
                        $att->setCaratteristicheMps($attivita->caratteristiche);
                        foreach ($attivita->mps as $materia) {
                            $mps = $_mps->findOneBy(array('descrizione' => $materia->descrizione, 'materia' => $materia->mps));
                            if(!$mps) {
                                $cat = $_categoria_mps->findOneBy(array('nome' => $materia->categoria->nome));
                                if(!$cat) {
                                    $cat = new \ES\CerMapBundle\Entity\Mps\Categoria();
                                    $cat->setNome($materia->categoria->nome);
                                    $cat->setCategoria($materia->categoria->categoria);
                                    $cat->setSeo(new \Ephp\UtilityBundle\Seo\Seo($em));
                                    $cat->generateSeo();
                                    $em->persist($cat);
                                    $em->flush();
                                }
                                $mps = new \ES\CerMapBundle\Entity\Mps\Mps();
                                $mps->setCategoria($cat);
                                $mps->setMateria($materia->mps);
                                $mps->setDescrizione($materia->descrizione);
                                $mps->setSeo(new \Ephp\UtilityBundle\Seo\Seo($em));
                                $mps->generateSeo();
                                $em->persist($mps);
                                $em->flush();
                            }
                        }
                        $att->addMps($mps);
                        foreach($attivita->recupero as $recupero) {
                            $att->addRecupero($_recupero->findOneBy(array('codice' => $recupero)));
                        }
                        $rif->addAttivitaRecupero($att);
                    }
                    
                    $rif->setSeo(new \Ephp\UtilityBundle\Seo\Seo($em));
                    $rif->generateSeo();
                    $em->persist($rif);
                    $em->flush();
                    foreach($rif->getAttivitaRecupero() as $att) {
                        $att->setRifiuto($rif);
                        $em->persist($att);
                        $em->flush();
                    }
                    $i++;
                    $conn->commit();
                } catch (\Exception $e) {
                    $conn->rollback();
                    throw $e;
                }
            }
        }

        return new \Symfony\Component\HttpFoundation\Response(json_encode(array('cer' => $i)));
    }
    
    /**
     * Importa CERIndex da un file CSV
     *
     * @Route("/import/cer", name="es_cer_admin_cer", defaults={"_format": "json"})
     */
    public function importCERIndexAction() {
        set_time_limit(3600);
        $em = $this->getEm();
        $conn = $em->getConnection();
        $i = 0;
        if ($handle = fopen(__DIR__ . "/../Resources/fixtures/CERIndexUTF8.csv", 'r')) {
            $colonne = fgetcsv($handle, 0, ';');
            while ($dati = fgetcsv($handle, 0, ';')) {
                try {
                    $conn->beginTransaction();
                    $cer = new \ES\CerMapBundle\Entity\Cer\Cer();
                    foreach ($colonne as $index => $colonna) {
                        $fx = "set{$colonna}";
                        $cer->$fx($dati[$index]);
                    }
                    $cer->setSeo(new \Ephp\UtilityBundle\Seo\Seo($em));
                    $cer->generateSeo();
                    $em->persist($cer);
                    $em->flush();
                    $i++;
                    $conn->commit();
                } catch (\Exception $e) {
                    $conn->rollback();
                    throw $e;
                }
            }
        }

        return new \Symfony\Component\HttpFoundation\Response(json_encode(array('cer' => $i)));
    }

    /**
     * Importa Metodologie di smaltimento e recupero da un file CSV
     *
     * @Route("/import/rd", name="es_cer_admin_sr", defaults={"_format": "json"})
     */
    public function importSmaltimentoRecuperoAction() {
        set_time_limit(3600);
        $em = $this->getEm();
        $conn = $em->getConnection();
        $i = 0;
        if ($handle = fopen(__DIR__ . "/../Resources/fixtures/SmaltimentoRecupero.csv", 'r')) {
            $colonne = fgetcsv($handle, 0, ';');
            while ($dati = fgetcsv($handle, 0, ';')) {
                try {
                    $conn->beginTransaction();
                    $cer = new \ES\CerMapBundle\Entity\Recuperabili\SmaltimentoRecupero();
                    foreach ($colonne as $index => $colonna) {
                        $fx = "set{$colonna}";
                        $cer->$fx($dati[$index]);
                    }
                    $em->persist($cer);
                    $em->flush();
                    $i++;
                    $conn->commit();
                } catch (\Exception $e) {
                    $conn->rollback();
                    throw $e;
                }
            }
        }

        return new \Symfony\Component\HttpFoundation\Response(json_encode(array('rd' => $i)));
    }

    /**
     * Importa Categorie di rifiuti da un file CSV
     *
     * @Route("/import/categorie", name="es_cer_admin_cr", defaults={"_format": "json"})
     */
    public function importCategorieRifiutiAction() {
        set_time_limit(3600);
        $em = $this->getEm();
        $conn = $em->getConnection();
        $i = 0;
        if ($handle = fopen(__DIR__ . "/../Resources/fixtures/CategorieRifiuti.csv", 'r')) {
            $colonne = fgetcsv($handle, 0, ';');
            try {
                while ($dati = fgetcsv($handle, 0, ';')) {
                    try {
                        $conn->beginTransaction();
                        $cer = new \ES\CerMapBundle\Entity\Recuperabili\Categoria();
                        foreach ($colonne as $index => $colonna) {
                            $fx = "set{$colonna}";
                            $cer->$fx($dati[$index]);
                        }
                        $cer->setSeo(new \Ephp\UtilityBundle\Seo\Seo($em));
                        $cer->generateSeo();
                        $em->persist($cer);
                        $em->flush();
                        $i++;
                        $conn->commit();
                    } catch (\Exception $e) {
                        $conn->rollback();
                        throw $e;
                    }
                }
            } catch (\Exception $e) {
            }
        }

        return new \Symfony\Component\HttpFoundation\Response(json_encode(array('categorie' => $i)));
    }

}
