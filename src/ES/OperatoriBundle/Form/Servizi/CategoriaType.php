<?php

namespace ES\OperatoriBundle\Form\Servizi;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CategoriaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sigla')
            ->add('categoria')
            ->add('descrizione')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ES\OperatoriBundle\Entity\Servizi\Categoria'
        ));
    }

    public function getName()
    {
        return 'es_operatoribundle_servizi_categoriatype';
    }
}
