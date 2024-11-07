<?php

namespace Ajaxy\Forms\Inc\Types;

use Ajaxy\Forms\Inc\Types\Data\Posts;
use Isolated\Symfony\Component\Form\AbstractType;
use Isolated\Symfony\Component\Form\ChoiceList\ChoiceList;
use Isolated\Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Isolated\Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Isolated\Symfony\Component\OptionsResolver\Options;
use Isolated\Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'choice_loader' => function (Options $options) {
                $args = $options;
                if ($args['include'] && !is_array($args['include'])) {
                    $args['include'] = explode(',', $args['include']);
                }
                if ($args['exclude'] && !is_array($args['exclude'])) {
                    $args['exclude'] = explode(',', $args['exclude']);
                }

                return ChoiceList::loader($this, new CallbackChoiceLoader(static fn() => Posts::getAll($args)));
            },
            'numberposts'      => "-1",
            'category'         => "0",
            'orderby'          => 'date',
            'order'            => 'DESC',
            'include'          => '',
            'exclude'          => '',
            'meta_key'         => '',
            'meta_value'       => '',
            'post_type'        => 'post',
            'post_status'        => 'publish',
            'multiple'        => '',
            'default_option'        => '',
            'suppress_filters' => '1',
            'invalid_message' => 'Please select a valid option.',
        ]);

        $resolver->setAllowedTypes('numberposts', 'string');
        $resolver->setAllowedTypes('category', 'string');
        $resolver->setAllowedTypes('orderby', 'string');
        $resolver->setAllowedTypes('order', 'string');
        $resolver->setAllowedTypes('include', 'string');
        $resolver->setAllowedTypes('exclude', 'string');
        $resolver->setAllowedTypes('meta_key', 'string');
        $resolver->setAllowedTypes('meta_value', 'string');
        $resolver->setAllowedTypes('multiple', 'string');
        $resolver->setAllowedTypes('post_type', 'string');
        $resolver->setAllowedTypes('post_status', 'string');
        $resolver->setAllowedTypes('invalid_message', 'string');
        $resolver->setAllowedTypes('default_option', 'string');
        $resolver->setAllowedTypes('suppress_filters', 'string');
    }

    public function getParent(): ?string
    {
        return ChoiceType::class;
    }

    public function getBlockPrefix(): string
    {
        return 'posts';
    }
}
