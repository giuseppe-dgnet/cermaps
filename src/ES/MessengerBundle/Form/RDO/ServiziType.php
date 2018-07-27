<?php

namespace ES\MessengerBundle\Form\RDO;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ServiziType extends AbstractType
{
    private $on_save;

    public function __construct($on_save) {
        $this->on_save = $on_save;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $comune_id = $this->on_save;
        
        $builder
                ->add('from_nome', null, array(
                    'translation_domain' => 'FOSUserBundle',
                    'label' => 'form.nome',
                    'attr' => array("style" => "width: 275px;", "class" => "input")
                ))
                ->add('from_cognome', null, array(
                    'translation_domain' => 'FOSUserBundle',
                    'label' => 'form.cognome',
                    'attr' => array("style" => "width: 275px;", "class" => "input")
                ))
                ->add('from_azienda', null, array(
                    'translation_domain' => 'FOSUserBundle',
                    'label' => 'form.ragione_sociale',
                    'attr' => array("style" => "width: 275px;", "class" => "input")
                ))
                ->add('from_email', 'repeated', array(
                    'translation_domain' => 'FOSUserBundle',
                    'options' => array('attr' => array("style" => "width: 275px;", "class" => "input")),
                    'first_options' => array('label' => 'form.email'),
                    'second_options' => array('label' => 'form.email_confirmation'),
                    'invalid_message' => 'fos_user.email.mismatch',
                ))
                ->add('testo', null, array(
                    'translation_domain' => 'FOSUserBundle',
                    'label' => 'form.testo',
                    'attr' => array("style" => "width: 275px;")
                ))
                ->add('telefono', null, array(
                    'translation_domain' => 'FOSUserBundle',
                    'label' => 'form.telefono',
                    'attr' => array("style" => "width: 275px;", "class" => "input")
                ))
                ->add('indirizzo', null, array(
                    'translation_domain' => 'FOSUserBundle',
                    'label' => 'form.indirizzo',
                    'required' => true,
                    'attr' => array("style" => "width: 275px"),
                ))
                 ->add('geo', 'text', array(
                    'translation_domain' => 'FOSUserBundle',
                    'label' => 'form.geo',
                    'mapped' => false,
                    'attr' => array("style" => "width: 275px")
                ))
                ->add('cap', 'hidden', array())
                ->add('latitudine', 'hidden', array()) //hidden
                ->add('longitudine', 'hidden', array()) //hidden
                ->add('servizi_list', 'hidden', array()) //array dell'id demm Mps selezionati
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
                    ->add('from_utente')
            ;
        } else {
            $builder
                    ->add('comune', 'hidden', array()) //hidden
                    ->add('from_utente', 'hidden', array()) //hidden
            ;
        }
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ES\MessengerBundle\Entity\RDO\Servizi'
        ));
    }

    public function getName()
    {
        return 'servizi';
    }
}
