<?php

namespace Ajaxy\Forms\Inc\Types;

use Ajaxy\Forms\Inc\Types\Data\Terms;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RepeaterEndType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'name' => '',
            'mapped' => false,
            'allow_extra_fields' => true,
            'fields' => [],
            'allow_add' => '0',
            'allow_delete' => '0',
            'add_label' => 'Add',
            'delete_label' => 'Delete',
            'min' => '0',
            'max' => '-1',
            'invalid_message' => 'Please select a valid option.',
        ]);
        $resolver->setAllowedTypes('name', 'string');
        $resolver->setAllowedTypes('allow_add', 'string');
        $resolver->setAllowedTypes('allow_delete', 'string');
        $resolver->setAllowedTypes('add_label', 'string');
        $resolver->setAllowedTypes('delete_label', 'string');
        $resolver->setAllowedTypes('min', 'string');
        $resolver->setAllowedTypes('max', 'string');
        $resolver->setAllowedTypes('invalid_message', 'string');
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        foreach ($options['fields'] as $field) {
            $nOptions = $field['options'] ?? [];

            //remove the constraints on template fields
            $nOptions['constraints'] = [];
            $builder->add($field['name'] . '--index', $field['class'], $nOptions);
        }

        $builder->addEventListener(
            FormEvents::PRE_SUBMIT,
            function (FormEvent $event) use ($options) {
                $data = $event->getData();
                $form = $event->getForm();

                $fields = $options['fields'];
                // $name = $options['name'];

                $nData = [];

                $indexes = [];
                foreach ($data as $key => $value) {
                    $index = (int)preg_replace('/.*--(\d+)$/', '$1', $key);
                    $indexes[(string)$index] = $key;
                }

                foreach ($fields as $field) {
                    foreach ($indexes as $index => $key) {
                        $fieldName = $field['name'] . '--' . $index;
                        $form->add($fieldName, $field['class'], $field['options'] ?? []);
                        // if ($field['type'] == 'checkbox') {
                        //     $form->get($key)->addModelTransformer(new CallbackTransformer(
                        //         function ($activeAsString) {
                        //             return (bool)(int)$activeAsString;
                        //         },
                        //         function ($activeAsBoolean) {
                        //             return (string)(int)$activeAsBoolean;
                        //         }
                        //     ));
                        // }
                    }
                }
                // foreach ($data as $key => $value) {
                //     $fieldName = preg_replace('/--\d+$/', '', $key);
                //     foreach ($fields as $field) {
                //         if ($field['name'] == $fieldName) {
                //             $form->add($key, $field['class'], $field['options'] ?? []);
                //             if ($field['type'] == 'checkbox') {
                //                 $form->get($key)->addModelTransformer(new CallbackTransformer(
                //                     function ($activeAsString) {
                //                         return (bool)(int)$activeAsString;
                //                     },
                //                     function ($activeAsBoolean) {
                //                         return (string)(int)$activeAsBoolean;
                //                     }
                //                 ));
                //             }
                //             continue 2; // skip to next field
                //         }
                //     }

                //     $form->addError($key, 'Field not found');
                //     echo $key;
                //     $form->add($key, $field['class'], $field['options'] ?? []);
                //     if ($field['type'] == 'checkbox') {
                //         $form->get($key)->addModelTransformer(new CallbackTransformer(
                //             function ($activeAsString) {
                //                 return (bool)(int)$activeAsString;
                //             },
                //             function ($activeAsBoolean) {
                //                 return (string)(int)$activeAsBoolean;
                //             }
                //         ));
                //     }
                // }
            }
        );
    }

    /**
     * @inheritDoc
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['fields'] = $options['fields'];
        $view->vars['allow_add'] = $options['allow_add'] == '1';
        $view->vars['allow_delete'] = $options['allow_delete'] == '1';
        $view->vars['add_label'] = $options['add_label'];
        $view->vars['delete_label'] = $options['delete_label'];
        $view->vars['min'] = $options['min'];
        $view->vars['max'] = $options['max'];

        $view->vars['settings'] = [
            'allowAdd' => $options['allow_add'] == '1',
            'allowDelete' => $options['allow_delete'] == '1',
            'addLabel' => $options['add_label'],
            'deleteLabel' => $options['delete_label'],
            'min' => (int)$options['min'],
            'max' => (int)$options['max'],
        ];

        // parent::buildView($view, $form, $options);
    }

    /**
     * Get the type
     *
     * @date 2022-05-21
     *
     * @return string
     */
    public function getType()
    {
        return 'repeater_end';
    }

    public function getBlockPrefix()
    {
        return 'repeater_end';
    }
}
