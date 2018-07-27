<?php

namespace ES\CerMapBundle\Entity\Recuperabili;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Ephp\UtilityBundle\Seo\Model\ISeo;

/**
 * ES\CerMapBundle\Entity\Recuperabili\Categoria
 *
 * @ORM\Table(name="cm_recuperabili_categorie")
 * @ORM\Entity(repositoryClass="ES\CerMapBundle\Entity\Recuperabili\CategoriaRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Categoria implements ISeo {

    use \Ephp\UtilityBundle\Seo\Model\Traits\BaseSeo,
        Traits\CategoriaImplements;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var integer $indice
     *
     * @ORM\Column(name="indice", type="integer")
     */
    private $indice;

    /**
     * @var text $categoria
     *
     * @ORM\Column(name="nome", type="string", length=40)
     */
    private $nome;

    /**
     * @var text $categoria
     *
     * @ORM\Column(name="categoria", type="text")
     */
    private $categoria;

    /**
     * @ORM\OneToMany(targetEntity="Rifiuto", mappedBy="categoria", cascade={"persist", "remove", "merge", "refresh"})
     */
    private $rifiuti;

    /**
     * @var text $pericoloso
     *
     * @ORM\Column(name="pericoloso", type="boolean", nullable=true)
     */
    private $pericoloso;

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
     * Set indice
     *
     * @param integer $indice
     */
    public function setIndice($indice) {
        $this->indice = $indice;
    }

    /**
     * Get indice
     *
     * @return integer 
     */
    public function getIndice() {
        return $this->indice;
    }

    /**
     * Set nome
     *
     * @param string $nome
     */
    public function setNome($nome) {
        $this->nome = $nome;
    }

    /**
     * Get nome
     *
     * @return string 
     */
    public function getNome() {
        return $this->nome;
    }

    /**
     * Set categoria
     *
     * @param text $categoria
     */
    public function setCategoria($categoria) {
        $this->categoria = $categoria;
    }

    /**
     * Get categoria
     *
     * @return text 
     */
    public function getCategoria() {
        return $this->categoria;
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
     * Add rifiuti
     *
     * @param \ES\CerMapBundle\Entity\Recuperabili\Rifiuto $rifiuti
     * @return Categoria
     */
    public function addRifiuti(\ES\CerMapBundle\Entity\Recuperabili\Rifiuto $rifiuti) {
        $this->rifiuti[] = $rifiuti;

        return $this;
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

    /**
     * Set pericoloso
     *
     * @param boolean $pericoloso
     * @return Categoria
     */
    public function setPericoloso($pericoloso)
    {
        $this->pericoloso = $pericoloso;

        return $this;
    }

    /**
     * Get pericoloso
     *
     * @return boolean 
     */
    public function getPericoloso()
    {
        return $this->pericoloso;
    }
}
