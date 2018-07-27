<?php

namespace ES\CerMapBundle\Entity\Recuperabili\Traits;

trait CategoriaImplements {
    /*
     * Utilità 
     */

    public function __toString() {
        return $this->getIndice() . ' - ' . $this->getCategoria();
    }

    /*
     * Implementazione ISeo
     */

    public function title() {
        $out = "{$this->indice} - {$this->categoria}";
        if (strlen($out) > 180) {
            return substr($out, 0, 180) . '...';
        }
        return $out;
    }

    public function getSeoFields() {
        return array(
            'titolo' => array(
                'fx' => 'getTitoloMinuscolo',
                'descrizione' => 'Titolo della categoria',
            ),
            'descrizione' => array(
                'fx' => 'getCategoriaMinuscola',
                'descrizione' => 'Descrizione della categoria',
            ),
        );
    }

    /*
     * Funzioni extra per Gestione Seo
     */

    public function getTitoloMinuscolo() {
        return str_replace('Ù', 'ù', strtolower($this->getNome()));
    }

    public function getCategoriaMinuscola() {
        return str_replace('Ù', 'ù', strtolower($this->getCategoria()));
    }

}
