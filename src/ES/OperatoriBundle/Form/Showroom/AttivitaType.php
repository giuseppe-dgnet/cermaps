<?php

namespace ES\OperatoriBundle\Form\Showroom;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AttivitaType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
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
                ->add('impianto', null, array(
                    'attr' => array('class' => 'check_secondario impianto checkbox-base', 'onclick' => 'select_secondario($(this))'),
                    'required' => false,
                ))
                ->add('discarica', null, array(
                    'attr' => array('class' => 'check_secondario discarica checkbox-base', 'onclick' => 'select_secondario($(this))'),
                    'required' => false,
                ))
                ->add('raccoglitore', null, array(
                    'attr' => array('class' => 'check_secondario raccoglitore checkbox-base', 'onclick' => 'select_secondario($(this))'),
                    'required' => false,
                ))
                ->add('trasportatore', null, array(
                    'attr' => array('class' => 'check_secondario trasportatore checkbox-base', 'onclick' => 'select_secondario($(this))'),
                    'required' => false,
                ))
                ->add('servizi', null, array(
                    'attr' => array('class' => 'check_secondario servizi_ambientali checkbox-base', 'onclick' => 'select_secondario($(this))'),
                    'required' => false,
                ))
                ->add('laboratorio', null, array(
                    'attr' => array('class' => 'check_secondario laboratori checkbox-base', 'onclick' => 'select_secondario($(this))'),
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
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'ES\OperatoriBundle\Entity\Showroom'
        ));
    }

    public function getName() {
        return 'showroom';
    }

}
