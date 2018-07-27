<?php

namespace ES\OperatoriBundle\Entity\Servizi\Traits;

trait ServizioImplements {
    /*
     * Utilità 
     */

    public function __toString() {
        return $this->getServizio() . ( $this->getDescrizione() ? " ({$this->getDescrizione()})" : '');
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
        return $this->servizio;
    }

    public function getSeoFields() {
        return array(
            'servizio' => array(
                'fx' => 'getServizio',
                'descrizione' => 'Nome servizio',
            ),
        );
    }

}