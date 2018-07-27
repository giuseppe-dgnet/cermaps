<?php

namespace ES\MessengerBundle\Entity\RDO;

use Doctrine\ORM\Mapping as ORM;
use ES\MessengerBundle\Entity\MessaggioBase;
use ES\WebBundle\Functions\Funzioni;

/**
 * ES\MessengerBundle\Entity\RDO\Cer
 *
 * @ORM\Table(name="msg_rdo_cer")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="ES\MessengerBundle\Entity\RDO\CerRepository")
 */
class Cer extends MessaggioBase {

    /**
     * @var string $telefono
     *
     * @ORM\Column(name="telefono", type="string", length=32)
     */
    private $telefono;
    
    /**
     * @var ES\OperatoriBundle\Entity\Showroom $showroom
     * 
     * @ORM\ManyToOne(targetEntity="ES\OperatoriBundle\Entity\Showroom")
     * @ORM\JoinColumn(name="from_showroom_id", referencedColumnName="id", nullable=true)
     */
    private $from_showroom;

    /**
     * @var string $condizione_fisica
     *
     * @ORM\Column(name="condizione_fisica", type="string", length=40, nullable=true)
     */
    private $condizione_fisica;

    /**
     * @var integer $quantita
     *
     * @ORM\Column(name="quantita", type="decimal", precision=15, scale=3, nullable=true)
     */
    private $quantita;

    /**
     * @var string $uumm
     *
     * @ORM\Column(name="uumm", type="string", length=40, nullable=true)
     */
    private $uumm;

    /**
     * @var integer $periodicita
     *
     * @ORM\Column(name="periodicita",type="string", length=40, nullable=true)
     */
    private $periodicita;

    /**
     * @var Comune $comune
     *
     * @ORM\ManyToOne(targetEntity="Ephp\GeoBundle\Entity\GeoNames")
     * @ORM\JoinColumn(name="comune_id", referencedColumnName="geonameid", nullable=true)
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
     * @var decimal $latitudine
     *
     * @ORM\Column(name="latitudine", type="decimal", precision=15, scale=10, nullable=true)
     */
    private $latitudine;

    /**
     * @var decimal $longitudine
     *
     * @ORM\Column(name="longitudine", type="decimal", precision=15, scale=10, nullable=true)
     */
    private $longitudine;

    /**
     * @var string $cer_list
     *
     * @ORM\Column(name="cer_list", type="text", nullable=true)
     */
    private $cer_list;

    /**
     * @ORM\ManyToMany(targetEntity="ES\CerMapBundle\Entity\Cer\Cer")
     * @ORM\JoinTable(name="msg_rdo_cer_list",
     *      joinColumns={@ORM\JoinColumn(name="rdo_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="cer_id", referencedColumnName="id")}
     *      )
     */
    private $cer;
    
    /**
     * @var text $note_admin
     *
     * @ORM\Column(name="note_admin", type="text", nullable=true)
     */
    private $note_admin;
    
    /**
     * @var text $note_admin
     *
     * @ORM\Column(name="admin", type="string", nullable=true)
     */
    private $admin;
    
    /**
     * @var boolean $contattato_admin
     *
     * @ORM\ManyToOne(targetEntity="Stato")
     * @ORM\JoinColumn(name="stato_id", referencedColumnName="id", nullable=true)
     */
    private $stato;
    
    public function getNoteAdmin() {
        return $this->note_admin;
    }

    public function setNoteAdmin($note_admin) {
        $this->note_admin = $note_admin;
    }
    
    public function getAdmin() {
        return $this->admin;
    }

    public function setAdmin($admin) {
        $this->admin = $admin;
    }

    /**
     * 
     * @return Stato
     */
    public function getStato() {
        return $this->stato;
    }

    public function setStato($stato) {
        $this->stato = $stato;
    }

        
    /**
     * Get from_showroom_id
     *
     * @return ES\OperatoriBundle\Entity\Showroom 
     */
    public function getFromShowroom() {
        return $this->from_showroom;
    }
    
    /**
    * Set form_showroom_id
    *
    * @param \ES\UserBundle\Entity\User $from_utente
    */
    public function setFromShowroom(\ES\OperatoriBundle\Entity\Showroom $from_showroom, $set_id = false) {
        $this->from_showroom =  $set_id ? $from_showroom->getId() : $from_showroom;
    }

    function __construct() {
        parent::__construct();
        $this->cer = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set from_user_id
     *
     * @param \ES\UserBundle\Entity\User $from_utente
     */
    public function setFromUtente(\ES\UserBundle\Entity\User $from_utente, $set_id = false) {
        parent::setFromUtente($from_utente, $set_id);
        $this->setTelefono($from_utente->getShowroom() ? $from_utente->getShowroom()->getTelefono() : '');
    }

    /**
     * Set condizione_fisica
     *
     * @param string $condizioneFisica
     */
    public function setCondizioneFisica($condizioneFisica) {
        $this->condizione_fisica = $condizioneFisica;
    }

    /**
     * Get condizione_fisica
     *
     * @return string 
     */
    public function getCondizioneFisica() {
        return $this->condizione_fisica;
    }

    /**
     * Set quantita
     *
     * @param integer $quantita
     */
    public function setQuantita($quantita) {
        $this->quantita = $quantita;
    }

    /**
     * Get quantita
     *
     * @return integer 
     */
    public function getQuantita() {
        return $this->quantita;
    }

    /**
     * Set uumm
     *
     * @param string $uumm
     */
    public function setUumm($uumm) {
        $this->uumm = $uumm;
    }

    /**
     * Get uumm
     *
     * @return string 
     */
    public function getUumm() {
        
        return $this->uumm ? $this->uumm : "unita";
        
        //return $this->uumm;
    }

    /**
     * Set periodicita
     *
     * @param integer $periodicita
     */
    public function setPeriodicita($periodicita) {
        $this->periodicita = $periodicita;
    }

    /**
     * Get periodicita
     *
     * @return integer 
     */
    public function getPeriodicita() {
        return $this->periodicita;
    }

    /**
     * Set comune
     *
     * @param \Ephp\GeoBundle\Entity\GeoNames $comune
     */
    public function setComune($comune) {
        $this->comune = $comune;
    }

    /**
     * Get comune
     *
     * @return \Ephp\GeoBundle\Entity\GeoNames 
     */
    public function getComune() {
        return $this->comune;
    }

    /**
     * Set indirizzo
     *
     * @param string $indirizzo
     */
    public function setIndirizzo($indirizzo) {
        $this->indirizzo = $indirizzo;
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
     */
    public function setCap($cap) {
        $this->cap = $cap;
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
     * Set latitudine
     *
     * @param decimal $latitudine
     */
    public function setLatitudine($latitudine) {
        $this->latitudine = $latitudine;
    }

    /**
     * Get latitudine
     *
     * @return decimal 
     */
    public function getLatitudine() {
        return $this->latitudine;
    }

    /**
     * Set longitudine
     *
     * @param decimal $longitudine
     */
    public function setLongitudine($longitudine) {
        $this->longitudine = $longitudine;
    }

    /**
     * Get longitudine
     *
     * @return decimal 
     */
    public function getLongitudine() {
        return $this->longitudine;
    }

    /**
     * Set cer_list
     *
     * @param string $cerList
     */
    public function setCerList($cerList) {
        $this->cer_list = $cerList;
    }

    /**
     * Get cer_list
     *
     * @return string 
     */
    public function getCerList() {
        return $this->cer_list;
    }

    /**
     * Set telefono
     *
     * @param string $telefono
     */
    public function setTelefono($telefono) {
        $this->telefono = $telefono;
    }

    /**
     * Get telefono
     *
     * @return string 
     */
    public function getTelefono() {
        return $this->telefono;
    }

    /**
     * get cer
     *
     * @return \Doctrine\Common\Collections\ArrayCollection 
     */
    public function getCer() {
        return $this->cer;
    }

    /**
     * ser cer
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $cer 
     */
    public function setCer($cer) {
        $this->cer = $cer;
    }

    /**
     * ser cer
     *
     * @param \ES\CERBundle\Entity\CERIndex $cer 
     */
    public function addCer($cer) {
        $this->cer->add($cer);
    }

    public function getUnitaMisura() {
        switch ($this->uumm) {
            case 1: return 'kg.';
            case 100: return 'q.';
            case 1000: return 't.';
            case -1: return 'lt.';
            case -1000: return 'm3';
            default:
                return '-';
        }
    }

    public function getPeriodicitaTestuale() {
        switch ($this->periodicita) {
            case 0: return 'Un solo recupero';
            case 7: return 'Settimanale';
            case 30: return 'Mensile';
            case 60: return 'Bimestrale.';
            case 90: return 'Trimestrale';
            default:
                return '-';
        }
    }

    public function getStatoRifiutoTestuale() {
        switch ($this->periodicita) {
            case 'S': return 'Solido non polveroso';
            case 'P': return 'Solido polveroso';
            case 'L': return 'Liquido';
            case 'F': return 'Fango Palabile';
            default:
                return '-';
        }
    }

    public function getElenco($n) {
        if (count($this->cer) <= $n) {
            $out = array();
            for ($i = 0; $i < count($this->cer); $i++) {
                $cer = $this->cer->get($i);
                $out[] = $cer->getCodice();
            }
        return implode(', ', $out);
        }
        $out = array();   
        for ($i = 0; $i < $n; $i++) {
            $cer = $this->cer->get($i);
            $out[] = $cer->getCodice();
        }
        return implode(', ', $out) . ' e altri' . (count($this->cer) - $n) . ' CER';
    }

    public function getTwigTesto() {
        return 'EcoSeekrMessengerBundle:Messenger:discussione/rdo/cer.html.twig';
    }

    /**
     * @ORM\PrePersist 
     */
    public function prePersist() {
        parent::prePersist();
        /* $subject = "[RDO][CER] Richiesta di recupero o smaltimento di CER { $this->cer_list } ({$this->quantita} {$this->getUnitaMisura()}) a {$this->getComune()->getNomeComune()}"; */
        /*$subject = "[RDO][CER] Richiesta di recupero o smaltimento di CER  ({$this->getElenco(10)} - {$this->quantita} {$this->getUumm()}) a {$this->getComune()->getNomeComune()}";*/
        $subject =" [RDO][CER] Richiesta di recupero o smaltimento di CER ".(count($this->cer) > 0 ? "(". $this->getElenco(5).")" : '')." ".($this->getComune() ? "a ".$this->getComune()->getNomeComune() : '')."";
        $this->setSubject($subject);
    }

    public function riepilogo() {
        return "
RICHIESTA PER RECUPERO O SMALTIMENTO RIFIUTI
--------------------------------------------

- CER: ".(count($this->cer) > 0 ? $this->getElenco(10) : 'Non è stato specificato')."
- QUANTITA': ".($this->getQuantita() ? $this->getQuantita() ." - ".$this->getUumm() : 'Non è stato specificato')."
- DOVE: {$this->indirizzo} - ".($this->getComune() ? $this->getComune()->getNomeComune() : 'Non è stato specificato')."
- PERIODICITA':".($this->getPeriodicita() ? $this->getPeriodicita() :'Non è stato specificata')."
- NOTE:".($this->getTesto() ? $this->getTesto() :'Non è stata inserita nessuna nota a riguardo' )." 

- RICHIEDENTE: ".($this->getNomeCompleto() != '' ? $this->getNomeCompleto() : 'Utente anonimo')."
- TIPO RICHIEDENTE: {$this->getStatoRichiedente()}
        ";
    }
    public function riepilogoAdmin() {
        //return "TEST";
        return "

<br /> CER: ".(count($this->cer) > 0 ? $this->getElenco(100) : 'Non è stato specificato')."
<br /> QUANTITA': ".($this->getQuantita() ? $this->getQuantita() ." - ".$this->getUumm() : 'Non è stato specificato')."
<br /> DOVE: {$this->indirizzo} - ".($this->getComune() ? $this->getComune()->getNomeComune() : 'Non è stato specificato')."
<br /> PERIODICITA':".($this->getPeriodicita() ? $this->getPeriodicita() :'Non è stato specificata')."
<br /> NOTE:".($this->getTesto() ? $this->getTesto() :'Non è stata inserita nessuna nota a riguardo' )." 

<br /> RICHIEDENTE: ".($this->getNomeCompleto() != '' ? $this->getNomeCompleto() : 'Utente anonimo')."
<br /> TIPO RICHIEDENTE: {$this->getStatoRichiedente()}
        ";
    }
    

    public function getTipo() {
        return 'cer';
    }

    public function getAnteprima() {
        return "Richiesta di recupero o smaltimento di " . str_replace(array('[', ']', '","'), array('', '', '", "'), $this->cer_list) . " a {$this->getComune()->getNomeComune()}";
    }

    public function getSubject() {
        return trim(str_replace(array('[RDO][CER]', '[', ']', '","'), array('', '', '', '", "'), parent::getSubject()));
    }

    private $mappa = null;

    public function setController(\ES\MessengerBundle\Controller\MessengerController $controller) {
        $this->mappa = $controller->getMap('map_sede_' . $this->getId(), $this->getLatitudine(), $this->getLongitudine(), 10);
        $infoWindow = $controller->getInfoWindow('iws_' . $this->getId(), $this->getLatitudine(), $this->getLongitudine());
        $infoWindow->setContent("<p>{$this->indirizzo} - {$this->getComune()->getNomeComune()} - {$this->cap}</p>");
        $marker = $controller->getMarker('mks_' . $this->getId(), $this->getLatitudine(), $this->getLongitudine());
        $marker->setInfoWindow($infoWindow);
        $this->mappa->addMarker($marker);
    }

    public function getMappa() {
        return $this->mappa;
    }

    public function getAltreInformazioni() {
        $d = $this->getDestinatario();
        $out = array();
        if ($d && $d->getDestinatario() != $this->getFromUtente() && $d->getDestinatario()->getShowroom()) {
            foreach ($d->getDestinatario()->getShowroom()->getSediOperative() as $sede) {
                $distanza = Funzioni::getDistanza(array('lat' => $this->getLatitudine(), 'lon' => $this->getLongitudine()), array('lat' => $sede->getLatitudine(), 'lon' => $sede->getLongitudine()));
                $inRaggio = $distanza <= $sede->getRaggioOperativo();
                $out[] = $sede->getIndirizzoCompleto() . ' distanza: ' . Funzioni::km($distanza) . ($inRaggio ? '' : ' fuori raggio operativo');
            }
        }
        return $out;
    }

}