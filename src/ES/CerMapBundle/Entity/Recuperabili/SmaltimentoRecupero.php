<?php

namespace ES\CerMapBundle\Entity\Recuperabili;

use Doctrine\ORM\Mapping as ORM;

/**
 * ES\CerMapBundle\Entity\Recuperabili\SmaltimentoRecupero
 *
 * @ORM\Table(name="cm_recuperabili_smaltimento_recupero",
 *            uniqueConstraints={@ORM\UniqueConstraint(name="u_sr_codice",columns={"codice"})},
 *            indexes={
 *                      @ORM\Index(name="i_sr_recupero", columns={"recupero"})
 *                    }
 * )
 * @ORM\Entity(repositoryClass="ES\CerMapBundle\Entity\Recuperabili\SmaltimentoRecuperoRepository")
 */
class SmaltimentoRecupero {

    use Traits\SmaltimentoRecuperoImplements;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string $codice
     *
     * @ORM\Column(name="codice", type="string", length=3)
     */
    private $codice;

    /**
     * @var text $descrizione
     *
     * @ORM\Column(name="descrizione", type="text")
     */
    private $descrizione;

    /**
     * @var boolean $recupero
     *
     * @ORM\Column(name="recupero", type="boolean")
     */
    private $recupero;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection $attivita_recupero
     * 
     * @ORM\ManyToMany(targetEntity="AttivitaRecupero", mappedBy="recupero")
     */
    private $attivita_recupero;

    /**
     * Constructor
     */
    public function __construct() {
        $this->attivita_recupero = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set codice
     *
     * @param string $codice
     */
    public function setCodice($codice) {
        $this->codice = $codice;
    }

    /**
     * Get codice
     *
     * @return string 
     */
    public function getCodice() {
        return $this->codice;
    }

    /**
     * Set descrizione
     *
     * @param text $descrizione
     */
    public function setDescrizione($descrizione) {
        $this->descrizione = $descrizione;
    }

    /**
     * Get descrizione
     *
     * @return text 
     */
    public function getDescrizione() {
        return $this->descrizione;
    }

    /**
     * Set recupero
     *
     * @param boolean $recupero
     */
    public function setRecupero($recupero) {
        $this->recupero = $recupero;
    }

    /**
     * Get recupero
     *
     * @return boolean 
     */
    public function getRecupero() {
        return $this->recupero;
    }

    public function getAttivitaRecupero() {
        return $this->attivita_recupero;
    }

    public function setAttivitaRecupero($attivita_recupero) {
        $this->attivita_recupero = $attivita_recupero;
    }

    /**
     * Add attivita_recupero
     *
     * @param \ES\CerMapBundle\Entity\Recuperabili\AttivitaRecupero $attivitaRecupero
     * @return SmaltimentoRecupero
     */
    public function addAttivitaRecupero(\ES\CerMapBundle\Entity\Recuperabili\AttivitaRecupero $attivitaRecupero) {
        $this->attivita_recupero[] = $attivitaRecupero;

        return $this;
    }

    /**
     * Remove attivita_recupero
     *
     * @param \ES\CerMapBundle\Entity\Recuperabili\AttivitaRecupero $attivitaRecupero
     */
    public function removeAttivitaRecupero(\ES\CerMapBundle\Entity\Recuperabili\AttivitaRecupero $attivitaRecupero) {
        $this->attivita_recupero->removeElement($attivitaRecupero);
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist() {
        
    }

    /**
     * @ORM\PreUpdate
     */
    public function preUpdate() {
        
    }

}
