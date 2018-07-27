<?php

namespace ES\OperatoriBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Richiesta
 *
 * @ORM\Table(name="op_richieste")
 * @ORM\Entity(repositoryClass="ES\OperatoriBundle\Entity\RichiestaRepository")
 */
class Richiesta {

    use \ES\OperatoriBundle\Entity\Traits\RichiestaImplements;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="ragione_sociale", type="string", length=255)
     */
    private $ragione_sociale;
    
    /**
     * @var string
     *
     * @ORM\Column(name="codice_fiscale_azienda", type="string", length=255)
     */
    private $codice_fiscale_azienda;
    
    /**
     * @var string
     *
     * @ORM\Column(name="attivita_principale", type="string", length=255)
     */
    private $attivita_principale;
    

    /**
     * @var string
     *
     * @ORM\Column(name="referente", type="string", length=255)
     */
    private $referente;

    /**
     * @var string
     *
     * @ORM\Column(name="partita_iva", type="string", length=11)
     */
    private $partita_iva;

    /**
     * @var string
     *
     * @ORM\Column(name="telefono", type="string", length=255)
     */
    private $telefono;
    
    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;
    
    /**
     * @var string
     *
     * @ORM\Column(name="email_pec", type="string", length=255,nullable=true)
     */
    private $email_pec;
    
    /**
     * @var string
     *
     * @ORM\Column(name="sito_web", type="string", length=255,nullable=true)
     */
    private $sito_web;
    
    
    /**
     * @var string
     *
     * @ORM\Column(name="fax", type="string", length=255,nullable=true)
     */
    private $fax;

    /**
     * @var string
     *
     * @ORM\Column(name="indirizzo", type="string", length=255)
     */
    private $indirizzo;

    /**
     * @var boolean
     *
     * @ORM\Column(name="impianto", type="boolean")
     */
    private $impianto;

    /**
     * @var boolean
     *
     * @ORM\Column(name="discarica", type="boolean")
     */
    private $discarica;

    /**
     * @var boolean
     *
     * @ORM\Column(name="raccoglitore", type="boolean")
     */
    private $raccoglitore;

    /**
     * @var boolean
     *
     * @ORM\Column(name="trasportatore", type="boolean")
     */
    private $trasportatore;

    /**
     * @var boolean
     *
     * @ORM\Column(name="laboratorio", type="boolean")
     */
    private $laboratorio;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="demolizioni", type="boolean", nullable=true)
     */
    private $demolizioni;

    /**
     * @var boolean
     *
     * @ORM\Column(name="spurghi", type="boolean", nullable=true)
     */
    private $spurghi;

    /**
     * @var boolean
     *
     * @ORM\Column(name="bonifiche", type="boolean", nullable=true)
     */
    private $bonifiche;

    /**
     * @var boolean
     *
     * @ORM\Column(name="rottamazione", type="boolean", nullable=true)
     */
    private $rottamazione;

    /**
     * @var boolean
     *
     * @ORM\Column(name="raee", type="boolean", nullable=true)
     */
    private $raee;

    /**
     * @var boolean
     *
     * @ORM\Column(name="olio_minerale", type="boolean", nullable=true)
     */
    private $olio_minerale;

    /**
     * @var boolean
     *
     * @ORM\Column(name="olio_vegetale", type="boolean", nullable=true)
     */
    private $olio_vegetale;

    /**
     * @var boolean
     *
     * @ORM\Column(name="servizi", type="boolean")
     */
    private $servizi;

    /**
     * @var text
     *
     * @ORM\Column(name="note", type="text", nullable=true)
     */
    private $note;

    /**
     * @var Showroom
     *
     * @ORM\ManyToOne(targetEntity="Showroom")
     * @ORM\JoinColumn(name="showroom_id", referencedColumnName="id", nullable=true)
     */
    private $showroom;
    
    /**
     * @var string
     *
     * @ORM\OneToOne(targetEntity="ES\UserBundle\Entity\User", inversedBy="showroom")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
     */
    private $user;
    
    /**
     * @var string
     *
     * @ORM\Column(name="ruolo", type="string", nullable=true)
     */
    private $ruolo;
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set ragione_sociale
     *
     * @param string $ragioneSociale
     * @return Richiesta
     */
    public function setRagioneSociale($ragioneSociale) {
        $this->ragione_sociale = $ragioneSociale;

        return $this;
    }

    /**
     * Get ragione_sociale
     *
     * @return string 
     */
    public function getRagioneSociale() {
        return $this->ragione_sociale;
    }

    /**
     * Set referente
     *
     * @param string $referente
     * @return Richiesta
     */
    public function setReferente($referente) {
        $this->referente = $referente;

        return $this;
    }

    /**
     * Get referente
     *
     * @return string 
     */
    public function getReferente() {
        return $this->referente;
    }

    /**
     * Set partita_iva
     *
     * @param string $partitaIva
     * @return Richiesta
     */
    public function setPartitaIva($partitaIva) {
        $this->partita_iva = $partitaIva;

        return $this;
    }

    /**
     * Get partita_iva
     *
     * @return string 
     */
    public function getPartitaIva() {
        return $this->partita_iva;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Richiesta
     */
    public function setEmail($email) {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Set indirizzo
     *
     * @param string $indirizzo
     * @return Richiesta
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
     * Set impianto
     *
     * @param boolean $impianto
     * @return Richiesta
     */
    public function setImpianto($impianto) {
        $this->impianto = $impianto;

        return $this;
    }

    /**
     * Get impianto
     *
     * @return boolean 
     */
    public function getImpianto() {
        return $this->impianto;
    }

    /**
     * Set discarica
     *
     * @param boolean $discarica
     * @return Richiesta
     */
    public function setDiscarica($discarica) {
        $this->discarica = $discarica;

        return $this;
    }

    /**
     * Get discarica
     *
     * @return boolean 
     */
    public function getDiscarica() {
        return $this->discarica;
    }

    /**
     * Set raccoglitore
     *
     * @param boolean $raccoglitore
     * @return Richiesta
     */
    public function setRaccoglitore($raccoglitore) {
        $this->raccoglitore = $raccoglitore;

        return $this;
    }

    /**
     * Get raccoglitore
     *
     * @return boolean 
     */
    public function getRaccoglitore() {
        return $this->raccoglitore;
    }

    /**
     * Set trasportatore
     *
     * @param boolean $trasportatore
     * @return Richiesta
     */
    public function setTrasportatore($trasportatore) {
        $this->trasportatore = $trasportatore;

        return $this;
    }

    /**
     * Get trasportatore
     *
     * @return boolean 
     */
    public function getTrasportatore() {
        return $this->trasportatore;
    }

    /**
     * Set laboratorio
     *
     * @param boolean $laboratorio
     * @return Richiesta
     */
    public function setLaboratorio($laboratorio) {
        $this->laboratorio = $laboratorio;

        return $this;
    }

    /**
     * Get laboratorio
     *
     * @return boolean 
     */
    public function getLaboratorio() {
        return $this->laboratorio;
    }

    /**
     * Set servizi
     *
     * @param boolean $servizi
     * @return Richiesta
     */
    public function setServizi($servizi) {
        $this->servizi = $servizi;

        return $this;
    }

    /**
     * Get servizi
     *
     * @return boolean 
     */
    public function getServizi() {
        return $this->servizi;
    }


    /**
     * Set telefono
     *
     * @param string $telefono
     * @return Richiesta
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;
    
        return $this;
    }

    /**
     * Get telefono
     *
     * @return string 
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set codice_fiscale_azienda
     *
     * @param string $codiceFiscaleAzienda
     * @return Richiesta
     */
    public function setCodiceFiscaleAzienda($codiceFiscaleAzienda)
    {
        $this->codice_fiscale_azienda = $codiceFiscaleAzienda;

        return $this;
    }

    /**
     * Get codice_fiscale_azienda
     *
     * @return string 
     */
    public function getCodiceFiscaleAzienda()
    {
        return $this->codice_fiscale_azienda;
    }

    /**
     * Set email_pec
     *
     * @param string $emailPec
     * @return Richiesta
     */
    public function setEmailPec($emailPec)
    {
        $this->email_pec = $emailPec;

        return $this;
    }

    /**
     * Get email_pec
     *
     * @return string 
     */
    public function getEmailPec()
    {
        return $this->email_pec;
    }

    /**
     * Set sito_web
     *
     * @param string $sitoWeb
     * @return Richiesta
     */
    public function setSitoWeb($sitoWeb)
    {
        $this->sito_web = $sitoWeb;

        return $this;
    }

    /**
     * Get sito_web
     *
     * @return string 
     */
    public function getSitoWeb()
    {
        return $this->sito_web;
    }

    /**
     * Set fax
     *
     * @param string $fax
     * @return Richiesta
     */
    public function setFax($fax)
    {
        $this->fax = $fax;

        return $this;
    }

    /**
     * Get fax
     *
     * @return string 
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * Set attivita_principale
     *
     * @param string $attivitaPrincipale
     * @return Richiesta
     */
    public function setAttivitaPrincipale($attivitaPrincipale)
    {
        $this->attivita_principale = $attivitaPrincipale;

        return $this;
    }

    /**
     * Get attivita_principale
     *
     * @return string 
     */
    public function getAttivitaPrincipale()
    {
        return $this->attivita_principale;
    }


    /**
     * Set note
     *
     * @param string $note
     * @return Richiesta
     */
    public function setNote($note)
    {
        $this->note = $note;
    
        return $this;
    }

    /**
     * Get note
     *
     * @return string 
     */
    public function getNote()
    {
        return $this->note;
    }
    
    /**
     * Set showroom
     *
     * @param \ES\OperatoriBundle\Entity\Showroom $showroom
     * @return ShowroomCategoria
     */
    public function setShowroom(\ES\OperatoriBundle\Entity\Showroom $showroom = null) {
        $this->showroom = $showroom;

        return $this;
    }

    /**
     * Get showroom
     *
     * @return \ES\OperatoriBundle\Entity\Showroom 
     */
    public function getShowroom() {
        return $this->showroom;
    }
    
    /**
     * Set ruolo
     *
     * @param string $ruolo
     * @return User
     */
    public function setRuolo($ruolo)
    {
        $this->ruolo = $ruolo;

        return $this;
    }

    /**
     * Get ruolo
     *
     * @return string 
     */
    public function getRuolo()
    {
        return $this->ruolo;
    }
    
    /**
     * Set user
     *
     * @param \ES\UserBundle\Entity\User $user
     * @return Showroom
     */
    public function setUser(\ES\UserBundle\Entity\User $user = null) {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \ES\UserBundle\Entity\User 
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * Set demolizioni
     *
     * @param boolean $demolizioni
     * @return Richiesta
     */
    public function setDemolizioni($demolizioni)
    {
        $this->demolizioni = $demolizioni;
    
        return $this;
    }

    /**
     * Get demolizioni
     *
     * @return boolean 
     */
    public function getDemolizioni()
    {
        return $this->demolizioni;
    }

    /**
     * Set spurghi
     *
     * @param boolean $spurghi
     * @return Richiesta
     */
    public function setSpurghi($spurghi)
    {
        $this->spurghi = $spurghi;
    
        return $this;
    }

    /**
     * Get spurghi
     *
     * @return boolean 
     */
    public function getSpurghi()
    {
        return $this->spurghi;
    }

    /**
     * Set bonifiche
     *
     * @param boolean $bonifiche
     * @return Richiesta
     */
    public function setBonifiche($bonifiche)
    {
        $this->bonifiche = $bonifiche;
    
        return $this;
    }

    /**
     * Get bonifiche
     *
     * @return boolean 
     */
    public function getBonifiche()
    {
        return $this->bonifiche;
    }

    /**
     * Set rottamazione
     *
     * @param boolean $rottamazione
     * @return Richiesta
     */
    public function setRottamazione($rottamazione)
    {
        $this->rottamazione = $rottamazione;
    
        return $this;
    }

    /**
     * Get rottamazione
     *
     * @return boolean 
     */
    public function getRottamazione()
    {
        return $this->rottamazione;
    }

    /**
     * Set raee
     *
     * @param boolean $raee
     * @return Richiesta
     */
    public function setRaee($raee)
    {
        $this->raee = $raee;
    
        return $this;
    }

    /**
     * Get raee
     *
     * @return boolean 
     */
    public function getRaee()
    {
        return $this->raee;
    }

    /**
     * Set olio_minerale
     *
     * @param boolean $olioMinerale
     * @return Richiesta
     */
    public function setOlioMinerale($olioMinerale)
    {
        $this->olio_minerale = $olioMinerale;
    
        return $this;
    }

    /**
     * Get olio_minerale
     *
     * @return boolean 
     */
    public function getOlioMinerale()
    {
        return $this->olio_minerale;
    }

    /**
     * Set olio_vegetale
     *
     * @param boolean $olioVegetale
     * @return Richiesta
     */
    public function setOlioVegetale($olioVegetale)
    {
        $this->olio_vegetale = $olioVegetale;
    
        return $this;
    }

    /**
     * Get olio_vegetale
     *
     * @return boolean 
     */
    public function getOlioVegetale()
    {
        return $this->olio_vegetale;
    }
}
