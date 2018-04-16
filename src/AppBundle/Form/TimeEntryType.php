<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class TimeEntryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('project', ChoiceType::class, array(
                'choices' => $options['data']['projects'],
                'choices_as_values' => true,
                'empty_value' => '',
                'empty_data'  => null
            ))
            ->add('issue', ChoiceType::class, array(
                'choices' => $options['data']['issues'],
                'choices_as_values' => true,
                'empty_value' => '',
                'empty_data'  => null
            ))
            ->add('hours', null, array(
                'required' => true,
            ))
            ->add('comment', TextareaType::class, array(
                'required' => false,
            ))
            ->add('activity', ChoiceType::class, array(
                'choices' => $options['data']['activities'],
                'choices_as_values' => true,
            ))
            ->add('create', SubmitType::class, array(
                'attr' => array('class' => 'btn btn-primary'),
            ))
        ;
    }
}
