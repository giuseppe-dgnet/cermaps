<?php

namespace ES\OperatoriBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Categorie
 *
 * @ORM\Table(name="op_categorie")
 * @ORM\Entity(repositoryClass="ES\OperatoriBundle\Entity\CategoriaRepository")
 */
class Categoria {

    use Traits\CategoriaImplements;

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
     * @ORM\Column(name="sigla", type="string", length=32)
     */
    private $sigla;

    /**
     * @var string
     *
     * @ORM\Column(name="categoria", type="string", length=255, nullable=true)
     */
    private $categoria;

    /**
     * @var string
     *
     * @ORM\Column(name="descrizione", type="text", nullable=true)
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
     * Set sigla
     *
     * @param string $sigla
     * @return Categorie
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
     * @return Categorie
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
     * @return Categorie
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

}
