<?php

namespace ES\MessengerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * ES\MessengerBundle\Entity\Rubrica
 *
 * @ORM\Table(name="msg_rubrica",
 *            uniqueConstraints={
 *                      @ORM\UniqueConstraint(name="rubrica_contatti",columns={"proprietario_id","contatto_id"})    
 *                           })
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="ES\MessengerBundle\Entity\RubricaRepository")
 */
class Rubrica
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
     * @var \ES\UserBundle\Entity\User $proprietario
     *
     * @ORM\ManyToOne(targetEntity="ES\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="proprietario_id", referencedColumnName="id")
     */
    private $proprietario;

    /**
     * @var \ES\UserBundle\Entity\User $contatto
     *
     * @ORM\ManyToOne(targetEntity="ES\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="contatto_id", referencedColumnName="id")
     */
    private $contatto;

    /**
     * @var string $tipo
     *
     * @ORM\Column(name="tipo", type="string", length=32)
     */
    private $tipo;

    /**
     * @var string $label
     *
     * @ORM\Column(name="label", type="string", length=128)
     */
    private $label;

    /**
     * @var boolean $partner
     *
     * @ORM\Column(name="partner", type="boolean", nullable=true)
     */
    private $partner;
    
    /**
     * @var boolean $preferito
     *
     * @ORM\Column(name="preferito", type="boolean", nullable=true)
     */
    private $preferito;
    
    /**
     * @var \Doctrine\Common\Collections\ArrayCollection $catalogo
     *
     * @ORM\ManyToMany(targetEntity="Cerchia", mappedBy="contatti", cascade={"persist", "merge", "refresh"})
     */
    private $cerchie;
    
    function __construct() {
        $this->cerchie = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set proprietario
     *
     * @param \ES\UserBundle\Entity\User $proprietario
     */
    public function setProprietario($proprietario)
    {
        $this->proprietario = $proprietario;
    }

    /**
     * Get proprietario
     *
     * @return \ES\UserBundle\Entity\User 
     */
    public function getProprietario()
    {
        return $this->proprietario;
    }

    /**
     * Set contatto
     *
     * @param \ES\UserBundle\Entity\User $contatto
     */
    public function setContatto($contatto)
    {
        $this->contatto = $contatto;
    }

    /**
     * Get contatto
     *
     * @return \ES\UserBundle\Entity\User 
     */
    public function getContatto()
    {
        return $this->contatto;
    }

    /**
     * Set tipo
     *
     * @param string $tipo
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }

    /**
     * Get tipo
     *
     * @return string 
     */
    public function getTipo()
    {
        return $this->tipo;
    }
    
    public function getLabel() {
        return $this->label ?: $this->getContatto()->getLabel();
    }

    public function setLabel($label) {
        $this->label = $label;
    }

        /**
     * Set partner
     *
     * @param boolean $partner
     */
    public function setPartner($partner)
    {
        $this->partner = $partner;
    }

    /**
     * Get partner
     *
     * @return boolean 
     */
    public function getPartner()
    {
        return $this->partner;
    }
    
    /**
     * Set preferito
     *
     * @param boolean $preferito
     */
    public function setPreferito($preferito)
    {
        $this->preferito = $preferito;
    }

    /**
     * Get partner
     *
     * @return boolean 
     */
    public function getPreferito()
    {
        return $this->preferito;
    }
     
    /**
     * @ORM\PrePersist 
     */
    public function prePersist() {
        $this->label = $this->getContatto()->getLabel();
        $this->tipo = $this->getContatto()->getAzienda() ? "azienda" : ($this->getContatto()->getProfessionista() ? "professionista" : "privato");
    }

    public function getCerchie() {
        return $this->cerchie;
    }

    public function setCerchie($cerchie) {
        $this->cerchie = $cerchie;
    }


    
}