<?php

namespace ES\CerMapBundle\Entity\Recuperabili;

use Doctrine\ORM\Mapping as ORM;

/**
 * ES\CerMapBundle\Entity\Recuperabili\AttivitaRecupero
 *
 * @ORM\Table(name="cm_recuperabili_attivita_recupero")
 * @ORM\Entity(repositoryClass="ES\CerMapBundle\Entity\Recuperabili\AttivitaRecuperoRepository")
 */
class AttivitaRecupero {
    
    use Traits\AttivitaRecuperoImplements;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Rifiuto $rifiuto
     *
     * @ORM\ManyToOne(targetEntity="Rifiuto", inversedBy="attivita_recupero")
     * @ORM\JoinColumn(name="rifiuto_id", referencedColumnName="id")
     */
    private $rifiuto;

    /**
     * @var text $attivita
     *
     * @ORM\Column(name="attivita", type="text")
     */
    private $attivita;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection $recupero
     *
     * @ORM\ManyToMany(targetEntity="SmaltimentoRecupero", inversedBy="attivita_recupero", cascade={"all"})
     * @ORM\JoinTable(name="cm_attivita_recupero_smaltimento")
     */
    private $recupero;

    /**
     * @var text $caratteristiche_mps
     *
     * @ORM\Column(name="caratteristiche_mps", type="text", nullable=true)
     */
    private $caratteristiche_mps;

    /**
     * @var ArrayCollection $cer
     * 
     * @ORM\ManyToMany(targetEntity="ES\CerMapBundle\Entity\Mps\Mps", inversedBy="attivita_recupero", cascade={"persist", "merge", "refresh"})
     * @ORM\JoinTable(name="cm_recuperabili_mps")
     */
    private $mps;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->recupero = new \Doctrine\Common\Collections\ArrayCollection();
        $this->mps = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set rifiuto
     *
     * @param Rifiuto $rifiutoId
     */
    public function setRifiuto($rifiutoId) {
        $this->rifiuto = $rifiutoId;
    }

    /**
     * Get rifiuto
     *
     * @return Rifiuto 
     */
    public function getRifiuto() {
        return $this->rifiuto;
    }

    /**
     * Set attivita
     *
     * @param text $attivita
     */
    public function setAttivita($attivita) {
        $this->attivita = $attivita;
    }

    /**
     * Get attivita
     *
     * @return text 
     */
    public function getAttivita() {
        return $this->attivita;
    }

    /**
     * Set recupero
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $recupero
     */
    public function setRecupero($recupero) {
        $this->recupero = $recupero;
    }

    /**
     * Get recupero
     *
     * @return \Doctrine\Common\Collections\ArrayCollection 
     */
    public function getRecupero() {
        return $this->recupero;
    }

    /**
     * Add recupero
     *
     * @param \ES\CerMapBundle\Entity\Recuperabili\SmaltimentoRecupero $recupero
     * @return AttivitaRecupero
     */
    public function addRecupero(\ES\CerMapBundle\Entity\Recuperabili\SmaltimentoRecupero $recupero)
    {
        $this->recupero[] = $recupero;
    
        return $this;
    }

    /**
     * Remove recupero
     *
     * @param \ES\CerMapBundle\Entity\Recuperabili\SmaltimentoRecupero $recupero
     */
    public function removeRecupero(\ES\CerMapBundle\Entity\Recuperabili\SmaltimentoRecupero $recupero)
    {
        $this->recupero->removeElement($recupero);
    }
    /**
     * Set caratteristiche_mps
     *
     * @param text $caratteristicheMps
     */
    public function setCaratteristicheMps($caratteristicheMps) {
        $this->caratteristiche_mps = $caratteristicheMps;
    }

    /**
     * Get caratteristiche_mps
     *
     * @return text 
     */
    public function getCaratteristicheMps() {
        return $this->caratteristiche_mps;
    }

    /**
     * Set mps
     *
     * @param text $mps
     */
    public function setMps($mps) {
        $this->mps = $mps;
    }

    /**
     * Get mps
     *
     * @return text 
     */
    public function getMps() {
        return $this->mps;
    }

    /**
     * Add mps
     *
     * @param \ES\CerMapBundle\Entity\Mps\Mps $mps
     * @return AttivitaRecupero
     */
    public function addMps(\ES\CerMapBundle\Entity\Mps\Mps $mps)
    {
        $this->mps[] = $mps;
    
        return $this;
    }

    /**
     * Remove mps
     *
     * @param \ES\CerMapBundle\Entity\Mps\Mps $mps
     */
    public function removeMps(\ES\CerMapBundle\Entity\Mps\Mps $mps)
    {
        $this->mps->removeElement($mps);
    }
}
