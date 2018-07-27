<?php

namespace ES\MessengerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ES\MessengerBundle\Entity\destinatario
 *
 * @ORM\Table(name="msg_allegati")
 * @ORM\Entity(repositoryClass="ES\MessengerBundle\Entity\AllegatoRepository")
 */
class Allegato
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var MessaggioBase $utente
     * 
     * @ORM\ManyToOne(targetEntity="MessaggioBase")
     * @ORM\JoinColumn(name="messaggio_id", referencedColumnName="id")
     */
    private $messaggio;

    /**
     * @var ES\UserBundle\Entity\User $utente
     * 
     * @ORM\ManyToOne(targetEntity="MessaggioBase")
     * @ORM\JoinColumn(name="conversazione_id", referencedColumnName="id")
     */
    private $conversazione;

    /**
     * @var integer $file
     *
     * @ORM\Column(name="prioirta", type="string", length=255)
     */
    private $file;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set messaggio
     *
     * @param MessaggioBase $messaggio
     */
    public function setMessaggio($messaggio)
    {
        $this->messaggio = $messaggio;
    }

    /**
     * Get messaggio
     *
     * @return MessaggioBase 
     */
    public function getMessaggio()
    {
        return $this->messaggio;
    }

    /**
     * Set conversazione
     *
     * @param MessaggioBase $conversazione
     */
    public function setConversazione($conversazione)
    {
        $this->conversazione = $conversazione;
    }

    /**
     * Get conversazione
     *
     * @return MessaggioBase 
     */
    public function getConversazione()
    {
        return $this->conversazione;
    }

    /**
     * Set file
     *
     * @param integer $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * Get file
     *
     * @return integer 
     */
    public function getFile()
    {
        return $this->file;
    }
    
    public function getCss() {
        $estensione = explode('.', $this->file);
        $estensione = strtolower($estensione[count($estensione) - 1]);
        switch ($estensione) {
            case 'pdf':
                return 'ico_pdf_att';
            case 'odt':
            case 'doc':
            case 'docx':
            case 'xdoc':
                return 'ico_doc_att';
            case 'cad':
                return 'ico_cad_att';
            case 'zip':
            case 'rar':
            case 'tar':
            case '7z':
            case 'gz':
                return 'ico_zip_att';
            case 'jpg':
            case 'gif':
            case 'png':
            case 'jpeg':
            case 'tif':
            case 'tiff':
                return 'ico_img_att';
            default:
                return 'ico_oth_att';
        }
    }
    public function getFilename() {
        $nome = explode('/', $this->file);
        return $nome[count($nome) - 1];
    }
    public function getFilenameBreve() {
        $nome = $this->getFilename();
        if(strlen($nome) > 17) {
            $pre = substr($nome, 0, 8);
            $post = substr($nome, strlen($nome) - 7);
            return "{$pre}...{$post}";
        }
        return $nome;
    }
    
}