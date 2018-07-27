<?php

namespace ES\OperatoriBundle\Form\Servizi;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ServizioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('categoria')
            ->add('servizio')
            ->add('descrizione')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ES\OperatoriBundle\Entity\Servizi\Servizio'
        ));
    }

    public function getName()
    {
        return 'servizio';
    }
}
