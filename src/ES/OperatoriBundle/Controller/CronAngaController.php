<?php

namespace ES\OperatoriBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/cron/grab/anga")
 */
class CronAngaController extends \Ephp\WsBundle\Controller\WsInvokerController
{

    /**
     * @Route("/aziende", name="anga_conta_aziende", defaults={"_format"="json"})
     */
    public function grabJsonContaAziendeAction()
    {
        $lista_aziende = $this->elenchi();
        return new Response(json_encode($lista_aziende));
    }

    /**
     * @Route("/aziende/{regione}/{pagina}", name="anga_aziende", defaults={"_format"="json"})
     */
    public function grabJsonAziendeAction($regione, $pagina)
    {
        $em = $this->getEM();
        $out = $this->elenco($regione, $pagina, $em);
        return new Response(json_encode($out));
    }

    /**
     * @Route("/aziende-grab-all", name="anga_aziende_grab_all", defaults={"_format"="json"})
     */
    public function grabJsonAziendeAllAction()
    {
        $lista_aziende = $this->elenchi();
        $em = $this->getEM();
        $out = array();
        foreach ($lista_aziende as $azienda_regione) {
            set_time_limit(3600);
            $out_regione = array();
            for ($pag = 1; $pag <= $azienda_regione->max; $pag++) {
                $out_regione[] = $this->elenco($azienda_regione->reg, $pag, $em);
            }
            $out[$azienda_regione->reg] = $out_regione;
        }
        return new Response(json_encode($out));
    }

    /**
     * @Route("/azienda/{n}", name="anga_azienda", defaults={"_format"="json"}))
     */
    public function grabAziendaAction($n)
    {
        $out = array('aziende' => 0, 'nuove' => 0, 'aggiornate' => 0, 'uguali' => 0, 'cancellate' => 0, 'errori' => 0, 'errors' => array());
        $em = $this->getEM();
        $i = 0;
        $_anga = $em->getRepository('ES\OperatoriBundle\Entity\Anga');
        /* @var \ES\OperatoriBundle\Entity\AngaRepository */
        do {
            $results = $_anga->findBy(array('parsed' => false, 'exist' => true), array('last_list_update_at' => 'ASC'), 10);
            foreach ($results as $anga) {
                if ($em->isOpen()) {
                    $output = $this->dettagli($anga, $em);
                    foreach ($output as $key) {
                        if (is_array($key)) {
                            $out['errors'][$key[0]] = $key[1];
                        } else {
                            $out[$key]++;
                        }
                    }
                    $i++;
                }
            }
        } while ($i <= $n);
        return new Response(json_encode($out));
    }

    /**
     * @Route("/singola-azienda/{id}", name="anga_singola_azienda", defaults={"_format"="json"}))
     */
    public function grabSingolaAziendaAction($id)
    {
        $out = array('aziende' => 0, 'nuove' => 0, 'aggiornate' => 0, 'uguali' => 0, 'cancellate' => 0, 'errori' => 0, 'errors' => array());
        $em = $this->getEM();
        $i = 0;
        $_anga = $em->getRepository('ES\OperatoriBundle\Entity\Anga');
        /* @var \ES\OperatoriBundle\Entity\AngaRepository */
        $output = $this->dettagli($_anga->findOneBy(array('anga_id' => $id)), $em);
        foreach ($output as $key) {
            if (is_array($key)) {
                $out['errors'][$key[0]] = $key[1];
            } else {
                $out[$key]++;
            }
        }
        return new Response(json_encode($out));
    }

    public function dettagli(\ES\OperatoriBundle\Entity\Anga $anga, \Doctrine\ORM\EntityManager $em)
    {
        set_time_limit(300);
        $this->call = $this->createCall('anga_detail_new');
        $params = array('id' => $anga->getAngaId());
        $d = null;
        try {
            $start = microtime(true);
            $em->beginTransaction();
            //$grab = $anga->getGrab();
            $response = $this->invokeService('ANGA', 'anga_detail_new', $this->call, $params);
            $json = json_decode($response->getXml());
            $d = $json->d;
            $out = array('aziende');
            $mode = 'uguali';
            $dove = array();
            $anga->setLastDetailUpdateAt(new \DateTime());
            $anga->setParsed(true);
            if ($anga->getCap() != $d->Cap) {
                $mode = 'aggiornate';
                $anga->setCap($d->Cap);
                $dove[] = 'cap';
            }
            if ($anga->getCategorie() != $d->Categorie) {
                $mode = 'aggiornate';
                $anga->setCategorie($d->Categorie);
                $dove[] = 'categorie';
            }
            if ($anga->getCer() != $d->Cer) {
                $mode = 'aggiornate';
                $anga->setCer($d->Cer);
                $dove[] = 'cer';
            }
            if ($anga->getCerCp() != $d->CerCP) {
                $mode = 'aggiornate';
                $anga->setCerCP($d->CerCP);
                $dove[] = 'cercp';
            }
            if ($anga->getComune() != $d->Comune) {
                $mode = 'aggiornate';
                $anga->setComune($d->Comune);
                $dove[] = 'comune';
            }
            if ($anga->getHasCer() != $d->HasCer) {
                $mode = 'aggiornate';
                $anga->setHasCer($d->HasCer);
                $dove[] = 'hascer';
            }
            if ($anga->getHasCerCp() != $d->HasCerCP) {
                $mode = 'aggiornate';
                $anga->setHasCerCp($d->HasCerCP);
                $dove[] = 'hascercp';
            }
            if ($anga->getHasTipologie() != $d->HasTipologie) {
                $mode = 'aggiornate';
                $anga->setHasTipologie($d->HasTipologie);
                $dove[] = 'hastipologie';
            }
            if ($anga->getIndirizzo() != $d->Via) {
                $mode = 'aggiornate';
                $anga->setIndirizzo($d->Via);
                $dove[] = 'indirizzo';
            }
            if ($anga->getNumeroIscrizione() != $d->NumeroIscrizione) {
                $mode = 'aggiornate';
                $anga->setNumeroIscrizione($d->NumeroIscrizione);
                $dove[] = 'numeroiscrizione';
            }
            if ($anga->getProvincia() != $d->SiglaProvincia) {
                $mode = 'aggiornate';
                $anga->setProvincia($d->SiglaProvincia);
                $dove[] = 'provincia';
            }
            if ($anga->getSezione() != $d->Sezione) {
                $mode = 'aggiornate';
                $anga->setSezione($d->Sezione);
                $dove[] = 'sezione';
            }
            if ($anga->getTipologie() != $d->Tipologie) {
                $mode = 'aggiornate';
                $anga->setTipologie($d->Tipologie);
                $dove[] = 'tipologie';
            }
            if ($anga->getListaCategorie() != $d->CategorieLista) {
                //$mode = 'aggiornate';
                $anga->setListaCategorie($d->CategorieLista);
                $dove[] = 'listacategorie';
            }

            if (!$anga->getCodiceFiscale()) {
                $mode = 'nuove';
                $anga->setCodiceFiscale($d->CodiceFiscale);
            }
            $em->persist($anga);
            $em->flush();
            $out[] = $mode;

            $this->createShowroom($anga->serialize(), $em);

            $em->commit();
        } catch (\Exception $e) {
            $em->rollback();
            try {
                $em->beginTransaction();
                if ($this->call) {
                    $this->completeCall($this->call, $params, $this->getErrorMessage($e));
                }
                $em->commit();
            } catch (\Exception $ex) {

            }
            $out[] = 'errori';
            if ($d != null) {
                $out[] = array($d->IdImpresa, $e->getMessage());
            } else {
                $out[] = array('Nessun ID', $e->getMessage());
            }
        }
        $time = microtime(true) - $start;
        $this->completeCall($this->call, $params, $out);
        return $out;
    }

    public function elenco($regione, $pagina, \Doctrine\ORM\EntityManager $em)
    {
        $this->call = $this->createCall('anga_list_new');
        $params = array('regione' => $regione, 'pag' => $pagina, 'cp' => 'true');
        $out = array('totale' => 0, 'nuove' => 0, 'aggiornate' => 0, 'errori' => 0, 'errors' => array());
        $id = null;
        try {
            $response = $this->invokeService('ANGA', 'anga_list_new', $this->call, $params);
            $json = json_decode($response->getXml());
            $d = $json->d;
            foreach ($d->ListaImprese as $impresa) {
                $em->beginTransaction();
                set_time_limit(300);
                $out['totale']++;
                $anga = $em->getRepository('ES\OperatoriBundle\Entity\Anga')->findOneBy(array('anga_id' => $impresa->IdImpresa));
                $id = $impresa->IdImpresa;
                if (!$anga) {
                    $out['nuove']++;
                    $anga = new \ES\OperatoriBundle\Entity\Anga();
                    $anga->setAngaId($impresa->IdImpresa);
                    $anga->setHasNoCp(true);
                } else {
                    $out['aggiornate']++;
                    continue;
                }
                $anga->setDenominazione(trim(str_replace(array('"', '   ', '  '), array('', ' ', ' '), $impresa->Denominazione)));
                $anga->setCap($impresa->Cap);
                $anga->setCodiceFiscale($impresa->CodiceFiscale);
                $anga->setSezione($impresa->Sezione);
                $anga->setNumeroIscrizione($impresa->ProvinciaIscrizione . ' ' . $impresa->NumeroIscrizione);
                $anga->setRegione($regione);
                $anga->setProvincia($impresa->SiglaProvincia);
                $anga->setIndirizzo($impresa->Via);
                $anga->setCategorie($impresa->Categorie);
                $anga->setPagina($pagina);
                $anga->setLastListUpdateAt(new \DateTime());
                $anga->setExist(true);
                $anga->setParsed(false);
                $em->persist($anga);
                $em->flush();

                /*
                $grab = new Grab();
                $grab->setTitolo($anga->getDenominazione());
                $grab->setCap($anga->getCap());
                $grab->setIndirizzo($anga->getIndirizzo());
                $grab->setLocalita($anga->getComune());
                $grab->setNazione('Italia');
                $grab->setRegione($this->regione($anga->getRegione()));
                $grab->setTipo('anga');
                if (strlen($anga->getCodiceFiscale()) == 16) {
                    $grab->setCodiceFiscale($anga->getCodiceFiscale());
                } else {
                    $grab->setPartitaIva($anga->getCodiceFiscale());
                }
                $grab->setProvincia($anga->getProvincia());
                $em->persist($grab);
                $em->flush();
                $anga->setGrab($grab);
                $em->persist($anga);
                $em->flush();
                $_grab = $em->getRepository('\ES\OperatoriBundle\Entity\Grab');
                $_grab->checkGrab($grab);
                */
                $em->commit();
            }
        } catch (\Exception $e) {
            if ($em->isOpen()) {
                $em->rollback();
            }
            try {
                $em->beginTransaction();
                if ($this->call) {
                    $this->completeCall($this->call, $params, $this->getErrorMessage($e));
                }
                $em->commit();
            } catch (\Exception $ex) {

            }
            $out['errori']++;
            $out['errors'][] = array($id, $e->getMessage());
        }
        $this->completeCall($this->call, json_encode(array('regione' => $regione, 'pagina' => $pagina)), $out);
        return $out;
    }

    public function elenchi()
    {
        $tot = 0;
        $this->call = $this->createCall('anga_list_new');
        $out = array();
        for ($i = 1; $i <= 21; $i++) {
            $params = array('regione' => $i, 'pag' => 1, 'cp' => 'false');
            try {
                $start = microtime(true);
                $response = $this->invokeService('ANGA', 'anga_list_new', $this->call, $params);
                $json = json_decode($response->getXml());
                $d = $json->d;
                $max = ceil($d->NumeroRecordTotali / 100);
                $tot += $d->NumeroRecordTotali;
                $out[] = array(
                    'reg' => $i,
                    'tot' => $d->NumeroRecordTotali,
                    'max' => $max,
                );
            } catch (\Exception $e) {
                if ($this->call) {
                    $this->completeCall($this->call, $params, $this->getErrorMessage($e));
                }
                echo $e->getMessage();
                throw $e;
            }
            $time = microtime(true) - $start;
        }
        $this->completeCall($this->call, $tot, $out);
        return $out;
    }

    protected function createShowroom($detail, $em)
    {
        $showroom = new \ES\OperatoriBundle\Entity\Showroom();

        $detail = (object)$detail;

        $showroom->setAnga($detail);
        $showroom->setRagioneSociale($detail->denominazione);
        $showroom->setAttivitaPrincipale('anga');
        $showroom->setDescrizioneAttivita("Impresa iscritta all'Albo Nazionale Gestori Ambientali");
        $showroom->setCap($detail->cap);
        $showroom->setCodiceFiscale($detail->codice_fiscale);
        //$showroom->setPartitaIva($detail->partita_iva);
        $showroom->setComuneTestuale($detail->comune . ' (' . $detail->provincia . ')');
        $showroom->setIndirizzo($detail->indirizzo ?: '-');

        $_geo = $em->getRepository('EphpGeoBundle:GeoNames');
        /* @var $_geo \Ephp\GeoBundle\Entity\GeoNamesRepository */
        $_cer = $em->getRepository('ESCerMapBundle:Cer\Cer');
        /* @var $_cer \ES\CerMapBundle\Entity\Cer\CerRepository */
        $_rif = $em->getRepository('ESCerMapBundle:Recuperabili\Rifiuto');
        /* @var $_rif \ES\CerMapBundle\Entity\Recuperabili\RifiutoRepository */
        $_cat = $em->getRepository('ESOperatoriBundle:ShowroomCategoria');
        /* @var $_rif \ES\OperatoriBundle\Entity\ShowroomCategoriaRepository */

        $showroom->setListUpdateAt(new \DateTime(date("c", $detail->list_update)));
        $showroom->setDetailUpdateAt(new \DateTime(date("c", $detail->detail_update)));
        $showroom->setHasCer($detail->has_cer);
        $showroom->setHasCerCp($detail->has_cer_cp);
        $showroom->setHasTipologie($detail->has_tipologie);
        $comune = $_geo->ricercaComune($detail->comune, $detail->provincia, $detail->regione, 'IT');
        /* @var $comune \Ephp\GeoBundle\Entity\GeoNames */
        if (!$comune) {
            $comune = $_geo->find(3175395);
        }
        $showroom->setComune($comune);
        $showroom->setLatitudine($comune->getLatitude() + (rand(-30000, 30000) / 1000000));
        $showroom->setLongitudine($comune->getLongitude() + (rand(-50000, 50000) / 1000000));
        if ($detail->cer{0} != '<') {
            $cers = explode('-', $detail->cer);
            foreach ($cers as $cer) {
                if ($cer) {
                    $showroom->addCer($_cer->findOneBy(array('codice' => trim(str_replace('.', '', $cer)))));
                }
            }
        }
        if ($detail->cercp{0} != '<') {
            $cerscp = explode('-', $detail->cercp);
            foreach ($cerscp as $cer) {
                if ($cer) {
                    $showroom->addCerCp($_cer->findOneBy(array('codice' => trim(str_replace('.', '', $cer)))));
                }
            }
        }
        if ($detail->tipologie{0} != '<') {
            $tipologie = explode('-', $detail->tipologie);
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
        $categorie = $detail->lista_categorie;
        if ($categorie) {
            foreach ($categorie as $categoria) {
                $showroom->addCategorie($_cat->ricercaDaDati($categoria));
                switch (strtolower($categoria->SiglaCategoria)) {
                    case '1c':
                    case '1o':
                        $showroom->setImpianto(true);
                        $showroom->setDiscarica(true);
                        $showroom->setAttivitaPrincipale('impianto');
                        break;
                    case '4':
                    case '5':
                        $showroom->setTrasportatore(true);
                        $showroom->setRaccoglitore(true);
                        $showroom->setAttivitaPrincipale('trasportatore');
                        break;
                    case '8':
                    case '9':
                    case '10a':
                    case '10b':
                        $showroom->setServizi(true);
                        $showroom->setAttivitaPrincipale('servizi');
                        break;
                    case 'trfr':
                        $showroom->setTrasportatore(true);
                        $showroom->setAttivitaPrincipale('trasportatore');
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

//     	$em->commit();
//     	$out[] = $showroom->getSlug();
    }

    private function regione($id)
    {
        $regioni = array(
            1 => "Piemonte",
            2 => "Val d'Aosta",
            3 => "Lombardia",
            4 => "Trentino Alto Adige",
            5 => "Veneto",
            6 => "Friuli Venezia Giulia",
            7 => "Liguria",
            8 => "Emilia Romagna",
            9 => "Toscana",
            10 => "Umbria",
            11 => "Marche",
            12 => "Lazio",
            13 => "Abruzzo",
            14 => "Molise",
            15 => "Campania",
            16 => "Puglia",
            17 => "Basilicata",
            18 => "Calabria",
            19 => "Sicilia",
            20 => "Sardegna",
            21 => "Trentino Alto Adige",
        );
        return $regioni[$id];
    }

}
