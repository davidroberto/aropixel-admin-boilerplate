<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Param;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class ParamType extends AbstractType
{
    private const DATES_PERIOD_FROM_NOW = 50;

    /** @var AuthorizationCheckerInterface $authorizationChecker */
    public $authorizationChecker;

    /**
     * ParamType constructor.
     *
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($this->authorizationChecker->isGranted('ROLE_HYPER_ADMIN')) {
            $builder
                ->add('id', null, ['label' => 'Code'])
                ->add('label', null, ['label' => 'Paramètre'])
                ->add('value', null, ['label' => 'Valeur'])
//                ->add('createdAt', DateTimeType::class, array(
//                    'required' => false,
//                    'date_widget' => 'single_text',
//                    'time_widget' => 'single_text',
//                    'date_format' => 'yyyy-MM-dd',
//                    'years' => range(date('Y') - self::DATES_PERIOD_FROM_NOW, date('Y') + 5)
//                ))
            ;
        } else {
            /** @var Param $data */
            $data = $options['data'];

            $builder
                ->add('value', null, ['label' => $data ? $data->getLabel() : 'Paramètre'])
//                ->add('createdAt', DateTimeType::class, array(
//                    'required' => false,
//                    'date_widget' => 'single_text',
//                    'time_widget' => 'single_text',
//                    'date_format' => 'yyyy-MM-dd',
//                    'years' => range(date('Y') - self::DATES_PERIOD_FROM_NOW, date('Y') + 5)
//                ))
            ;
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Param::class,
        ]);
    }
}
