<?php

namespace ES\CerMapBundle\Entity\Recuperabili\Traits;

trait SmaltimentoRecuperoImplements {

    public function __toString() {
        return $this->getCodice() . ' - ' . $this->getDescrizione();
    }

}
