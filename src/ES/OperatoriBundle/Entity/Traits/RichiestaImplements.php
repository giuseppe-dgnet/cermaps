<?php

namespace ES\OperatoriBundle\Entity\Traits;

trait RichiestaImplements {

    /*
     * Utilità
     */
    public function __toString() {
        return $this->getRagioneSociale();
    }

}
