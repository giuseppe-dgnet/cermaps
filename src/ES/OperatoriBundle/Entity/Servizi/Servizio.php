<?php

namespace ES\OperatoriBundle\Entity\Servizi;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Ephp\UtilityBundle\Seo\Model\ISeo;

/**
 * Servizio
 *
 * @ORM\Table(name="op_servizi")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="ES\OperatoriBundle\Entity\Servizi\ServizioRepository")
 */
class Servizio implements ISeo {

    use \Ephp\UtilityBundle\Seo\Model\Traits\BaseSeo,
        Traits\ServizioImplements;

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
     * @ORM\Column(name="servizio", type="string", length=255)
     */
    private $servizio;

    /**
     * @ORM\ManyToOne(targetEntity="Categoria", inversedBy="servizi")
     * @ORM\JoinColumn(name="categoria_id", referencedColumnName="id")
     */
    private $categoria;

    /**
     * @var string
     *
     * @ORM\Column(name="descrizione", type="text")
     */
    private $descrizione;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set servizio
     *
     * @param string $servizio
     * @return Servizio
     */
    public function setServizio($servizio) {
        $this->servizio = $servizio;

        return $this;
    }

    /**
     * Get servizio
     *
     * @return string 
     */
    public function getServizio() {
        return $this->servizio;
    }

    /**
     * Set categoria
     *
     * @param Categoria $categoria
     * @return Servizio
     */
    public function setCategoria(Categoria $categoria) {
        $this->categoria = $categoria;

        return $this;
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
     * Set descrizione
     *
     * @param string $descrizione
     * @return Servizio
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
