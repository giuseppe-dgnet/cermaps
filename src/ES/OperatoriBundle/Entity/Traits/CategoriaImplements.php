<?php

namespace ES\OperatoriBundle\Entity\Traits;

trait CategoriaImplements {

    /*
     * Utilità
     */
    public function __toString() {
        return $this->getCategoria();
    }

}
