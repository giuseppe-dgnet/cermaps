<?php

namespace ES\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ephp\ACLBundle\Model\BaseUser;

/**
 * ES\UserBundle\Entity\User
 *
 * @ORM\Table(name="es_users")
 * @ORM\Entity(repositoryClass="ES\UserBundle\Entity\UserRepository")
 * @ORM\HasLifecycleCallbacks 
 */
class User extends BaseUser {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="operatore", type="boolean", nullable=true)
     */
    private $operatore;

    /**
     * @var string
     *
     * @ORM\Column(name="produttore", type="boolean", nullable=true)
     */
    private $produttore;

    /**
     * @var string
     *
     * @ORM\Column(name="ruolo", type="string", nullable=true)
     */
    private $ruolo;

    /**
     * @var string
     *
     * @ORM\Column(name="nome_completo", type="string", nullable=true)
     */
    private $nome_completo;

    /**
     * @var \ES\OperatoriBundle\Entity\Showroom 
     * 
     * @ORM\OneToOne(targetEntity="ES\OperatoriBundle\Entity\Showroom", mappedBy="user")
     */
    private $showroom;

    public function __construct() {
        parent::__construct();
        // your own logic
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
     * Set operatore
     *
     * @param boolean $operatore
     * @return User
     */
    public function setOperatore($operatore) {
        $this->operatore = $operatore;

        return $this;
    }

    /**
     * Get operatore
     *
     * @return boolean 
     */
    public function getOperatore() {
        return $this->operatore;
    }

    /**
     * Set produttore
     *
     * @param boolean $produttore
     * @return User
     */
    public function setProduttore($produttore) {
        $this->produttore = $produttore;

        return $this;
    }

    /**
     * Get produttore
     *
     * @return boolean 
     */
    public function getProduttore() {
        return $this->produttore;
    }

    /**
     * Set showroom
     *
     * @param \ES\OperatoriBundle\Entity\Showroom $showroom
     * @return User
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
     * Salviamo la email nel posto dell username
     * @param type $email
     */
    public function setEmail($email) {
        parent::setEmail($email);
        $this->setUsername($email);
        //$this->username = $email;
    }

    public function setEmailCanonical($emailCanonical) {
        parent::setEmailCanonical($emailCanonical);
        $this->setUsernameCanonical($emailCanonical);
    }

    /**
     * Set ruolo
     *
     * @param string $ruolo
     * @return User
     */
    public function setRuolo($ruolo) {
        $this->ruolo = $ruolo;

        return $this;
    }

    /**
     * Get ruolo
     *
     * @return string 
     */
    public function getRuolo() {
        return $this->ruolo;
    }
    
    public function getNomeCompleto() {
        return $this->nome_completo;
    }

    public function setNomeCompleto($nome_completo) {
        $this->nome_completo = $nome_completo;
    }

    
    /**
     * @ORM\PostPersist()
     */
    public function postPersist() {
        $this->nome_completo = $this->firstname." ".$this->lastname;
    }

}
