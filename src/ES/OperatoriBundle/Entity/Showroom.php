<?php

namespace ES\OperatoriBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Ephp\UtilityBundle\Seo\Model\ISeo;
use Ephp\TagBundle\Model\IModelTag;

/**
 * ES\OperatoriBundle\Entity\Showroom
 *
 * @ORM\Table(name="op_showroom")
 * @ORM\Entity(repositoryClass="ES\OperatoriBundle\Entity\ShowroomRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Showroom implements ISeo, IModelTag {

    use \Ephp\GeoBundle\Model\Traits\BaseGeo,
        Traits\ShowroomImplements,
        \Ephp\UtilityBundle\Seo\Model\Traits\BaseSeo,
        \Ephp\TagBundle\Model\ModelTagTrait;

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
     * @ORM\OneToOne(targetEntity="ES\UserBundle\Entity\User", inversedBy="showroom")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="ES\FotoBundle\Entity\Foto", mappedBy="showroom")
     */
    protected $foto;

    /**
     * @var string
     *
     * @ORM\Column(name="ragione_sociale", type="string", length=255)
     */
    private $ragione_sociale;

    /**
     * @var string
     *
     * @ORM\Column(name="logo", type="string", length=255, nullable=true)
     */
    private $logo;

    /**
     * @var string
     *
     * @ORM\Column(name="descrizione_attivita", type="string", length=255, nullable=true)
     */
    private $descrizione_attivita;

    /**
     * @var string
     *
     * @ORM\Column(name="descrizione", type="text", nullable=true)
     */
    private $descrizione;

    /**
     * @var string
     *
     * @ORM\Column(name="partita_iva", type="string", length=11, nullable=true)
     */
    private $partita_iva;

    /**
     * @var string
     *
     * @ORM\Column(name="codice_fiscale", type="string", length=16, nullable=true)
     */
    private $codice_fiscale;

    /**
     * @var string
     *
     * @ORM\Column(name="codice_rae", type="string", length=16, nullable=true)
     */
    private $codice_rae;

    /**
     * @var string
     *
     * @ORM\Column(name="sito", type="string", length=255, nullable=true)
     */
    private $sito;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="email_pec", type="string", length=255, nullable=true)
     */
    private $email_pec;

    /**
     * @var string
     *
     * @ORM\Column(name="attivita_principale", type="string", length=255)
     */
    private $attivita_principale;

    /**
     * @var string
     *
     * @ORM\Column(name="telefono", type="string", length=255, nullable=true)
     */
    private $telefono;

    /**
     * @var string
     *
     * @ORM\Column(name="fax", type="string", length=255, nullable=true)
     */
    private $fax;

    /**
     * @var string
     *
     * @ORM\Column(name="cellulare", type="string", length=255, nullable=true)
     */
    private $cellulare;

    /**
     * @var boolean
     *
     * @ORM\Column(name="has_cer", type="boolean", nullable=true)
     */
    private $has_cer;

    /**
     * @var boolean
     *
     * @ORM\Column(name="has_tipologie", type="boolean", nullable=true)
     */
    private $has_tipologie;

    /**
     * @var boolean
     *
     * @ORM\Column(name="has_cer_cp", type="boolean", nullable=true)
     */
    private $has_cer_cp;

    /**
     * @var boolean
     *
     * @ORM\Column(name="has_cer_trattati", type="boolean", nullable=true)
     */
    private $has_cer_trattati;

    /**
     * @var boolean
     *
     * @ORM\Column(name="has_servizi", type="boolean", nullable=true)
     */
    private $has_servizi;

    /**
     * @var array
     *
     * @ORM\Column(name="anga", type="array")
     */
    private $anga;

    /**
     * @var boolean
     *
     * @ORM\Column(name="impianto", type="boolean", nullable=true)
     */
    private $impianto;

    /**
     * @var boolean
     *
     * @ORM\Column(name="discarica", type="boolean", nullable=true)
     */
    private $discarica;

    /**
     * @var boolean
     *
     * @ORM\Column(name="raccoglitore", type="boolean", nullable=true)
     */
    private $raccoglitore;

    /**
     * @var boolean
     *
     * @ORM\Column(name="trasportatore", type="boolean", nullable=true)
     */
    private $trasportatore;

    /**
     * @var boolean
     *
     * @ORM\Column(name="servizi", type="boolean", nullable=true)
     */
    private $servizi;

    /**
     * @var boolean
     *
     * @ORM\Column(name="laboratorio", type="boolean", nullable=true)
     */
    private $laboratorio;

    /**
     * @var boolean
     *
     * @ORM\Column(name="demolizioni", type="boolean", nullable=true)
     */
    private $demolizioni;

    /**
     * @var boolean
     *
     * @ORM\Column(name="spurghi", type="boolean", nullable=true)
     */
    private $spurghi;

    /**
     * @var boolean
     *
     * @ORM\Column(name="bonifiche", type="boolean", nullable=true)
     */
    private $bonifiche;

    /**
     * @var boolean
     *
     * @ORM\Column(name="rottamazione", type="boolean", nullable=true)
     */
    private $rottamazione;

    /**
     * @var boolean
     *
     * @ORM\Column(name="raee", type="boolean", nullable=true)
     */
    private $raee;

    /**
     * @var boolean
     *
     * @ORM\Column(name="olio_minerale", type="boolean", nullable=true)
     */
    private $olio_minerale;

    /**
     * @var boolean
     *
     * @ORM\Column(name="olio_vegetale", type="boolean", nullable=true)
     */
    private $olio_vegetale;

    /**
     * @var boolean
     *
     * @ORM\Column(name="conto_proprio", type="boolean", nullable=true)
     */
    private $conto_proprio;

    /**
     * @var string
     *
     * @ORM\Column(name="sezione", type="string", length=2, nullable=true)
     */
    private $sezione;

    /**
     * @var string
     *
     * @ORM\Column(name="numero_iscrizione", type="string", length=6, nullable=true)
     */
    private $numero_iscrizione;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="detail_update_at", type="datetime")
     */
    private $detail_update_at;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="list_update_at", type="datetime")
     */
    private $list_update_at;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     * 
     * @ORM\ManyToMany(targetEntity="ES\CerMapBundle\Entity\Mps\Mps")
     * @ORM\JoinTable(name="op_showroom_mps",
     *      joinColumns={@ORM\JoinColumn(name="showroom_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="mps_id", referencedColumnName="id")}
     *      )
     */
    private $mps;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     * 
     * @ORM\ManyToMany(targetEntity="ES\CerMapBundle\Entity\Recuperabili\SmaltimentoRecupero")
     * @ORM\JoinTable(name="op_showroom_rd",
     *      joinColumns={@ORM\JoinColumn(name="showroom_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="rd_id", referencedColumnName="id")}
     *      )
     */
    private $rd;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     * 
     * @ORM\ManyToMany(targetEntity="ES\CerMapBundle\Entity\Cer\Cer")
     * @ORM\JoinTable(name="op_showroom_cer",
     *      joinColumns={@ORM\JoinColumn(name="showroom_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="cer_id", referencedColumnName="id")}
     *      )
     */
    private $cer;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     * 
     * @ORM\ManyToMany(targetEntity="ES\CerMapBundle\Entity\Cer\Cer")
     * @ORM\JoinTable(name="op_showroom_cer_cp",
     *      joinColumns={@ORM\JoinColumn(name="showroom_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="cer_id", referencedColumnName="id")}
     *      )
     */
    private $cer_cp;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     * 
     * @ORM\ManyToMany(targetEntity="ES\CerMapBundle\Entity\Cer\Cer")
     * @ORM\JoinTable(name="op_showroom_cer_trattati",
     *      joinColumns={@ORM\JoinColumn(name="showroom_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="cer_id", referencedColumnName="id")}
     *      )
     */
    private $cer_trattati;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     * 
     * @ORM\ManyToMany(targetEntity="ES\CerMapBundle\Entity\Recuperabili\Rifiuto")
     * @ORM\JoinTable(name="op_showroom_tipologie",
     *      joinColumns={@ORM\JoinColumn(name="showroom_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="cer_id", referencedColumnName="id")}
     *      )
     */
    private $tipologie;
    
    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     * 
     * @ORM\ManyToMany(targetEntity="ES\OperatoriBundle\Entity\Servizi\Servizio")
     * @ORM\JoinTable(name="op_showroom_servizi",
     *      joinColumns={@ORM\JoinColumn(name="showroom_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="servizio_id", referencedColumnName="id")}
     *      )
     */
    private $servizi_sr;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     * 
     * @ORM\OneToMany(targetEntity="ShowroomCategoria", mappedBy="showroom", cascade={"all"})
     */
    private $categorie;

    /**
     * @var string
     *
     * @ORM\Column(name="comune_testuale", type="string", length=255, nullable=true)
     */
    private $comune_testuale;

    /**
     * @var integer
     *
     * @ORM\Column(name="click_telefono", type="integer")
     */
    private $click_telefono;

    /**
     * @var integer
     *
     * @ORM\Column(name="click_cellulare", type="integer")
     */
    private $click_cellulare;

    /**
     * @var integer
     *
     * @ORM\Column(name="click_fax", type="integer")
     */
    private $click_fax;

    /**
     * @var integer
     *
     * @ORM\Column(name="click_email", type="integer")
     */
    private $click_email;

    /**
     * @var integer
     *
     * @ORM\Column(name="click_sito", type="integer")
     */
    private $click_sito;

    /**
     * @var integer
     *
     * @ORM\Column(name="genera_tag", type="boolean")
     */
    private $genera_tag;

    /**
     * @var ArrayCollection $tags
     *
     * @ORM\OneToMany(targetEntity="Tag", mappedBy="profilo", cascade={"all"})
     */
    protected $tags;

    /**
     * Constructor
     */
    public function __construct() {
        $this->rd = new \Doctrine\Common\Collections\ArrayCollection();
        $this->mps = new \Doctrine\Common\Collections\ArrayCollection();
        $this->cer = new \Doctrine\Common\Collections\ArrayCollection();
        $this->cer_cp = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tipologie = new \Doctrine\Common\Collections\ArrayCollection();
        $this->categorie = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
        $this->servizi_sr = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set ragione_sociale
     *
     * @param string $ragioneSociale
     * @return Showroom
     */
    public function setRagioneSociale($ragioneSociale) {
        $this->ragione_sociale = $ragioneSociale;

        return $this;
    }

    /**
     * Get ragione_sociale
     *
     * @return string 
     */
    public function getRagioneSociale() {
        return $this->ragione_sociale;
    }

    public function getComuneTestuale() {
        return $this->comune_testuale;
    }

    public function setComuneTestuale($comune_testuale) {
        $this->comune_testuale = $comune_testuale;
    }

    /**
     * Set logo
     *
     * @param string $logo
     * @return Showroom
     */
    public function setLogo($logo) {
        $this->logo = $logo;

        return $this;
    }

    /**
     * Get descrizione_attivita
     *
     * @return string 
     */
    public function getLogo() {
        return $this->logo;
    }

    /**
     * Set descrizione_attivita
     *
     * @param string $descrizioneAttivita
     * @return Showroom
     */
    public function setDescrizioneAttivita($descrizioneAttivita) {
        $this->descrizione_attivita = $descrizioneAttivita;

        return $this;
    }

    /**
     * Get descrizione_attivita
     *
     * @return string 
     */
    public function getDescrizioneAttivita() {
        return $this->descrizione_attivita;
    }

    /**
     * Set descrizione
     *
     * @param string $descrizione
     * @return Showroom
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
     * Set partita_iva
     *
     * @param string $partitaIva
     * @return Showroom
     */
    public function setPartitaIva($partitaIva) {
        $this->partita_iva = $partitaIva;

        return $this;
    }

    /**
     * Get partita_iva
     *
     * @return string 
     */
    public function getPartitaIva() {
        return $this->partita_iva;
    }

    /**
     * Set codice_fiscale
     *
     * @param string $codiceFiscale
     * @return Showroom
     */
    public function setCodiceFiscale($codiceFiscale) {
        $this->codice_fiscale = $codiceFiscale;

        return $this;
    }

    /**
     * Get codice_fiscale
     *
     * @return string 
     */
    public function getCodiceFiscale() {
        return $this->codice_fiscale;
    }

    /**
     * Set codice_rae
     *
     * @param string $codiceRae
     * @return Showroom
     */
    public function setCodiceRae($codiceRae) {
        $this->codice_rae = $codiceRae;

        return $this;
    }

    /**
     * Get codice_rae
     *
     * @return string 
     */
    public function getCodiceRae() {
        return $this->codice_rae;
    }

    /**
     * Set sito
     *
     * @param string $sito
     * @return Showroom
     */
    public function setSito($sito) {
        $this->sito = $sito;

        return $this;
    }

    /**
     * Get sito
     *
     * @return string 
     */
    public function getSito() {
        return $this->sito;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Showroom
     */
    public function setEmail($email) {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Set email_pec
     *
     * @param string $email_pec
     * @return Showroom
     */
    public function setEmailPec($email_pec) {
        $this->email_pec = $email_pec;

        return $this;
    }

    /**
     * Get email_pec
     *
     * @return string 
     */
    public function getEmailPec() {
        return $this->email_pec;
    }

    /**
     * Set telefono
     *
     * @param string $telefono
     * @return Showroom
     */
    public function setTelefono($telefono) {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono
     *
     * @return string 
     */
    public function getTelefono() {
        return $this->telefono;
    }

    /**
     * Set fax
     *
     * @param string $fax
     * @return Showroom
     */
    public function setFax($fax) {
        $this->fax = $fax;

        return $this;
    }

    /**
     * Get fax
     *
     * @return string 
     */
    public function getFax() {
        return $this->fax;
    }

    /**
     * Set cellulare
     *
     * @param string $cellulare
     * @return Showroom
     */
    public function setCellulare($cellulare) {
        $this->cellulare = $cellulare;

        return $this;
    }

    /**
     * Get cellulare
     *
     * @return string 
     */
    public function getCellulare() {
        return $this->cellulare;
    }

    /**
     * Set has_cer
     *
     * @param boolean $hasCer
     * @return Showroom
     */
    public function setHasCer($hasCer) {
        $this->has_cer = $hasCer;

        return $this;
    }

    /**
     * Get has_cer
     *
     * @return boolean 
     */
    public function getHasCer() {
        return $this->has_cer;
    }

    /**
     * Set has_tipologie
     *
     * @param boolean $hasTipologie
     * @return Showroom
     */
    public function setHasTipologie($hasTipologie) {
        $this->has_tipologie = $hasTipologie;

        return $this;
    }

    /**
     * Get has_tipologie
     *
     * @return boolean 
     */
    public function getHasTipologie() {
        return $this->has_tipologie;
    }

    /**
     * Set has_cer_cp
     *
     * @param boolean $hasCerCp
     * @return Showroom
     */
    public function setHasCerCp($hasCerCp) {
        $this->has_cer_cp = $hasCerCp;

        return $this;
    }

    /**
     * Get has_cer_cp
     *
     * @return boolean 
     */
    public function getHasCerCp() {
        return $this->has_cer_cp;
    }

    /**
     * Set has_cer_trattati
     *
     * @param boolean $hasCerTrattati
     * @return Showroom
     */
    public function setHasCerTrattati($hasCerTrattati) {
        $this->has_cer_trattati = $hasCerTrattati;

        return $this;
    }

    /**
     * Get has_cer_trattati
     *
     * @return boolean 
     */
    public function getHasCerTrattati() {
        return $this->has_cer_trattati;
    }

    /**
     * Set has_trattati
     *
     * @param boolean $hasServizi
     * @return Showroom
     */
    public function setHasServizi($hasServizi) {
        $this->has_trattati = $hasServizi;

        return $this;
    }

    /**
     * Get has_trattati
     *
     * @return boolean 
     */
    public function getHasServizi() {
        return $this->has_servizi;
    }

    /**
     * Set anga
     *
     * @param array $anga
     * @return Showroom
     */
    public function setAnga($anga) {
        $this->anga = $anga;

        return $this;
    }

    /**
     * Get anga
     *
     * @return array 
     */
    public function getAnga() {
        return $this->anga;
    }

    /**
     * Set impianto
     *
     * @param boolean $impianto
     * @return Showroom
     */
    public function setImpianto($impianto) {
        $this->impianto = $impianto;

        return $this;
    }

    /**
     * Get impianto
     *
     * @return boolean 
     */
    public function getImpianto() {
        return $this->impianto;
    }

    /**
     * Set attivita_principale
     *
     * @param string $attivitaPrincipale
     * @return Richiesta
     */
    public function setAttivitaPrincipale($attivitaPrincipale) {
        $this->attivita_principale = $attivitaPrincipale;

        return $this;
    }

    /**
     * Get attivita_principale
     *
     * @return string 
     */
    public function getAttivitaPrincipale() {
        return $this->attivita_principale;
    }

    /**
     * Set discarica
     *
     * @param boolean $discarica
     * @return Showroom
     */
    public function setDiscarica($discarica) {
        $this->discarica = $discarica;

        return $this;
    }

    /**
     * Get discarica
     *
     * @return boolean 
     */
    public function getDiscarica() {
        return $this->discarica;
    }

    /**
     * Set raccoglitore
     *
     * @param boolean $raccoglitore
     * @return Showroom
     */
    public function setRaccoglitore($raccoglitore) {
        $this->raccoglitore = $raccoglitore;

        return $this;
    }

    /**
     * Get raccoglitore
     *
     * @return boolean 
     */
    public function getRaccoglitore() {
        return $this->raccoglitore;
    }

    /**
     * Set trasportatore
     *
     * @param boolean $trasportatore
     * @return Showroom
     */
    public function setTrasportatore($trasportatore) {
        $this->trasportatore = $trasportatore;

        return $this;
    }

    /**
     * Get trasportatore
     *
     * @return boolean 
     */
    public function getTrasportatore() {
        return $this->trasportatore;
    }

    /**
     * Set servizi
     *
     * @param boolean $servizi
     * @return Showroom
     */
    public function setServizi($servizi) {
        $this->servizi = $servizi;

        return $this;
    }

    /**
     * Get servizi
     *
     * @return boolean 
     */
    public function getServizi() {
        return $this->servizi;
    }

    /**
     * Set laboratorio
     *
     * @param boolean $laboratorio
     * @return Showroom
     */
    public function setLaboratorio($laboratorio) {
        $this->laboratorio = $laboratorio;

        return $this;
    }

    /**
     * Get laboratorio
     *
     * @return boolean 
     */
    public function getLaboratorio() {
        return $this->laboratorio;
    }

    /**
     * Set sezione
     *
     * @param string $sezione
     * @return Showroom
     */
    public function setSezione($sezione) {
        $this->sezione = $sezione;

        return $this;
    }

    /**
     * Get sezione
     *
     * @return string 
     */
    public function getSezione() {
        return $this->sezione;
    }

    /**
     * Set numero_iscrizione
     *
     * @param string $numeroIscrizione
     * @return Showroom
     */
    public function setNumeroIscrizione($numeroIscrizione) {
        $this->numero_iscrizione = $numeroIscrizione;

        return $this;
    }

    /**
     * Get numero_iscrizione
     *
     * @return string 
     */
    public function getNumeroIscrizione() {
        return $this->numero_iscrizione;
    }

    /**
     * Set detail_update_at
     *
     * @param \DateTime $detailUpdateAt
     * @return Showroom
     */
    public function setDetailUpdateAt($detailUpdateAt) {
        $this->detail_update_at = $detailUpdateAt;

        return $this;
    }

    /**
     * Get detail_update_at
     *
     * @return \DateTime 
     */
    public function getDetailUpdateAt() {
        return $this->detail_update_at;
    }

    /**
     * Set list_update_at
     *
     * @param \DateTime $listUpdateAt
     * @return Showroom
     */
    public function setListUpdateAt($listUpdateAt) {
        $this->list_update_at = $listUpdateAt;

        return $this;
    }

    /**
     * Get list_update_at
     *
     * @return \DateTime 
     */
    public function getListUpdateAt() {
        return $this->list_update_at;
    }

    private $mps_id = array();

    /**
     * Add mps
     *
     * @param \ES\CerMapBundle\Entity\Mps\Mps $mps
     * @return Showroom
     */
    public function addMps($mps) {
        if ($mps instanceof \ES\CerMapBundle\Entity\Mps\Mps) {
            if (!in_array($mps->getId(), $this->mps_id)) {
                $this->mps[] = $mps;
                $this->mps_id[] = $mps->getId();
            }
        }

        return $this;
    }

    /**
     * Remove mps
     *
     * @param \ES\CerMapBundle\Entity\Mps\Mps $mps
     */
    public function removeMps(\ES\CerMapBundle\Entity\Mps\Mps $mps) {
        $this->mps->removeElement($mps);
    }

    /**
     * Get mps
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMps() {
        return $this->mps;
    }

    private $rd_id = array();

    /**
     * Add rd
     *
     * @param \ES\CerMapBundle\Entity\Recuperabili\SmaltimentoRecupero $rd
     * @return Showroom
     */
    public function addRd($rd) {
        if ($rd instanceof \ES\CerMapBundle\Entity\Recuperabili\SmaltimentoRecupero) {
            if (!in_array($rd->getId(), $this->rd_id)) {
                $this->rd[] = $rd;
                $this->rd_id[] = $rd->getId();
            }
        }

        return $this;
    }

    /**
     * Remove rd
     *
     * @param \ES\CerMapBundle\Entity\Recuperabili\SmaltimentoRecupero $rd
     */
    public function removeRd(\ES\CerMapBundle\Entity\Recuperabili\SmaltimentoRecupero $rd) {
        $this->rd->removeElement($rd);
    }

    /**
     * Get rd
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRd() {
        return $this->rd;
    }

    /**
     * Add cer
     *
     * @param \ES\CerMapBundle\Entity\Cer\Cer $cer
     * @return Showroom
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
     * Get cer
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCer() {
        return $this->cer;
    }

    /**
     * Add cer_cp
     *
     * @param \ES\CerMapBundle\Entity\Cer\Cer $cerCp
     * @return Showroom
     */
    public function addCerCp(\ES\CerMapBundle\Entity\Cer\Cer $cerCp) {
        $this->cer_cp[] = $cerCp;

        return $this;
    }

    /**
     * Remove cer_cp
     *
     * @param \ES\CerMapBundle\Entity\Cer\Cer $cerCp
     */
    public function removeCerCp(\ES\CerMapBundle\Entity\Cer\Cer $cerCp) {
        $this->cer_cp->removeElement($cerCp);
    }

    /**
     * Get cer_cp
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCerCp() {
        return $this->cer_cp;
    }

    /**
     * Add cer_trattati
     *
     * @param \ES\CerMapBundle\Entity\Cer\Cer $cerTrattati
     * @return Showroom
     */
    public function addcerTrattati(\ES\CerMapBundle\Entity\Cer\Cer $cerTrattati) {
        $this->cer_trattati[] = $cerTrattati;

        return $this;
    }

    /**
     * Remove cer_trattati
     *
     * @param \ES\CerMapBundle\Entity\Cer\Cer $cerTrattati
     */
    public function removecerTrattati(\ES\CerMapBundle\Entity\Cer\Cer $cerTrattati) {
        $this->cer_trattati->removeElement($cerTrattati);
    }

    /**
     * Get cer_trattati
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getcerTrattati() {
        return $this->cer_trattati;
    }

    private $tipologia_id = array();

    /**
     * Add tipologie
     *
     * @param \ES\CerMapBundle\Entity\Recuperabili\Rifiuto $tipologie
     * @return Showroom
     */
    public function addTipologie($tipologie) {
        if ($tipologie instanceof \ES\CerMapBundle\Entity\Recuperabili\Rifiuto) {
            if (!in_array($tipologie->getId(), $this->tipologia_id)) {
                $this->tipologie[] = $tipologie;
                $this->tipologia_id[] = $tipologie->getId();
            }
        }

        return $this;
    }

    /**
     * Remove tipologie
     *
     * @param \ES\CerMapBundle\Entity\Recuperabili\Rifiuto $tipologie
     */
    public function removeTipologie(\ES\CerMapBundle\Entity\Recuperabili\Rifiuto $tipologie) {
        $this->tipologie->removeElement($tipologie);
    }

    /**
     * Get tipologie
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTipologie() {
        return $this->tipologie;
    }

    /**
     * Add categorie
     *
     * @param \ES\OperatoriBundle\Entity\ShowroomCategoria $categorie
     * @return Showroom
     */
    public function addCategorie(\ES\OperatoriBundle\Entity\ShowroomCategoria $categorie) {
        $this->categorie[] = $categorie;

        return $this;
    }

    /**
     * Remove categorie
     *
     * @param \ES\OperatoriBundle\Entity\ShowroomCategoria $categorie
     */
    public function removeCategorie(\ES\OperatoriBundle\Entity\ShowroomCategoria $categorie) {
        $this->categorie->removeElement($categorie);
    }

    /**
     * Get categorie
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCategorie() {
        return $this->categorie;
    }

    /**
     * Add servizi
     *
     * @param \ES\OperatoriBundle\Entity\Servizi\Servizio $servizi
     * @return Showroom
     */
    public function addServiziSr(\ES\OperatoriBundle\Entity\Servizi\Servizio $servizi) {
        $this->servizi_sr[] = $servizi;

        return $this;
    }

    /**
     * Remove servizi
     *
     * @param \ES\OperatoriBundle\Entity\Servizi\Servizio $servizi
     */
    public function removeServiziSr(\ES\OperatoriBundle\Entity\Servizi\Servizio $servizi) {
        $this->servizi_sr->removeElement($servizi);
    }

    /**
     * Get servizi
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getServiziSr() {
        return $this->servizi_sr;
    }

    /**
     * Set user
     *
     * @param \ES\UserBundle\Entity\User $user
     * @return Showroom
     */
    public function setUser(\ES\UserBundle\Entity\User $user = null) {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \ES\UserBundle\Entity\User 
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist() {
        $this->latitudinerad = deg2rad(floatval($this->latitudine));
        $this->longitudinerad = deg2rad(floatval($this->longitudine));
        $this->click_cellulare = 0;
        $this->click_fax = 0;
        $this->click_telefono = 0;
        $this->click_email = 0;
        $this->click_sito = 0;
        $this->genera_tag = true;
    }

    /**
     * @ORM\PreUpdate
     */
    public function preUpdate() {
        $this->latitudinerad = deg2rad($this->latitudine);
        $this->longitudinerad = deg2rad($this->longitudine);
    }

    /**
     * Set click_telefono
     *
     * @param integer $clickTelefono
     * @return Showroom
     */
    public function setClickTelefono($clickTelefono) {
        $this->click_telefono = $clickTelefono;

        return $this;
    }

    /**
     * Get click_telefono
     *
     * @return integer 
     */
    public function getClickTelefono() {
        return $this->click_telefono;
    }

    /**
     * Set click_cellulare
     *
     * @param integer $clickCellulare
     * @return Showroom
     */
    public function setClickCellulare($clickCellulare) {
        $this->click_cellulare = $clickCellulare;

        return $this;
    }

    /**
     * Get click_cellulare
     *
     * @return integer 
     */
    public function getClickCellulare() {
        return $this->click_cellulare;
    }

    /**
     * Set click_fax
     *
     * @param integer $clickFax
     * @return Showroom
     */
    public function setClickFax($clickFax) {
        $this->click_fax = $clickFax;

        return $this;
    }

    /**
     * Get click_fax
     *
     * @return integer 
     */
    public function getClickFax() {
        return $this->click_fax;
    }

    /**
     * Set click_email
     *
     * @param integer $clickEmail
     * @return Showroom
     */
    public function setClickEmail($clickEmail) {
        $this->click_email = $clickEmail;

        return $this;
    }

    /**
     * Get click_email
     *
     * @return integer 
     */
    public function getClickEmail() {
        return $this->click_email;
    }

    /**
     * Set click_sito
     *
     * @param integer $clickSito
     * @return Showroom
     */
    public function setClickSito($clickSito) {
        $this->click_sito = $clickSito;

        return $this;
    }

    /**
     * Get click_sito
     *
     * @return integer 
     */
    public function getClickSito() {
        return $this->click_sito;
    }

    /**
     * Set click_sito
     *
     * @param integer $generaTag
     * @return Showroom
     */
    public function setGeneraTag($generaTag) {
        $this->genera_tag = $generaTag;

        return $this;
    }

    /**
     * Get click_sito
     *
     * @return integer 
     */
    public function getGeneraTag() {
        return $this->genera_tag;
    }

    /**
     * Add foto
     *
     * @param \ES\FotoBundle\Entity\Foto $foto
     * @return Showroom
     */
    public function addFoto(\ES\FotoBundle\Entity\Foto $foto) {
        $this->foto[] = $foto;

        return $this;
    }

    /**
     * Remove foto
     *
     * @param \ES\FotoBundle\Entity\Foto $foto
     */
    public function removeFoto(\ES\FotoBundle\Entity\Foto $foto) {
        $this->foto->removeElement($foto);
    }

    /**
     * Get foto
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFoto() {
        return $this->foto;
    }
    
    /**
     * Set demolizioni
     *
     * @param boolean $demolizioni
     * @return Showroom
     */
    public function setDemolizioni($demolizioni)
    {
        $this->demolizioni = $demolizioni;
    
        return $this;
    }

    /**
     * Get demolizioni
     *
     * @return boolean 
     */
    public function getDemolizioni()
    {
        return $this->demolizioni;
    }

    /**
     * Set spurghi
     *
     * @param boolean $spurghi
     * @return Showroom
     */
    public function setSpurghi($spurghi)
    {
        $this->spurghi = $spurghi;
    
        return $this;
    }

    /**
     * Get spurghi
     *
     * @return boolean 
     */
    public function getSpurghi()
    {
        return $this->spurghi;
    }

    /**
     * Set bonifiche
     *
     * @param boolean $bonifiche
     * @return Showroom
     */
    public function setBonifiche($bonifiche)
    {
        $this->bonifiche = $bonifiche;
    
        return $this;
    }

    /**
     * Get bonifiche
     *
     * @return boolean 
     */
    public function getBonifiche()
    {
        return $this->bonifiche;
    }

    /**
     * Set rottamazione
     *
     * @param boolean $rottamazione
     * @return Showroom
     */
    public function setRottamazione($rottamazione)
    {
        $this->rottamazione = $rottamazione;
    
        return $this;
    }

    /**
     * Get rottamazione
     *
     * @return boolean 
     */
    public function getRottamazione()
    {
        return $this->rottamazione;
    }

    /**
     * Set raee
     *
     * @param boolean $raee
     * @return Showroom
     */
    public function setRaee($raee)
    {
        $this->raee = $raee;
    
        return $this;
    }

    /**
     * Get raee
     *
     * @return boolean 
     */
    public function getRaee()
    {
        return $this->raee;
    }

    /**
     * Set olio_minerale
     *
     * @param boolean $olioMinerale
     * @return Showroom
     */
    public function setOlioMinerale($olioMinerale)
    {
        $this->olio_minerale = $olioMinerale;
    
        return $this;
    }

    /**
     * Get olio_minerale
     *
     * @return boolean 
     */
    public function getOlioMinerale()
    {
        return $this->olio_minerale;
    }

    /**
     * Set olio_vegetale
     *
     * @param boolean $olioVegetale
     * @return Showroom
     */
    public function setOlioVegetale($olioVegetale)
    {
        $this->olio_vegetale = $olioVegetale;
    
        return $this;
    }

    /**
     * Get olio_vegetale
     *
     * @return boolean 
     */
    public function getOlioVegetale()
    {
        return $this->olio_vegetale;
    }

    /**
     * Set conto_proprio
     *
     * @param boolean $contoProprio
     * @return Showroom
     */
    public function setContoProprio($contoProprio)
    {
        $this->conto_proprio = $contoProprio;
    
        return $this;
    }

    /**
     * Get conto_proprio
     *
     * @return boolean 
     */
    public function getContoProprio()
    {
        return $this->conto_proprio;
    }

    /*
     * PORTA IN TRAIT
     */

}
