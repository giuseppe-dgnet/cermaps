<?php

namespace ES\OperatoriBundle\Form\Showroom;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ContattiType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
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
                    'required' => true,
                    'attr' => array('class' => 'small')
                ))
                ->add('email_pec', null, array(
                    'translation_domain' => 'ESOperatoriBundle',
                    'label' => 'registrazione.email_pec',
                    'attr' => array('class' => 'small'),
                    'required' => false,
                ))
                ->add('sito', null, array(
                    'translation_domain' => 'ESOperatoriBundle',
                    'label' => 'registrazione.sito_web',
                    'attr' => array('class' => 'small'),
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
