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

/**
 * @Route("/messenger")
 */
class MessengerController extends MessengerControllerBase {

    /**
     * @Route("/", name="messenger")
     * @Template()
     */
    public function indexAction() {
        //$this->generateCerchie();
        //Funzioni::cancellaRicerca($this->getRequest()->getSession());
        $em = $this->getEm();
        $utente = $this->getUtente();
        $_messaggi = $em->getRepository('ES\MessengerBundle\Entity\MessaggioBase');
        $zona = new \DateTimeZone('Europe/Rome');
        $now = new \DateTime('now', $zona);
        $this->getRequest()->getSession()->set('messenger.lasttimestamp', $now);
        //$centro_messaggi = $_messaggi->getCentroMessaggi($utente);
        //$categorie_messaggi = $this->creaCategorie($centro_messaggi);
        $filtro_tendina = $this->getFiltroTendina();

        //$rubrica = $this->getRubrica($utente,true);
       
        //Funzioni::vd($rubrica);
        
        $messengerFiltro = $filtro_tendina['tutti'];
        $messaggi = $_messaggi->filtraCasella($utente, $messengerFiltro);
        $this->getRequest()->getSession()->set('messenger.n', 10);
        $this->getRequest()->getSession()->set('messenger.filtro', $messengerFiltro);
        $this->getRequest()->getSession()->set('messenger.filtro.tutti', $messengerFiltro);


        return array(
            //'cerchie' => $utente->getCerchie(),
            'results' => $messaggi["risultato"],
            'risultati_totali' => $messaggi["risultati_totali"],
            //'rubrica' => $rubrica,
            //'categorie_messaggi' => $categorie_messaggi,
            'filtro_tendina' => $filtro_tendina,
//            'rdo_rimasti' => $utente->getShowroom() ? $utente->getShowroom()->getRdoResidui() : 0,
//            'is_showroom' => $utente->getShowroom() ? true : false,
        );
    }
    
    /**
     * @Route("-app/preview", name="notifiche_preview")
     * @Template()
     */
    public function appPreviewAction() {
        $em = $this->getEm();
        $utente = $this->getUtente();
        $_messaggi = $em->getRepository('ES\MessengerBundle\Entity\MessaggioBase');
        $filtro_tendina = $this->getFiltroTendina();
        $messengerFiltro = $filtro_tendina['tutti'];
        $messengerFiltro['tipo'] = '{"spam":false,"read":false,"stored":false,"send":null}';
        $messaggi = $_messaggi->filtraCasella($utente, $messengerFiltro, false, 0 ,5);
        return array('data' => $messaggi["risultato"]);
    }

    /**
     * @Route("/ajax", name="messenger_filtro")
     * @Template("EcoSeekrMessengerBundle:Messenger:moduli/risultatiAjax.html.twig")
     */
    public function filtroAction() {
        $em = $this->getEm();
        $utente = $this->getUtente();
        $_messaggi = $em->getRepository('ES\MessengerBundle\Entity\MessaggioBase');
        $messengerFiltro = $this->getRequest()->getSession()->get('messenger.filtro');
        $messengerFiltro['filtro'] = $this->getRequest()->get('filtri');
        $messengerFiltro['tipo'] = $this->getRequest()->get('tipo');
        $messaggi = $_messaggi->filtraCasella($utente, $messengerFiltro);

        $centro_messaggi = $_messaggi->getCentroMessaggi($utente);


        $this->getRequest()->getSession()->set('messenger.filtro', $messengerFiltro);
        $this->getRequest()->getSession()->set('messenger.n', 10);
        return array(
            'results' => $messaggi["risultato"],
            'risultati_totali' => $messaggi["risultati_totali"],
            'showrooms' => $utente->getShowroom(),
            'rdo_rimasti' => $utente->getShowroom() ? $utente->getShowroom()->getRdoResidui() : 0,
            'centro_messaggi' => $centro_messaggi,
            'is_showroom' => $utente->getShowroom() ? true : false,
        );
    }

    /**
     * @Route("/ajax/scroll", name="messenger_scroll_messaggi")
     * @Template("EcoSeekrMessengerBundle:Messenger:moduli/risultatiScrollAjax.html.twig")
     */
    public function scrollMessaggiAction() {
        $em = $this->getEm();
        $utente = $this->getUtente();
        $_messaggi = $em->getRepository('ES\MessengerBundle\Entity\MessaggioBase');
        $messengerFiltro = $this->getRequest()->getSession()->get('messenger.filtro');
        $messaggi_esistenti = $this->getRequest()->getSession()->get('messenger.n');
        $messaggi = $_messaggi->filtraCasella($utente, $messengerFiltro, null, $messaggi_esistenti);
        $this->getRequest()->getSession()->set('messenger.n', $messaggi_esistenti + count($messaggi["risultato"]));
        return array(
            'results' => $messaggi["risultato"],
            'risultati_totali' => $messaggi["risultati_totali"],
            'rdo_rimasti' => $utente->getMessaggi(),
            'is_showroom' => $utente->getShowroom() ? true : false,
        );
    }

    /**
     * @Route("/ajax/nuovi_messaggi", name="messenger_nuovi_messaggi", defaults={"_format"="json"})
     * @Template()
     */
    public function nuoviMessaggiAction() {
        $request = $this->getRequest();
        $em = $this->getEm();
        $utente = $this->getUtente();
        $showroom = $utente->getShowroom();
        $_messaggi = $em->getRepository('ES\MessengerBundle\Entity\MessaggioBase');
        $messengerFiltro = array(
                'label' => 'Tutti i messaggi',
                'filtro' => "['servizi', 'mps', 'cer','rdi','post','system']",
                'tipo' => '{"spam":false,"read":null,"stored":false,"send":null}',
            );
        $lasttimestamp = $request->getSession()->get('messenger.lasttimestamp');
        $zona = new \DateTimeZone('Europe/Rome');
        $now = new \DateTime('now', $zona);
        $request->getSession()->set('messenger.lasttimestamp', $now);
        $messaggi = $_messaggi->filtraCasella($utente, $messengerFiltro, $lasttimestamp);
        $notifiche = array();
        $slugs = array();
        foreach ($messaggi["risultato"] as $messaggio) {
//            $messaggio->cercaDestinatario($utente);
            if (!$messaggio->isMessaggioNotificato()) {
                $notifiche[] = array(
                    'slug' => $messaggio->getSlug(),
                    'subject' => $messaggio->getSubject(),
                    'from' => $messaggio->getFromUtente()->getDenominazioneCompleta(),
                    'type' => $messaggio->getRisposte()->count() == 0 ? 'Nuovo messaggio:' : 'Nuova risposta a:',
                );
                $messaggio->setNotificato(true);
                $em->persist($messaggio);
                $em->flush();
            }
        }
        $centro_messaggi = $_messaggi->getCentroMessaggi($utente);
        $non_letti = $centro_messaggi['da_leggere'];
        $categorie_messaggi = $this->creaCategorie($centro_messaggi);
        $cm = $request->get('cm') == 'true' ? $this->renderView('EcoSeekrMessengerBundle:Messenger:moduli/centroMessaggi.html.twig', array('categorie_messaggi' => $categorie_messaggi, 'rdo_rimasti' => $showroom ? $showroom->getRdoResidui() : null, 'is_showroom' => $showroom ? true : false,)) : false;

        $n = false;
        if ($request->get('d') != 'null') {
            if ($request->get('d') == 'false') {
                $messengerFiltro = $this->getRequest()->getSession()->get('messenger.filtro');
                $messengerFiltroTutti = $this->getRequest()->getSession()->get('messenger.filtro.tutti');
                if ($messengerFiltro != $messengerFiltroTutti) {
                    $messaggi = $_messaggi->filtraCasella($utente, $messengerFiltro, $lasttimestamp);
                }
                $n = array();
                foreach ($messaggi["risultato"] as $messaggio) {
                    $m = $this->renderView('EcoSeekrMessengerBundle:Messenger:moduli/risultato.html.twig', array('result' => $messaggio));
                    $n[$messaggio->getSlug()] = $m;
                }
            } else {
                $messaggio = $_messaggi->findOneBy(array('slug' => $request->get('d')));
                $messaggio->cercaDestinatario($utente);
                $messaggio->setController($this);
                foreach ($messaggio->getRisposte() as $risposta) {
                    if (!$risposta->isNotificato()) {
                        $risposta->getDestinatario()->setNotificato(true);
                        $em->persist($risposta->getDestinatario());
                        $em->flush();
                        $m = $this->renderView('EcoSeekrMessengerBundle:Messenger:discussione/show.html.twig', array('result' => $risposta));
                        $n[$risposta->getSlug()] = $m;
                    }
                }
                $m = $this->renderView('EcoSeekrMessengerBundle:Messenger:discussione/allegati.html.twig', array('result' => $messaggio));
                $n['allegati'] = $m;
//                $n = $this->renderView('EcoSeekrMessengerBundle:Messenger:discussione/index.html.twig', array('result' => $messaggio));
            }
        }

        //Funzioni::vd($notifiche,true);

        $out = array(
            'nl' => $non_letti,
            'nf' => $notifiche,
            'cm' => $cm,
            'nw' => $n,
            'rdo_rimasti' => $utente->getMessaggi(),
            'is_showroom' => $utente->getShowroom() ? true : false,
        );
        echo json_encode($out);
        exit;
    }

    /**
     * @Route("/tutti_messaggi", name="tutti_messaggi_utente")
     * @Template("EcoSeekrMessengerBundle:Messenger:moduli/risultatiAjax.html.twig")
     */
    public function tuttiMessaggiAction() {
        $em = $this->getEm();
        $utente = $this->getUtente();
        $_messaggi = $em->getRepository('ES\MessengerBundle\Entity\MessaggioBase');
        $id_utente = $this->getRequest()->get('id_utente');
        $messaggi = $_messaggi->visualizzaMessaggi($utente, $id_utente);

        return array(
            'results' => $messaggi,
            'showrooms' => $utente->getShowroom(),
        );
    }

    /**
     * @Route("/ajax_invio", name="invio_filtro")
     * @Template("EcoSeekrMessengerBundle:Messenger:moduli/risultatiScrollAjax.html.twig")
     */
    public function filtroInvioAction() {
        $em = $this->getEm();
        $request = $this->getRequest();
        $utente = $this->getUtente();
        $_messaggi = $em->getRepository('ES\MessengerBundle\Entity\MessaggioBase');
        $messaggi = $_messaggi->filtra_ricerca($utente, $this->getRequest(), "html");
        return array(
            'results' => $messaggi,
            'showrooms' => $utente->getShowroom(),
            'rdo_rimasti' => $utente->getShowroom() ? $utente->getShowroom()->getRdoResidui() : 0,
            'is_showroom' => $utente->getShowroom() ? true : false,
        );
    }

    /**
     * @Route("/cerca_autocomplete", name="cerca_messenger",defaults={"_format"="json"}))
     * @Template("EcoSeekrMotoreBundle:Cerca:result.json.twig")
     */
    public function cerca_autocompleteAction() {
        $em = $this->getEm();
        $request = $this->getRequest();
        $utente = $this->getUtente();
        $_messaggi = $em->getRepository('ES\MessengerBundle\Entity\MessaggioBase');
        $messaggi = $_messaggi->filtra_ricerca($utente, $this->getRequest());
        return array(
            'json' => json_encode($messaggi),
        );
    }

    /**
     * @Route("/discussione/{slug}", name="messenger_discussione")
     * @Template()
     */
    public function discussioneOpenAction($slug) {
        $em = $this->getEm();
        $utente = $this->getUtente();
        $_messaggi = $em->getRepository('ES\MessengerBundle\Entity\MessaggioBase');
        $messaggio = $_messaggi->findOneBy(array('slug' => $slug));
        $messaggio->cercaDestinatario($utente);
        $messaggio->setController($this);
        $messaggio->setLetto(true);
        $messaggio->setNotificato(true);
        try {
            $em->beginTransaction();
            $em->persist($messaggio);
            $em->flush();
            $em->commit();
        } catch (\Exception $e) {
            $em->rollback();
            throw $e;
        }
        $messaggi = $_messaggi->casella($utente);

        // exit;
        $centro_messaggi = $_messaggi->getCentroMessaggi($utente);
        $categorie_messaggi = $this->creaCategorie($centro_messaggi);
        $filtro_tendina = $this->getFiltroTendina();
        $rubrica = $this->getRubrica($utente,true);

        $destinatari = $messaggio->getNomiUtenti(6);
        
        //Funzioni::vd($destinatari);
        
        
        return array(
            'cerchie' => $utente->getCerchie(),
            'result' => $messaggio,
            'destinatari' => $destinatari,
            'slug' => $slug,
            'results' => $messaggi,
            'rubrica' => $rubrica,
            'categorie_messaggi' => $categorie_messaggi,
            'filtro_tendina' => $filtro_tendina,
            'rdo_rimasti' => $utente->getMessaggi(),
            'is_showroom' => $utente->getShowroom() ? true : false,
        );
    }
    
    /**
     * @Route("/nuova-discussione", name="messenger_new_discussione")
     * @Template()
     */
    public function discussioneNewAction() {
        $em = $this->getEm();
        $utente = $this->getUtente();
        $messaggio = new Messaggio($utente);

        $_messaggi = $em->getRepository('ES\MessengerBundle\Entity\MessaggioBase');
        $centro_messaggi = $_messaggi->getCentroMessaggi($utente);
        $categorie_messaggi = $this->creaCategorie($centro_messaggi);
        $filtro_tendina = $this->getFiltroTendina();
        $rubrica = $this->getRubrica($utente,true);

        $_rubrica = $em->getRepository('ES\MessengerBundle\Entity\Rubrica');
        $dests = $this->getRequest()->get('usrs');
        $destinatari = array();
        foreach (json_decode($dests) as $dest) {
            $r = $_rubrica->find($dest);
            $destinatari[] = array('id' => $r->getContatto()->getId(), 'name' => $r->getContatto()->getLabel());
        }
        
        return array(
            'cerchie' => $utente->getCerchie(),
            'result' => $messaggio,
            'destinatari' => json_encode($destinatari),
            'result' => $messaggio,
            'rubrica' => $rubrica,
            'categorie_messaggi' => $categorie_messaggi,
            'filtro_tendina' => $filtro_tendina,
            'rdo_rimasti' => $utente->getMessaggi(),
            'is_showroom' => $utente->getShowroom() ? true : false,
        );
    }

    /**
     * @Route("/leggi/{slug}", name="messenger_discussione_login")
     * @Template("EcoSeekrMessengerBundle:Messenger:discussioneOpen.html.twig")
     */
    public function discussioneLoginOpenAction($slug) {
        $em = $this->getEm();
        $_destinatario = $em->getRepository('ES\MessengerBundle\Entity\Destinatario');
        $destinatario = $_destinatario->findOneBy(array('slug' => $slug));
        $utente = $destinatario->getDestinatario();

        $token = new UsernamePasswordToken(
                        $utente instanceOf \ES\ACLBundle\Entity\UserShowroom  ? $utente->getAmministratore()->getUser() :$utente->getUser(),
                        null,
                        'secured_area',
                         $utente instanceOf \ES\ACLBundle\Entity\UserShowroom  ? $utente->getAmministratore()->getUser()->getRoles() : $utente->getUser()->getRoles()
        );
        $this->getRequest()->getSession()->set('_security_secured_area', serialize($token));
        $this->getRequest()->getSession()->set('user', $utente->serialize());

        $_messaggi = $em->getRepository('ES\MessengerBundle\Entity\MessaggioBase');
        $messaggio = $_messaggi->findOneBy(array('slug' => $destinatario->getMessaggio()->getSlug()));
        $messaggio = $messaggio->getConversazione();
        $messaggio->cercaDestinatario($utente);
        $messaggio->setController($this);
        $messaggio->setLetto(true);
        $messaggio->setNotificato(true);
        try {
            $em->beginTransaction();
            $em->persist($messaggio);
            $em->flush();
            $em->commit();
        } catch (\Exception $e) {
            $em->rollback();
            throw $e;
        }
        
        

        $out = $this->indexAction();
        $out['result'] = $messaggio;
        $out['destinatari'] = $messaggio->getNomiUtenti(6);
        $out['slug'] = $messaggio->getSlug();
        $out['cerchie'] = $utente->getCerchie();

        return $out;
    }

    /**
     * @Route("/discussione", name="leggi_discussione")
     * @Template("EcoSeekrMessengerBundle:Messenger:discussione/index.html.twig")
     */
    public function discussioneAction() {
        $em = $this->getEm();
        $utente = $this->getUtente();
        $_messaggi = $em->getRepository('ES\MessengerBundle\Entity\MessaggioBase');
        $messaggio = $_messaggi->findOneBy(array('slug' => $this->getRequest()->get('slug')));
        $messaggio->cercaDestinatario($utente);
        $messaggio->setController($this);
        $messaggio->setLetto(true);
        try {
            $em->beginTransaction();
            $em->persist($messaggio);
            $em->flush();
            $em->commit();
        } catch (\Exception $e) {
            $em->rollback();
            throw $e;
        }
        
        //Funzioni::vd($messaggio->getDestinatari(),true);
        
        return array(
            'result' => $messaggio,
            'destinatari' => $messaggio->getNomiUtenti(6),
        );
    }

    /**
     * @Route("/nuova/discussione", name="nuova_discussione")
     * @Template("EcoSeekrMessengerBundle:Messenger:newMessage/index.html.twig")
     */
    public function nuovaDiscussioneAction() {
        $em = $this->getEm();
        $utente = $this->getUtente();
        $messaggio = new Messaggio($utente);
        return array(
            'result' => $messaggio,
        );
    }

    /**
     * @Route("/cerca/destinatari", name="messenger_destinatari")
     * @Template("EcoSeekrMessengerBundle:Messenger:newMessage/index.html.twig")
     */
    public function cercaDestinatariAction() {
        $em = $this->getEm();
        $utente = $this->getUtente();
        $_utenti = $em->getRepository('ES\ACLBundle\Entity\User');
        $results = $_utenti->cercaContatti($utente, $this->getRequest()->get('q'));
        $out = array();
        foreach ($results as $result) {
            $out[] = array(
                'id' => $result->getId() ? : $result->getEmail(),
                'name' => $result->getLabel() ? : $result->getEmail(),
            );
        }
        echo json_encode($out);
        exit;
    }

    /**
     * @Route("/letto", name="segna_letto")
     * @Template("EcoSeekrMessengerBundle:Messenger:discussione/index.html.twig")
     */
    public function segna_lettoAction() {
        $em = $this->getEm();
        $utente = $this->getUtente();
        $_messaggi = $em->getRepository('ES\MessengerBundle\Entity\MessaggioBase');
        $messaggio = $_messaggi->findOneBy(array('slug' => $this->getRequest()->get('slug')));
        $messaggio->cercaDestinatario($utente);
        $messaggio->setLetto(true);
        try {
            $em->beginTransaction();
            $em->persist($messaggio);
            $em->flush();
            $em->commit();
        } catch (\Exception $e) {
            $em->rollback();
            throw $e;
        }

        echo "ok";
        exit;
    }

    /**
     * @Route("/spam", name="segna_spam")
     * @Template("EcoSeekrMessengerBundle:Messenger:discussione/index.html.twig")
     */
    public function segna_spamAction() {
        $em = $this->getEm();
        $utente = $this->getUtente();
        $_messaggi = $em->getRepository('ES\MessengerBundle\Entity\MessaggioBase');
        $messaggio = $_messaggi->findOneBy(array('slug' => $this->getRequest()->get('slug')));
        $messaggio->cercaDestinatario($utente);
        $messaggio->setSpam(true);

        try {
            $em->beginTransaction();
            $em->persist($messaggio);
            $em->flush();
            $em->commit();
        } catch (\Exception $e) {
            $em->rollback();
            throw $e;
        }

        echo "ok";
        exit;
    }

    /**
     * @Route("/disabilita_spam", name="disabilita_spam")
     * @Template("EcoSeekrMessengerBundle:Messenger:discussione/index.html.twig")
     */
    public function disabilita_spamAction() {
        $em = $this->getEm();
        $utente = $this->getUtente();
        $_messaggi = $em->getRepository('ES\MessengerBundle\Entity\MessaggioBase');
        $messaggio = $_messaggi->findOneBy(array('slug' => $this->getRequest()->get('slug')));
        $messaggio->cercaDestinatario($utente);
        $messaggio->setSpam(false);

        try {
            $em->beginTransaction();
            $em->persist($messaggio);
            $em->flush();
            $em->commit();
        } catch (\Exception $e) {
            $em->rollback();
            throw $e;
        }

        echo "ok";
        exit;
    }

    /**
     * @Route("/da_leggere", name="segna_da_leggere")
     * @Template("EcoSeekrMessengerBundle:Messenger:discussione/index.html.twig")
     */
    public function segna_da_leggereAction() {
        $em = $this->getEm();
        $utente = $this->getUtente();
        $_messaggi = $em->getRepository('ES\MessengerBundle\Entity\MessaggioBase');
        $messaggio = $_messaggi->findOneBy(array('slug' => $this->getRequest()->get('slug')));
        $messaggio->cercaDestinatario($utente);
        $messaggio->setLetto(false);

        try {
            $em->beginTransaction();
            $em->persist($messaggio);
            $em->flush();
            $em->commit();
        } catch (\Exception $e) {
            $em->rollback();
            throw $e;
        }

        echo "ok";
        exit;
    }

    /**
     * @Route("/archivia", name="archivia_discussione")
     * @Template("EcoSeekrMessengerBundle:Messenger:discussione/index.html.twig")
     */
    public function archiviaAction() {
        $em = $this->getEm();
        $utente = $this->getUtente();
        $_messaggi = $em->getRepository('ES\MessengerBundle\Entity\MessaggioBase');
        $messaggio = $_messaggi->findOneBy(array('slug' => $this->getRequest()->get('slug')));
        $messaggio->cercaDestinatario($utente);
        $messaggio->setArchiviato(true);

        try {
            $em->beginTransaction();
            $em->persist($messaggio);
            $em->flush();
            $em->commit();
        } catch (\Exception $e) {
            $em->rollback();
            throw $e;
        }

        echo "ok";
        exit;
    }

    /**
     * @Route("/disarchivia", name="disarchivia_discussione")
     * @Template("EcoSeekrMessengerBundle:Messenger:discussione/index.html.twig")
     */
    public function disarchiviaAction() {
        $em = $this->getEm();
        $utente = $this->getUtente();
        $_messaggi = $em->getRepository('ES\MessengerBundle\Entity\MessaggioBase');
        $messaggio = $_messaggi->findOneBy(array('slug' => $this->getRequest()->get('slug')));
        $messaggio->cercaDestinatario($utente);
        $messaggio->setArchiviato(false);

        try {
            $em->beginTransaction();
            $em->persist($messaggio);
            $em->flush();
            $em->commit();
        } catch (\Exception $e) {
            $em->rollback();
            throw $e;
        }

        echo "ok";
        exit;
    }

    /**
     * @Route("/contatto/aggiungi", name="rubrica_aggiungi")
     * @Template("EcoSeekrMessengerBundle:Messenger:moduli/rubrica.html.twig")
     */
    public function aggiungiContattoAction() {
        $em = $this->getEm();
        $utente = $this->getUtente();
        $_rubrica = $em->getRepository('ES\MessengerBundle\Entity\Rubrica');
        $_user = $em->getRepository('ES\ACLBundle\Entity\User');
        $contatto = $_user->find($this->getRequest()->get("contatto"));

        if (!$contatto) {
            throw $this->createNotFoundException("Contatto Inesistente");
        }

        $rubrica = $_rubrica->findOneBy(array(
            "contatto" => $contatto->getId(),
            "proprietario" => $utente->getId(),
                ));
        if ($rubrica) {
            throw $this->createNotFoundException("Il contatto è già presente");
        }

        try {
            $em->beginTransaction();
            $rubrica = new \ES\MessengerBundle\Entity\Rubrica();
            $rubrica->setProprietario($utente);
            $rubrica->setContatto($contatto);
            $em->persist($rubrica);
            $em->flush();
            $em->commit();
        } catch (\Exception $e) {
            $em->rollback();
            throw $e;
        }
        $rubrica = $this->getRubrica($utente,true);
        return array(
            'rubrica' => $rubrica,
        );
    }

    /**
     * @Route("/contatto/elimina", name="rubrica_elimina")
     * @Template("EcoSeekrMessengerBundle:Messenger:moduli/rubrica.html.twig")
     */
    public function eliminaContattoAction() {
        $em = $this->getEm();
        $utente = $this->getUtente();
        $_rubrica = $em->getRepository('ES\MessengerBundle\Entity\Rubrica');
        $_user = $em->getRepository('ES\ACLBundle\Entity\User');
        $contatto = $_user->find($this->getRequest()->get("contatto"));

        if (!$contatto) {
            throw $this->createNotFoundException("Contatto Inesistente");
        }

        $rubrica = $_rubrica->findOneBy(array(
            "contatto" => $contatto->getId(),
            "proprietario" => $utente->getId(),
                ));
        if (!$rubrica) {
            throw $this->createNotFoundException("Il contatto non è presente");
        }

        try {
            $em->beginTransaction();

            $em->remove($rubrica);
            $em->flush();
            $em->commit();
        } catch (\Exception $e) {
            $em->rollback();
            throw $e;
        }
        $rubrica = $this->getRubrica($utente,true);
        return array(
            'rubrica' => $rubrica,
        );
    }

    /**
     * @Route("/nuova", name="messenger_new")
     * @Template("EcoSeekrMessengerBundle:Messenger:discussione/show.html.twig")
     */
    public function nuovaAction() {
        $request = $this->getRequest();
        $data = $request->get('messaggio');
        
        $em = $this->getEm();
        $_utenti = $em->getRepository('ES\ACLBundle\Entity\User');
        try {
            $em->beginTransaction();
            $utente = $this->getUtente();
            $messaggio = new \ES\MessengerBundle\Entity\Messaggio();
            $messaggio->setPreSlug($data['slug']);
            $messaggio->setSubject($data['subject']);
            $messaggio->setTesto($data['testo_risposta']);
            $messaggio->setFromUtente($utente);
            $em->persist($messaggio);
            $em->flush();
            foreach (json_decode(str_replace("'", '"', $data['destinatari_id'])) as $destinatario_id) {
                if (Funzioni::isEmail($destinatario_id)) {
                    $utente_destinataio = new User();
                    $utente_destinataio->setEmail($destinatario_id);
                    $utente_destinataio->setNomecompleto($destinatario_id);
                    $utente_destinataio->setEmailVerificata(false);
                    $em->persist($utente_destinataio);
                    $em->flush();
                } else {
                    $utente_destinataio = $_utenti->find($destinatario_id);
                }
                $destinatario = new \ES\MessengerBundle\Entity\Destinatario();
                $destinatario->setDestinatario($utente_destinataio);
                $destinatario->setMessaggio($messaggio);
                $em->persist($destinatario);
                $em->flush();
                $messaggio->addDestinatari($destinatario);
            }
            foreach (json_decode(str_replace("'", '"', $data['allegati'])) as $_allegato) {
                $allegato = new Allegato();
                $allegato->setConversazione($messaggio);
                $allegato->setMessaggio($messaggio);
                $allegato->setFile('/uploads/files/messenger/' . $data['slug'] . '/' . $_allegato);
                $messaggio->addAllegati($allegato);
                $em->persist($allegato);
                $em->flush();
            }
            foreach ($messaggio->getDestinatari() as $destinatario) {
                if ($destinatario != $utente) {
                    $this->inviaNotifica($utente, $destinatario, $messaggio);
                }
            }

            $em->commit();
        } catch (\Exception $e) {
            $em->rollback();
            echo "ko ({$e->getMessage()})";
            exit;
        }
        return array(
            'result' => $messaggio,
            'aperto' => true,
        );
    }

    /**
     * @Route("/rispondi", name="messenger_new_response")
     * @Template("EcoSeekrMessengerBundle:Messenger:discussione/show.html.twig")
     */
    public function rispostaAction() {
        $request = $this->getRequest();
        $data = $request->get('messaggio');
        $em = $this->getEm();
//        var_dump($data->mittente);
//        exit;
        try {
            $em->beginTransaction();
            $utente = $this->getUtente();
            $_messaggio = $em->getRepository('ES\MessengerBundle\Entity\MessaggioBase');
            $conversazione = $_messaggio->findOneBy(array('slug' => $data['slug']));
            if (!$conversazione) {
                throw new \Exception('Conversazione inesistente');
            }
            $risposta = new Risposta();
            $risposta->setMessaggio($conversazione);
            $risposta->setTesto($data['testo_risposta']);
            $risposta->setFromUtente($utente);
            $em->persist($risposta);
            $em->flush();
            foreach ($conversazione->getDestinatari() as $_destinatario) {
                $utente_destinataio = $_destinatario->getDestinatario();
                $add = true;
                foreach ($risposta->getDestinatari() as $d) {
                    if ($d->getDestinatario() == $utente_destinataio) {
                        $add = false;
                    }
                }
                if ($add && $utente_destinataio != $utente) {
                    $destinatario = new \ES\MessengerBundle\Entity\Destinatario();
                    $destinatario->setDestinatario($utente_destinataio);
                    $destinatario->setMessaggio($risposta);
                    $em->persist($destinatario);
                    $em->flush();
                    $conversazione = $risposta->getMessaggio()->getConversazione();
                    $zona = new \DateTimeZone('Europe/Rome');
                    $now = new \DateTime('now', $zona);
                    $conversazione->setUpdatedAt($now);
                    $em->persist($conversazione);
                    $em->flush();
                    $conversazione->cercaDestinatario($utente_destinataio);
                    $dest = $conversazione->getDestinatario();
                    $dest->setLetto(false);
                    $em->persist($dest);
                    $em->flush();
                    $risposta->addDestinatari($destinatario);
                }
            }
            foreach (json_decode(str_replace("'", '"', $data['allegati'])) as $_allegato) {
                $allegato = new Allegato();
                $allegato->setConversazione($conversazione);
                $allegato->setMessaggio($risposta);
                $allegato->setFile('/uploads/files/messenger/' . $data['slug'] . '/' . $_allegato);
                $risposta->addAllegati($allegato);
                $em->persist($allegato);
                $em->flush();
            }
            foreach ($risposta->getDestinatari() as $destinatario) {
                if ($destinatario != $utente) {
                    $this->inviaNotifica($utente, $destinatario, $risposta);
                }
            }

            $em->commit();
        } catch (\Exception $e) {
            $em->rollback();
            echo "ko ({$e->getMessage()})";
            exit;
        }
        return array(
            'result' => $risposta,
            'aperto' => true,
        );
    }

    /**
     * Riceve i dati dalle email
     * Dati ricevuti: {
     *  'slug': slug dell'email (che può essere nuovo)
     *  'subject': subject dell'email
     *  'testo_risposta': testo pulito dell'email
     *  'mittente': email del mittente 
     *  'allegati': array con i nomi dei file da leggere con json_decode
     *  'nome_mittente': mome del mittente
     *  'destinatari': array con le email dei destinatari (to: + cc:)
     *  'nuova': vale 1 se è una discussione nuova, 0 se è una risposta
     * }
     * esempio json funzionante: {"slug":"e15e5e53a3680617e44e3ef933561191","testo_risposta":"Va bene","mittente":"ephraim.pepe@gmail.com","allegati":["test.pdf","melo.pdf"]}
     */

    /**
     * @Route("/answers", name="messenger_answers")
     */
    public function answersEmailAction() {
        $request = $this->getRequest();
        $data = json_decode(str_replace('""', '"', $request->get('data')));
        if (!$data) {
            $message = \Swift_Message::newInstance()
                    ->setSubject("Errore parser mail")
                    ->setFrom($this->container->getParameter('email_robot'))
                    ->setTo('cron@ecoseekr.com')
                    ->setReplyTo($this->container->getParameter('email_robot'), "No-Reply")
                    ->setBody("DATA:\n" . $request->get('data') . "\n\nJSON:\n" . print_r($data, true));
            ;
            $message->getHeaders()->addTextHeader('X-Mailer', 'PHP v' . phpversion());
            $message->getHeaders()->get('Message-ID')->setId('ES-' . md5(time()) . rand(100, 999) . '@ecoseekr.com');
            $this->get('mailer')->send($message);
        }
        $em = $this->getEm();
        $_utente = $em->getRepository('ES\ACLBundle\Entity\User');
        $_messaggio = $em->getRepository('ES\MessengerBundle\Entity\MessaggioBase');
        $_professionista = $em->getRepository('ES\ProfessionistaBundle\Entity\Professionista');
        try {
            $em->beginTransaction();
            if ($data->nuova == 1 || $data->nuova == '1') {
                $utente = $_utente->findOneBy(array('email' => urldecode($data->mittente)));
                if (!$utente) {
                    $utente = new \ES\ACLBundle\Entity\User();
                    $utente->setEmail($data->mittente);
                    $utente->setEmailVerificata(true);
                    $utente->setNomeCompleto($data->nome_mittente);
                    $em->persist($utente);
                    $em->flush();
                }
                $email = new Messaggio($utente);
                $email->setSubject($data->subject);
                $email->setPreSlug($data->slug);
            } else {
                $utente = $_utente->findOneBy(array('email' => urldecode($data->mittente)));
                if (!$utente) {
                    throw new \Exception('Mittente inesistente (' . urldecode($data->mittente) . ')');
                }
                $conversazione = $_messaggio->findOneBy(array('slug' => urldecode($data->slug)));
                if (!$conversazione) {
                    throw new \Exception('Conversazione inesistente (' . urldecode($data->slug) . ')');
                }
                $email = new Risposta();
                $email->setMessaggio($conversazione);
            }
            $email->setTesto($this->ripluilisciTestoEmail(urldecode($data->testo_risposta)));
            $email->setFromUtente($utente);
            $em->persist($email);
            $em->flush();
            if ($data->nuova == 1 || $data->nuova == '1') {
                foreach ($data->destinatari as $_destinatario) {
                    $_destinatario = urldecode($_destinatario);
                    if (strpos($_destinatario, '@ecoseekr.com')) {
                        $referente = $_professionista->findOneBy(array('email_ecoseekr' => $_destinatario));
                        if (!$referente) {
                            throw new \Exception('Alias non riconosciuto');
                        }
                        $utente_destinataio = $referente->getUtente();
                    } else {
                        $utente_destinataio = $_utente->findOneBy(array('email' => $_destinatario));
                        if (!$utente_destinataio) {
                            $utente_destinataio = new \ES\ACLBundle\Entity\User();
                            $utente_destinataio->setEmail($_destinatario);
                            $utente_destinataio->setNomeCompleto($_destinatario);
                            $em->persist($utente_destinataio);
                            $em->flush();
                        }
                    }
                    if ($utente_destinataio != $utente) {
                        $add = true;
                        foreach ($email->getDestinatari() as $d) {
                            if ($d->getDestinatario() == $utente_destinataio) {
                                $add = false;
                            }
                        }
                        if ($add) {
                            $destinatario = new \ES\MessengerBundle\Entity\Destinatario();
                            $destinatario->setDestinatario($utente_destinataio);
                            $destinatario->setMessaggio($email);
                            $em->persist($destinatario);
                            $em->flush();
                            $email->addDestinatari($destinatario);
                        }
                    }
                }
                $conversazione = $email;
            } else {
                foreach ($conversazione->getDestinatari() as $_destinatario) {
                    $utente_destinataio = $_destinatario->getDestinatario();
                    if ($utente_destinataio != $utente) {
                        $destinatario = new \ES\MessengerBundle\Entity\Destinatario();
                        $destinatario->setDestinatario($utente_destinataio);
                        $destinatario->setMessaggio($email);
                        $em->persist($destinatario);
                        $em->flush();
                        $conversazione = $email->getMessaggio()->getConversazione();
                        $zona = new \DateTimeZone('Europe/Rome');
                        $now = new \DateTime('now', $zona);
                        $conversazione->setUpdatedAt($now);
                        $em->persist($conversazione);
                        $em->flush();
                        $conversazione->cercaDestinatario($utente_destinataio);
                        $dest = $conversazione->getDestinatario();
                        $dest->setLetto(false);
                        $em->persist($dest);
                        $em->flush();
                        $email->addDestinatari($destinatario);
                    }
                }
            }

            foreach ($data->allegati as $_allegato) {
                $allegato = new Allegato();
                $allegato->setConversazione($conversazione);
                $allegato->setMessaggio($email);
                $allegato->setFile('/uploads/files/messenger/' . $data->slug . '/' . $_allegato);
                $email->addAllegati($allegato);
                $em->persist($allegato);
                $em->flush();
            }

            foreach ($email->getDestinatari() as $destinatario) {
                if ($destinatario != $utente) {
                    $this->inviaNotifica($utente, $destinatario, $email);
                }
            }

            $em->commit();
        } catch (\Exception $e) {
            $em->rollback();
            echo "ko ({$e->getMessage()})";
            exit;
        }
        echo "ok";
        exit;
    }

    private function creaCategorie($centro_messaggi) {
        $categorie_messaggi = array(
            'rdo' => array(
                'label' => 'Richiesta di offerta',
                'numero' => $centro_messaggi['rdo_cer_totale'] + $centro_messaggi['rdo_mps_totale'] + $centro_messaggi['rdo_servizi_totale'],
                'nuovi' => $centro_messaggi['rdo_cer_da_leggere'] + $centro_messaggi['rdo_mps_da_leggere'] + $centro_messaggi['rdo_servizi_da_leggere'],
                'show' => "['servizi', 'mps', 'cer']",
                'sottocategorie' => array(
                    'servizi' => array(
                        'label' => 'Richiesta <strong>Servizi</strong>',
                        'numero' => $centro_messaggi['rdo_servizi_totale'],
                        'nuovi' => $centro_messaggi['rdo_servizi_da_leggere'],
                        'show' => "['servizi']",
                    ),
                    'mps' => array(
                        'label' => 'Richiesta <strong>MPS</strong>',
                        'numero' => $centro_messaggi['rdo_mps_totale'],
                        'nuovi' => $centro_messaggi['rdo_mps_da_leggere'],
                        'show' => "['mps']",
                    ),
                    'cer' => array(
                        'label' => 'Richiesta <strong>CER</strong>',
                        'numero' => $centro_messaggi['rdo_cer_totale'],
                        'nuovi' => $centro_messaggi['rdo_cer_da_leggere'],
                        'show' => "['cer']",
                    ),
                )
            ),
            'post' => array(
                'label' => 'Risposte ai post',
                'numero' => $centro_messaggi['post_totale'],
                'nuovi' => $centro_messaggi['post_da_leggere'],
                'show' => "['post']",
                'sottocategorie' => false
            ),
            'rdi' => array(
                'label' => 'Richiesta di Info',
                'numero' => $centro_messaggi['rdi_totale'],
                'nuovi' => $centro_messaggi['rdi_da_leggere'],
                'show' => "['rdi']",
                'sottocategorie' => false
            ),
            'system' => array(
                'label' => 'Messaggi di sistema',
                'numero' => $centro_messaggi['system_totale'],
                'nuovi' => $centro_messaggi['system_da_leggere'],
                'show' => "['system']",
                'sottocategorie' => false
            ),
        );

        return $categorie_messaggi;
    }

    private function getFiltroTendina() {
        return array(
            'tutti' => array(
                'label' => 'Tutti i messaggi',
                'filtro' => "['servizi', 'mps', 'cer','rdi','post','system']",
                'tipo' => '{"spam":false,"read":null,"stored":false,"send":null}',
            ),
            /* 'rdo' => array(
              'label' => 'Richieste di offerta',
              'filtro' => "['servizi', 'mps', 'cer']",
              'tipo' => '{}',
              ),
              'servizi' => array(
              'label' => 'Richieste di offerta Servizi',
              'filtro' => "['servizi']",
              'tipo' => '{}',
              ),
              'mps' => array(
              'label' => 'Richieste di smaltimento (CER)',
              'filtro' => "['cer']",
              'tipo' => '{}',
              ),
              'cer' => array(
              'label' => 'Richieste per materie (MPS)',
              'filtro' => "['mps']",
              'tipo' => '{}',
              ),
              'rdi' => array(
              'label' => 'Richieste di informazioni',
              'filtro' => "['rdi']",
              'tipo' => '{}',
              ),
              'partner' => array(
              'label' => 'Messaggi dai partners',
              'filtro' => "['partner']",
              'tipo' => '{}',
              ), */
            'msg_letti' => array(
                'label' => 'Messaggi letti',
                'filtro' => "[]",
                'tipo' => '{"spam":false,"read":true,"stored":false,"send":null}',
            ),
            'msg_non_letti' => array(
                'label' => 'Messaggi non letti',
                'filtro' => "[]",
                'tipo' => '{"spam":false,"read":false,"stored":false,"send":null}',
            ),
            'msg_archivati' => array(
                'label' => 'Messaggi Archiviati',
                'filtro' => "[]",
                'tipo' => '{"spam":false,"read":null,"stored":true,"send":null}',
            ),
            'msg_non_archivati' => array(
                'label' => 'Messaggi non Archiviati',
                'filtro' => "[]",
                'tipo' => '{"spam":false,"read":null,"stored":false,"send":null}',
            ),
            'msg_inviati' => array(
                'label' => 'Messaggi Inviati',
                'filtro' => "[]",
                'tipo' => '{"spam":false,"read":null,"stored":false,"send":true}',
            ),
            'msg_ricevuti' => array(
                'label' => 'Messaggi Ricevuti',
                'filtro' => "[]",
                'tipo' => '{"spam":false,"read":null,"stored":false,"send":false}',
            ),
            'spam' => array(
                'label' => 'Spam',
                'filtro' => "[]",
                'tipo' => '{"spam":true,"read":null,"stored":false,"send":null}',
            ),
        );
    }

}
