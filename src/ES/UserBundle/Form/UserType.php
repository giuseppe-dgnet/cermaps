<?php

namespace ES\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('usernameCanonical')
            ->add('email')
            ->add('emailCanonical')
            ->add('enabled')
            ->add('salt')
            ->add('password')
            ->add('lastLogin')
            ->add('locked')
            ->add('expired')
            ->add('expiresAt')
            ->add('confirmationToken')
            ->add('passwordRequestedAt')
            ->add('roles')
            ->add('credentialsExpired')
            ->add('credentialsExpireAt')
            ->add('operatore')
            ->add('produttore')
            ->add('partita_iva')
            ->add('codice_fiscale')
            ->add('firstname')
            ->add('lastname')
            ->add('nickname')
            ->add('birthday')
            ->add('gender')
            ->add('locale')
            ->add('facebookId')
            ->add('showroom')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ES\UserBundle\Entity\User'
        ));
    }

    public function getName()
    {
        return 'es_userbundle_usertype';
    }
}
