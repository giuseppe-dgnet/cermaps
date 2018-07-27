<?php

namespace ES\OperatoriBundle\Entity\Servizi;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Ephp\UtilityBundle\Seo\Model\ISeo;

/**
 * Categoria
 *
 * @ORM\Table(name="op_servizi_categorie")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="ES\OperatoriBundle\Entity\Servizi\CategoriaRepository")
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
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="sigla", type="string", length=3)
     */
    private $sigla;

    /**
     * @var string
     *
     * @ORM\Column(name="categoria", type="string", length=64)
     */
    private $categoria;

    /**
     * @var string
     *
     * @ORM\Column(name="descrizione", type="text")
     */
    private $descrizione;

    /**
     * @ORM\OneToMany(targetEntity="Servizio", mappedBy="categoria", cascade={"persist", "remove", "merge", "refresh"}, orphanRemoval=true)
     */
    private $servizi;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->servizi = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set sigla
     *
     * @param string $sigla
     * @return Categoria
     */
    public function setSigla($sigla) {
        $this->sigla = $sigla;

        return $this;
    }

    /**
     * Get sigla
     *
     * @return string 
     */
    public function getSigla() {
        return $this->sigla;
    }

    /**
     * Set categoria
     *
     * @param string $categoria
     * @return Categoria
     */
    public function setCategoria($categoria) {
        $this->categoria = $categoria;

        return $this;
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
     * Set descrizione
     *
     * @param string $descrizione
     * @return Categoria
     */
    public function setDescrizione($descrizione) {
        $this->descrizione = $descrizione;

        return $this;
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
     * Add servizi
     *
     * @param \ES\OperatoriBundle\Entity\Servizi\Servizio $servizi
     * @return Categoria
     */
    public function addServizi(\ES\OperatoriBundle\Entity\Servizi\Servizio $servizi)
    {
        $this->servizi[] = $servizi;
    
        return $this;
    }

    /**
     * Remove servizi
     *
     * @param \ES\OperatoriBundle\Entity\Servizi\Servizio $servizi
     */
    public function removeServizi(\ES\OperatoriBundle\Entity\Servizi\Servizio $servizi)
    {
        $this->servizi->removeElement($servizi);
    }

    /**
     * Get servizi
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getServizi()
    {
        return $this->servizi;
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