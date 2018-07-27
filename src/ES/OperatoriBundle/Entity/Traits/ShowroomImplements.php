<?php

namespace ES\OperatoriBundle\Entity\Traits;

trait ShowroomImplements {

    /*
     * Utilità
     */
    public function __toString() {
        return $this->getRagioneSociale();
    }
    
    /*
     * Implementazione ISeo 
     */
    public function title() {
        return "{$this->getRagioneSociale()}";
    }

    public function setComuneId() {
        return $this->comune = $this->comune->getGeonameid();
    }

    public function getSeoFields() {
        return array(
            'ragione_sociale' => array(
                'fx' => 'getRagioneSociale',
                'descrizione' => 'Restituisce la denominazione dello showroom',
            ),
            'attivita' => array(
                'fx' => 'getAttivita',
                'descrizione' => 'Restituisce l\'attività principale dello showroom',
            ),
            'cer' => array(
                'fx' => 'getElencoCer',
                'descrizione' => 'Restituisce un elenco di 10 cer',
            ),
            'cer_cp' => array(
                'fx' => 'getElencoCerCp',
                'descrizione' => 'Restituisce un elenco di 10 cer conto proprio',
            ),
            'tipologie' => array(
                'fx' => 'getElencoTipologie',
                'descrizione' => 'Restituisce un elenco di 10 tipologie',
            ),
            'categorie' => array(
                'fx' => 'getElencoTipologie',
                'descrizione' => 'Restituisce un elenco di 5 mps',
            ),
            'comune' => array(
                'fx' => 'getComuneTestuale',
                'descrizione' => 'Restituisce il comune',
            ),
        );
    }

    public function getElencoCer() {
        $out = array();
        $n = count($this->cer);
        for ($i = 0; $i < min(10, $n); $i++) {
            $out[] = 'cer ' . $this->cer->get($i)->getCodice();
        }
        if ($n > 10) {
            $out[] = 'e altri ' . ($n - 10) . ' codici cer';
        }
        return implode(', ', $out);
    }

    public function getElencoCerCp() {
        $out = array();
        $n = count($this->cer_cp);
        for ($i = 0; $i < min(10, $n); $i++) {
            $out[] = $this->cer_cp->get($i)->getCodice();
        }
        if ($n > 10) {
            $out[] = 'e altri ' . ($n - 10) . ' CER Conto Proprio';
        }
        return implode(', ', $out);
    }

    public function getElencoTipologie() {
        $out = array();
        $n = count($this->cer_cp);
        for ($i = 0; $i < min(10, $n); $i++) {
            if($this->tipologie->get($i)){
                $out[] = $this->tipologie->get($i)->getRifiuto();
            }
        }
        if ($n > 10) {
            $out[] = 'e altri ' . ($n - 10) . ' Tipologie';
        }
        return implode(', ', $out);
    }

    public function getElencoCategorie() {
        $out = array();
        $n = count($this->mps);
        for ($i = 0; $i < min(5, $n); $i++) {
            $out[] = $this->categorie->get($i)->getCategoriaBreve();
        }
        if ($n > 5) {
            $out[] = 'e altri ' . ($n - 5) . ' Categorie';
        }
        return implode(', ', $out);
    }
    
    public function getLogoTmb() {
        return '/bundles/esweb/images/azienda_placeholder.jpg';
    }

    public function getAttivita() {
        switch($this->getAttivitaPrincipale()) {
            case 'anga': return "Impresa iscritta all'Albo Nazionale Gestori Ambientali";
            case 'impianto': return 'Impianto di trattamento o recupero rifiuti';
            case 'discarica': return 'Discarica';
            case 'raccoglitore': return 'Raccoglitore';
            case 'trasportatore': return 'Trasportatore';
            case 'servizi_ambientali': return 'Servizi ambientali e smaltimenti';
            case 'laboratori': return 'Laboratorio di analisi chimiche, ambientali';
            case 'demolizioni': return 'Demolizioni immobili';
            case 'spurghi': return 'Spurghi e serbatoi';
            case 'bonifiche': return 'Bonifiche ambientali';
            case 'rottamazione': return 'Rottamazione veicoli';
            case 'raee': return 'Rottamazione apparecchiature elettriche';
            case 'olio_minerale': return 'Recupero olio minerale';
            case 'olio_vegetale': return 'Recupero olio vegetale';
        }
    }
    
    public function getRelationModel() {
        return "\ES\OperatoriBundle\Entity\Tag";
    }

    public function getSetterModel() {
        return "setShowroom";
    }
    
        public function generaTag(\Doctrine\ORM\EntityManager $em) {
        try {
            $em->beginTransaction();
            $em->getRepository('ESOperatoriBundle:Tag')->cancella($this->getId());
            $em->flush();
            $this->tags->clear();
            foreach ($this->getCer() as $cer) {
                /* @var $cer \ES\CerMapBundle\Entity\Cer\Cer */
                $this->addCreateTag($em, $cer->getCodice(), 'cer', $cer->getDescrizione());
            }
            foreach ($this->getCerCp() as $cer) {
                /* @var $cer \ES\CerMapBundle\Entity\Cer\Cer */
                $this->addCreateTag($em, $cer->getCodice(), 'cer_cp', $cer->getDescrizione());
            }
            foreach ($this->getCerTrattati() as $cer) {
                /* @var $cer \ES\CerMapBundle\Entity\Cer\Cer */
                $this->addCreateTag($em, $cer->getCodice(), 'cer_trattati', $cer->getDescrizione());
            }
//// DA RISCRIVERE QUANDO SI ATTIVA LA MPS-MAP
            foreach ($this->getMps() as $mps) {
                /* @var $mps \ES\CerMapBundle\Entity\Mps\Mps */
                $tag = $em->getRepository('EphpTagBundle:Tag')->findOneBy(array('tag' => $mps->getMateria(),'descrizione' => $mps->getDescrizione(),'favicon' => 'tag_mps'));
                if(!$tag){
                    $tag = new \Ephp\TagBundle\Entity\Tag();
                    $tag->setDescrizione($mps->getDescrizione());
                    $tag->setTag($mps->getMateria());
                    $tag->setFavicon('tag_mps');
                    $tag->setPubblico(true);
                    $em->persist($tag);
                    $gruppo = $em->getRepository('EphpTagBundle:Gruppo')->findOneBy(array('sigla' => 'mps'));
                    /* @var $gruppo \Ephp\TagBundle\Entity\Gruppo */
                    $gruppo->addTags($tag);
                    $em->persist($gruppo);
                    
                }
                
                $this->addTag($tag,'mps');

            }
//            $keyword_mps = array_unique($keyword_mps);
//            foreach ($keyword_mps as $keyword) {
//                $this->addCreateTag($em, str_replace('_', ' ', $keyword), 'mps');
//            }
//// DA RISCRIVERE QUANDO SI ATTIVA LA RIFIUTI-MAP
//            foreach ($this->getTipologie() as $rifiuto) {
//                /* @var $rifiuto \ES\CerMapBundle\Entity\Recuperabili\Rifiuto */
//                $this->addCreateTag($em, $rifiuto->getCodice(), 'recuperabile', $rifiuto->getRifiuto());
//            }
            if ($this->getImpianto()) {
                $this->addCreateTag($em, 'impianto', 'attivita');
            }
            if ($this->getDiscarica()) {
                $this->addCreateTag($em, 'discarica', 'attivita');
            }
            if ($this->getRaccoglitore()) {
                $this->addCreateTag($em, 'raccoglitore', 'attivita');
            }
            if ($this->getTrasportatore()) {
                $this->addCreateTag($em, 'trasportatore', 'attivita');
            }
            if ($this->getLaboratorio()) {
                $this->addCreateTag($em, 'laboratorio', 'attivita');
            }
            if ($this->getServizi()) {
                $this->addCreateTag($em, 'servizi ambientali', 'attivita');
            }
            if ($this->getDemolizioni()) {
                $this->addCreateTag($em, 'demolizioni immobili', 'attivita');
            }
            if ($this->getSpurghi()) {
                $this->addCreateTag($em, 'spurghi serbatoi', 'attivita');
            }
            if ($this->getBonifiche()) {
                $this->addCreateTag($em, 'bonifiche ambientali', 'attivita');
            }
            if ($this->getRottamazione()) {
                $this->addCreateTag($em, 'rottamazione veicoli', 'attivita');
            }
            if ($this->getRaee()) {
                $this->addCreateTag($em, 'raee', 'attivita');
            }
            if ($this->getOlioMinerale()) {
                $this->addCreateTag($em, 'olio minerale', 'attivita');
            }
            if ($this->getOlioVegetale()) {
                $this->addCreateTag($em, 'olio vegetale', 'attivita');
            }
            $this->setGeneraTag(false);
            $em->persist($this);
            $em->flush();
            $em->commit();
        } catch (\Exception $e) {
            $em->rollback();
            throw $e;
        }
    }
      
    /**
     * Tutte le categorie di rifiuti dello showroom
     * @return type
     */
    public function getCategorieTipologie() {
        $out = array();
        foreach ($this->tipologie as $tipologia) {
            /* @var $tipologia \ES\CerMapBundle\Entity\Recuperabili\Rifiuto */
            $out[$tipologia->getCategoria()->getId()] = $tipologia->getCategoria();
        }
        return $out;
    }
    
    /**
     * Tutte le tipologie di una categoria dello showroom
     * @return type
     */
    public function getTipologieCategoria($id) {
        $out = array();
        foreach ($this->tipologie as $tipologia) {
            /* @var $tipologia \ES\CerMapBundle\Entity\Recuperabili\Rifiuto */
            if($id == $tipologia->getCategoria()->getId()){                
                $out[] = $tipologia;
            }
        }
        return $out;
    }
    
    /**
     * Tutte le categorie di rifiuti dello showroom
     * @return type
     */
    public function getCategorieMps() {
        $out = array();
        foreach ($this->mps as $mps) {
            /* @var $mps \ES\CerMapBundle\Entity\Mps\Mps */
            if(!isset($out[$mps->getCategoria()->getNome()])) {
                $out[$mps->getCategoria()->getNome()] = array();
            }
            $out[$mps->getCategoria()->getNome()][] = $mps;
        }
        return $out;
    }
    
    /**
     * Tutte le tipologie di una categoria dello showroom
     * @return type
     */
    public function getMpsCategoria($id) {
        $out = array();
        foreach ($this->mps as $mps) {
            /* @var $mps \ES\CerMapBundle\Entity\Mps\Mps */
            if($id == $mps->getCategoria()->getId()){                
                $out[] = $mps;
            }
        }
        return $out;
    }
    
    /**
     * Tutte le categorie di rifiuti dello showroom
     * @return type
     */
    public function getCategorieServizi() {
        $out = array();
        foreach ($this->servizi_sr as $servizio) {
            /* @var $servizio \ES\OperatoriBundle\Entity\Servizi\Servizio */
            if(!isset($out[$servizio->getCategoria()->getCategoria()])) {
                $out[$servizio->getCategoria()->getCategoria()] = array();
            }
            $out[$servizio->getCategoria()->getCategoria()][] = $servizio;
        }
        return $out;
    }
    
    /**
     * Tutte le tipologie di una categoria dello showroom
     * @return type
     */
    public function getServiziCategoria($id) {
        $out = array();
        foreach ($this->servizi_sr as $servizio) {
            /* @var $servizi \ES\OperatoriBundle\Entity\Servizi\Servizio */
            if($id == $servizio->getCategoria()->getId()){                
                $out[] = $servizio;
            }
        }
        return $out;
    }

}

