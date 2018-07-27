<?php

namespace ES\OperatoriBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Ephp\Bundle\WsInvokerBundle\Functions\Funzioni;

/**
 * ElencoAziendeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AngaRepository extends EntityRepository {
    /*
     * Denominazione
     * Partita Iva
     * Cap
     * Comune
     */

    public function checkAnga(Anga $anga) {
        $out = array();
        $titolo = Funzioni::ripulisci($anga->getDenominazione());

        $parole = explode(' ', $titolo);
        $parola = '';
        foreach ($parole as $p) {
            if (strlen($p) > strlen($parola)) {
                $parola = $p;
            }
        }
        if (strlen($parola) < 4) {
            return $anga;
        }

        $q = $this->createQueryBuilder('g')->where('g.denominazione LIKE :tit')->setParameter('tit', "%{$parola}%");

        $results = $q->getQuery()->execute();

        $match = array();
        foreach ($results as $result) {
            /* @var $result Anga */
            $match[$result->getId()] = array(
                'grab' => $result,
                'punti' => 0,
                'note' => array(),
            );

            //TEST CODICE FISCALE
            $tlr = $result->getCodiceFiscale();
            $tlg = $anga->getCodiceFiscale();
            $tlg1 = $tlg[0];
            $tlg2 = $tlg[1];
            $tlt1 = 0;
            $tlt2 = 0;
            $tlt = 0;
            if ($tlr == '' || ($tlg1 == '' && $tlg2 == '')) {
                $tlt += 1;
            } else {
                $min = min(strlen($tlr), strlen($tlg1));
                $max = max(strlen($tlr), strlen($tlg1));
                $tlt1 = $max - $min;
                for ($i = 0; $i < $min; $i++) {
                    if ($tlr{$i} != $tlg1{$i}) {
                        $tlt1 += pow(2, ($max - $i));
                    }
                }
                $min = min(strlen($tlr), strlen($tlg2));
                $max = max(strlen($tlr), strlen($tlg2));
                $tlt2 = $max - $min;
                for ($i = 0; $i < $min; $i++) {
                    if ($tlr{$i} != $tlg2{$i}) {
                        $tlt2 += pow(2, ($max - $i));
                    }
                }
                $tlt = min($tlt1, $tlt2);
            }
            if ($tlt == 0) {
                $match[$result->getId()]['punti'] = 0;
                $match[$result->getId()]['note'][] = 'Corrispondenza esatta della partita iva/codice fiscale';
                goto fine;
                /* ########## TROVATO ########## */
            } else {
                if ($tlt == 1) {
                    $match[$result->getId()]['punti'] += 1;
                    $match[$result->getId()]['note'][] .= 'Corrispondenza della partita iva/codice fiscale non calcolabile';
                } else {
                    $match[$result->getId()]['note'][] .= "Corrispondenza della partita iva/codice fiscale: {$match[$result->getId()]['punti']}";
                    $match[$result->getId()]['punti'] += 5000;
                }
            }

            //TEST CAP
            if ($result->getCap() && $anga->getCap()) {
                //Più basso è il valore CAP più ci sono probabilità
                if ($result->getCap() == $anga->getCap()) {
                    $match[$result->getId()]['note'][] = 'CAP corrispondente';
                } elseif (substr($result->getCap(), 0, 2) == substr($anga->getCap(), 0, 2)) {
                    if (substr($result->getCap(), 3, 2) == '00' || substr($anga->getCap(), 3, 2) == '00') {
                        $match[$result->getId()]['punti'] += 2;
                        $match[$result->getId()]['note'][] = 'CAP generico corrispondente';
                    } elseif (substr($result->getCap(), 2, 1) == '1' && substr($anga->getCap(), 2, 1) == '1') {
                        $match[$result->getId()]['punti'] += 5;
                        $match[$result->getId()]['note'][] = 'CAP comunale corrispondente';
                    } else {
                        $match[$result->getId()]['punti'] = 2500;
                        $match[$result->getId()]['note'][] = 'CAP di comuni diversi';
                        goto fine;
                        /* ########## SCARTATO ########## */
                    }
                } else {
                    $match[$result->getId()]['punti'] = 5000;
                    $match[$result->getId()]['note'][] = 'CAP di province diverse';
                    goto fine;
                    /* ########## SCARTATO ########## */
                }
            } else {
                $match[$result->getId()]['punti'] = 1;
                $match[$result->getId()]['note'][] = 'CAP non confrontabile';
            }

            //TEST TITOLO 
            $tt = 0;
            $tr = Funzioni::ripulisci($result->getDenominazione());
            $tg = Funzioni::ripulisci($anga->getDenominazione());
            $tt += levenshtein($tr, $tg);
            $match[$result->getId()]['punti'] += $tt;
            $match[$result->getId()]['note'][] = "Confronto titolo: {$tt}/50";
            if ($tt == 0) {
                $match[$result->getId()]['punti'] = 0;
                goto fine;
                /* ########## TROVATO ########## */
            }
            if ($tt > 50) {
                $match[$result->getId()]['punti'] = 9999;
                goto fine;
                /* ########## SCARTATO ########## */
            }

            /* #################################### */
            /* ########## FINE CONFRONTO ########## */
            /* #################################### */
            fine:
            if ($match[$result->getId()]['punti'] < 9999) {
                $out[] = array('match' => $match[$result->getId()]['note']);
            }
        }
        return $out;
    }

}