<?php

namespace AppBundle\Form\Redmine;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('text')
            ->add('projectId')
            ->add('isVerified')
            ->add('publishedAt')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Redmine\Comment'
        ));
    }

    public function getBlockPrefix()
    {
        return 'appbundle_redmine_comment';
    }
}
