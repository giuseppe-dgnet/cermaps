<?php

/* ---------------------------------- 
 * 
 *  Sovrascrivo il Form del FOS 
 *  doc: https://github.com/FriendsOfSymfony/FOSUserBundle/blob/master/Resources/doc/overriding_forms.md
 * 
 * ---------------------------------- */

namespace ES\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class RegistrationFormType extends BaseType {

    public function buildForm(FormBuilderInterface $builder, array $options) {

        //questo prende tutte le voci del form FOS
        parent::buildForm($builder, $options);
        $builder->remove('username');  // we use email as the username
        //qui si possono aggiungere campi personalizzati
        /* CAMPO PERSONALIZZATO
         * $builder
          ->add('partita_iva', null, array(
          'label' => 'form.piva',
          'translation_domain' => 'FOSUserBundle',
          )
          ); */
        $builder
                ->add('ruolo', 'text', array(
                    'translation_domain' => 'ESOperatoriBundle',
                    'label' => 'registrazione.ruolo',
                    'attr' => array('class' => 'small'),
                    'required' => true,
                ))
                ->add('firstname', 'text', array(
                    'translation_domain' => 'ESOperatoriBundle',
                    'label' => 'Nome',
                    'attr' => array('class' => 'small'),
                    'required' => true
        ))
                ->add('lastname', 'text', array(
                    'translation_domain' => 'ESOperatoriBundle',
                    'label' => 'Cognome',
                    'attr' => array('class' => 'small'),
                    'required' => true
        ));
    }

    public function getName() {
        return 'es_user_registration';
    }

}