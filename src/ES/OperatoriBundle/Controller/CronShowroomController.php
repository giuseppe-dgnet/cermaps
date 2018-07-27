<?php

namespace ES\OperatoriBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use ES\OperatoriBundle\Entity\Richiesta;
use ES\OperatoriBundle\Form\RichiestaType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Richiesta controller.
 */
class CronShowroomController extends Controller {

    use \Ephp\UtilityBundle\Controller\Traits\BaseController;

    /**
     * Displays a form to create a new Richiesta entity.
     *
     * @Route("/tag-first", name="cron_sr_first")
     * @Template()
     */
    public function indexAction() {
        set_time_limit(1200);
        $out = array();
        for ($i = 0; $i < 100; $i++) {
            $sr = $this->findOneBy('ESOperatoriBundle:Showroom', array('genera_tag' => 1));
            if ($sr) {
                /* @var $sr \ES\OperatoriBundle\Entity\Showroom */
                $sr->generaTag($this->getEm());
                $out[] = array(
                    'showroom' => $sr->getRagioneSociale(),
                    'tag' => count($sr->getTags()),
                );
            } else {
                break;
            }
        }
        return new \Symfony\Component\HttpFoundation\Response(json_encode($out));
    }

    /**
     * Displays a form to create a new Richiesta entity.
     *
     * @Route("/next", name="cron_sr_next")
     * @Template()
     */
    public function nextAction() {
        set_time_limit(1200);
        $out = array();
        for ($i = 0; $i < 10; $i++) {

            $angaController = new AdminAngaController();
            $angaController->setContainer($this->container);
            $detail = $angaController->next();
            if(!isset($detail->anga)) {
                $i--;
                continue;
            }
            $anga = $detail->anga;

            if ($this->findOneBy('ESOperatoriBundle:Showroom', array('codice_fiscale' => $detail->codice_fiscale))) {
                $i--;
                continue;
            }

            $em = $this->getEm();
            try {
                $em->beginTransaction();
                $richiesta = new Richiesta();
                $richiesta->setRagioneSociale($detail->denominazione);
                $richiesta->setCodiceFiscaleAzienda($anga->codice_fiscale);
                $richiesta->setReferente("Importato da ANGA");
                $richiesta->setPartitaIva($detail->partita_iva ? : $anga->codice_fiscale);
                $richiesta->setIndirizzo($detail->sede->indirizzo);
                $richiesta->setAttivitaPrincipale('anga');
                $richiesta->setTelefono('n.d.');
                $richiesta->setEmail('n.d.');
                $richiesta->setImpianto(false);
                $richiesta->setDiscarica(false);
                $richiesta->setRaccoglitore(false);
                $richiesta->setTrasportatore(false);
                $richiesta->setLaboratorio(false);
                $richiesta->setServizi(false);
                $richiesta->setDemolizioni(false);
                $richiesta->setSpurghi(false);
                $richiesta->setBonifiche(false);
                $richiesta->setRottamazione(false);
                $richiesta->setRaee(false);
                $richiesta->setOlioMinerale(false);
                $richiesta->setOlioVegetale(false);
                $em->persist($richiesta);
                $em->flush();

                $showroom = new \ES\OperatoriBundle\Entity\Showroom();

                $showroom->setAnga($detail);
                $showroom->setRagioneSociale($detail->denominazione);
                $showroom->setAttivitaPrincipale('anga');
                $showroom->setDescrizioneAttivita("Impresa iscritta all'Albo Nazionale Gestori Ambientali");
                $showroom->setCap($detail->sede->cap);
                $showroom->setCodiceFiscale($anga->codice_fiscale);
                $showroom->setPartitaIva($detail->partita_iva);
                $showroom->setComuneTestuale($detail->sede->comune . ' (' . $detail->sede->provincia . ')');
                $showroom->setIndirizzo($detail->sede->indirizzo);

                $_geo = $this->getRepository('EphpGeoBundle:GeoNames');
                /* @var $_geo \Ephp\GeoBundle\Entity\GeoNamesRepository */
                $_cer = $this->getRepository('ESCerMapBundle:Cer\Cer');
                /* @var $_cer \ES\CerMapBundle\Entity\Cer\CerRepository */
                $_rif = $this->getRepository('ESCerMapBundle:Recuperabili\Rifiuto');
                /* @var $_rif \ES\CerMapBundle\Entity\Recuperabili\RifiutoRepository */
                $_cat = $this->getRepository('ESOperatoriBundle:ShowroomCategoria');
                /* @var $_rif \ES\OperatoriBundle\Entity\ShowroomCategoriaRepository */

                $showroom->setListUpdateAt(new \DateTime(date("c", $anga->list_update)));
                $showroom->setDetailUpdateAt(new \DateTime(date("c", $anga->detail_update)));
                $showroom->setHasCer($anga->has_cer);
                $showroom->setHasCerCp($anga->has_cer_cp);
                $showroom->setHasTipologie($anga->has_tipologie);
                $comune = $_geo->ricercaComune($detail->sede->comune, $detail->sede->provincia, $detail->sede->regione, 'IT');
                /* @var $comune \Ephp\GeoBundle\Entity\GeoNames */
                if(!$comune) {
                    $comune = $_geo->find(3175395);
                }
                $showroom->setComune($comune);
                $showroom->setLatitudine($comune->getLatitude() + (rand(-30000, 30000) / 1000000));
                $showroom->setLongitudine($comune->getLongitude() + (rand(-50000, 50000) / 1000000));
                if ($anga->cer{0} != '<') {
                    $cers = explode('-', $anga->cer);
                    foreach ($cers as $cer) {
                        if ($cer) {
                            $showroom->addCer($_cer->findOneBy(array('codice' => trim(str_replace('.', '', $cer)))));
                        }
                    }
                }
                if ($anga->cercp{0} != '<') {
                    $cerscp = explode('-', $anga->cercp);
                    foreach ($cerscp as $cer) {
                        if ($cer) {
                            $showroom->addCerCp($_cer->findOneBy(array('codice' => trim(str_replace('.', '', $cer)))));
                        }
                    }
                }
                if ($anga->tipologie{0} != '<') {
                    $tipologie = explode('-', $anga->tipologie);
                    foreach ($tipologie as $tipologia) {
                        $tipologia = $_rif->ricercaDaSigla(trim($tipologia));
                        if ($tipologia) {
                            /* @var $tipologia \ES\CerMapBundle\Entity\Recuperabili\Rifiuto */
                            $showroom->addTipologie($tipologia);
                            foreach ($tipologia->getAttivitaRecupero() as $attivita) {
                                /* @var $attivita \ES\CerMapBundle\Entity\Recuperabili\AttivitaRecupero */
                                foreach ($attivita->getMps() as $mps) {
                                    if ($mps) {
                                        $showroom->addMps($mps);
                                    }
                                }
                                foreach ($attivita->getRecupero() as $rd) {
                                    if ($rd) {
                                        $showroom->addRd($rd);
                                    }
                                }
                            }
                        }
                    }
                }
                $categorie = $anga->lista_categorie;
                if ($categorie) {
                    foreach ($categorie as $categoria) {
                        $showroom->addCategorie($_cat->ricercaDaDati($categoria));
                        switch (strtolower($categoria->SiglaCategoria)) {
                            case '1c':
                            case '1o':
                                $showroom->setImpianto(true);
                                $showroom->setDiscarica(true);
                                $richiesta->setImpianto(true);
                                $richiesta->setDiscarica(true);
                                break;
                            case '4':
                            case '5':
                                $showroom->setTrasportatore(true);
                                $showroom->setRaccoglitore(true);
                                $richiesta->setTrasportatore(true);
                                $richiesta->setRaccoglitore(true);
                                break;
                            case '8':
                            case '9':
                            case '10a':
                            case '10b':
                                $showroom->setServizi(true);
                                $richiesta->setServizi(true);
                                break;
                            case 'trfr':
                                $showroom->setTrasportatore(true);
                                $richiesta->setTrasportatore(true);
                                break;
                        }
                    }
                }
                $showroom->setSeo(new \Ephp\UtilityBundle\Seo\Seo($em));
                $showroom->generateSeo();
                $em->persist($showroom);
                $em->flush();
                foreach ($showroom->getCategorie() as $categoria) {
                    /* @var $categoria \ES\OperatoriBundle\Entity\ShowroomCategoria */
                    $categoria->setShowroom($showroom);
                    $em->persist($categoria);
                    $em->flush();
                }

                $richiesta->setShowroom($showroom);
                $em->persist($richiesta);
                $em->flush();

                $em->commit();
                $out[] = $showroom->getSlug();
            } catch (\Exception $e) {
                $em->rollback();
                throw $e;
            }
        }
        return new \Symfony\Component\HttpFoundation\Response(json_encode($out));
    }

}
