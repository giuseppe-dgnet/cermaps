<?php

namespace ES\MessengerBundle\Entity\RDO;

use Doctrine\ORM\Mapping as ORM;
use ES\MessengerBundle\Entity\MessaggioBase;
use ES\WebBundle\Functions\Funzioni;

/**
 * ES\MessengerBundle\Entity\RDO\Servizi
 *
 * @ORM\Table(name="msg_rdo_servizi")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="ES\MessengerBundle\Entity\RDO\ServiziRepository")
 */
class Servizi extends MessaggioBase {

    /**
     * @var string $telefono
     *
     * @ORM\Column(name="telefono", type="string", length=32)
     */
    private $telefono;

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
     * @ORM\Column(name="indirizzo", type="string", length=255)
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
     * @var string $servizi_list
     *
     * @ORM\Column(name="servizi_list", type="text")
     */
    private $servizi_list;

    /**
     * @ORM\ManyToMany(targetEntity="ES\OperatoriBundle\Entity\Servizi\Servizio")
     * @ORM\JoinTable(name="msg_rdo_servizi_list",
     *      joinColumns={@ORM\JoinColumn(name="rdo_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="servizio_id", referencedColumnName="id")}
     *      )
     */
    private $servizi;


    function __construct() {
        parent::__construct();
        $this->servizi = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set servizi_list
     *
     * @param string $serviziList
     */
    public function setServiziList($serviziList) {
        $this->servizi_list = $serviziList;
    }

    /**
     * Get servizi_list
     *
     * @return string 
     */
    public function getServiziList() {
        return $this->servizi_list;
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
     * get servizi
     *
     * @return \Doctrine\Common\Collections\ArrayCollection 
     */
    public function getServizi() {
        return $this->servizi;
    }

    /**
     * ser servizi
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $servizi 
     */
    public function setServizi($servizi) {
        $this->servizi = $servizi;
    }

    /**
     * ser servizi
     *
     * @param \ES\ServiziAmbientaliBundle\Entity\Servizio $servizi 
     */
    public function addServizi($servizi) {
        $this->servizi->add($servizi);
    }
    
    
    public function getTwigTesto() {
        return 'EcoSeekrMessengerBundle:Messenger:discussione/rdo/servizi.html.twig';
    }

    public function getElenco($n) {
        
         if (count($this->servizi) < $n) {
            $out = array();
            for ($i = 0; $i < count($this->servizi); $i++) {
                $servizio = $this->servizi->get($i);
                $out[] = $servizio->getServizio();
            }
        return implode(', ', $out);
        }
        $out = array();
        
        for ($i = 0; $i < $n; $i++) {
            $servizio = $this->servizi->get($i);
            $out[] = $servizio->getServizio();
        }
        return implode(', ', $out) . ' e altri ' . (count($this->servizi) - $n) . ' servizi';
        
        
//        if(count($this->servizi) < $n) {
//            return $this->servizi_list;
//        }
//        $out = array();
//        for($i = 0; $i < $n; $i++) {
//            $servizio = $this->servizi->get($i);
//            $out[] = $servizio->getServizio();
//        }
//        return implode(', ', $out).' e altri'.(count($this->servizi) - $n).' servizi';
    }
   
    /**
     * @ORM\PrePersist 
     */
    public function prePersist() {
        parent::prePersist();
        /* $subject = "[RDO][SERVIZI] Richiesta per servizi ambientali {# $this->servizi_list} a {$this->getComune()->getNomeComune()}";*/
        $subject = "[RDO][SERVIZI] Richiesta per servizi ambientali ({$this->getElenco(5)}) a {$this->getComune()->getNomeComune()}";
        $this->setSubject($subject);
    }

    public function riepilogo() {
        return "
RICHIESTA PER INTERVENTO SERVIZI AMBIENTALI
-------------------------------------------
SERVIZI: {$this->getElenco(5)}
DOVE: {$this->indirizzo} - {$this->getComune()->getNomeComune()} - {$this->cap}
NOTE: {$this->getTesto()}

RICHIEDENTE: {$this->getNomeCompleto()}
TIPO RICHIEDENTE: {$this->getStatoRichiedente()}
        ";
    }

    public function getTipo() {
        return 'servizi';
    }
    
    public function getAnteprima() {
        return "Richiesta di intervento servizi ambientali per ".str_replace(array('[', ']', '","'), array('', '', '", "'), $this->servizi_list)." a {$this->getComune()->getNomeComune()}";
    }
    
    public function getSubject() {
        return trim(str_replace(array('[RDO][SERVIZI]', '[', ']', '","'), array('', '', '', '", "'), parent::getSubject()));
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