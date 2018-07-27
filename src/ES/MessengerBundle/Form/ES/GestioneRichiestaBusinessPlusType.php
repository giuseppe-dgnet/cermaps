<?php

namespace ES\MessengerBundle\Form\ES;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class GestioneRichiestaBusinessPlusType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('preso_in_carico')
            ->add('contattato')
            ->add('note')
        ;
    }

    public function getName()
    {
        return 'ecoseekr_bundle_messengerbundle_es_richiestabusinessplustype';
    }
}
