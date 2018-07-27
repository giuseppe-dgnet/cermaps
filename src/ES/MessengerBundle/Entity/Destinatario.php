<?php

namespace ES\MessengerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * ES\MessengerBundle\Entity\destinatario
 *
 * @ORM\Table(name="msg_destinatari")
 * @ORM\Entity(repositoryClass="ES\MessengerBundle\Entity\DestinatarioRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Destinatario
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var MessaggioBase $messaggio
     * 
     * @ORM\ManyToOne(targetEntity="MessaggioBase")
     * @ORM\JoinColumn(name="messaggio_id", referencedColumnName="id")
     */
    private $messaggio;

    /**
     * @var \ES\UserBundle\Entity\User $utente
     * 
     * @ORM\ManyToOne(targetEntity="ES\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="destinatario_id", referencedColumnName="id")
     */
    private $destinatario;

    /**
     * @var boolean $notificato
     *
     * @ORM\Column(name="notificato", type="boolean", nullable=true)
     */
    private $notificato;

    /**
     * @var boolean $letto
     *
     * @ORM\Column(name="letto", type="boolean", nullable=true)
     */
    private $letto;

    /**
     * @var boolean $archiviato
     *
     * @ORM\Column(name="archiviato", type="boolean", nullable=true)
     */
    private $archiviato;

    /**
     * @var integer $prioirta
     *
     * @ORM\Column(name="prioirta", type="integer", nullable=true)
     */
    private $prioirta;

    /**
     * @var integer $prioirta
     *
     * @ORM\Column(name="lock_mail", type="integer", nullable=true)
     */
    private $lock;
    
    /**
     * @var integer $spam
     *
     * @ORM\Column(name="spam", type="boolean", nullable=true)
     */
    private $spam;
        

    /**
     * var string $pre_slug 
     * 
     * @ORM\Column(name="pre_slug", type="string", length=255)
     */
    private $pre_slug;
    
    /**
     * var string $slug 
     * 
     * @Gedmo\Slug(fields={"pre_slug"})
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     */
    private $slug;

    function __construct() {
        $this->lock = 0;
    }
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set messaggio
     *
     * @param MessaggioBase $messaggio
     */
    public function setMessaggio($messaggio)
    {
        $this->messaggio = $messaggio;
    }

    /**
     * Get messaggio
     *
     * @return MessaggioBase 
     */
    public function getMessaggio()
    {
        return $this->messaggio;
    }

    /**
     * Set conversazione
     *
     * @param MessaggioBase $conversazione
     */
    public function setConversazione($conversazione)
    {
        $this->conversazione = $conversazione;
    }

    /**
     * Get conversazione
     *
     * @return MessaggioBase 
     */
    public function getConversazione()
    {
        return $this->conversazione;
    }

    /**
     * Set destinatario
     *
     * @param ES\UserBundle\Entity\User $destinatario
     */
    public function setDestinatario($destinatario)
    {
        $this->destinatario = $destinatario;
    }

    /**
     * Get destinatario
     *
     * @return ES\UserBundle\Entity\User 
     */
    public function getDestinatario()
    {
        return $this->destinatario;
    }

    /**
     * Set letto
     *
     * @param boolean $letto
     */
    public function setLetto($letto)
    {
        $this->letto = $letto;
    }

    /**
     * Get letto
     *
     * @return boolean 
     */
    public function getLetto()
    {
        return $this->letto;
    }
    

    /**
     * Set notificato
     *
     * @param boolean $notificato
     */
    public function setNotificato($notificato)
    {
        $this->notificato = $notificato;
    }

    /**
     * Get notificato
     *
     * @return boolean 
     */
    public function getNotificato()
    {
        return $this->notificato;
    }
    
    /**
     * Set spam
     *
     * @param boolean $spam
     */
    public function setSpam($spam)
    {
        $this->spam = $spam;
    }

    /**
     * Get spam
     *
     * @return boolean 
     */
    public function getSpam()
    {
        return $this->spam;
    }


    /**
     * Set archiviato
     *
     * @param boolean $archiviato
     */
    public function setArchiviato($archiviato)
    {
        $this->archiviato = $archiviato;
    }

    /**
     * Get archiviato
     *
     * @return boolean 
     */
    public function getArchiviato()
    {
        return $this->archiviato;
    }

    /**
     * Set prioirta
     *
     * @param integer $prioirta
     */
    public function setPrioirta($prioirta)
    {
        $this->prioirta = $prioirta;
    }

    public function getPrioirta()
    {
        return $this->prioirta;
    }

    /**
     * Set prioirta
     *
     * @param integer $lock
     */
    public function setLock($lock)
    {
        $this->lock = $lock;
    }

    public function getLock()
    {
        return $this->lock;
    }

    
    
    public function getPreSlug() {
        return $this->pre_slug;
    }
    

    public function setPreSlug($pre_slug) {
        $this->pre_slug = $pre_slug;
    }

    public function getSlug() {
        return $this->slug;
    }

    public function setSlug($slug) {
        $this->slug = $slug;
    }
    
    /**
     * 
     * @return \ES\GrabBundle\Entity\Azienda
     */
    public function getImpresa() {
        return $this->impresa;
    }

    /**
     * 
     * @param \ES\GrabBundle\Entity\Azienda $impresa
     */
    public function setImpresa($impresa) {
        $this->impresa = $impresa;
    }

    /**
     * @ORM\PrePersist 
     */
    public function prePersist() {
        if($this->letto != true) {
            $this->letto = false;
        }
        if($this->notificato != true) {
            $this->notificato = false;
        }
        $this->archiviato = false;
        $this->spam = false;
        $this->prioirta = 5;
        $this->pre_slug = md5('read_'.
                $this->getMessaggio()->getId().'_'.
                $this->getDestinatario()->getId().'_'.
                $this->getMessaggio()->getSlug());
    }

}