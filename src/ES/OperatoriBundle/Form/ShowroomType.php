<?php

namespace ES\OperatoriBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ShowroomType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('ragione_sociale', null, array('attr' => array('style' => 'width: 400px;')))
                ->add('descrizione_attivita', null, array('attr' => array('style' => 'width: 400px;')))
                ->add('descrizione', null, array('attr' => array('style' => 'width: 400px; height: 200px;')))
                ->add('partita_iva')
                ->add('codice_fiscale')
                ->add('codice_rae')
                ->add('sito', 'url', array( 'required' => false,'attr' => array('style' => 'width: 200px;')))
                ->add('email', 'email', array('attr' => array('style' => 'width: 200px;')))
                ->add('email_pec', 'email', array(
                    'required' => false,
                    'attr' => array('style' => 'width: 200px;'),
                ))
                ->add('attivita_principale', 'choice', array(
                    'choices' => array(
                        '' => 'Seleziona la tua attività principale',
                        'impianto' => 'Impianto di trattamento o recupero',
                        'discarica' => 'Discarica',
                        'raccoglitore' => 'Raccoglitori',
                        'trasportatore' => 'Trasportatore',
                        'servizi_ambientali' => 'Servizi ambientali e smaltimenti',
                        'laboratori' => 'Laboratorio di analisi chimiche, ambientali',
                        'demolizioni' => 'Demolizioni immobili',
                        'spurghi' => 'Spurghi e serbatoi',
                        'bonifiche' => 'Bonifiche ambientali',
                        'rottamazione' => 'Rottamazione veicoli',
                        'raee' => 'Rottamazione apparecchiature elettriche',
                        'olio_minerale' => 'Recupero olio minerale',
                        'olio_vegetale' => 'Recupero olio vegetale',
                    ),
                ))
                ->add('telefono', null, array('attr' => array('style' => 'width: 200px;')))
                ->add('fax', null, array('attr' => array('style' => 'width: 200px;')))
                ->add('cellulare', null, array('attr' => array('style' => 'width: 200px;')))
                ->add('anga', 'hidden', array()) //hidden
                ->add('latitudine', 'hidden', array()) //hidden
                ->add('longitudine', 'hidden', array()) //hidden
                ->add('impianto', null, array(
                    'attr' => array('class' => 'check_secondario impianto cer_trattati', 'onclick' => 'select_secondario($(this))'),
                    'required' => false,
                ))
                ->add('discarica', null, array(
                    'attr' => array('class' => 'check_secondario discarica cer_trattati', 'onclick' => 'select_secondario($(this))'),
                    'required' => false,
                ))
                ->add('raccoglitore', null, array(
                    'attr' => array('class' => 'check_secondario raccoglitore', 'onclick' => 'select_secondario($(this))'),
                    'required' => false,
                ))
                ->add('trasportatore', null, array(
                    'attr' => array('class' => 'check_secondario trasportatore', 'onclick' => 'select_secondario($(this))'),
                    'required' => false,
                ))
                ->add('servizi', null, array(
                    'translation_domain' => 'ESOperatoriBundle',
                    'label' => 'registrazione.attivita_secondarie.servizi_ambientali',
                    'attr' => array('class' => 'check_secondario servizi_ambientali', 'onclick' => 'select_secondario($(this))'),
                    'required' => false,
                ))
                ->add('laboratorio', null, array(
                    'attr' => array('class' => 'check_secondario laboratori cer_trattati', 'onclick' => 'select_secondario($(this))'),
                    'required' => false,
                ))
                ->add('demolizioni', null, array(
                    'attr' => array('class' => 'check_secondario demolizioni checkbox-base', 'onclick' => 'select_secondario($(this))'),
                    'required' => false,
                ))
                ->add('spurghi', null, array(
                    'attr' => array('class' => 'check_secondario spurghi checkbox-base', 'onclick' => 'select_secondario($(this))'),
                    'required' => false,
                ))
                ->add('bonifiche', null, array(
                    'attr' => array('class' => 'check_secondario bonifiche checkbox-base', 'onclick' => 'select_secondario($(this))'),
                    'required' => false,
                ))
                ->add('rottamazione', null, array(
                    'attr' => array('class' => 'check_secondario rottamazione checkbox-base', 'onclick' => 'select_secondario($(this))'),
                    'required' => false,
                ))
                ->add('raee', null, array(
                    'attr' => array('class' => 'check_secondario raee checkbox-base', 'onclick' => 'select_secondario($(this))'),
                    'required' => false,
                ))
                ->add('olio_minerale', null, array(
                    'attr' => array('class' => 'check_secondario olio_minerale checkbox-base', 'onclick' => 'select_secondario($(this))'),
                    'required' => false,
                ))
                ->add('olio_vegetale', null, array(
                    'attr' => array('class' => 'check_secondario olio_vegetale checkbox-base', 'onclick' => 'select_secondario($(this))'),
                    'required' => false,
                ))
                ->add('sezione', null, array(
                    'read_only' => 'true'
                ))//read only
                ->add('numero_iscrizione', null, array(
                    'read_only' => 'true'
                ))//read only
                ->add('cap')
                ->add('indirizzo', null, array('attr' => array('style' => 'width: 200px;')))
                ->add('comune_testuale', null, array('label' => 'Comune', 'attr' => array('style' => 'width: 200px;')))
                ->add('elenco_cer', 'textarea', array('mapped' => false, 'required' => false, 'label' => 'Elenco CER', 'attr' => array('style' => 'width: 300px; height: 200px;')))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'ES\OperatoriBundle\Entity\Showroom'
        ));
    }

    public function getName() {
        return 'form_crea_showroom';
    }

}
