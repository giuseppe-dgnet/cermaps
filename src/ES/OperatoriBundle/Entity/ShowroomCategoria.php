<?php

namespace ES\OperatoriBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ShowroomCategoria
 *
 * @ORM\Table(name="op_showroom_categoria")
 * @ORM\Entity(repositoryClass="ES\OperatoriBundle\Entity\ShowroomCategoriaRepository")
 */
class ShowroomCategoria {

    use Traits\ShowroomCategoriaImplements;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Showroom
     *
     * @ORM\ManyToOne(targetEntity="Showroom")
     * @ORM\JoinColumn(name="showroom_id", referencedColumnName="id")
     */
    private $showroom;

    /**
     * @var Categoria
     *
     * @ORM\ManyToOne(targetEntity="Categoria")
     * @ORM\JoinColumn(name="categoria_id", referencedColumnName="id")
     */
    private $categoria;

    /**
     * @var ClasseCategoria
     *
     * @ORM\ManyToOne(targetEntity="ClasseCategoria")
     * @ORM\JoinColumn(name="classe_id", referencedColumnName="id")
     */
    private $classe;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo_iscrizione", type="string", length=255, nullable=true)
     */
    private $tipo_iscrizione;

    /**
     * @var string
     *
     * @ORM\Column(name="sottocategoria", type="string", length=255, nullable=true)
     */
    private $sottocategoria;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="scadenza_at", type="datetime", nullable=true)
     */
    private $scadenza_at;

    /**
     * @var boolean
     *
     * @ORM\Column(name="sospesa", type="boolean", nullable=true)
     */
    private $sospesa;

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
     * @return ShowroomCategoria
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
     * @return ShowroomCategoria
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
     * Set tipo_iscrizione
     *
     * @param string $tipoIscrizione
     * @return ShowroomCategoria
     */
    public function setTipoIscrizione($tipoIscrizione) {
        $this->tipo_iscrizione = $tipoIscrizione;

        return $this;
    }

    /**
     * Get tipo_iscrizione
     *
     * @return string 
     */
    public function getTipoIscrizione() {
        return $this->tipo_iscrizione;
    }

    /**
     * Set sottocategoria
     *
     * @param string $sottocategoria
     * @return ShowroomCategoria
     */
    public function setSottocategoria($sottocategoria) {
        $this->sottocategoria = $sottocategoria;

        return $this;
    }

    /**
     * Get sottocategoria
     *
     * @return string 
     */
    public function getSottocategoria() {
        return $this->sottocategoria;
    }

    /**
     * Set scadenza_at
     *
     * @param \DateTime $scadenzaAt
     * @return ShowroomCategoria
     */
    public function setScadenzaAt($scadenzaAt) {
        $this->scadenza_at = $scadenzaAt;

        return $this;
    }

    /**
     * Get scadenza_at
     *
     * @return \DateTime 
     */
    public function getScadenzaAt() {
        return $this->scadenza_at;
    }

    /**
     * Set sospesa
     *
     * @param boolean $sospesa
     * @return ShowroomCategoria
     */
    public function setSospesa($sospesa) {
        $this->sospesa = $sospesa;

        return $this;
    }

    /**
     * Get sospesa
     *
     * @return boolean 
     */
    public function getSospesa() {
        return $this->sospesa;
    }

    /**
     * Set showroom
     *
     * @param \ES\OperatoriBundle\Entity\Showroom $showroom
     * @return ShowroomCategoria
     */
    public function setShowroom(\ES\OperatoriBundle\Entity\Showroom $showroom = null) {
        $this->showroom = $showroom;

        return $this;
    }

    /**
     * Get showroom
     *
     * @return \ES\OperatoriBundle\Entity\Showroom 
     */
    public function getShowroom() {
        return $this->showroom;
    }

}
