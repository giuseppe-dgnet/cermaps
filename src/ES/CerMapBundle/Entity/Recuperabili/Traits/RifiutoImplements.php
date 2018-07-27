<?php

namespace ES\CerMapBundle\Entity\Recuperabili\Traits;

trait RifiutoImplements {
    /*
     * Utilità
     */

    public function __toString() {
        return $this->getId();
    }

    public function getExtraField() {
        return array(
            'route' => $this->getCategoria()->getPericoloso() ? 'web_lpsp_rifiuti' : 'web_lps_rifiuti',
            'label_link' => 'Apri la scheda',
            'route_param' => array(
                'slug' => $this->getSlug(),
            ),
        );
    }
    
    public function getRds() {
        $out = array();
        foreach ($this->attivita_recupero as $ar) {
            /* @var $ar \ES\CerMapBundle\Entity\Recuperabili\AttivitaRecupero */
            foreach ($ar->getRecupero() as $rd) {
                /* @var $rd \ES\CerMapBundle\Entity\Recuperabili\SmaltimentoRecupero */
                $out[$rd->getCodice()] = $rd;
            }
        }
        return $out;
    }
    
    public function getMps() {
        $out = array();
        foreach ($this->attivita_recupero as $ar) {
            /* @var $ar \ES\CerMapBundle\Entity\Recuperabili\AttivitaRecupero */
            foreach ($ar->getMps() as $mps) {
                /* @var $rd \ES\CerMapBundle\Entity\Recuperabili\SmaltimentoRecupero */
                $out[$mps->getId()] = $mps;
            }
        }
        return $out;
    }
    
    public function getCategorieMps() {
        $out = array();
        foreach ($this->attivita_recupero as $ar) {
            /* @var $ar \ES\CerMapBundle\Entity\Recuperabili\AttivitaRecupero */
            foreach ($ar->getMps() as $mps) {
                /* @var $rd \ES\CerMapBundle\Entity\Mps\Mps */
                if(!isset($out[$mps->getCategoria()->getNome()])) {
                    $out[$mps->getCategoria()->getNome()] = array(
                        'descrizione' => $mps->getCategoria()->getCategoria(),
                        'mps' => array(),
                    );
                }
                $out[$mps->getCategoria()->getNome()]['mps'] = $mps;
            }
        }
        return $out;
    }
    
    /*
     * Implementazione ISeo
     */

    public function title() {
        $out = $this->rifiuto;
        if (strlen($out) > 180) {
            return substr($out, 0, 180) . '...';
        }
        return $out;
    }

    public function getSeoFields() {
        return array(
            'titolo' => array(
                'fx' => 'getTitoloLowerCase',
                'descrizione' => 'Titolo della LPS dei rifiuti',
            ),
            'cer_list' => array(
                'fx' => 'getListaCer',
                'descrizione' => 'Elenco degli indici CER seprati da virgola',
            ),
            'categoria' => array(
                'fx' => 'getCategoriaDescrizione',
                'descrizione' => 'Descrizione della categoria del rifiuto',
            ),
            'caratteristiche' => array(
                'fx' => 'getCaratteristicheLowerCase',
                'descrizione' => 'caratteristiche del rifiuto',
            ),
        );
    }

    /*
     * Funzioni per generare SEO
     */

    public function getTitoloLowerCase() {
        return strtolower($this->getRifiuto());
    }

    public function getListaCer() {
        $cers = array();
        foreach ($this->getCer() as $cer) {
            $cers[] = $cer->getCodice();
        }
        return implode(', ', $cers);
    }

    public function getCategoriaDescrizione() {
        return strtolower($this->getCategoria()->getNome());
    }

    public function getCaratteristicheLowerCase() {
        return strtolower($this->getCaratteristiche());
    }
    
    public function getCodice() {
        $categoria = $this->getCategoria();
        return ($categoria->getPericoloso() ? '*' : '').$categoria->getIndice().'.'.$this->getNumero();
    }

}
