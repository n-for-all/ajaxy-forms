<?php

namespace Ajaxy\Forms\Inc\Types;

use Ajaxy\Forms\Inc\Types\Data\Terms;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TermType extends AbstractType
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

                return ChoiceList::loader($this, new CallbackChoiceLoader(static fn() => Terms::getAll($args)));
            },
           'taxonomy' => '',
            'object_ids' => '',
            'orderby' => 'name',
            'order' => 'ASC',
            'include' => '',
            'exclude' => '',
            'exclude_tree' => '',
            'number' => '',
            'offset' => '',
            'hide_empty' => '',
            'child_of' => '',
            'meta_key' => '',
            'meta_value' => '',
            'meta_compare' => '',
            'default_option' => '',
            'invalid_message' => 'Please select a valid option.',
        ]);

        $resolver->setAllowedTypes('taxonomy', 'string');
        $resolver->setAllowedTypes('object_ids', 'string');
        $resolver->setAllowedTypes('orderby', 'string');
        $resolver->setAllowedTypes('order', 'string');
        $resolver->setAllowedTypes('include', 'string');
        $resolver->setAllowedTypes('exclude', 'string');
        $resolver->setAllowedTypes('exclude_tree', 'string');
        $resolver->setAllowedTypes('number', 'string');
        $resolver->setAllowedTypes('offset', 'string');
        $resolver->setAllowedTypes('child_of', 'string');
        $resolver->setAllowedTypes('meta_key', 'string');
        $resolver->setAllowedTypes('hide_empty', 'string');
        $resolver->setAllowedTypes('meta_value', 'string');
        $resolver->setAllowedTypes('meta_compare', 'string');
        $resolver->setAllowedTypes('default_option', 'string');
        $resolver->setAllowedTypes('invalid_message', 'string');
    }

    public function getParent()
    {
        return ChoiceType::class;
    }

    public function getBlockPrefix()
    {
        return 'terms';
    }
}
