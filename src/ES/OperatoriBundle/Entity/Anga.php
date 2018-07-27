<?php

namespace ES\OperatoriBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ES\OperatoriBundle\Entity\Anga
 * @ORM\Table(name="grab_anga")
 * @ORM\Entity(repositoryClass="ES\OperatoriBundle\Entity\AngaRepository")
 */
class Anga {

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer $id_impresa
     *
     * @ORM\Column(name="anga_id", type="integer", unique=true)
     */
    private $anga_id;

    /**
     * @var string $exist
     *
     * @ORM\Column(name="esiste", type="boolean")
     */
    private $exist;

    /**
     * @var string $parsed
     *
     * @ORM\Column(name="parsata", type="boolean")
     */
    private $parsed;

    /**
     * @var string $provincia
     *
     * @ORM\Column(name="provincia", type="string", length=2, nullable=true)
     */
    private $provincia;

    /**
     * @var string $comune
     *
     * @ORM\Column(name="comune", type="string", length=255, nullable=true)
     */
    private $comune;

    /**
     * @var string $indirizzo
     *
     * @ORM\Column(name="indirizzo", type="string", length=255, nullable=true)
     */
    private $indirizzo;

    /**
     * @var string $cap
     *
     * @ORM\Column(name="cap", type="string", length=5, nullable=true)
     */
    private $cap;

    /**
     * @var string $codice_fiscale
     *
     * @ORM\Column(name="codice_fiscale", type="string", length=16, nullable=true)
     */
    private $codice_fiscale;

    /**
     * @var string $denominazione
     *
     * @ORM\Column(name="denominazione", type="string", length=255, nullable=true)
     */
    private $denominazione;

    /**
     * @var string $sezione
     *
     * @ORM\Column(name="sezione", type="string", length=2, nullable=true)
     */
    private $sezione;

    /**
     * @var string $numero_iscrizione
     *
     * @ORM\Column(name="numero_iscrizione", type="string", length=255, nullable=true)
     */
    private $numero_iscrizione;

    /**
     * @var string $categorie
     *
     * @ORM\Column(name="categorie", type="string", length=255, nullable=true)
     */
    private $categorie;

    /**
     * @var string $cer
     *
     * @ORM\Column(name="cer", type="text", nullable=true)
     */
    private $cer;

    /**
     * @var string $tipologie
     *
     * @ORM\Column(name="tipologie", type="text", nullable=true)
     */
    private $tipologie;

    /**
     * @var string $cer_cp
     *
     * @ORM\Column(name="cer_cp", type="text", nullable=true)
     */
    private $cer_cp;

    /**
     * @var string $has_cer
     *
     * @ORM\Column(name="has_cp", type="boolean", nullable=true)
     */
    private $has_cp;

    /**
     * @var string $has_cer
     *
     * @ORM\Column(name="has_cer", type="boolean", nullable=true)
     */
    private $has_cer;

    /**
     * @var string $has_tipologie
     *
     * @ORM\Column(name="has_tipologie", type="boolean", nullable=true)
     */
    private $has_tipologie;

    /**
     * @var string $has_cer_cp
     *
     * @ORM\Column(name="has_cer_cp", type="boolean", nullable=true)
     */
    private $has_cer_cp;

    /**
     * @var string $lista_categorie
     *
     * @ORM\Column(name="lista_categorie", type="array", nullable=true)
     */
    private $lista_categorie;

    /**
     * @var string $regione
     *
     * @ORM\Column(name="regione", type="integer", nullable=true)
     */
    private $regione;

    /**
     * @var string $pagina
     *
     * @ORM\Column(name="pagina", type="integer", nullable=true)
     */
    private $pagina;

    /**
     * @var string $last_list_update_at
     *
     * @ORM\Column(name="last_list_update_at", type="datetime", nullable=true)
     */
    private $last_list_update_at;

    /**
     * @var string $last_detail_update_at
     *
     * @ORM\Column(name="last_detail_update_at", type="datetime", nullable=true)
     */
    private $last_detail_update_at;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set anga_id
     *
     * @param integer $angaId
     * @return Anga
     */
    public function setAngaId($angaId) {
        $this->anga_id = $angaId;

        return $this;
    }

    /**
     * Get anga_id
     *
     * @return integer 
     */
    public function getAngaId() {
        return $this->anga_id;
    }

    /**
     * Set exist
     *
     * @param boolean $exist
     * @return Anga
     */
    public function setExist($exist) {
        $this->exist = $exist;

        return $this;
    }

    /**
     * Get exist
     *
     * @return boolean 
     */
    public function getExist() {
        return $this->exist;
    }

    /**
     * Set parsed
     *
     * @param boolean $parsed
     * @return Anga
     */
    public function setParsed($parsed) {
        $this->parsed = $parsed;

        return $this;
    }

    /**
     * Get parsed
     *
     * @return boolean 
     */
    public function getParsed() {
        return $this->parsed;
    }

    /**
     * Set provincia
     *
     * @param string $provincia
     * @return Anga
     */
    public function setProvincia($provincia) {
        $this->provincia = $provincia;

        return $this;
    }

    /**
     * Get provincia
     *
     * @return string 
     */
    public function getProvincia() {
        return $this->provincia;
    }

    /**
     * Set comune
     *
     * @param string $comune
     * @return Anga
     */
    public function setComune($comune) {
        $this->comune = $comune;

        return $this;
    }

    /**
     * Get comune
     *
     * @return string 
     */
    public function getComune() {
        return $this->comune;
    }

    /**
     * Set indirizzo
     *
     * @param string $indirizzo
     * @return Anga
     */
    public function setIndirizzo($indirizzo) {
        $this->indirizzo = $indirizzo;

        return $this;
    }

    /**
     * Get indirizzo
     *
     * @return string 
     */
    public function getIndirizzo() {
        return $this->indirizzo;
    }

    /**
     * Set cap
     *
     * @param string $cap
     * @return Anga
     */
    public function setCap($cap) {
        $this->cap = $cap;

        return $this;
    }

    /**
     * Get cap
     *
     * @return string 
     */
    public function getCap() {
        return $this->cap;
    }

    /**
     * Set codice_fiscale
     *
     * @param string $codiceFiscale
     * @return Anga
     */
    public function setCodiceFiscale($codiceFiscale) {
        $this->codice_fiscale = $codiceFiscale;

        return $this;
    }

    /**
     * Get codice_fiscale
     *
     * @return string 
     */
    public function getCodiceFiscale() {
        return $this->codice_fiscale;
    }

    /**
     * Set denominazione
     *
     * @param string $denominazione
     * @return Anga
     */
    public function setDenominazione($denominazione) {
        $this->denominazione = $denominazione;

        return $this;
    }

    /**
     * Get denominazione
     *
     * @return string 
     */
    public function getDenominazione() {
        return $this->denominazione;
    }

    /**
     * Set sezione
     *
     * @param string $sezione
     * @return Anga
     */
    public function setSezione($sezione) {
        $this->sezione = $sezione;

        return $this;
    }

    /**
     * Get sezione
     *
     * @return string 
     */
    public function getSezione() {
        return $this->sezione;
    }

    /**
     * Set numero_iscrizione
     *
     * @param string $numeroIscrizione
     * @return Anga
     */
    public function setNumeroIscrizione($numeroIscrizione) {
        $this->numero_iscrizione = $numeroIscrizione;

        return $this;
    }

    /**
     * Get numero_iscrizione
     *
     * @return string 
     */
    public function getNumeroIscrizione() {
        return $this->numero_iscrizione;
    }

    /**
     * Set categorie
     *
     * @param string $categorie
     * @return Anga
     */
    public function setCategorie($categorie) {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * Get categorie
     *
     * @return string 
     */
    public function getCategorie() {
        return $this->categorie;
    }

    /**
     * Set cer
     *
     * @param string $cer
     * @return Anga
     */
    public function setCer($cer) {
        $this->cer = $cer;

        return $this;
    }

    /**
     * Get cer
     *
     * @return string 
     */
    public function getCer() {
        return $this->cer;
    }

    /**
     * Set tipologie
     *
     * @param string $tipologie
     * @return Anga
     */
    public function setTipologie($tipologie) {
        $this->tipologie = $tipologie;

        return $this;
    }

    /**
     * Get tipologie
     *
     * @return string 
     */
    public function getTipologie() {
        return $this->tipologie;
    }

    /**
     * Set cer_cp
     *
     * @param string $cerCp
     * @return Anga
     */
    public function setCerCp($cerCp) {
        $this->cer_cp = $cerCp;

        return $this;
    }

    /**
     * Get cer_cp
     *
     * @return string 
     */
    public function getCerCp() {
        return $this->cer_cp;
    }

    /**
     * Set has_cp
     *
     * @param boolean $hasCp
     * @return Anga
     */
    public function setHasCp($hasCp) {
        $this->has_cp = !$hasCp;

        return $this;
    }

    /**
     * Get has_cp
     *
     * @return boolean 
     */
    public function getHasCp() {
        return $this->has_cp;
    }

    /**
     * Set has_cp
     *
     * @param boolean $hasCp
     * @return Anga
     */
    public function setHasNoCp($hasCp) {
        $this->has_cp = !$hasCp;

        return $this;
    }

    /**
     * Get has_cp
     *
     * @return boolean 
     */
    public function getHasNoCp() {
        return $this->has_cp;
    }

    /**
     * Set has_cer
     *
     * @param boolean $hasCer
     * @return Anga
     */
    public function setHasCer($hasCer) {
        $this->has_cer = $hasCer;

        return $this;
    }

    /**
     * Get has_cer
     *
     * @return boolean 
     */
    public function getHasCer() {
        return $this->has_cer;
    }

    /**
     * Set has_tipologie
     *
     * @param boolean $hasTipologie
     * @return Anga
     */
    public function setHasTipologie($hasTipologie) {
        $this->has_tipologie = $hasTipologie;

        return $this;
    }

    /**
     * Get has_tipologie
     *
     * @return boolean 
     */
    public function getHasTipologie() {
        return $this->has_tipologie;
    }

    /**
     * Set has_cer_cp
     *
     * @param boolean $hasCerCp
     * @return Anga
     */
    public function setHasCerCp($hasCerCp) {
        $this->has_cer_cp = $hasCerCp;

        return $this;
    }

    /**
     * Get has_cer_cp
     *
     * @return boolean 
     */
    public function getHasCerCp() {
        return $this->has_cer_cp;
    }

    /**
     * Set lista_categorie
     *
     * @param array $listaCategorie
     * @return Anga
     */
    public function setListaCategorie($listaCategorie) {
        $this->lista_categorie = $listaCategorie;

        return $this;
    }

    /**
     * Get lista_categorie
     *
     * @return array 
     */
    public function getListaCategorie() {
        return $this->lista_categorie;
    }

    /**
     * Set last_list_update_at
     *
     * @param \DateTime $lastListUpdateAt
     * @return Anga
     */
    public function setLastListUpdateAt($lastListUpdateAt) {
        $this->last_list_update_at = $lastListUpdateAt;

        return $this;
    }

    /**
     * Get last_list_update_at
     *
     * @return \DateTime 
     */
    public function getLastListUpdateAt() {
        return $this->last_list_update_at;
    }

    /**
     * Set last_detail_update_at
     *
     * @param \DateTime $lastDetailUpdateAt
     * @return Anga
     */
    public function setLastDetailUpdateAt($lastDetailUpdateAt) {
        $this->last_detail_update_at = $lastDetailUpdateAt;

        return $this;
    }

    /**
     * Get last_detail_update_at
     *
     * @return \DateTime 
     */
    public function getLastDetailUpdateAt() {
        return $this->last_detail_update_at;
    }

    /**
     * Set regione
     *
     * @param integer $regione
     * @return Anga
     */
    public function setRegione($regione) {
        $this->regione = $regione;

        return $this;
    }

    /**
     * Get regione
     *
     * @return integer 
     */
    public function getRegione() {
        return $this->regione;
    }

    /**
     * Set pagina
     *
     * @param integer $pagina
     * @return Anga
     */
    public function setPagina($pagina) {
        $this->pagina = $pagina;

        return $this;
    }

    /**
     * Get pagina
     *
     * @return integer 
     */
    public function getPagina() {
        return $this->pagina;
    }

    public function serialize() {
        return array(
            'id' => 0,
            'anga_id' => $this->getId(),
            'denominazione' => $this->getDenominazione(),
            'codice_fiscale' => $this->getCodiceFiscale(),
            'sezione' => $this->getSezione(),
            'numero_iscrizione' => $this->getNumeroIscrizione(),
            
            'indirizzo' => $this->getIndirizzo(),
        	'nazione' => 'IT',
        	'regione' => $this->getRegione(),
            'provincia' => $this->getProvincia(),
            'comune' => $this->getComune(),
            'cap' => $this->getCap(),
            
            'categorie' => $this->getCategorie(),
            'has_cer' => $this->getHasCer(),
            'has_cer_cp' => $this->getHasCerCp(),
            'has_tipologie' => $this->getHasTipologie(),
            'lista_categorie' => $this->getListaCategorie(),
            'cer' => $this->getCer(),
            'cercp' => $this->getCerCp(),
            'tipologie' => $this->getTipologie(),
            
            'list_update' => $this->getLastListUpdateAt()->getTimestamp(),
            'detail_update' => $this->getLastDetailUpdateAt() ? $this->getLastDetailUpdateAt()->getTimestamp() : 0,
        );
    }

}