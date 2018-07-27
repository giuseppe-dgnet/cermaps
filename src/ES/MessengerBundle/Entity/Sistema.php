<?php

namespace ES\MessengerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ES\MessengerBundle\Entity\Messaggio
 *
 * @ORM\Table(name="msg_system")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="ES\MessengerBundle\Entity\SistemaRepository")
 */
class Sistema extends MessaggioBase {

    /**
     * @var integer $messaggio
     *
     * @ORM\Column(name="twig", type="string", length=64)
     */
    private $twig;

    /**
     * @var integer $messaggio
     *
     * @ORM\Column(name="obbligatorio", type="boolean", nullable=true)
     */
    private $obbligatorio;

    /**
     *
     * @var \Symfony\Bundle\FrameworkBundle\Controller\Controller 
     */
    private $controller;

    /**
     *
     * @var array 
     */
    private $params;

    public function __construct(\ES\UserBundle\Entity\User $system, $twig, $params, \Symfony\Bundle\FrameworkBundle\Controller\Controller $controller) {
        parent::__construct();
        $this->setFromUtente($system);
        $zona = new \DateTimeZone('Europe/Rome');
        $now = new \DateTime('now', $zona);
        $this->setPreSlug(md5('mail_' . $now->getTimestamp() . '_' . $system->getId()));
        $this->controller = $controller;
        $this->params = $params;
        $this->twig = $twig;
        $obbligatorio = false;
    }

    public function getTwig() {
        return $this->twig;
    }

    public function setTwig($twig) {
        $this->twig = $twig;
    }

    public function getObbligatorio() {
        return $this->obbligatorio;
    }

    public function setObbligatorio($obbligatorio) {
        $this->obbligatorio = $obbligatorio;
    }

    public function riepilogo() {
        return $this->getTesto();
    }

    public function getTipo() {
        return 'system';
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
        $this->setTesto($this->controller->renderView("EcoSeekrMessengerBundle:system:{$this->twig}.txt.twig", $this->params));
    }

    /**
     * @ORM\PostPersist 
     */
    public function postPersit() {
        // Email inviata a chi riceve l'invito
        foreach ($this->getDestinatari() as $destinatario) {
            $user = $destinatario->getDestinatario();
            if ($user != $this->getFromUtente()) {
                $message = \Swift_Message::newInstance()
                        ->setSubject($this->getSubject())
                        ->setFrom($this->controller->getContainer()->getParameter('email_robot'))
                        ->setTo($user->getEmail())
                        ->setReplyTo($this->controller->getContainer()->getParameter('email_robot'), 'EcoSeekr Italia s.r.l.')
                        ->setBody($this->getTesto())
                ;
                $message->getHeaders()->addTextHeader('X-Mailer', 'PHP v' . phpversion());
                $message->getHeaders()->get('Message-ID')->setId('ES-' . md5(time()) . rand(100, 999) . '@ecoseekr.com');
                $this->controller->get('mailer')->send($message);
            }
        }
    }

    public function getReplyAvailable() {
        return false;
    }

}