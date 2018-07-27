<?php

namespace ES\OperatoriBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RichiestaType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('ragione_sociale', null, array(
                    'translation_domain' => 'ESOperatoriBundle',
                    'label' => 'registrazione.ragione_sociale',
                    'attr' => array('class' => 'small'),
                    'required' => true
                ))
                ->add('partita_iva', null, array(
                    'translation_domain' => 'ESOperatoriBundle',
                    'label' => 'registrazione.partita_iva',
                    'attr' => array('class' => 'small', 'maxlength' => '11'),
                    'required' => true
                ))
                ->add('codice_fiscale_azienda', null, array(
                    'translation_domain' => 'ESOperatoriBundle',
                    'label' => 'registrazione.codice_fiscale_azienda',
                    'attr' => array('class' => 'small'),
                    'required' => true
                ))
                ->add('indirizzo', null, array(
                    'translation_domain' => 'ESOperatoriBundle',
                    'label' => 'registrazione.indirizzo',
                    'attr' => array('class' => 'small'),
                    'required' => true
                ))
                ->add('attivita_principale', 'choice', array(
                    'translation_domain' => 'ESOperatoriBundle',
                    'label' => 'registrazione.attivita_principale.label',
                    'choices' => array(
                        '' => 'registrazione.attivita_principale.seleziona',
                        'impianto' => 'registrazione.attivita_principale.impianto',
                        'discarica' => 'registrazione.attivita_principale.discarica',
                        'raccoglitore' => 'registrazione.attivita_principale.raccoglitori',
                        'trasportatore' => 'registrazione.attivita_principale.trasportatore',
                        'servizi_ambientali' => 'registrazione.attivita_principale.servizi_ambientali',
                        'laboratori' => 'registrazione.attivita_principale.laboratori',
                        'demolizioni' => 'registrazione.attivita_principale.demolizioni',
                        'spurghi' => 'registrazione.attivita_principale.spurghi',
                        'bonifiche' => 'registrazione.attivita_principale.bonifiche',
                        'rottamazione' => 'registrazione.attivita_principale.rottamazione',
                        'raee' => 'registrazione.attivita_principale.raee',
                        'olio_minerale' => 'registrazione.attivita_principale.olio_minerale',
                        'olio_vegetale' => 'registrazione.attivita_principale.olio_vegetale',
                    ),
                ))
                ->add('impianto', null, array(
                    'translation_domain' => 'ESOperatoriBundle',
                    'label' => 'registrazione.attivita_secondarie.impianto',
                    'attr' => array('class' => 'check_secondario impianto checkbox-base', 'onclick' => 'select_secondario($(this))'),
                    'required' => false,
                ))
                ->add('discarica', null, array(
                    'translation_domain' => 'ESOperatoriBundle',
                    'label' => 'registrazione.attivita_secondarie.discarica',
                    'attr' => array('class' => 'check_secondario discarica checkbox-base', 'onclick' => 'select_secondario($(this))'),
                    'required' => false,
                ))
                ->add('raccoglitore', null, array(
                    'translation_domain' => 'ESOperatoriBundle',
                    'label' => 'registrazione.attivita_secondarie.raccoglitore',
                    'attr' => array('class' => 'check_secondario raccoglitore checkbox-base', 'onclick' => 'select_secondario($(this))'),
                    'required' => false,
                ))
                ->add('trasportatore', null, array(
                    'translation_domain' => 'ESOperatoriBundle',
                    'label' => 'registrazione.attivita_secondarie.trasportatore',
                    'attr' => array('class' => 'check_secondario trasportatore checkbox-base', 'onclick' => 'select_secondario($(this))'),
                    'required' => false,
                ))
                ->add('servizi', null, array(
                    'translation_domain' => 'ESOperatoriBundle',
                    'label' => 'registrazione.attivita_secondarie.servizi_ambientali',
                    'attr' => array('class' => 'check_secondario servizi_ambientali checkbox-base', 'onclick' => 'select_secondario($(this))'),
                    'required' => false,
                ))
                ->add('laboratorio', null, array(
                    'translation_domain' => 'ESOperatoriBundle',
                    'label' => 'registrazione.attivita_secondarie.laboratori',
                    'attr' => array('class' => 'check_secondario laboratori checkbox-base', 'onclick' => 'select_secondario($(this))'),
                    'required' => false,
                ))
                ->add('demolizioni', null, array(
                    'translation_domain' => 'ESOperatoriBundle',
                    'label' => 'registrazione.attivita_secondarie.demolizioni',
                    'attr' => array('class' => 'check_secondario demolizioni checkbox-base', 'onclick' => 'select_secondario($(this))'),
                    'required' => false,
                ))
                ->add('spurghi', null, array(
                    'translation_domain' => 'ESOperatoriBundle',
                    'label' => 'registrazione.attivita_secondarie.spurghi',
                    'attr' => array('class' => 'check_secondario spurghi checkbox-base', 'onclick' => 'select_secondario($(this))'),
                    'required' => false,
                ))
                ->add('bonifiche', null, array(
                    'translation_domain' => 'ESOperatoriBundle',
                    'label' => 'registrazione.attivita_secondarie.bonifiche',
                    'attr' => array('class' => 'check_secondario bonifiche checkbox-base', 'onclick' => 'select_secondario($(this))'),
                    'required' => false,
                ))
                ->add('rottamazione', null, array(
                    'translation_domain' => 'ESOperatoriBundle',
                    'label' => 'registrazione.attivita_secondarie.rottamazione',
                    'attr' => array('class' => 'check_secondario rottamazione checkbox-base', 'onclick' => 'select_secondario($(this))'),
                    'required' => false,
                ))
                ->add('raee', null, array(
                    'translation_domain' => 'ESOperatoriBundle',
                    'label' => 'registrazione.attivita_secondarie.raee',
                    'attr' => array('class' => 'check_secondario raee checkbox-base', 'onclick' => 'select_secondario($(this))'),
                    'required' => false,
                ))
                ->add('olio_minerale', null, array(
                    'translation_domain' => 'ESOperatoriBundle',
                    'label' => 'registrazione.attivita_secondarie.olio_minerale',
                    'attr' => array('class' => 'check_secondario olio_minerale checkbox-base', 'onclick' => 'select_secondario($(this))'),
                    'required' => false,
                ))
                ->add('olio_vegetale', null, array(
                    'translation_domain' => 'ESOperatoriBundle',
                    'label' => 'registrazione.attivita_secondarie.olio_vegetale',
                    'attr' => array('class' => 'check_secondario olio_vegetale checkbox-base', 'onclick' => 'select_secondario($(this))'),
                    'required' => false,
                ))
                ->add('telefono', null, array(
                    'translation_domain' => 'ESOperatoriBundle',
                    'label' => 'registrazione.telefono',
                    'attr' => array('class' => 'small'),
                    'required' => true
                ))
                ->add('fax', null, array(
                    'translation_domain' => 'ESOperatoriBundle',
                    'label' => 'registrazione.fax',
                    'attr' => array('class' => 'small'),
                    'required' => false,
                ))
                ->add('email', null, array(
                    'translation_domain' => 'ESOperatoriBundle',
                    'label' => 'registrazione.email',
                    'attr' => array('class' => 'small')
                ))
                ->add('email_pec', null, array(
                    'translation_domain' => 'ESOperatoriBundle',
                    'label' => 'registrazione.email_pec',
                    'attr' => array('class' => 'small'),
                    'required' => false,
                ))
                ->add('referente', null, array(
                    'translation_domain' => 'ESOperatoriBundle',
                    'label' => 'registrazione.referente',
                    'attr' => array('class' => 'small')
                ))
                ->add('ruolo', 'text', array(
                    'translation_domain' => 'ESOperatoriBundle',
                    'label' => 'registrazione.ruolo',
                    'attr' => array('class' => 'small'),
                    'required' => false,
                ))
                ->add('sito_web', null, array(
                    'translation_domain' => 'ESOperatoriBundle',
                    'label' => 'registrazione.sito_web',
                    'attr' => array('class' => 'small'),
                    'required' => false,
                ))
        ;
    }

    public function buildView(\Symfony\Component\Form\FormView $view, \Symfony\Component\Form\FormInterface $form, array $options) {
        $view->setAttribute('fieldsets', array(
            array(
                'translation_domain' => 'ESOperatoriBundle',
                'legend' => 'registrazione.fieldset_titolo_1',
                'content' => array(
                    'ragione_sociale',
                    'partita_iva',
                    'codice_fiscale_azienda',
                    'indirizzo'
                )
            ),
            array(
                'translation_domain' => 'ESOperatoriBundle',
                'legend' => 'registrazione.fieldset_titolo_2',
                'content' => array(
                    'attivita_principale',
                    'registrazione.attivita_secondarie.label' => array(
                        'impianto',
                        'discarica',
                        'raccoglitore',
                        'trasportatore',
                        'servizi',
                        'laboratorio',
                        'demolizioni',
                        'spurghi',
                        'bonifiche',
                        'rottamazione',
                        'raee',
                        'olio_minerale',
                        'olio_vegetale',
                    ),
                )
            ),
            array(
                'legend' => 'registrazione.fieldset_titolo_3',
                'translation_domain' => 'ESOperatoriBundle',
                'content' => array(
                    'telefono',
                    'fax',
                    'email',
                    'email_pec',
                    'referente',
                    'sito_web',
                    'ruolo'
                )
            ),
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'ES\OperatoriBundle\Entity\Richiesta'
        ));
    }

    public function getName() {
        return 'form_richiesta';
    }

}
