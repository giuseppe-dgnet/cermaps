<?php

namespace ES\OperatoriBundle\Entity\Traits;

trait ClasseCategoriaImplements {

    /*
     * Utilità
     */
    public function __toString() {
        return $this->getClasse();
    }

}
