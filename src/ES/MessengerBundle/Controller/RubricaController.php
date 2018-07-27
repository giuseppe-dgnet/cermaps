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
use ES\MessengerBundle\Entity\InvitoRubrica;
use ES\ACLBundle\Form\InvitoType;

/**
 * @Route("/rubrica")
 */
class RubricaController extends MessengerControllerBase {

    /**
     * @Route("/{cerchia}", name="rubrica", defaults={"cerchia"=""})
     * @Template()
     */
    public function indexAction($cerchia) {
        $this->generateCerchie($this->getRequest()->get('aggiorna', false));
        $utente = $this->getUtente();
        $em = $this->getEm();
        $utenti = array();

        $ids = array();
        if ($cerchia == '') {
            foreach ($utente->getRubrica() as $contatto) {
                $ids[] = $contatto->getContatto()->getId();
                $utenti[$contatto->getId()] = array(
                    'contatto' => $contatto->getContatto(), //ID DI CHI HO GIA IN RUBRICA
                    'preferito' => $contatto->getPreferito(),
                    'cerchie' => $contatto->getCerchie(),
                );
            }
        } else {
            foreach ($utente->getCerchie() as $cerchie) {
                if (strtolower($cerchie->getCerchia()) == $cerchia) {
                    foreach ($cerchie->getContatti() as $contatto) {
                        $ids[] = $contatto->getContatto()->getId();
                        $utenti[$contatto->getId()] = array(
                            'contatto' => $contatto->getContatto(), //ID DI CHI HO GIA IN RUBRICA
                            'preferito' => $contatto->getPreferito(),
                            'cerchie' => $contatto->getCerchie(),
                        );
                    }
                    break;
                }
            }
        }

        $this->getRequest()->getSession()->set('cerchia', $cerchia);

        return array(
            'cerchie' => $utente->getCerchie(),
            'cerchia' => $cerchia,
            'utenti_rubrica' => $utenti,
            'alfabeto' => Funzioni::alfabeto(),
            'form_action' => 'aggiungi_provider',
        );
    }

    /**
     * @Route("-cerchia/add", name="rubrica_add_cercio")
     */
    public function addcerchiaAction() {
        $request = $this->getRequest();
        $em = $this->getEm();
        $_cerchia = $em->getRepository('ES\MessengerBundle\Entity\Cerchia');
        $cerchia = $_cerchia->find($request->get('cerc'));
        $conts = $request->get('cont');
        if (strpos($conts, ']') === false) {
            $conts = array(intval($conts));
        } else {
            $conts = json_decode($conts);
        }
        $n = 0;
        foreach ($conts as $cont) {
            if (intval($request->get('check')) == 1) {
                foreach ($this->getUtente()->getRubrica() as $rubrica) {
                    if ($rubrica->getId() == $cont) {
                        $n += $cerchia->addContatti($rubrica);
                        break;
                    }
                }
                $em->persist($cerchia);
                $em->flush();
            } else {
                foreach ($cerchia->getContatti() as $rubrica) {
                    if ($rubrica->getId() == $cont) {
                        $n += $cerchia->remContatti($rubrica);
                        break;
                    }
                }
                $em->persist($cerchia);
                $em->flush();
            }
        }
        echo $n;
        exit;
    }

    /**
     * @Route("-carica_tutti_contatti", name="carica_tutti_contatti")
     * @Template("EcoSeekrMessengerBundle:Rubrica:moduli/risultati.html.twig")
     */
    public function tuttiContattiAction() {
        $utente = $this->getUtente();
        $em = $this->getEm();
        $utenti = array();

        foreach ($utente->getRubrica() as $contatto) {
            $utenti[$contatto->getId()] = array(
                'contatto' => $contatto->getContatto(), //ID DI CHI HO GIA IN RUBRICA
                'preferito' => $contatto->getPreferito(),
            );
        }

        return array(
            'utenti_rubrica' => $utenti,
        );

        //exit;
    }

    /**
     * @Route("-cancella-gruppo/{cerchia}", name="rubrica_cancella_cerchia")
     */
    public function remCerchieAction($cerchia) {
        $request = $this->getRequest();
        $em = $this->getEm();
        $utente = $this->getUtente();
        $_cerchia = $em->getRepository('ES\MessengerBundle\Entity\Cerchia');
        $find = $_cerchia->findOneBy(array('proprietario' => $utente->getId(), 'cerchia' => $cerchia));
        if ($find) {
            try {
                $em->beginTransaction();
                $em->remove($find);
                $em->flush();
                $em->commit();
            } catch (\Exception $e) {
                $em->rollback();
                throw $e;
            }
        }
        echo 'OK';
        exit;
    }

    /**
     * @Route("-nuovo-gruppo", name="rubrica_add_cerchia")
     * @Template("EcoSeekrMessengerBundle:Rubrica:moduli/cerchia.html.twig")
     */
    public function addCerchieAction() {
        $request = $this->getRequest();
        $cerc = $request->get('cerc');
        $em = $this->getEm();
        $utente = $this->getUtente();
        $_cerchia = $em->getRepository('ES\MessengerBundle\Entity\Cerchia');
        $find = $_cerchia->findOneBy(array('proprietario' => $utente->getId(), 'cerchia' => $cerc['nome']));
        $cerchia = null;
        if (!$find) {
            try {
                $em->beginTransaction();
                $cerchia = $this->cerateCerchia($cerc['nome'], $utente, false, $em);
                $contatti = isset($cerc['users']) ? json_decode($cerc['users']) : false;
                if ($contatti) {
                    $_contatto = $em->getRepository('ES\MessengerBundle\Entity\Rubrica');
                    foreach ($contatti as $contatto) {
                        $cerchia->addContatto($_contatto->find($contatto));
                    }
                }
                $em->persist($cerchia);
                $em->flush();
                $em->commit();
                return array(
                    'cer' => $cerchia,
                    'cerchia' => '',
                );
            } catch (\Exception $e) {
                $em->rollback();
                throw $e;
            }
        }
        echo "KO";
        exit;
    }

    /**
     * @Route("-label", name="label_rubrica")
     * @Template("EcoSeekrMessengerBundle:Rubrica:moduli/risultati.html.twig")
     */
    public function labelAction() {
        $em = $this->getEm();
        $_rubrica = $em->getRepository('ES\MessengerBundle\Entity\Rubrica');
        foreach ($_rubrica->findAll() as $rubrica) {
            $rubrica->setLabel($rubrica->getContatto()->getLabel(false));
            $em->persist($rubrica);
            $em->flush();
        }
        return $this->redirect($this->generateUrl('rubrica'));
    }

    /**
     * @Route("-filtra_contatto", name="filtra_contatto")
     * @Template("EcoSeekrMessengerBundle:Rubrica:moduli/risultati.html.twig")
     */
    public function cercaContattoAction() {
        $em = $this->getEm();
        $cerchia = $this->getRequest()->getSession()->get('cerchia', '');
        $utente = $this->getUtente();
        $ids = array();
        if ($cerchia == '') {
            foreach ($utente->getRubrica() as $contatto) {
                $ids[] = $contatto->getContatto()->getId();
            }
        } else {
            foreach ($utente->getCerchie() as $cerchie) {
                if (strtolower($cerchie->getCerchia()) == $cerchia) {
                    foreach ($utente->getRubrica() as $contatto) {
                        $ids[] = $contatto->getContatto()->getId();
                    }
                    break;
                }
            }
        }

        $request = $this->getRequest();
        $_rubrica = $em->getRepository('ES\MessengerBundle\Entity\Rubrica');
        $rubrica = $_rubrica->ricerca_per_lettera($utente, $ids, $request->get('lettera'));

        return array(
            'utenti_rubrica' => $rubrica,
            'lettera' => $request->get('lettera'),
                //'alfabeto' => Funzioni::alfabeto(),
        );

        //exit;
    }

    /**
     * @Route("-filtra_preferiti_rubrica", name="filtra_preferiti_rubrica")
     * @Template("EcoSeekrMessengerBundle:Rubrica:moduli/risultati.html.twig")
     */
    public function preferitoContattoAction() {
        $em = $this->getEm();

        $utente = $this->getUtente();
        $ids = array();
        foreach ($utente->getRubrica() as $contatto) {
            $ids[] = $contatto->getContatto()->getId();
        }

        $request = $this->getRequest();
        $_rubrica = $em->getRepository('ES\MessengerBundle\Entity\Rubrica');
        $rubrica = $_rubrica->filtra_preferiti($utente);

        return array(
            'utenti_rubrica' => $rubrica,
                //'lettera' => $request->get('lettera'),
                //'alfabeto' => Funzioni::alfabeto(),
        );

        //exit;
    }

    //Elimina contatto
    /**
     * @Route("-elimina_contatto", name="elimina_contatto", defaults={"_format"="json"})
     */
    public function eliminaContattoAction() {
        $em = $this->getEm();
        $request = $this->getRequest();
        $utente = $this->getUtente();
        $id_utenti = $request->get('id_contatto');
        if (strpos($id_utenti, ']') === false) {
            $id_utenti = array(intval($id_utenti));
        } else {
            $id_utenti = json_decode($id_utenti);
        }
        foreach ($id_utenti as $id_utente) {
            $_utente_eliminato = $em->getRepository('ES\MessengerBundle\Entity\Rubrica');
            $utente_eliminato = $_utente_eliminato->find($id_utente);
            $em->remove($utente_eliminato);
            $em->flush();
        }
        echo "ok";

        exit;
    }

    //contatto preferito
    /**
     * @Route("-contatto_preferito", name="contatto_preferito", defaults={"_format"="json"})
     */
    public function contattoPreferitoAction() {
        $em = $this->getEm();
        $request = $this->getRequest();
        $utente = $this->getUtente();
        $id_utente = $request->get('id_preferito');

        try {
            $em->beginTransaction();
            $_preferito = $em->getRepository('ES\MessengerBundle\Entity\Rubrica');
            $preferito = $_preferito->findOneBy(array('contatto' => $id_utente, 'proprietario' => $utente->getId()));
            $preferito->setPreferito(true);
            $em->persist($preferito);
            $em->flush();
            $em->commit();
            $em->flush();
            echo "ok";
        } catch (\Exception $e) {
            $em->rollback();
            echo "ko ({$e->getMessage()})";
        }

        exit;
    }

    //contatto preferito
    /**
     * @Route("-rimuovi_contatto_preferito", name="rimuovi_contatto_preferito", defaults={"_format"="json"})
     */
    public function rimuoviContattoPreferitoAction() {
        $em = $this->getEm();
        $request = $this->getRequest();
        $utente = $this->getUtente();
        $id_utente = $request->get('id_utente_rimosso');

        try {
            $em->beginTransaction();
            $_preferito = $em->getRepository('ES\MessengerBundle\Entity\Rubrica');
            $preferito = $_preferito->findOneBy(array('contatto' => $id_utente, 'proprietario' => $utente->getId()));
            $preferito->setPreferito(false);
            $em->persist($preferito);
            $em->flush();
            $em->commit();
            echo "ok";
        } catch (\Exception $e) {
            $em->rollback();
            echo "ko ({$e->getMessage()})";
        }

        exit;
    }

    //Recupera Contatto per invio singolo della mail 

    /**
     * @Route("-nuova/nuova_email", name="nuova_email")
     * @Template("EcoSeekrMessengerBundle:Rubrica:mail/nuovaMail.html.twig")
     */
    public function nuovaDiscussioneAction() {
        $em = $this->getEm();
        $request = $this->getRequest();
        $id_utente = $request->get('id_utente');
        $_destinatario = $em->getRepository('ES\ACLBundle\Entity\User');
        $destinatario = $_destinatario->findOneBy(array('id' => $id_utente));

        $utente = $this->getUtente();
        $messaggio = new Messaggio($utente);
        return array(
            'result' => $messaggio,
            'utente' => $destinatario,
        );
    }

    /**
     * @Route("-aggiungi/destinatario", name="destinatario_email", defaults={"_format"="json"})
     * @Template("EcoSeekrMessengerBundle:Rubrica:mail/nuovaMail.html.twig")
     */
    public function aggiungiContattoAction() {
        $em = $this->getEm();
        $request = $this->getRequest();
        $id_utente = $request->get('id');
        $utente = $this->getUtente();
        $_destinatario = $em->getRepository('ES\ACLBundle\Entity\User');
        $destinatario = $_destinatario->findOneBy(array('id' => $id_utente));

        //$out = array();

        $out = array(
            'id' => $destinatario->getId() ? : $result->getEmail(),
            'email' => $destinatario->getEmail(),
            'nome_completo' => $destinatario->getNomeCompleto(),
        );

        echo json_encode($out);
        exit;
    }

    /**
     * @Route("-aggiungi_rubrica", name="url_aggiungi_rubrica_invito", defaults={"_format"="json"})
     * @Template("EcoSeekrMessengerBundle:Rubrica/formInvito.html.twig")
     */
    public function aggiungiContattoRubricaAction() {
        $em = $this->getEm();
        $invitante = $this->getUtente();
        $request = $this->getRequest();
        $data = $request->get('invito_rubrica');
        $_user = $em->getRepository('ES\ACLBundle\Entity\User');
        $user = $_user->findOneBy(array('email' => $data["email_destinatario"]));

        
       // foreach ($out as $email) {
            try {
                $em->beginTransaction();
                 //if ($email["valida"] == "ok" || $email["valida"] == "esistente") {
                        
                        if (!$user) {
                            $user = new User();
                            $user->setEmail($data['email_destinatario']);
                            $user->setNome($data['nome']);
                            $user->setCognome($data['cognome']);
                            $user->setNomeAzienda($data['nome_azienda']);
                            $user->setEmailVerificata(false);
                            $em->persist($user);
                            $em->flush();
                            
//                            $pre_invito = new \ES\SocialBundle\Entity\PreInviti();
//                            $pre_invito->setEmail($data['email_destinatario']);
//                            $pre_invito->setCodice("rubrica");
//                            $pre_invito->setUtente($this->getUtente());
//                            $em->persist($pre_invito);
//                            $em->flush();
                            
                            $invito = new \ES\PartnershipBundle\Entity\ProfInvito($invitante, $user, $this, "RUB");
                            $em->persist($invito);
                            $em->flush();
                        }

//                        $_pre_invito = $__pre_invito->findOneBy(array('email' => $data['email_destinatario']));
//                        
//                        if(!$_pre_invito) {
//                            $pre_invito = new \ES\SocialBundle\Entity\PreInviti();
//                            $pre_invito->setEmail($data['email_destinatario']);
//                            $pre_invito->setCodice("rubrica");
//                            $pre_invito->setUtente($this->getUtente());
//                            $em->persist($pre_invito);
//                            $em->flush();
//                        }
                         
                   // }
                    
             $em->commit();                        
            } catch (\Exception $e) {
                
                $out = array(
                    'status' => "KO",
                );
                
                $em->rollback();
                throw $e;
            }
        //}

        $out = array(
            'status' => "OK",
            'id_utente' => $user->getId(),
            'utente' => $user,
        );


        echo json_encode($out);
        exit;
    }

    /**
     * @Route("-crea_box_rubrica", name="crea_box_rubrica")
     * @Template("EcoSeekrMessengerBundle:Rubrica:moduli/risultati.html.twig")
     */
    public function creaBoxAction() {
        $utente = $this->getUtente();
        $em = $this->getEm();
        $utenti = array();
        foreach ($utente->getRubrica() as $contatto) {
            $utenti[] = array(
                'contatto' => $contatto->getContatto(), //ID DI CHI HO GIA IN RUBRICA
                'preferito' => $contatto->getPreferito(),
            );
        }

        return array(
            'utenti_rubrica' => $utenti,
                //'alfabeto' => Funzioni::alfabeto(),
        );
    }

    /**
     * @Route("-aggiungi_provider", name="aggiungi_provider")
     */
    public function registraIndexEmailAction() {
        return $this->registraIndexEmail('rubrica');
    }
    
    
    /**
     * @Route("-salva_email_ajax", name="salva_email_ajax")
     */
    public function salvaEmailAction() {
        $em = $this->getEm();
        $invitante = $this->getUtente();
        $request = $this->getRequest();
        $out = $request->get('invito_ecoseekr');
        
        $__pre_invito = $em->getRepository('ES\SocialBundle\Entity\PreInviti');
        
        foreach ($out as $email) {
            try {
                $em->beginTransaction();
                 if ($email["valida"] == "ok" || $email["valida"] == "esistente") {
                     
//                        $_pre_invito = $__pre_invito->findOneBy(array('email' => $email["email"]));
//                        
//                        if(!$_pre_invito) {
                            $pre_invito = new \ES\SocialBundle\Entity\PreInviti();
                            $pre_invito->setEmail($email["email"]);
                            $pre_invito->setCodice($email["provenienza"]);
                            $pre_invito->setUtente($this->getUtente());
                            $em->persist($pre_invito);
                            $em->flush();
                        //}
                         
                    }
                    
             $em->commit();                        
            } catch (\Exception $e) {
                $em->rollback();
                throw $e;
            }
        }
        
        exit;
    }

    /**
     * @Route("-aggiungi_provider_ajax", name="aggiungi_provider_ajax")
     */
    public function registraIndexEmail() {
        $em = $this->getEm();
        $invitante = $this->getUtente();
        $request = $this->getRequest();
        $out = $request->get('invito_ecoseekr');
        $_rubrica = $em->getRepository('ES\MessengerBundle\Entity\Rubrica');
        $_user = $em->getRepository('ES\ACLBundle\Entity\User');
        $professionista = $this->getUtente()->getProfessionista();
        
        
        
        foreach ($out as $email) {
            try {
                $em->beginTransaction();
                set_time_limit(3600);
                if ($email["valida"] == "ok" || $email["valida"] == "esistente") {
                    $user = $_user->findOneBy(array('email' => $email["email"]));

                    if($user) {
                        $user = new \ES\ACLBundle\Entity\User();
                        $user->setEmail($email["email"]);
                        $user->setEmailVerificata(false);
                        $user->setNomeCompleto($email["email"]);
                        $em->persist($user);
                        $em->flush();
                        $invito = new \ES\PartnershipBundle\Entity\ProfInvito($invitante, $user, $this);
                        $em->persist($invito);
                        $em->flush();
                        $professionista->setFaseRegistrazione(\ES\ConfigurazioneBundle\Controller\ConfigurazioneBaseController::$PROF_VIRAL);
                        $contatto = $_user->find($user->getId());
                    } else {
                        $contatto = $_user->find($email["id_utente"]);
                    }

                    $rubrica = $_rubrica->findOneBy(array(
                        "contatto" => $contatto->getId(),
                        "proprietario" => $invitante->getId(),
                            ));

                    if (!$rubrica) {
                        $rubrica = new \ES\MessengerBundle\Entity\Rubrica();
                        $rubrica->setProprietario($invitante);
                        $rubrica->setContatto($contatto);
                        $em->persist($rubrica);
                        $em->flush();
                    }
                }

                $em->persist($professionista);
                $em->flush();
                $em->commit();
            } catch (\Exception $e) {
                $em->rollback();
                throw $e;
            }
        }

        exit;
        //return $this->redirect($this->generateUrl($redirect));
    }

    /**
     * 
     * @return \Symfony\Component\DependencyInjection\ContainerInterface
     */
    public function getContainer() {
        return $this->container;
    }

}
