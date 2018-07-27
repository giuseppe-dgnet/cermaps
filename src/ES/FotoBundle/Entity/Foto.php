<?php

namespace ES\FotoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Foto
 *
 * @ORM\Table("op_showroom_foto")
 * @ORM\Entity(repositoryClass="ES\FotoBundle\Entity\FotoRepository")
 * @ORM\HasLifecycleCallbacks() 
 */
class Foto {

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
     * @ORM\Column(name="nome", type="string", length=255)
     */
    private $nome;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255)
     */
    private $path;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $created_at;

    /**
     * @var boolean
     *
     * @ORM\Column(name="foto_eliminata", type="boolean")
     */
    private $foto_eliminata;

    /**
     * @ORM\ManyToOne(targetEntity="ES\OperatoriBundle\Entity\Showroom", inversedBy="foto")
     * @ORM\JoinColumn(name="showroom_id", referencedColumnName="id") 
     */
    protected $showroom;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="foto_profilo", type="boolean",nullable=true)
     * 
     */
    private $foto_profilo;

    /**
     * @ORM\PrePersist 
     */
    public function prePersist() {
        $this->created_at = new \DateTime('now');
        $this->foto_eliminata = false;
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
     * @return Foto
     */
    public function setNome($nome) {
        $this->nome = $nome;

        return $this;
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
     * Set path
     *
     * @param string $path
     * @return Foto
     */
    public function setPath($path) {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath() {
        return $this->path;
    }

    /**
     * Set created_at
     *
     * @param \DateTime $createdAt
     * @return Foto
     */
    public function setCreatedAt($createdAt) {
        $this->created_at = $createdAt;

        return $this;
    }

    /**
     * Get created_at
     *
     * @return \DateTime 
     */
    public function getCreatedAt() {
        return $this->created_at;
    }

    /**
     * Set foto_eliminata
     *
     * @param boolean $fotoEliminata
     * @return Foto
     */
    public function setFotoEliminata($fotoEliminata) {
        $this->foto_eliminata = $fotoEliminata;

        return $this;
    }

    /**
     * Get foto_eliminata
     *
     * @return boolean 
     */
    public function getFotoEliminata() {
        return $this->foto_eliminata;
    }

    /**
     * Set showroom
     *
     * @param \ES\OperatoriBundle\Entity\Showroom $showroom
     * @return Foto
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
    
    /**
     * Set foto_profilo
     *
     * @param boolean $fotoProfilo
     * @return Foto
     */
    public function setFotoProfilo($fotoProfilo) {
        $this->foto_profilo = $fotoProfilo;

        return $this;
    }

    /**
     * Get foto_profilo
     *
     * @return boolean 
     */
    public function getFotoProfilo() {
        return $this->foto_profilo;
    }


    /**
     * Metodi Utitli per la resa delle foto
     */
    public function getAbsolutePath() {
        return !$this->path ? null : $this->getUploadRootDir() . '/' . $this->path;
    }

    public function getWebPath() {
        return !$this->path ? null : $this->getUploadDir() . '/' . $this->path;
    }

    public function getUploadRootDir() {
        // il percorso assoluto della cartella dove i documenti caricati verranno salvati
        return __DIR__ . '/../../../../web/' . $this->getUploadDir();
    }

    protected function getUploadDir() {
        // togliamo __DIR__ in modo da visualizzare correttamente nella vista il file caricato
        return 'uploads/attachments';
    }

    public function getAbsoluteUrl($size) {
        if (!in_array($size, array('large', 'medium', 'originals', 'small', 'thumbnails', 'profilo'))) {
            $size = 'thumbnails';
        }
        return !$this->path ? null : $this->getUploadRootDir() . '/' . $this->path . '/' . $size . '/' . $this->nome;
    }

    /**
     * 
     * @param string $size 'large', 'medium', 'originals', 'small', 'thumbnails'
     * @return string path percorso dell'immagine 
     */
    public function getWebUrl($size) {
        if (!in_array($size, array('large', 'medium', 'originals', 'small', 'thumbnails', 'profilo'))) {
            $size = 'thumbnails';
        }
        return !$this->path ? null : '/' . $this->getWebPath() . '/' . $size . '/' . $this->nome;
    }

}
