<?php

namespace ES\CerMapBundle\Entity\Mps;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Ephp\UtilityBundle\Seo\Model\ISeo;

/**
 * ES\CerMapBundle\Entity\Mps\Mps
 *
 * @ORM\Table(name="cm_mps",
 *            indexes={@ORM\Index(name="mps_materia",columns={"materia"})})
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="ES\CerMapBundle\Entity\Mps\MpsRepository")
 */
class Mps implements ISeo {

    use \Ephp\UtilityBundle\Seo\Model\Traits\BaseSeo,
        Traits\MpsImplements;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string $materia
     *
     * @ORM\Column(name="materia", type="string", length=255)
     */
    private $materia;

    /**
     * @var text $descrizione
     *
     * @ORM\Column(name="descrizione", type="text", nullable=true)
     */
    private $descrizione;

    /**
     * @ORM\ManyToOne(targetEntity="Categoria", inversedBy="mps")
     * @ORM\JoinColumn(name="categoria_id", referencedColumnName="id")
     */
    private $categoria;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection $rifiuti
     * 
     * @ORM\ManyToMany(targetEntity="ES\CerMapBundle\Entity\Recuperabili\AttivitaRecupero", mappedBy="mps")
     */
    private $attivita_recupero;

    /**
     * @var boolean $condiviso
     *
     * @ORM\Column(name="condiviso", type="boolean", nullable=true)
     */
    private $condiviso;

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
     * Set materia
     *
     * @param string $materia
     */
    public function setMateria($materia) {
        $this->materia = $materia;
    }

    /**
     * Get materia
     *
     * @return string 
     */
    public function getMateria() {
        return $this->materia;
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
     * Set categoria
     *
     * @param Categoria $categoria
     */
    public function setCategoria($categoria) {
        $this->categoria = $categoria;
    }

    /**
     * Get categoria
     *
     * @return Categoria
     */
    public function getCategoria() {
        return $this->categoria;
    }

    /**
     * Set condiviso
     *
     * @param boolean $condiviso
     */
    public function setCondiviso($condiviso) {
        $this->condiviso = $condiviso;
    }

    /**
     * Get condiviso
     *
     * @return boolean 
     */
    public function getCondiviso() {
        return $this->condiviso;
    }

    /**
     * Set attivita_recupero
     *
     * @param text $attivita_recupero
     */
    public function setAttivitaRecupero($attivita_recupero) {
        $this->attivita_recupero = $attivita_recupero;
    }

    /**
     * Get descrizione
     *
     * @return text 
     */
    public function getAttivitaRecupero() {
        return $this->attivita_recupero;
    }

    /**
     * Add attivita_recupero
     *
     * @param \ES\CerMapBundle\Entity\Recuperabili\AttivitaRecupero $attivitaRecupero
     * @return Mps
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
