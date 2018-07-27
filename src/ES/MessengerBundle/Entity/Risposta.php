<?php

namespace ES\MessengerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use ES\WebBundle\Functions\Funzioni;

/**
 * ES\MessengerBundle\Entity\Risposta
 *
 * @ORM\Table(name="msg_risposte")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="ES\MessengerBundle\Entity\RispostaRepository")
 */
class Risposta extends MessaggioBase
{

    /**
     * @var integer $messaggio
     *
     * @ORM\ManyToOne(targetEntity="MessaggioBase", inversedBy="risposte")
     * @ORM\JoinColumn(name="messaggio_id", referencedColumnName="id")
     */
    private $messaggio;



    /**
     * Set messaggio
     *
     * @param integer $messaggio
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

    public function riepilogo() {
        return $this->getTesto();
    }
    
    public function getConversazione() {
        return $this->getMessaggio();
    }
    
    public function getTipo() {
        return 'risposta';
    }

    public function getAnteprima() {
        return Funzioni::tronca($this->getTesto(), 80);
    }
    
    /**
     * @ORM\PrePersist 
     */
    public function prePersist() {
        parent::prePersist();
        $this->setSubject('RE: '.$this->getMessaggio()->getSubject());
    }

}