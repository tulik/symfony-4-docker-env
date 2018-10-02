<?php

/*
 * (c) Lukasz D. Tulikowski <lukasz.tulikowski@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Form\Filter;

use Lexik\Bundle\FormFilterBundle\Filter\Form\Type as Filters;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookFilterFormType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $sharedDateTimeFieldOptions = [
            'widget' => 'single_text',
            'format' => 'yyyy-MM-dd',
        ];

        $builder->add('isbn', Filters\TextFilterType::class)
            ->add('title', Filters\TextFilterType::class)
            ->add('description', Filters\TextFilterType::class)
            ->add('author', Filters\TextFilterType::class)
            ->add(
                'publicationDate', Filters\DateTimeRangeFilterType::class, [
                'left_datetime_options' => $sharedDateTimeFieldOptions,
                'right_datetime_options' => $sharedDateTimeFieldOptions,
                ]
            );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
            'validation_groups' => ['filtering'],
            ]
        );
    }
}
