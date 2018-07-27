<?php

namespace ES\MessengerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * ES\MessengerBundle\Entity\MessaggioBase
 *
 * @ORM\Table(name="msg_base")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({
 *          "rdo_cer"                       = "ES\MessengerBundle\Entity\RDO\Cer", 
 *          "rdo_mps"                       = "ES\MessengerBundle\Entity\RDO\Mps", 
 *          "rdo_servizi"                   = "ES\MessengerBundle\Entity\RDO\Servizi", 
 *          "rdi"                           = "ES\MessengerBundle\Entity\Messaggio", 
 *          "risposta"                      = "ES\MessengerBundle\Entity\Risposta", 
 *          "system"                        = "ES\MessengerBundle\Entity\Sistema", 
 *          "null"                          = "MessaggioBase" 
 * })
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="ES\MessengerBundle\Entity\MessaggioBaseRepository")
 */
abstract class MessaggioBase {

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Doctrine\Common\Collections\ArrayCollection $destinatari
     * 
     * @ORM\OneToMany(targetEntity="Destinatario", mappedBy="messaggio", cascade={"persist", "remove", "merge", "refresh"})
     */
    private $destinatari;

    /**
     * @var ES\UserBundle\Entity\User $utente
     * 
     * @ORM\ManyToOne(targetEntity="ES\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="from_utente_id", referencedColumnName="id", nullable=true)
     */
    private $from_utente;
    
    

    /**
     * @var string $from_nome
     *
     * @ORM\Column(name="from_nome", type="string", length=64, nullable=true)
     */
    private $from_nome;

    /**
     * @var string $from_cognome
     *
     * @ORM\Column(name="from_cognome", type="string", length=64, nullable=true)
     */
    private $from_cognome;

    /**
     * @var string $from_azienda
     *
     * @ORM\Column(name="from_azienda", type="string", length=128, nullable=true)
     */
    private $from_azienda;

    /**
     * @var string $from_email
     *
     * @ORM\Column(name="from_email", type="string", length=128)
     */
    private $from_email;

    /**
     * @var string $subject
     *
     * @ORM\Column(name="subject", type="string", length=255)
     */
    private $subject;

    /**
     * @var text $testo
     *
     * @ORM\Column(name="testo", type="text", nullable=true)
     */
    private $testo;

    /**
     * var string $pre_slug 
     * 
     * @ORM\Column(name="pre_slug", type="string", length=255)
     */
    private $pre_slug;

    /**
     * var string $tipologia
     * 
     * @ORM\Column(name="tipologia", type="string", length=16)
     */
    private $tipologia;

    /**
     * var string $slug 
     * 
     * @Gedmo\Slug(fields={"pre_slug"})
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     */
    private $slug;

    /**
     * @var datetime $created_at
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $created_at;

    /**
     * @var datetime $updated_at
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updated_at;

    /**
     * @var \octrine\Common\Collections\ArrayCollection $allegati
     * 
     * @ORM\OneToMany(targetEntity="Allegato", mappedBy="messaggio", cascade={"persist", "remove", "merge", "refresh"})
     */
    private $allegati;

    /**
     * @var \octrine\Common\Collections\ArrayCollection $allegati
     * 
     * @ORM\OneToMany(targetEntity="Allegato", mappedBy="conversazione", cascade={"persist", "remove", "merge", "refresh"})
     */
    private $allegati_conversazione;

    /**
     * @ORM\OneToMany(targetEntity="Risposta", mappedBy="messaggio", cascade={"persist", "remove", "merge", "refresh"})     
     * @ORM\OrderBy({"created_at" = "ASC"}) 
     */
    private $risposte;

    function __construct() {
        $this->risposte = new \Doctrine\Common\Collections\ArrayCollection();
        $this->destinatari = new \Doctrine\Common\Collections\ArrayCollection();
        $this->allegati = new \Doctrine\Common\Collections\ArrayCollection();
        $this->allegati_conversazione = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set destinatari
     *
     * @param \Doctrine\Common\Collections\ArrayCollection  $destinatari
     */
    public function setDestinatari($destinatari) {
        $this->destinatari = $destinatari;
    }

    /**
     * Get destinatari
     *
     * @return \Doctrine\Common\Collections\ArrayCollection  
     */
    public function getDestinatari() {
        return $this->destinatari;
    }

    /**
     * Get destinatari
     *
     * @return integer 
     */
    public function addDestinatari($destinatari) {
        $this->destinatari->add($destinatari);
    }

    /**
     * Set destinatari
     *
     * @param \Doctrine\Common\Collections\ArrayCollection  $allegati
     */
    public function setAllegati($allegati) {
        $this->allegati = $allegati;
    }

    /**
     * Get destinatari
     *
     * @return \Doctrine\Common\Collections\ArrayCollection 
     */
    public function getAllegati() {
        return $this->allegati;
    }

    /**
     * Get destinatari
     *
     * @return integer 
     */
    public function addAllegati($allegati) {
        $this->allegati->add($allegati);
    }

    /**
     * Set destinatari
     *
     * @param \Doctrine\Common\Collections\ArrayCollection  $allegati
     */
    public function setAllegatiConversazione($allegati) {
        $this->allegati_conversazione = $allegati;
    }

    /**
     * Get destinatari
     *
     * @return \Doctrine\Common\Collections\ArrayCollection 
     */
    public function getAllegatiConversazione() {
        return $this->allegati_conversazione;
    }

    /**
     * Get destinatari
     *
     * @return integer 
     */
    public function addAllegatiConversazione($allegati) {
        $this->allegati_conversazione->add($allegati);
    }
    
    
     

    /**
     * Set from_user_id
     *
     * @param \ES\UserBundle\Entity\User $from_utente
     */
    public function setFromUtente(\ES\UserBundle\Entity\User $from_utente, $set_id = false) {
        $this->from_utente = $set_id ? $from_utente->getId() : $from_utente;
        $destinatario = new Destinatario();
        $destinatario->setDestinatario($from_utente);
        $destinatario->setMessaggio($this);
        $destinatario->setConversazione($this->getConversazione());
        $destinatario->setNotificato(true);
        $destinatario->setLetto(true);
        $this->addDestinatari($destinatario);
        $this->setFromNome($from_utente->getFirstname());
        $this->setFromCognome($from_utente->getLastname());
        $this->setFromEmail($from_utente->getShowroom() ? $from_utente->getShowroom()->getEmail() : '');
        $this->setFromAzienda($from_utente->getShowroom() ? $from_utente->getShowroom()->getRagioneSociale() : '');
    }

    /**
     * Get from_user_id
     *
     * @return \ES\UserBundle\Entity\User 
     */
    public function getFromUtente() {
        return $this->from_utente;
    }

    /**
     * Set from_nome
     *
     * @param string $fromNome
     */
    public function setFromNome($fromNome) {
        $this->from_nome = $fromNome;
    }

    /**
     * Get from_nome
     *
     * @return string 
     */
    public function getFromNome() {
        return $this->from_nome;
    }

    /**
     * Set from_cognome
     *
     * @param string $fromCognome
     */
    public function setFromCognome($fromCognome) {
        $this->from_cognome = $fromCognome;
    }

    /**
     * Get from_cognome
     *
     * @return string 
     */
    public function getFromCognome() {
        return $this->from_cognome;
    }

    /**
     * Set from_azienda
     *
     * @param string $fromAzienda
     */
    public function setFromAzienda($fromAzienda) {
        $this->from_azienda = $fromAzienda;
    }

    /**
     * Get from_azienda
     *
     * @return string 
     */
    public function getFromAzienda() {
        return $this->from_azienda;
    }

    /**
     * Set from_email
     *
     * @param string $fromEmail
     */
    public function setFromEmail($fromEmail) {
        $this->from_email = $fromEmail;
    }

    /**
     * Get from_email
     *
     * @return string 
     */
    public function getFromEmail() {
        return $this->from_email;
    }

    /**
     * Set subject
     *
     * @param string $subject
     */
    public function setSubject($subject) {
        $this->subject = $subject;
    }

    /**
     * Get subject
     *
     * @return string 
     */
    public function getSubject() {
        return $this->subject;
    }

    /**
     * Set testo
     *
     * @param text $testo
     */
    public function setTesto($testo) {
        $this->testo = $testo;
    }

    /**
     * Get testo
     *
     * @return text 
     */
    public function getTesto() {
        return $this->testo;
    }

    /**
     * get risposte
     *
     * @return \Doctrine\Common\Collections\ArrayCollection 
     */
    public function getRisposte() {
        return $this->risposte;
    }

    /**
     * ser risposte
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $risposte 
     */
    public function setRisposte($risposte) {
        $this->risposte = $risposte;
    }

    /**
     * ser risposte
     *
     * @param Risposta $risposte 
     */
    public function addRisposte($risposte) {
        $this->risposte->add($risposte);
    }

    /**
     * Set created_at
     *
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt) {
        $this->created_at = $createdAt;
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
     * Set created_at
     *
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt($updatedAt) {
        $this->updated_at = $updatedAt;
    }

    /**
     * Get created_at
     *
     * @return \DateTime 
     */
    public function getUpdatedAt() {
        return $this->updated_at;
    }

    public function getNomeCompleto() {
        if ($this->from_azienda != '') {
            return "{$this->from_nome} {$this->from_cognome} - {$this->from_azienda}";
        }
        return "{$this->from_nome} {$this->from_cognome}";
    }

    public function getStatoRichiedente() {
        if (!$this->from_utente) {
            return "Utente non registrato";
        } else {
            if ($this->from_utente->getOperatore()) {
                return "Utente Operatore";
            }
            if ($this->from_utente->getProduttore()) {
                return "Utente Produttore";
            }
        }
        return "Utente non verificato *";
    }

    public function getPreSlug() {
        return $this->pre_slug;
    }

    public function setPreSlug($pre_slug) {
        $this->pre_slug = $pre_slug;
    }

    public function getSlug() {
        return $this->slug;
    }

    public function setSlug($slug) {
        $this->slug = $slug;
    }

    public function getTipologia() {
        return $this->tipologia;
    }

    public function setTipologia($tipologia) {
        $this->tipologia = $tipologia;
    }

    /**
     * @ORM\PrePersist 
     */
    public function prePersist() {
        $zona = new \DateTimeZone('Europe/Rome');
        $now = new \DateTime('now', $zona);
        $this->created_at = $now;
        $this->updated_at = $now;
        $this->tipologia = $this->getTipo();
        $this->pre_slug = md5('mail_' . $now->getTimestamp() . '_' . ($this->getFromUtente() != null ? $this->getFromUtente()->getId() : sha1(uniqid($now->getTimestamp(), true))) . '_' . $this->getDestinatari()->count());
    }

    abstract public function riepilogo();

    abstract public function getTipo();

    abstract public function getAnteprima();

    public function getTwigTesto() {
        return 'EcoSeekrMessengerBundle:Messenger:discussione/testo.html.twig';
    }

    public function getLastAnteprima() {
        if ($this->risposte->count() == 0) {
            return $this->getAnteprima();
        }
        return $this->risposte->last()->getAnteprima();
    }

    public function getLastId() {
        if ($this->risposte->count() == 0) {
            return $this->getId();
        }
        return $this->risposte->last()->getId();
    }

    public function getLastMessageAt() {
        if ($this->risposte->count() == 0) {
            return $this->getCreatedAt();
        }
        $out = $this->risposte->last()->getCreatedAt();
        return $out;
    }

    public function getLastMittente() {
        if ($this->risposte->count() == 0) {
            return $this->getFromUtente()->getLabel();
        }
        return $this->risposte->last()->getFromUtente()->getLabel();
    }

    public function getLastMittenteId() {
        if ($this->risposte->count() == 0) {
            return $this->getFromUtente()->getId();
        }
        return $this->risposte->last()->getFromUtente()->getId();
    }

    /**
     * 
     * @return \ES\UserBundle\Entity\User
     */
    public function getLastMittenteUser() {
        if ($this->risposte->count() == 0) {
            return $this->getFromUtente();
        }
        return $this->risposte->last()->getFromUtente();
    }

    private $nomi = array();
    private $ids = array();
    private $percorso = array();

    public function getNomiUtenti($max = 2) {
        if (count($this->nomi) == 0) {
            $my_id = $this->getDestinatario()->getDestinatario()->getId();
            $last = $this->getLastMittenteUser();
            if ($last->getId() != $my_id) {
                $this->nomi[] = $last->getLabel();
                $this->ids[] = $last->getId();
            }
            foreach ($this->getDestinatari() as $_destinatario) {
                $destinatario = $_destinatario->getDestinatario();
                if ($destinatario->getId() != $my_id && $last->getId() != $destinatario->getId()) {
                    if ($destinatario->getId() == 1 && $_destinatario->getImpresa()) {
                        $this->nomi[] = $_destinatario->getImpresa()->getDenominazionePulita();
                    } else {
                        $this->nomi[] = $destinatario->getLabel();
                    }
                    $this->percorso[] = $_destinatario->getDestinatario()->serialize();
                    $this->ids[] = $destinatario->getId();
                }
            }
        }
        $out = array('big' => implode(', ', $this->nomi), 'array' => $this->nomi);
        if (count($this->nomi) <= $max) {
            $small = implode(', ', $this->nomi);
            $percorso = $this->percorso;
        } else {
            $small = implode(', ', array_slice($this->nomi, 0, $max - 1)) . ' e altri ' . (count($this->nomi) - ($max - 1));
            $percorso = $this->percorso;
        }
        $out['small'] = $small;
        $out['percorso_utente'] = $percorso;

        //\ES\WebBundle\Functions\Funzioni::vd($out,true);

        return $out;
    }

    public function getLastMittenteUserSerialize() {
        $my_id = $this->getDestinatario()->getDestinatario()->getId();
        $last = $this->getLastMittenteUser();
        if ($last->getId() != $my_id) {
            return $last->serialize();
        }
        foreach ($this->getDestinatari() as $_destinatario) {
            $destinatario = $_destinatario->getDestinatario();
            if ($destinatario->getId() != $my_id && $last->getId() != $destinatario->getId()) {
                return $destinatario->serialize();
            }
        }
    }

    public function getConversazione() {
        return $this;
    }

    public function getNumeroAllegati() {
        return $this->allegati->count();
    }

    public function getFromUtenteDenominazioneCompleta() {
        if ($this->getFromUtente()) {
            return $this->getFromUtente()->getLabel();
        }
        return $this->getNomeCompleto();
    }

    public function getAvatar() {
        if ($this->getFromUtente() && $this->getFromUtente()->getProfessionista()) {
            return $this->getFromUtente()->getProfessionista()->getAvatar();
        }
        return '/images/user_placeholder.jpg';
    }

    public function getAvatarSize() {
        if ($this->getFromUtente() && $this->getFromUtente()->getProfessionista()) {
            return $this->getFromUtente()->getProfessionista()->getAvatarSize();
        }
        return array('x' => 100, 'y' => 100);
    }

    /**
     *
     * @var Destinatario 
     */
    private $destinatario = false;

    /**
     * Restituisce il mio destinatario tramite l'oggetto destinatario
     * 
     * @param ES\UserBundle\Entity\User $user
     * @return Destinatario 
     */
    public function myStatus(\ES\UserBundle\Entity\User $user) {
        foreach ($this->destinatari as $destinatario) {
            if ($destinatario->getDestinatario()->getId() == $user->getId()) {
                $this->destinatario = $destinatario;
                break;
            }
        }
        return $this->destinatario;
    }

    public function cercaDestinatario(\ES\UserBundle\Entity\User $user) {
        $this->myStatus($user);
        foreach ($this->getRisposte() as $risposta) {
            $risposta->myStatus($user);
        }
    }

    /**
     *
     * @return Destinatario 
     */
    public function getDestinatario() {
        return $this->destinatario;
    }

    public function setArchiviato($archiviato, $all = true) {
        $this->destinatario->setArchiviato($archiviato);
        if ($all) {
            foreach ($this->getRisposte() as $risposta) {
                $risposta->setArchiviato($archiviato);
            }
        }
    }

    public function setLetto($letto, $all = true) {
        $this->destinatario->setLetto($letto);
        if ($all) {
            foreach ($this->getRisposte() as $risposta) {
                $risposta->setLetto($letto);
            }
        }
    }

    public function setNotificato($notificato) {
        if ($this->destinatario) {
            $this->destinatario->setNotificato($notificato);
            foreach ($this->getRisposte() as $risposta) {
                $risposta->setNotificato($notificato);
            }
        }
    }

    public function setSpam($spam, $all = true) {
        $this->destinatario->setSpam($spam);
        if ($all) {
            foreach ($this->getRisposte() as $risposta) {
                $risposta->setSpam($spam);
            }
        }
    }

    public function isNotificato() {
        $isLetto = false;
        if ($this->destinatario) {
            $isLetto = $this->destinatario->getNotificato();
        }
        return $isLetto;
    }

    public function isMessaggioNotificato() {
        $isLetto = false;
        if ($this->destinatario) {
            $isLetto = $this->destinatario->getNotificato();
            foreach ($this->risposte as $risposta) {
                $isLetto &= $risposta->isNotificato();
            }
        }
        return $isLetto;
    }

    public function isLetto() {
        $isLetto = false;
        if ($this->destinatario) {
            $isLetto = $this->destinatario->getLetto();
        }
        return $isLetto;
    }

    public function isLock() {
        $isLock = 0;
        if ($this->destinatario) {
            $isLock = $this->destinatario->getLock();
        }
        return $isLock > 0;
    }

    public function isMessaggioLetto() {
        $isLetto = false;
        if ($this->destinatario) {
            $isLetto = $this->destinatario->getLetto();
            foreach ($this->risposte as $risposta) {
                $isLetto &= $risposta->isLetto();
            }
        }
        return $isLetto;
    }

    public function isArchiviato() {
        $isArchiviato = false;
        if ($this->destinatario) {
            $isArchiviato = $this->destinatario->getArchiviato();
        }
        return $isArchiviato;
    }

    public function isMessaggioArchiviato() {
        $isArchiviato = false;
        if ($this->destinatario) {
            $isArchiviato = $this->destinatario->getArchiviato();
            foreach ($this->risposte as $risposta) {
                $isArchiviato &= $risposta->isArchiviato();
            }
        }
        return $isArchiviato;
    }

    public function isSpam() {
        $isSpam = false;
        if ($this->destinatario) {
            $isSpam = $this->destinatario->getSpam();
        }
        return $isSpam;
    }

    public function isMessaggioSpam() {
        $isSpam = false;
        if ($this->destinatario) {
            $isSpam = $this->destinatario->getSpam();
            foreach ($this->risposte as $risposta) {
                $isSpam |= $risposta->isSpam();
            }
        }
        return $isSpam;
    }

    public function getPriorita() {
        if ($this->destinatario) {
            return $this->destinatario->getPrioirta();
        }
        return 0;
    }

    public function setController(\ES\MessengerBundle\Controller\MessengerController $controller) {
        
    }

    public function getAltreInformazioni() {
        
    }

    public function getReplyAvailable() {
        return true;
    }

    public function getObbligatorio() {
        return false;
    }

}