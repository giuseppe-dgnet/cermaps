<?php

namespace ES\OperatoriBundle\Form\Showroom;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DatiSocietariType extends AbstractType {

    private $on_save;

    public function __construct($on_save) {
        $this->on_save = $on_save;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $comune_id = $this->on_save;

        $builder
                ->add('indirizzo', null, array(
                    'translation_domain' => 'ESOperatoriBundle',
                    'label' => 'registrazione.indirizzo',
                    'attr' => array('class' => 'small'),
                    'required' => true
                ))
                ->add('geo', 'text', array(
                    'translation_domain' => 'FOSUserBundle',
                    'label' => 'form.geo',
                    'mapped' => false,
                    'attr' => array('class' => 'small', 'style' => 'z-index: 20000;'),
                ))
                ->add('partita_iva', null, array(
                    'translation_domain' => 'ESOperatoriBundle',
                    'label' => 'registrazione.partita_iva',
                    'attr' => array('class' => 'small', 'maxlength' => '11'),
                    'required' => true
                ))
                ->add('codice_fiscale', null, array(
                    'translation_domain' => 'ESOperatoriBundle',
                    'label' => 'registrazione.codice_fiscale_azienda',
                    'attr' => array('class' => 'small'),
                    'required' => true
                ))
                ->add('codice_rae', null, array(
                    'translation_domain' => 'ESOperatoriBundle',
                    'label' => 'registrazione.codice_rae',
                    'attr' => array('class' => 'small'),
                    'required' => false
                ))
                ->add('cap', 'hidden', array()) //hidden
                ->add('latitudine', 'hidden', array()) //hidden
                ->add('longitudine', 'hidden', array()) //hidden
        ;
        if ($this->on_save) {
            $builder
                    ->add('comune', null, array(
                        'query_builder' => function(\Ephp\GeoBundle\Entity\GeoNamesRepository $er) use ($comune_id) {
                            return $er->createQueryBuilder('u')
                                    ->where('u.geonameid = :id')
                                    ->setParameter('id', $comune_id);
                        },
                    ))
            ;
        } else {
            $builder
                    ->add('comune', 'hidden', array()) //hidden
            ;
        }
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
