<?php

namespace App\Form;

use App\Entity\Post;
use App\Entity\Topic;
use App\Entity\User;
use Doctrine\DBAL\Types\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('reponse', TextType::class)
            ->add('creationDate', DateType::class, [
                'years' => range(date('Y'), date('Y')-70),
                'label' => "Date d'ajout"
            ])
            ->add('topic', EntityType::class, [
                'class' => Topic::class, 
                'choice_label' => 'topic',
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'user',
            ])
            ->getForm();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
