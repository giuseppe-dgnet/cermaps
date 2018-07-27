<?php

namespace ES\MessengerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use ES\MessengerBundle\Entity\Risposta;
use ES\MessengerBundle\Entity\Allegato;
use ES\MessengerBundle\Entity\Messaggio;
use ES\WebBundle\Functions\Funzioni;
use ES\ACLBundle\Entity\User;

class MessengerControllerBase extends Controller {

    /**
     * @return \Doctrine\ORM\EntityManager 
     */
    public function getEm() {
        return $this->getDoctrine()->getEntityManager();
    }

    /**
     * Recupera Azienda o Professionista
     * 
     * @return \ES\ACLBundle\Entity\User
     */
    protected function getUtente($anonimo = false) {
        $user = $this->getUser();
        if ($anonimo && !$user) {
            return null;
        }
        if (!$anonimo && !$user) {
            throw $this->createNotFoundException('Utente non riconosciuto. Accedere a Ecoseekr per effettuare modifiche allo ShowRoom');
        }
//        $em = $this->getEm();
//        $_user = $em->getRepository('ES\ACLBundle\Entity\User');
//        $user = $_user->find($user['id']);
        return $user;
    }

    protected function generateCerchie($forza = false) {
        $oggi = 'C' . date('Ymd');
        $sessione = $this->getRequest()->getSession()->get('check_cerchie', 'C20000101');
        if (true || $forza || $oggi != $sessione) {
            $this->getRequest()->getSession()->set('check_cerchie', $oggi);
            $user = $this->getUtente();
            $utente = $user->serialize(true);
            $em = $this->getEm();
            try {
                $em->beginTransaction();
                if (count($user->getCerchie()) == 0) {
                    $azienda = null;
                    switch ($utente['tipo']) {
                        case 'anga':
                            $this->cerateCerchia('Conferimento', $user, false, $em);
                        case 'showroom':
                            $azienda = $this->cerateCerchia('Dipendenti', $user, true, $em);
                            $this->cerateCerchia('Fornitori', $user, false, $em);
                            $this->cerateCerchia('Clienti', $user, false, $em);
                            break;
                        case 'profilo':
                            if ($utente['azienda_id']) {
                                $azienda = $this->cerateCerchia('Colleghi', $user, true, $em);
                            }
                            $this->cerateCerchia('Contatti', $user, false, $em);
                            break;
                        case 'studente':
                            $this->cerateCerchia('Università', $user, false, $em);
                            $this->cerateCerchia('Contatti', $user, false, $em);
                            break;
                    }
                } else {
                    switch ($utente['tipo']) {
                        case 'anga':
                        case 'showroom':
                            $azienda = $this->cercaCerchia('Dipendenti', $user);
                            break;
                        case 'profilo':
                            $azienda = $this->cercaCerchia('Colleghi', $user);
                            break;
                        case 'studente':
                            break;
                    }
                }
                if (isset($azienda)) {
                    $_cerchia = $em->getRepository('ES\MessengerBundle\Entity\Cerchia');
                    $_cerchia->cancellaContattiCerchia($azienda);
                    if ($utente['azienda_id']) {
                        $_rubrica = $em->getRepository('ES\MessengerBundle\Entity\Rubrica');
                        $_azienda = $em->getRepository('ES\AziendaBundle\Entity\Azienda');
                        $my_azienda = $_azienda->find($utente['azienda_id']);
                        foreach ($my_azienda->getDipendenti() as $dipendente) {
                            $duser = $dipendente->getUtente();
                            if ($duser->getId() != $user->getId()) {
                                $contatto = $_rubrica->cercaContatto($user, $duser);
                                if (!$contatto) {
                                    $contatto = new \ES\MessengerBundle\Entity\Rubrica();
                                    $contatto->setProprietario($user);
                                    $contatto->setContatto($duser);
                                    $contatto->setPartner(false);
                                    $em->persist($contatto);
                                    $em->flush();
                                    $user->addRubrica($contatto);
                                }
                                $azienda->addContatti($contatto);
                            }
                        }
                        $em->persist($azienda);
                        $em->flush();
                    }
                }
                $em->commit();
            } catch (\Exception $e) {
                $this->getRequest()->getSession()->set('check_cerchie', 'C20000101');
                $em->rollback();
                throw $e;
            }
        }
    }

    protected function cerateCerchia($nome_cerchia, $proprietario, $predefinito, \Doctrine\ORM\EntityManager $em) {
        $cerchia = new \ES\MessengerBundle\Entity\Cerchia();
        $cerchia->setCerchia($nome_cerchia);
        $cerchia->setProprietario($proprietario);
        $cerchia->setPredefinito($predefinito);
        $em->persist($cerchia);
        $em->flush();
        $proprietario->addCerchie($cerchia);
        return $cerchia;
    }

    private function cercaCerchia($nome_cerchia, \ES\ACLBundle\Entity\User $proprietario) {
        foreach ($proprietario->getCerchie() as $cerchia) {
            if ($cerchia->getCerchia() == $nome_cerchia) {
                return $cerchia;
            }
        }
        return null;
    }

    protected function getRubrica(\ES\ACLBundle\Entity\User $utente, $preferiti = false) {
        $output = array(
            'numero_contatti' => 0,
            'contatti_in_rubrica' => array(),
            'small-azienda' => array(),
            'small-pro' => array(),
            'small-privato' => array(),
        );
        /*
        if ($preferiti) {
            $em = $this->getEm();
            $_rubrica = $em->getRepository('ES\MessengerBundle\Entity\Rubrica');
            $rubrica = $_rubrica->filtra_preferiti($utente);
            $collection = $rubrica;
            if (count($rubrica) == 0) {
                $preferiti = false;
            }
        }

        if (!$preferiti) {
            $collection = $utente->getRubrica();
        }
         */
        $collection = $utente->getRubrica();
        foreach ($collection as $contatto) {
            $output['contatti_in_rubrica'][] = $contatto->getContatto()->getId();
            if ($preferiti && $contatto->getPreferito()) {
                $output["numero_contatti"]++;
                $c = $contatto->getContatto()->serialize();
                if ($c['tipo'] == 'showroom') {
                    $output["small-azienda"][] = $c;
                } else if ($c['tipo'] == 'profilo') {
                    $output["small-pro"][] = $c;
                } else {
                    $output["small-privato"][] = $c;
                }
            }
        }
        if ($output["numero_contatti"] == 0) {
            foreach ($collection as $contatto) {
                $output["numero_contatti"]++;
                $c = $contatto->getContatto()->serialize();
                if ($c['tipo'] == 'showroom') {
                    $output["small-azienda"][] = $c;
                } else if ($c['tipo'] == 'profilo') {
                    $output["small-pro"][] = $c;
                } else {
                    $output["small-privato"][] = $c;
                }
                if($output["numero_contatti"] == 5) {
                    break;
                }
            }
        }
        $output['contatti_in_rubrica'] = json_encode($output['contatti_in_rubrica'], JSON_NUMERIC_CHECK);
        return $output;
    }

    protected function inviaNotifica(\ES\ACLBundle\Entity\User $mittente, \ES\MessengerBundle\Entity\Destinatario $destinatario, \ES\MessengerBundle\Entity\MessaggioBase $risposta) {
        // Email inviata a chi riceve la richiesta

        $email = $this->container->getParameter('running_mode') == 'prod' &&
                $mittente->getShowroom() &&
                $mittente->getShowroom()->getReferente()->getEmailEcoseekr() ?
                $mittente->getShowroom()->getReferente()->getEmailEcoseekr() : $this->container->getParameter('email_messenger');


        $message = \Swift_Message::newInstance()
                ->setSubject($risposta->getSubject())
                ->setFrom($this->container->getParameter('email_messenger'))
                ->setTo($destinatario->getDestinatario()->getEmailMessenger())
                ->setReplyTo($email, $mittente->getDenominazioneCompleta() . ' - ' . $risposta->getConversazione()->getSlug())
                ->setBody($this->renderView('EcoSeekrMessengerBundle::notificaRisposta.txt.twig', array('destinatario' => $destinatario, 'risposta' => $risposta->riepilogo())))
        ;
        $message->getHeaders()->addTextHeader('X-Mailer', 'PHP v' . phpversion());
        $message->getHeaders()->get('Message-ID')->setId('ES-' . md5(time()) . rand(100, 999) . '@ecoseekr.com');
        $message->getHeaders()->addTextHeader('Slug', $risposta->getConversazione()->getSlug());

        if ($risposta->getAllegati()->count() > 0) {
            set_time_limit($risposta->getAllegati()->count() * 60);
        }
        foreach ($risposta->getAllegati() as $allegato) {
            if (file_exists(__DIR__ . '/../../../../../web' . $allegato->getFile())) {
                $message->attach(\Swift_Attachment::fromPath(__DIR__ . '/../../../../../web' . $allegato->getFile()));
            }
        }
        $this->get('mailer')->send($message);
    }

    protected function ripluilisciTestoEmail($testo) {
        $risposte = array(
            'thunderbird_it' => '/Il [0-9]{2}\/[0-9]{2}\/[0-9]{4} [0-9]{2}:[0-9]{2}, [a-zA-Z0-9\.\-\_ ]+[@]?[a-zA-Z0-9\.\-\_]+ ha scritto:/',
            'mail_it' => '/Il giorno [0-9]{2}\/[a-z]{3}\/[0-9]{4}, alle ore [0-9]{2}:[0-9]{2}, [a-zA-Z0-9\.\-\_ ]+[@]?[a-zA-Z0-9\.\-\_]+ ha scritto:/',
            'gmail_it' => '/Il giorno [0-9]{2} [a-z]{5-12} [0-9]{4} [0-9]{2}:[0-9]{2}, [a-zA-Z0-9\.\-\_ ]+ \<[a-zA-Z0-9\.\-\_ ]+[@]?[a-zA-Z0-9\.\-\_]+\> ha scritto:/',
        );
        foreach ($risposte as $risposta) {
            $testo = preg_replace($risposta, '', $testo);
        }

        //$firma = preg_match_all('/\-\-/', $testo);
        $firma_pos = strpos($testo, '--');
        if ($firma_pos > 0) {
            $testo = substr($testo, 0, $firma_pos);
        }
        return $testo;
    }

    /**
     * Requests the ivory google map service
     *
     * @return \Ivory\GoogleMapBundle\Model\Map 
     */
    public function getMap($name = 'map', $lat = 41.87194, $lon = 12.56738, $dist = 0) {
        $dist = !($this->isEmpty($lat) && $this->isEmpty($lon)) ? $dist : 0;
        $lat = !$this->isEmpty($lat) ? $lat : 41.87194;
        $lon = !$this->isEmpty($lon) ? $lon : 12.56738;

        $zoom = $this->getZoom($dist);

        $map = $this->get('ivory_google_map.map');
        $map->setJavascriptVariable($name);
        $map->setHtmlContainerId($name . '_canvas');
        $map->setAsync(false);
        $map->setAutoZoom(false);
        $map->setCenter($lat, $lon, true);
        $map->setMapOption('zoom', $zoom);
        $map->setMapOption('mapTypeControl', false);
        $map->setMapOption('streetViewControl', false);
        $map->setMapOption('mapTypeId', \Ivory\GoogleMapBundle\Model\MapTypeId::ROADMAP);
        $map->setStylesheetOption('width', '150px');
        $map->setStylesheetOption('height', '150px');
        $map->setLanguage('it');
        return $map;
    }

    /**
     * Requests the ivory google map service
     *
     * @return \Ivory\GoogleMapBundle\Model\Overlays\Marker
     */
    public function getMarker($name = 'map', $lat = 41.87194, $lon = 12.56738) {
        $marker = $this->get('ivory_google_map.marker');
        $marker->setJavascriptVariable($name);
        $marker->setPosition($lat, $lon, true);
        $marker->setOption('clickable', true);
        $marker->setOption('flat', true);
        return $marker;
    }

    /**
     * Requests the ivory google map service
     *
     * @return \Ivory\GoogleMapBundle\Model\Overlays\Circle
     */
    public function getCircle($name = 'map', $lat = 41.87194, $lon = 12.56738, $dist = 0) {
        $dist = !($this->isEmpty($lat) && $this->isEmpty($lon)) ? $dist : 0;
        $lat = !$this->isEmpty($lat) ? $lat : 41.87194;
        $lon = !$this->isEmpty($lon) ? $lon : 12.56738;
        $circle = $this->get('ivory_google_map.circle');
        $circle->setJavascriptVariable($name);
        $circle->setCenter($lat, $lon, true);
        $circle->setRadius($dist * 1000);
        $circle->setOption('clickable', true);
        $circle->setOption('strokeWeight', 2);
        $circle->setOption('fillOpacity', 0.25);

        return $circle;
    }

    /**
     * Requests the ivory google map service
     *
     * @return \Ivory\GoogleMapBundle\Model\Overlays\InfoWindow
     */
    public function getInfoWindow($name = 'map', $lat = 41.87194, $lon = 12.56738) {
        $infoWindow = $this->get('ivory_google_map.info_window');
        $infoWindow->setJavascriptVariable($name);
        $infoWindow->setPosition($lat, $lon, true);
        $infoWindow->setPixelOffset(0, 35, 'px', 'px');
        $infoWindow->setOpen(false);
        $infoWindow->setAutoOpen(true);
        $infoWindow->setOpenEvent(\Ivory\GoogleMapBundle\Model\Events\MouseEvent::CLICK);
        $infoWindow->setAutoClose(false);
        $infoWindow->setOption('disableAutoPan', false);
        $infoWindow->setOption('zIndex', 10);

        return $infoWindow;
    }

    public function getZoom($dist) {
        if ($dist == 0)
            return 4;
        if ($dist >= 75 && $dist < 150)
            return 6;
        if ($dist >= 150 && $dist < 300)
            return 5;
        if ($dist >= 300)
            return 4;
        if ($dist >= 50 && $dist < 75)
            return 7;
        if ($dist >= 25 && $dist < 50)
            return 8;
        if ($dist < 25)
            return 9;
    }

    protected function isEmpty($test) {
        switch ($test) {
            case null:
            case 0:
            case '':
            case false:
                return true;
            default:
                return false;
        }
    }

}
