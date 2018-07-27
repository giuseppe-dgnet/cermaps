<?php

namespace ES\MessengerBundle\Form\RDO;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MpsType extends AbstractType {
    
    private $on_save;

    public function __construct($on_save) {
        $this->on_save = $on_save;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $comune_id = $this->on_save;
        
        $builder
                ->add('from_nome', null, array(
                    'translation_domain' => 'FOSUserBundle',
                    'label' => 'form.nome',
                    'attr' => array("style" => "width: 288px;", "class" => "input")
                ))
                ->add('from_cognome', null, array(
                    'translation_domain' => 'FOSUserBundle',
                    'label' => 'form.cognome',
                    'attr' => array("style" => "width: 288px;", "class" => "input")
                ))
                ->add('from_azienda', null, array(
                    'translation_domain' => 'FOSUserBundle',
                    'label' => 'form.ragione_sociale',
                    'attr' => array("style" => "width: 288px;", "class" => "input")
                ))
                ->add('from_email', null, array(
                    'translation_domain' => 'FOSUserBundle',
                    'label' => 'form.email',
                    'attr' => array("style" => "width: 288px;", "class" => "input")
                ))
                ->add('testo', null, array(
                    'translation_domain' => 'FOSUserBundle',
                    'label' => 'Descrizione della materia prima seconda',
                    'attr' => array("style" => "width: 608px; height: 35px")
                ))
                ->add('telefono', null, array(
                    'translation_domain' => 'FOSUserBundle',
                    'label' => 'form.telefono',
                    'attr' => array("style" => "width: 288px;", "class" => "input")
                ))
                ->add('indirizzo', null, array(
                    'translation_domain' => 'FOSUserBundle',
                    'label' => 'form.indirizzo',
                    'required' => false,
                    'attr' => array("style" => "width: 288px"),
                ))
                 ->add('geo', 'text', array(
                    'translation_domain' => 'FOSUserBundle',
                    'label' => 'form.geo',
                    'mapped' => false,
                    'required' => false,
                    'attr' => array("style" => "width: 288px")
                ))
                
                ->add('cerca_mps', 'text', array(
                    'translation_domain' => 'FOSUserBundle',
                    'label' => 'Definizione materia prima seconda (es. \'carta\', \'pneumatici\' ecc..)',
                    'mapped' => false,
                    'attr' => array("style" => "width: 288px"),
                    'required' => false
                ))
                
                ->add('cap', 'hidden', array())
                ->add('latitudine', 'hidden', array()) //hidden
                ->add('longitudine', 'hidden', array()) //hidden
                ->add('mps_list', 'hidden', array()) //array dell'id demm Mps selezionati
        ;
//        if ($this->on_save) {
//            $builder
//                    ->add('comune', null, array(
//                        'query_builder' => function(\Ephp\GeoBundle\Entity\GeoNamesRepository $er) use ($comune_id) {
//                            return $er->createQueryBuilder('u')
//                                    ->where('u.geonameid = :id')
//                                    ->setParameter('id', $comune_id);
//                        },
//                    ))
//                    ->add('from_utente')
//                    ->add('from_showroom')
//
//            ;
//        } else {
//            $builder
//                    ->add('comune', 'hidden', array()) //hidden
//                    ->add('from_utente', 'hidden', array()) //hidden
//                    ->add('from_showroom', 'hidden') //hidden
//
//            ;
//        }
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'ES\MessengerBundle\Entity\RDO\Mps'
        ));
    }

    public function getName() {
        return 'mps';
    }

}
