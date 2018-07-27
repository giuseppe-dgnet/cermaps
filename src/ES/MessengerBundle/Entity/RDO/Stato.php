<?php

namespace ES\MessengerBundle\Entity\RDO;

use Doctrine\ORM\Mapping as ORM;

/**
 * Stati
 *
 * @ORM\Table(name="msg_rdo_stati")
 * @ORM\Entity(repositoryClass="ES\MessengerBundle\Entity\RDO\StatoRepository")
 */
class Stato
{
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
     * @ORM\Column(name="stato", type="string", length=255)
     */
    private $stato;

    /**
     * @var boolean
     *
     * @ORM\Column(name="admin", type="boolean")
     */
    private $admin;

    /**
     * @var boolean
     *
     * @ORM\Column(name="operatore", type="boolean")
     */
    private $operatore;


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
     * Set stato
     *
     * @param string $stato
     * @return Stati
     */
    public function setStato($stato)
    {
        $this->stato = $stato;
    
        return $this;
    }

    /**
     * Get stato
     *
     * @return string 
     */
    public function getStato()
    {
        return $this->stato;
    }

    /**
     * Set admin
     *
     * @param boolean $admin
     * @return Stati
     */
    public function setAdmin($admin)
    {
        $this->admin = $admin;
    
        return $this;
    }

    /**
     * Get admin
     *
     * @return boolean 
     */
    public function getAdmin()
    {
        return $this->admin;
    }

    /**
     * Set operatore
     *
     * @param boolean $operatore
     * @return Stati
     */
    public function setOperatore($operatore)
    {
        $this->operatore = $operatore;
    
        return $this;
    }

    /**
     * Get operatore
     *
     * @return boolean 
     */
    public function getOperatore()
    {
        return $this->operatore;
    }

    /**
     * Get stato
     *
     * @return string 
     */
    public function __toString()
    {
        return $this->stato;
    }
}