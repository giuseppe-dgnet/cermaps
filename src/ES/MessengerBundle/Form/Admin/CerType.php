<?php

namespace ES\MessengerBundle\Form\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CerType extends AbstractType {

    protected $key;

    public function __construct($chiave) {
        $this->key = $chiave;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder
                ->add('note_admin', null, array(
                    'label' => 'Note',
                    'attr' => array(
                        "style" => "width: 450px; height: 50px",
                        "class" => "autoupdate showbutton",
                        )
                ))
                ->add('stato', null, array(
                    'empty_value' => 'NUOVA RICHIESTA',
                    'required' => false,
                    'attr' => array(
                        "class" => "autoupdate",
                        )
                ))

        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'ES\MessengerBundle\Entity\RDO\Cer'
        ));
    }

    public function getName() {
        return 'rdo_cer_'. $this->key;

    }

}
