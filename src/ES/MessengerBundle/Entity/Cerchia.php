<?php

namespace ES\MessengerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ES\MessengerBundle\Entity\Cerchia
 *
 * @ORM\Table(name="msg_cerchia")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="ES\MessengerBundle\Entity\CerchiaRepository")
 */
class Cerchia {

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
     * @ORM\ManyToOne(targetEntity="ES\UserBundle\Entity\User", inversedBy="cerchie")
     * @ORM\JoinColumn(name="proprietario_id", referencedColumnName="id")
     */
    private $proprietario;

    /**
     * @var string $cerchia
     *
     * @ORM\Column(name="cerchia", type="string", length=64)
     */
    private $cerchia;

    /**
     * @ORM\ManyToMany(targetEntity="Rubrica", inversedBy="cerchie", cascade={"persist", "merge", "refresh"})
     * @ORM\JoinTable(name="msg_cerchie_contatti",
     *      joinColumns={@ORM\JoinColumn(name="cerchia_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="contatto_id", referencedColumnName="id")}
     *      )
     * @ORM\OrderBy({"label" = "ASC"}) 
     */
    private $contatti;

    /**
     * @var boolean $predefinito
     *
     * @ORM\Column(name="predefinito", type="boolean", nullable=true)
     */
    private $predefinito;

    function __construct() {
        $this->contatti = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set proprietario
     *
     * @param \ES\UserBundle\Entity\User $proprietario
     */
    public function setProprietario($proprietario) {
        $this->proprietario = $proprietario;
    }

    /**
     * Get proprietario
     *
     * @return \ES\UserBundle\Entity\User 
     */
    public function getProprietario() {
        return $this->proprietario;
    }

    /**
     * Set contatti
     *
     * @param \ES\UserBundle\Entity\User $contatti
     */
    public function setContatti($contatti) {
        $this->contatti = $contatti;
    }

    /**
     * Get contatti
     *
     * @return \ES\UserBundle\Entity\User 
     */
    public function getContatti() {
        return $this->contatti;
    }

    /**
     * Set contatti
     *
     * @param \ES\UserBundle\Entity\User $contatti
     */
    public function addContatti($contatti) {
        foreach ($this->contatti as $key => $contatto) {
            if ($contatto->getId() == $contatti->getId()) {
                return 0;
            }
        }
        $this->contatti->add($contatti);
        return 1; 
    }

    /**
     * Set contatti
     *
     * @param \ES\UserBundle\Entity\User $contatti
     */
    public function remContatti($contatti) {
        foreach ($this->contatti as $key => $contatto) {
            if ($contatto->getId() == $contatti->getId()) {
                $this->contatti->remove($key);
                return 1; 
            }
        }
        return 0;
    }

    /**
     * Set cerchia
     *
     * @param string $cerchia
     */
    public function setCerchia($cerchia) {
        $this->cerchia = $cerchia;
    }

    /**
     * Get cerchia
     *
     * @return string 
     */
    public function getCerchia() {
        return $this->cerchia;
    }

    /**
     * Set preferito
     *
     * @param boolean $predefinito
     */
    public function setPredefinito($predefinito) {
        $this->predefinito = $predefinito;
    }

    /**
     * Get partner
     *
     * @return boolean 
     */
    public function getPredefinito() {
        return $this->predefinito;
    }

}