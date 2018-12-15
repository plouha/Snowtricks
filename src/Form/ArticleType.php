<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('content')
            ->add('categorie', EntityType::class, [
                "class" => Category::class,
                "choice_label" => "name"
            ])
            ->add('file', FileType::class, array ( 'label' => 'Image principale à uploader pour cet article', 'required' => false ))

            ->add('photos', CollectionType::class, [
                "entry_type"    => PhotoType::class,
                "allow_add"     => true,
                "allow_delete"  => true,
                "by_reference"  => false
            ])
            ->add('videos', CollectionType::class, [
                "entry_type"    => VideoType::class,
                "allow_add"     => true,
                "allow_delete"  => true,
                "by_reference"  => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
