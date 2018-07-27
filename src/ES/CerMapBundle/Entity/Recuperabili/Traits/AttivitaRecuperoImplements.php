<?php

namespace ES\CerMapBundle\Entity\Recuperabili\Traits;

trait AttivitaRecuperoImplements {
    /*
     * Utilità 
     */

    public function titolo() {
        $out = $this->attivita;
        if (strlen($out) > 180) {
            return substr($out, 0, 180) . '...';
        }
        return $out;
    }

}
