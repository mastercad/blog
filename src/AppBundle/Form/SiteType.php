<?php
/**
 * Kurze Beschreibung der Datei
 *
 * Lange Beschreibung der Datei (wenn vorhanden)...
 *
 * @package    UNKNOWN
 * @copyright  Copyright (c) 2016 Unister GmbH
 * @author     Unister GmbH <entwicklung@unister.de>
 * @author     Andreas Kempe <andreas.kempe@unister.de>
 * @version    $Id$
 */
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class SiteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $aOptions)
    {
        $builder
            ->add('title', TextType::class, array(
                    'required' => true
                )
            )
//            ->add('parentSite', TextType::class)
            ->add('content', TextareaType::class, array(
                    'required' => true
                )
            )
            ->add('visible', CheckboxType::class, array(
                    'mapped' => false,
                    'required' => false
                )
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Site',
        ));
    }
}
