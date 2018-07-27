<?php

namespace ES\CerMapBundle\Entity\Mps\Traits;

trait MpsImplements {
    /*
     * Utilità 
     */

    public function __toString() {
        return "[{$this->getCategoria()->getNome()}] {$this->getMateria()} ({$this->getDescrizione()})";
    }

    public function getExtraField() {
        return array(
            'route' => 'lps_mps',
            'label_link' => 'Apri la scheda',
            'route_param' => array(
                'slug' => $this->getSlug(),
            ),
        );
    }

    /*
     * Implementazione ISeo 
     */

    public function title() {
        return $this->materia;
    }

    public function getSeoFields() {
        return array(
            'materia_con_descrizione' => array(
                'fx' => 'getDescrizioneFormattata',
                'descrizione' => 'Stringa formattata con materia e descrizione',
            ),
            'materia' => array(
                'fx' => 'getMateriaLowerCase',
                'descrizione' => 'Nome della materia restituita il lower case',
            ),
        );
    }

    /*
     * Funzioni extra per Gestione Seo
     */

    public function getDescrizioneFormattata() {
        return strtolower($this->getMateria() . ($this->getDescrizione() ? "({$this->getDescrizione()})" : ''));
    }

    /**
     * Get materia
     *
     * @return string 
     */
    public function getMateriaLowerCase() {
        return strtolower($this->materia);
    }
}
