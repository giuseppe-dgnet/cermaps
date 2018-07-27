<?php

namespace ES\OperatoriBundle\Entity\Traits;

trait ShowroomCategoriaImplements {

    /*
     * Utilità
     */
    public function __toString() {
        return $this->getCategoria()->getCategoria().($this->getClasse() ? ' classe '.$this->getClasse()->getClasse() : '');
    }
    
    public function getCategoriaBreve() {
        return $this->getCategoria()->getCategoria().($this->getClasse() ? ' classe '.$this->getClasse()->getClasse() : '');
    }

}
