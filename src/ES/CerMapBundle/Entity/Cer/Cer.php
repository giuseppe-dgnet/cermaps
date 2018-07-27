<?php

namespace ES\CerMapBundle\Entity\Cer;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Ephp\UtilityBundle\Seo\Model\ISeo;

/**
 * ES\CerMapBundle\Entity\Cer\Cer
 *
 * @ORM\Table(name="cm_cer",
 *            uniqueConstraints={@ORM\UniqueConstraint(name="u_codice",columns={"codice"})},
 *            indexes={
 *                      @ORM\Index(name="i_pericoloso", columns={"pericoloso"}),
 *                      @ORM\Index(name="i_classe", columns={"classe"}),
 *                      @ORM\Index(name="i_sottoclasse", columns={"classe", "sottoclasse"}),
 *                      @ORM\Index(name="i_categoria", columns={"classe", "sottoclasse", "categoria"})
 *                    }
 * )
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="ES\CerMapBundle\Entity\Cer\CerRepository")
 */
class Cer implements ISeo {

    use \Ephp\UtilityBundle\Seo\Model\Traits\BaseSeo,
        Traits\CerImplements;

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
     * @ORM\Column(name="codice", type="string", length=6)
     */
    private $codice;

    /**
     * @var string $classe
     *
     * @ORM\Column(name="classe", type="string", length=2)
     */
    private $classe;

    /**
     * @var string $sottoclasse
     *
     * @ORM\Column(name="sottoclasse", type="string", length=2)
     */
    private $sottoclasse;

    /**
     * @var string $categoria
     *
     * @ORM\Column(name="categoria", type="string", length=2)
     */
    private $categoria;

    /**
     * @var boolean $pericoloso
     *
     * @ORM\Column(name="pericoloso", type="boolean")
     */
    private $pericoloso;

    /**
     * @var string $descrizione
     *
     * @ORM\Column(name="descrizione", type="text")
     */
    private $descrizione;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection $rifiuti
     * 
     * @ORM\ManyToMany(targetEntity="ES\CerMapBundle\Entity\Recuperabili\Rifiuto", mappedBy="cer")
     */
    private $rifiuti;

    /**
     * Constructor
     */
    public function __construct() {
        $this->rifiuti = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set classe
     *
     * @param string $classe
     */
    public function setClasse($classe) {
        $this->classe = $classe;
    }

    /**
     * Get classe
     *
     * @return string 
     */
    public function getClasse() {
        return $this->classe;
    }

    /**
     * Set sottoclasse
     *
     * @param string $sottoclasse
     */
    public function setSottoclasse($sottoclasse) {
        $this->sottoclasse = $sottoclasse;
    }

    /**
     * Get sottoclasse
     *
     * @return string 
     */
    public function getSottoclasse() {
        return $this->sottoclasse;
    }

    /**
     * Set categoria
     *
     * @param string $categoria
     */
    public function setCategoria($categoria) {
        $this->categoria = $categoria;
    }

    /**
     * Get categoria
     *
     * @return string 
     */
    public function getCategoria() {
        return $this->categoria;
    }

    /**
     * Set pericoloso
     *
     * @param boolean $pericoloso
     */
    public function setPericoloso($pericoloso) {
        $this->pericoloso = $pericoloso;
    }

    /**
     * Get pericoloso
     *
     * @return boolean 
     */
    public function getPericoloso() {
        return $this->pericoloso;
    }

    /**
     * Get pericoloso
     *
     * @return string 
     */
    public function getCodicePericoloso() {
        return $this->pericoloso ? 'pericoloso' : '';
    }

    /**
     * Set descrizione
     *
     * @param string $descrizione
     */
    public function setDescrizione($descrizione) {
        $this->descrizione = $descrizione;
    }

    /**
     * Get descrizione
     *
     * @return string 
     */
    public function getDescrizione() {
        return $this->descrizione;
    }

    /**
     * Get rifiuti
     * 
     * @return \Doctrine\Common\Collections\ArrayCollection 
     */
    public function getRifiuti() {
        return $this->rifiuti;
    }

    /**
     * Set rifiuti
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $rifiuti
     */
    public function setRifiuti($rifiuti) {
        $this->rifiuti = $rifiuti;
    }

    /**
     * Set rifiuti
     *
     * @param \ES\CerMapBundle\Entity\Recuperabili\Rifiuto $rifiuti
     */
    public function addRifiuti($rifiuto) {
        $this->rifiuti->add($rifiuto);
    }

    /**
     * Remove rifiuti
     *
     * @param \ES\CerMapBundle\Entity\Recuperabili\Rifiuto $rifiuti
     */
    public function removeRifiuti(\ES\CerMapBundle\Entity\Recuperabili\Rifiuto $rifiuti) {
        $this->rifiuti->removeElement($rifiuti);
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
