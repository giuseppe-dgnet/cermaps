<?php

namespace ES\OperatoriBundle\Form\Showroom;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DescrizioneBreveType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('descrizione_attivita', null, array('attr' => array('class' => 'small margin-top-5', 'style' => 'width: 540px;')))
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
