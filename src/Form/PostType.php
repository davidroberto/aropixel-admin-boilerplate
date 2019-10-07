<?php

declare(strict_types=1);

namespace App\Form;

use Aropixel\AdminBundle\Form\Type\Image\Single\ImageType;
use App\Entity\Post;
use App\Entity\PostCategory;
use App\Entity\PostImage;
use App\Entity\PostImageCrop;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    private const DATES_PERIOD_FROM_NOW = 50;

    /** @var string */
    private $categoryMode;

    /**
     * PostType constructor.
     */
    public function __construct($categoryMode)
    {
        $this->categoryMode = $categoryMode;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, ['label' => 'Titre'])
            ->add('excerpt', null, ['label' => 'Chapeau'])
            ->add('description', null, ['label' => 'Description', 'attr' => ['class' => 'ckeditor']])
            ->add('slug', HiddenType::class)
            ->add('metaTitle', null, ['label' => 'Meta title'])
            ->add('metaDescription', null, ['label' => 'Meta description'])
            ->add('metaKeywords', null, ['label' => 'Meta keywords'])
            ->add('image', ImageType::class, [
                'data_class' => PostImage::class,
                'crop_class' => PostImageCrop::class,
            ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Oui' => 'online',
                    'Non' => 'offline',
                ],
                'expanded' => true,
            ])
            ->add('createdAt', DateTimeType::class, [
                'required' => false,
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'date_format' => 'yyyy-MM-dd',
            ])
            ->add('publishAt', null, [
                'required' => false,
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'date_format' => 'yyyy-MM-dd',
                'years' => range(date('Y') - self::DATES_PERIOD_FROM_NOW, date('Y') + self::DATES_PERIOD_FROM_NOW),
            ])
            ->add('publishUntil', null, [
                'required' => false,
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'date_format' => 'yyyy-MM-dd',
                'years' => range(date('Y') - self::DATES_PERIOD_FROM_NOW, date('Y') + self::DATES_PERIOD_FROM_NOW),
            ])
        ;

        if ('category' == $this->categoryMode) {
            $builder
                ->add('category', EntityType::class, [
                    'class' => PostCategory::class,
                    'required' => false,
                    'label' => 'Catégorie',
                    'placeholder' => 'Sélectionner une catégorie',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('c')
                            ->orderBy('c.position', 'ASC');
                    },
                    'choice_label' => 'name',
                ])
            ;
        } elseif ('tags' == $this->categoryMode) {
            $builder
                ->add('tags', EntityType::class, [
                    'class' => PostCategory::class,
                    'multiple' => true,
                    'required' => false,
                    'label' => 'Tags',
                    'placeholder' => 'Sélectionner des catégorie',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('c')
                            ->orderBy('c.position', 'ASC');
                    },
                    'choice_label' => 'name',
                ])
            ;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
