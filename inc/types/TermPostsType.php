<?php

namespace Ajaxy\Forms\Inc\Types;

use Ajaxy\Forms\Inc\Types\Data\Terms;
use Isolated\Symfony\Component\Form\AbstractType;
use Isolated\Symfony\Component\Form\ChoiceList\ChoiceList;
use Isolated\Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Isolated\Symfony\Component\Form\FormBuilderInterface;
use Isolated\Symfony\Component\Form\FormError;
use Isolated\Symfony\Component\Form\FormEvent;
use Isolated\Symfony\Component\Form\FormEvents;
use Isolated\Symfony\Component\Form\FormInterface;
use Isolated\Symfony\Component\Form\FormView;
use Isolated\Symfony\Component\OptionsResolver\Options;
use Isolated\Symfony\Component\OptionsResolver\OptionsResolver;

class TermPostsType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'error_bubbling' => false,
            'compound' => true,
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
            'loading_message' => 'Loading...',
            'not_found_message' => 'Nothing found',
            'invalid_message' => 'Please select a valid option.',

            'post_placeholder'          => '',
            'post_orderby'          => 'date',
            'post_order'            => 'DESC',
            'post_include'          => '',
            'post_exclude'          => '',
            'post_meta_key'         => '',
            'post_meta_value'       => '',
            'post_type'        => 'post',
            'multiple'        => '',
            'post_status'        => 'publish',
            // 'required'        => '',
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
        $resolver->setAllowedTypes('multiple', 'string');
        // $resolver->setAllowedTypes('required', 'string');
    }

    public function getBlockPrefix()
    {
        return 'term_posts';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $termFields = [
            'choice_loader' => $options['choice_loader'],
            'taxonomy' => $options['taxonomy'],
            'object_ids' => $options['object_ids'],
            'orderby' => $options['orderby'],
            'order' => $options['order'],
            'include' => $options['include'],
            'exclude' => $options['exclude'],
            'exclude_tree' => $options['exclude_tree'],
            'number' => $options['number'],
            'offset' => $options['offset'],
            'child_of' => $options['child_of'],
            'hide_empty' => $options['hide_empty'],
            'invalid_message' => $options['invalid_message'],
        ];

        $builder
            ->add('terms', TermType::class, $termFields);
        $builder
            ->add('posts', PostType::class, [
                'choice_loader' => new Loader\DummyChoiceLoader($options['post_placeholder']),
                'choices' => [
                    $options['post_placeholder'] => '',
                ],
                'multiple' => $options['multiple'] ?? '',
                'invalid_message' => $options['invalid_message'],
                'default_option' => $options['post_placeholder']
            ]);

        if (isset($options['required']) && intval($options['required']) == 1) {
            $builder->addEventListener(FormEvents::POST_SUBMIT, static function (FormEvent $event) use ($options) {
                $form = $event->getForm();
                $data = $event->getData();
                $term = $data['terms'] ?? null;
                if (!$term || trim($term) === '') {
                    $form->addError(new FormError($options['invalid_message']));
                    return;
                }

                $posts = array_filter((array)($data['posts'] ?? []));

                $multiple = $options['multiple'] ?? false;
                if (empty($posts) || (!$multiple && count($posts) > 1)) {
                    $form->addError(new FormError($options['invalid_message']));
                }
            });
        }
    }

    /**
     * @inheritDoc
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $postFields = [
            'taxonomy' => $options['taxonomy'],
            'post_type' => $options['post_type'],
            // 'post_status' => $options['post_status'],
        ];

        if (isset($options['post_orderby']) && $options['post_orderby'] != '') {
            $postFields['orderby'] = $options['post_orderby'];
            $postFields['order'] = $options['post_order'] ?? 'DESC';
        }

        if (isset($options['post_include']) && $options['post_include'] != '') {
            $postFields['include'] = $options['post_include'];
        }
        if (isset($options['post_exclude']) && $options['post_exclude'] != '') {
            $postFields['exclude'] = $options['post_exclude'];
        }

        if (isset($options['post_meta_key']) && $options['post_meta_key'] != '') {
            $postFields['meta_key'] = $options['post_meta_key'];
            $postFields['meta_value'] = $options['post_meta_value'];
        }

        $messages = [
            'loading' => $options['loading_message'],
            'not_found' => $options['not_found_message'],
            'default_option' => $options['post_placeholder'],
        ];
        $view->vars['attr']['data-term-posts'] = json_encode($postFields);
        $view->vars['attr']['data-messages'] = json_encode($messages);
        $view->vars['post'] = $postFields;
        $view->vars['messages'] = $messages;
    }
}
