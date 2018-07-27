<?php

namespace ES\OperatoriBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ClasseCategoria
 *
 * @ORM\Table(name="op_categorie_classi")
 * @ORM\Entity(repositoryClass="ES\OperatoriBundle\Entity\ClasseCategoriaRepository")
 */
class ClasseCategoria {

    use Traits\ClasseCategoriaImplements;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Categoria
     *
     * @ORM\ManyToOne(targetEntity="Categoria")
     * @ORM\JoinColumn(name="categoria_id", referencedColumnName="id")
     */
    private $categoria;

    /**
     * @var string
     *
     * @ORM\Column(name="classe", type="string", length=1)
     */
    private $classe;

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
     * Set categoria
     *
     * @param string $categoria
     * @return ClasseCategoria
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
     * Set classe
     *
     * @param string $classe
     * @return ClasseCategoria
     */
    public function setClasse($classe) {
        $this->classe = $classe;

        return $this;
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
     * Set descrizione
     *
     * @param string $descrizione
     * @return ClasseCategoria
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
