<?php

namespace ES\MessengerBundle\Form\RDO;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CerType extends AbstractType {

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
                    'label' => 'form.testo',
                    'attr' => array("style" => "width: 608px; height: 35px")
                ))
                ->add('telefono', null, array(
                    'translation_domain' => 'FOSUserBundle',
                    'label' => 'form.telefono',
                    'attr' => array("style" => "width: 288px;", "class" => "input")
                ))
                ->add('condizione_fisica', 'choice', array(
                    'choices' => array(
                        '' => 'Seleziona lo stato fisico del rifiuto',
                        'solido non polveroso' => 'Solido non polveroso',
                        'solido polverso' => 'Solido polveroso',
                        'liquido' => 'Liquido',
                        'fango palabile' => 'Fango Palabile',
                    ),
                    'attr' => array("class" => "select", "style" => "width: 310px"),
                    'required' => false,
                ))
                ->add('quantita', null, array(
                    'translation_domain' => 'FOSUserBundle',
                    'label' => 'form.quantita',
                    'attr' => array("style" => "width: 110px;", "class" => "input")
                ))
                ->add('uumm', 'choice', array(
                    'translation_domain' => 'FOSUserBundle',
                    'label' => 'form.unita',
                    'choices' => array(
                        '' => 'Unita di misura',
                        'tonnellate' => 'Tonnellate',
                        'quintali' => 'Quintali',
                        'kg' => 'Kg',
                        'metri_cubi' => 'Metri Cubi',
                        'litri' => 'Litri',
                    ),
                    'attr' => array("class" => "select", "style" => "width: 160px"),
                    'required' => false,
                ))
                ->add('periodicita', 'choice', array(
                    'translation_domain' => 'FOSUserBundle',
                    'label' => 'form.periodicita',
                    'choices' => array(
                        '' => 'Seleziona...',
                        'un solo recupero' => 'Un solo Recupero   ',
                        'settimanale' => 'Settimanale',
                        'mensile' => 'Mensile',
                        'bimestrale' => 'Bimestrale',
                        'Trimestrale' => 'Trimestrale',
                    ),
                    'attr' => array("class" => "select", "style" => "width: 310px"),
                    'required' => false,
                ))
                ->add('indirizzo', null, array(
                    'translation_domain' => 'FOSUserBundle',
                    'label' => 'form.indirizzo',
                    'required' => true,
                    'attr' => array("style" => "width: 288px"),
                    'required' => false
                ))
                ->add('geo', 'text', array(
                    'translation_domain' => 'FOSUserBundle',
                    'label' => 'form.geo',
                    'mapped' => false,
                    'attr' => array("style" => "width: 288px"),
                    'required' => false
                ))
                ->add('cerca_cer', 'text', array(
                    'translation_domain' => 'FOSUserBundle',
                    'label' => 'form.cer',
                    'mapped' => false,
                    'attr' => array("style" => "width: 288px"),
                    'required' => false
                ))
                ->add('cer_list', 'hidden', array()) //array dell'id dei Cer selezionati
                ->add('cap', 'hidden', array()) //hidden
                ->add('latitudine', 'hidden', array()) //hidden
                ->add('longitudine', 'hidden', array()) //hidden
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
//                                ;
//        } else {
//            $builder
//                    ->add('comune', 'hidden', array()) //hidden
//                    ->add('from_utente', 'hidden', array()) //hidden
//                    ->add('from_showroom', 'hidden') //hidden
//                    ;
//        }

        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'ES\MessengerBundle\Entity\RDO\Cer'
        ));
    }

    public function getName() {
        return 'rdo';
    }

}
