<?php

namespace ES\MessengerBundle\Entity\RDO;

use Doctrine\ORM\Mapping as ORM;
use ES\MessengerBundle\Entity\MessaggioBase;
use ES\WebBundle\Functions\Funzioni;

/**
 * ES\MessengerBundle\Entity\RDO\Mps
 *
 * @ORM\Table(name="msg_rdo_mps")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="ES\MessengerBundle\Entity\RDO\MpsRepository")
 */
class Mps extends MessaggioBase {

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
     * @var Comune $comune
     *
     * @ORM\ManyToOne(targetEntity="Ephp\GeoBundle\Entity\GeoNames")
     * @ORM\JoinColumn(name="comune_id", referencedColumnName="geonameid")
     */
    private $comune;

    /**
     * @var string $indirizzo
     *
     * @ORM\Column(name="indirizzo", type="string", length=255,nullable=true)
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
     * @var string $mps_list
     *
     * @ORM\Column(name="mps_list", type="text", nullable=true)
     */
    private $mps_list;

    /**
     * @ORM\ManyToMany(targetEntity="ES\CerMapBundle\Entity\Mps\Mps")
     * @ORM\JoinTable(name="msg_rdo_mps_list",
     *      joinColumns={@ORM\JoinColumn(name="rdo_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="mps_id", referencedColumnName="id")}
     *      )
     */
    private $mps;
    
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
        $this->mps = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set mps_list
     *
     * @param string $mpsList
     */
    public function setMpsList($mpsList) {
        $this->mps_list = $mpsList;
    }

    /**
     * Get mps_list
     *
     * @return string 
     */
    public function getMpsList() {
        return $this->mps_list;
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
     * get mps
     *
     * @return \Doctrine\Common\Collections\ArrayCollection 
     */
    public function getMps() {
        return $this->mps;
    }

    /**
     * ser mps
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $mps 
     */
    public function setMps($mps) {
        $this->mps = $mps;
    }

    /**
     * ser mps
     *
     * @param \ES\MPSBundle\Entity\Mps\Mps $mps 
     */
    public function addMps($mps) {
        $this->mps->add($mps);
    }
    
    public function getTwigTesto() {
        return 'EcoSeekrMessengerBundle:Messenger:discussione/rdo/mps.html.twig';
    }
    
    public function getElenco($n) {
        if (count($this->mps) <= $n) {
            $out = array();
            for ($i = 0; $i < count($this->mps); $i++) {
                $mps = $this->mps->get($i);
                $out[] = $mps->getMateria();
            }
        return implode(', ', $out);
        }
        $out = array();
        
        for ($i = 0; $i < $n; $i++) {
            $cer = $this->mps->get($i);
            $out[] = $cer->getMateria();
        }
        
        return implode(', ', $out) . ' e altri ' . (count($this->mps) - $n) . ' mps';
//        
//        
//        
//        if(count($this->mps) < $n) {
//            return $this->mps_list;
//        }
//        $out = array();
//        for($i = 0; $i < $n; $i++) {
//            $mps = $this->mps->get($i);
//            $out[] = $mps->getMateria();
//        }
//        return implode(', ', $out).' e altri'.(count($this->mps) - $n).' mps';
    }
    
    /**
     * @ORM\PrePersist 
     */
    public function prePersist() {
        parent::prePersist();
        //$subject = "[RDO][MPS] Richiesta per acquisto MPS {$this->getElenco(5)} a {$this->getComune()->getNomeComune()}";
        $subject = "[RDO][MPS] Richiesta per acquisto MPS ".(count($this->mps) > 0 ?  "(".$this->getElenco(5).")" : '')." ".($this->getComune() ? "a ".$this->getComune()->getNomeComune() : '')."";
        $this->setSubject($subject);
    }

    public function riepilogo() {
        return "
RICHIESTA PER ACQUISTO MATERIE PRIME SECONDE
--------------------------------------------
- MPS: ".(count($this->mps) > 0 ? $this->getElenco(10) : 'Non è stato specificato')."
- DOVE: {$this->indirizzo} - ".($this->getComune() ? $this->getComune()->getNomeComune() : 'Non è stato specificato')."    
- NOTE: ".($this->getTesto() ? $this->getTesto() :'Non è stata inserita nessuna nota a riguardo' )." 

- RICHIEDENTE: ".($this->getNomeCompleto() != '' ? $this->getNomeCompleto() : 'Utente anonimo')."
- TIPO RICHIEDENTE: {$this->getStatoRichiedente()}
        ";
    }
    
    public function riepilogoAdmin() {
        return "
<br /> MPS: ".(count($this->mps) > 0 ? $this->getElenco(10) : 'Non è stato specificato')."
<br /> DOVE: {$this->indirizzo} - ".($this->getComune() ? $this->getComune()->getNomeComune() : 'Non è stato specificato')."    
<br /> NOTE:".($this->getTesto() ? $this->getTesto() :'Non è stata inserita nessuna nota a riguardo' )." 

<br /> RICHIEDENTE: ".($this->getNomeCompleto() != '' ? $this->getNomeCompleto() : 'Utente anonimo')."
<br /> TIPO RICHIEDENTE: {$this->getStatoRichiedente()}
        ";
    }

    public function getTipo() {
        return 'mps';
    }
    
    public function getAnteprima() {
        /*return "Richiesta di acquisto MPS per ".str_replace(array('[', ']', '","'), array('', '', '", "'), $this->mps_list)." a {$this->getComune()->getNomeComune()}";*/
        return "Richiesta di acquisto MPS a {$this->getComune()->getNomeComune()}";
    }
    
    public function getSubject() {
        return trim(str_replace(array('[RDO][MPS]', '[', ']', '","'), array('', '', '', '", "'), parent::getSubject()));
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
        if($d && $d->getDestinatario() != $this->getFromUtente() && $d->getDestinatario()->getShowroom()) {
            foreach ($d->getDestinatario()->getShowroom()->getSediOperative() as $sede) {
                $distanza = Funzioni::getDistanza(array('lat' => $this->getLatitudine(), 'lon' => $this->getLongitudine()), array('lat' => $sede->getLatitudine(), 'lon' => $sede->getLongitudine()));
                $inRaggio = $distanza <= $sede->getRaggioOperativo();
                $out[] = $sede->getIndirizzoCompleto().' distanza: '.Funzioni::km($distanza).($inRaggio ? '' : ' fuori raggio operativo');
            }
        }
        return $out;
    }

}