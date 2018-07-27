<?php

namespace ES\CerMapBundle\Entity\Mps\Traits;

trait CategoriaImplements {
    /*
     * Utilità
     */

    public function __toString() {
        return $this->getNome() . ' (' . $this->getCategoria() . ')';
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
            'nome' => array(
                'fx' => 'getNomeLowerCase',
                'descrizione' => 'Nome categoria',
            ),
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
     * Get nome
     *
     * @return string 
     */
    public function getNomeLowerCase() {
        return strtoupper($this->nome{0}) . strtolower(substr($this->nome, 1));
    }

    /**
     * Get categoria
     *
     * @return text 
     */
    public function getCategoriaLowerCase() {
        return strtolower($this->categoria);
    }

}
