<?php

namespace ES\CerMapBundle\Entity\Recuperabili;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use Ephp\UtilityBundle\Seo\Model\ISeo;

/**
 * ES\CerMapBundle\Entity\Recuperabili\Rifiuto
 *
 * @ORM\Table(name="cm_recuperabili_rifiuti")
 * @ORM\Entity(repositoryClass="ES\CerMapBundle\Entity\Recuperabili\RifiutoRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Rifiuto implements ISeo {

    use \Ephp\UtilityBundle\Seo\Model\Traits\BaseSeo,
        Traits\RifiutoImplements;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var text $rifiuto
     *
     * @ORM\Column(name="rifiuto", type="string", length=255)
     */
    private $rifiuto;

    /**
     * @var text $sottotitolo
     *
     * @ORM\Column(name="sottotitolo", type="string", length=255, nullable=true)
     */
    private $sottotitolo;

    /**
     * @var integer $numero
     *
     * @ORM\Column(name="numero", type="integer", nullable=true)
     */
    private $numero;

    /**
     * @var text $tipologia
     *
     * @ORM\Column(name="tipologia", type="text")
     */
    private $tipologia;

    /**
     * @ORM\ManyToOne(targetEntity="Categoria", inversedBy="rifiuti")
     * @ORM\JoinColumn(name="categoria_id", referencedColumnName="id")
     */
    private $categoria;

    /**
     * @var text $provenienza
     *
     * @ORM\Column(name="provenienza", type="text")
     */
    private $provenienza;

    /**
     * @var text $caratteristiche
     *
     * @ORM\Column(name="caratteristiche", type="text")
     */
    private $caratteristiche;

    /**
     * @var ArrayCollection $cer
     * 
     * @ORM\ManyToMany(targetEntity="ES\CerMapBundle\Entity\Cer\Cer", inversedBy="rifiuti", cascade={"all"})
     * @ORM\JoinTable(name="cm_rifiuti_cer")
     */
    private $cer;

    /**
     * @var ArrayCollection $attivita_recupero
     * 
     * @ORM\OneToMany(targetEntity="AttivitaRecupero", mappedBy="rifiuto", cascade={"persist", "remove", "merge", "refresh"}, orphanRemoval=true)
     */
    private $attivita_recupero;

    public function __construct() {
        $this->attivita_recupero = new ArrayCollection();
        $this->cer = new ArrayCollection();
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
     * Set tipologia
     *
     * @param text $tipologia
     */
    public function setTipologia($tipologia) {
        $this->tipologia = $tipologia;
    }

    /**
     * Get tipologia
     *
     * @return text 
     */
    public function getTipologia() {
        return $this->tipologia;
    }

    /**
     * Set numero
     *
     * @param integer $numero
     */
    public function setNumero($numero) {
        $this->numero = $numero;
    }

    /**
     * Get numero
     *
     * @return integer 
     */
    public function getNumero() {
        return $this->numero;
    }

    /**
     * Set rifiuto
     *
     * @param text $rifiuto
     */
    public function setRifiuto($rifiuto) {
        $this->rifiuto = $rifiuto;
    }

    /**
     * Get rifiuto
     *
     * @return text 
     */
    public function getSottotitolo() {
        return $this->sottotitolo;
    }

    /**
     * Set rifiuto
     *
     * @param text $sottotitolo
     */
    public function setSottotitolo($sottotitolo) {
        $this->sottotitolo = $sottotitolo;
    }

    /**
     * Get rifiuto
     *
     * @return text 
     */
    public function getRifiuto() {
        return $this->rifiuto;
    }

    /**
     * Set categoria
     *
     * @param CategoriaRifiuto $categoria
     */
    public function setCategoria($categoria) {
        $this->categoria = $categoria;
    }

    /**
     * Get categoria
     *
     * @return CategoriaRifiuto 
     */
    public function getCategoria() {
        return $this->categoria;
    }

    /**
     * Set provenienza
     *
     * @param text $provenienza
     */
    public function setProvenienza($provenienza) {
        $this->provenienza = $provenienza;
    }

    /**
     * Get provenienza
     *
     * @return text 
     */
    public function getProvenienza() {
        return $this->provenienza;
    }

    /**
     * Set caratteristiche
     *
     * @param text $caratteristiche
     */
    public function setCaratteristiche($caratteristiche) {
        $this->caratteristiche = $caratteristiche;
    }

    /**
     * Get caratteristiche
     *
     * @return text 
     */
    public function getCaratteristiche() {
        return $this->caratteristiche;
    }

    /**
     * Get cer
     * 
     * @return ArrayCollection 
     */
    public function getCer() {
        return $this->cer;
    }

    /**
     * Set car
     *
     * @param ArrayCollection $caratteristiche
     */
    public function setCer($cer) {
        $this->cer = $cer;
    }

    /**
     * Add cer
     *
     * @param \ES\CerMapBundle\Entity\Cer\Cer $cer
     * @return Rifiuto
     */
    public function addCer(\ES\CerMapBundle\Entity\Cer\Cer $cer) {
        $this->cer[] = $cer;

        return $this;
    }

    /**
     * Remove cer
     *
     * @param \ES\CerMapBundle\Entity\Cer\Cer $cer
     */
    public function removeCer(\ES\CerMapBundle\Entity\Cer\Cer $cer) {
        $this->cer->removeElement($cer);
    }

    /**
     * Get attivita_recupero
     * 
     * @return ArrayCollection 
     */
    public function getAttivitaRecupero() {
        return $this->attivita_recupero;
    }

    /**
     * Set attivita_recupero
     *
     * @param ArrayCollection $caratteristiche
     */
    public function setAttivitaRecupero($attivita_recupero) {
        $this->attivita_recupero = $attivita_recupero;
    }

    /**
     * Set attivita_recupero
     *
     * @param AttivitaRecupero $attivita_recupero
     */
    public function addAttivitaRecupero($attivita_recupero) {
        $this->attivita_recupero[] = $attivita_recupero;
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
