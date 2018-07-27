<?php

namespace ES\MessengerBundle\Form\ES;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class RichiestaBusinessPlusType extends AbstractType {

    public function buildForm(FormBuilder $builder, array $options) {
        $fatturato = array(
            'meno di 25k €uro',
            'da 25k €uro a 100k €uro',
            'da 100k €uro a 250k €uro',
            'da 250k €uro a 500k €uro',
            'da 500k €uro a 1M €uro',
            'da 1M €uro a 5M €uro',
            'oltre 5M €uro',
        );
        
        $tipo = array(
            'Azienda',
            'Professionista',
        );
        
        $builder
                ->add('nome')
                ->add('cognome')
                ->add('email', 'email')
                ->add('telefono')
                ->add('messaggio')
                
                ->add('tipo', 'choice', array('choices' => array_combine($tipo, $tipo), 'empty_value' => 'Seleziona'))
                ->add('azienda')
                ->add('codice_fiscale')
                ->add('comune', 'hidden')
                ->add('fatturato', 'choice', array('choices' => array_combine($fatturato, $fatturato), 'empty_value' => 'Seleziona'))
                ->add('link', 'url', array('required' => false))
                ->add('impresa', 'hidden')
        ;
    }

    public function getName() {
        return 'richiestabusinessplus';
    }

}
