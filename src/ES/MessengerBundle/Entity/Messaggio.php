<?php

namespace ES\MessengerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ES\MessengerBundle\Entity\Messaggio
 *
 * @ORM\Table(name="msg_rdi")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="ES\MessengerBundle\Entity\MessaggioRepository")
 */
class Messaggio extends MessaggioBase {

    /**
     * @var string $url
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=true)
     */
    private $url;

    public function __construct($utente = null) {
        parent::__construct();
        if ($utente) {
            $zona = new \DateTimeZone('Europe/Rome');
            $now = new \DateTime('now', $zona);
            $this->setFromUtente($utente);
            $this->setPreSlug(md5('mail_' . $now->getTimestamp() . '_' . $this->getFromUtente()->getId()));
        }
    }

    public function getUrl() {
        return $this->url;
    }

    public function setUrl($url) {
        $this->url = $url;
    }

    public function riepilogo() {
        return $this->getTesto().($this->url ? "\n\n{$this->url}" : '');
    }

    public function getTipo() {
        return 'rdi';
    }

    public function getAnteprima() {
        return \ES\WebBundle\Functions\Funzioni::tronca($this->getTesto(), 80);
    }

    /**
     * @ORM\PrePersist 
     */
    public function prePersist() {
        $slug = $this->getPreSlug();
        parent::prePersist();
        $this->setPreSlug($slug);
    }

}