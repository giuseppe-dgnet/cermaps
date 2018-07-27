<?php

namespace ES\OperatoriBundle\Entity\Servizi\Traits;

trait CategoriaImplements {
    /*
     * Utilità
     */

    public function __toString() {
        return $this->getCategoria();
    }

    /*
     * Implementazione ISeo 
     */

    public function title() {
        $out = "{$this->categoria}";
        if (strlen($out) > 180) {
            return substr($out, 0, 180) . '...';
        }
        return $out;
    }

    public function getSeoFields() {
        return array(
            'categoria' => array(
                'fx' => 'getCategoriaLowerCase',
                'descrizione' => 'Descrizione categoria',
            ),
        );
    }
    
    /*
     * Funzioni extra per Gestione Seo
     */

    /**
     * Get categoria
     *
     * @return text 
     */
    public function getCategoriaLowerCase() {
        return strtolower($this->categoria);
    }

}
