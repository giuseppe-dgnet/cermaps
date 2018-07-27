<?php

namespace ES\CerMapBundle\Entity\Cer\Traits;

trait CerImplements {

    /*
     * Utilità
     */
    public function __toString() {
        return $this->codice;
    }
    
    public function getExtraField() {
        return array(
            'route' => $this->getSottoclasse() == '00' ? 'sottoclassi_indici_cer' : ($this->getCategoria() == '00' ? 'categorie_indici_cer' : 'cer_indici_cer'),
            'label_link' => $this->getSottoclasse() == '00' || $this->getCategoria() == '00' ? "Apri l'elenco" : 'Apri la scheda',
            'route_param' => array(
                'slug' => $this->getSlug(),
            ),
        );
    }
    
    /**
     * @return array
     */
    public function toArray() {
        return array(
            'id' => $this->getId(),
            'slug' => $this->getSlug(),
            'textslug' => substr($this->getSlug(), 9),
            'codice' => $this->codice,
            'classe' => $this->classe,
            'sottoclasse' => $this->sottoclasse,
            'categoria' => $this->categoria,
            'pericoloso' => $this->pericoloso,
            'descrizione' => $this->descrizione,
            'description' => $this->getDescription(),
            'keywords' => $this->getKeywords(),
        );
    }

    public function getDescrizioneTrocata() {
        return \Ephp\UtilityBundle\Utility\String::tronca($this->descrizione, 180);
    }

    /*
     * Implementazione ISeo 
     */
    public function title() {
        return "{$this->getClasse()}{$this->getSottoclasse()}{$this->getCategoria()} {$this->getDescrizioneTrocata()}";
    }

    public function getSeoFields() {
        return array(
            'pericoloso' => array(
                'fx' => 'getCodicePericoloso',
                'descrizione' => 'Indica se il CER è pericoloso',
            ),
            'codice' => array(
                'fx' => 'getCodice',
                'descrizione' => 'Codice indice CER',
            ),
            'descrizione' => array(
                'fx' => 'getDescrizione',
                'descrizione' => 'Descrizione del CER',
            ),
        );
    }

    
    private $avaible = false;
    
    public function setAvaible($avaible) {
        $this->avaible = $avaible;
    }
    public function getAvaible() {
        return $this->avaible;
    }
}
