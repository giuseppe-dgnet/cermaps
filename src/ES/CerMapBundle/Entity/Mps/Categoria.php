<?php

namespace ES\CerMapBundle\Entity\Mps;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Ephp\UtilityBundle\Seo\Model\ISeo;

/**
 * ES\CerMapBundle\Entity\Mps\Categoria
 *
 * @ORM\Table(name="cm_mps_categorie")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="ES\CerMapBundle\Entity\Mps\CategoriaRepository")
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
     * @var text $categoria
     *
     * @ORM\Column(name="nome", type="string", length=32)
     */
    private $nome;

    /**
     * @var text $categoria
     *
     * @ORM\Column(name="categoria", type="text")
     */
    private $categoria;

    /**
     * @ORM\OneToMany(targetEntity="Mps", mappedBy="categoria", cascade={"persist", "remove", "merge", "refresh"}, orphanRemoval=true)
     */
    private $mps;

    /**
     * Constructor
     */
    public function __construct() {
        $this->mps = new \Doctrine\Common\Collections\ArrayCollection();
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
    public function getMps() {
        return $this->mps;
    }

    /**
     * Set rifiuti
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $rifiuti
     */
    public function setMps($rifiuti) {
        $this->mps = $rifiuti;
    }

    /**
     * Add mps
     *
     * @param \ES\CerMapBundle\Entity\Mps\Mps $mps
     * @return Categoria
     */
    public function addMps(\ES\CerMapBundle\Entity\Mps\Mps $mps) {
        $this->mps[] = $mps;

        return $this;
    }

    /**
     * Remove mps
     *
     * @param \ES\CerMapBundle\Entity\Mps\Mps $mps
     */
    public function removeMps(\ES\CerMapBundle\Entity\Mps\Mps $mps) {
        $this->mps->removeElement($mps);
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
