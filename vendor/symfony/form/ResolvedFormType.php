<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Isolated\Symfony\Component\Form;

use Isolated\Symfony\Component\EventDispatcher\EventDispatcher;
use Isolated\Symfony\Component\Form\Exception\UnexpectedTypeException;
use Isolated\Symfony\Component\OptionsResolver\Exception\ExceptionInterface;
use Isolated\Symfony\Component\OptionsResolver\OptionsResolver;
/**
 * A wrapper for a form type and its extensions.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class ResolvedFormType implements ResolvedFormTypeInterface
{
    /**
     * @var FormTypeInterface
     */
    private $innerType;
    /**
     * @var FormTypeExtensionInterface[]
     */
    private $typeExtensions;
    /**
     * @var ResolvedFormTypeInterface|null
     */
    private $parent;
    /**
     * @var OptionsResolver
     */
    private $optionsResolver;
    /**
     * @param FormTypeExtensionInterface[] $typeExtensions
     */
    public function __construct(FormTypeInterface $innerType, array $typeExtensions = [], ResolvedFormTypeInterface $parent = null)
    {
        foreach ($typeExtensions as $extension) {
            if (!$extension instanceof FormTypeExtensionInterface) {
                throw new UnexpectedTypeException($extension, FormTypeExtensionInterface::class);
            }
        }
        $this->innerType = $innerType;
        $this->typeExtensions = $typeExtensions;
        $this->parent = $parent;
    }
    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return $this->innerType->getBlockPrefix();
    }
    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return $this->parent;
    }
    /**
     * {@inheritdoc}
     */
    public function getInnerType()
    {
        return $this->innerType;
    }
    /**
     * {@inheritdoc}
     */
    public function getTypeExtensions()
    {
        return $this->typeExtensions;
    }
    /**
     * {@inheritdoc}
     */
    public function createBuilder(FormFactoryInterface $factory, string $name, array $options = [])
    {
        try {
            $options = $this->getOptionsResolver()->resolve($options);
        } catch (ExceptionInterface $e) {
            throw new $e(\sprintf('An error has occurred resolving the options of the form "%s": ', \get_debug_type($this->getInnerType())) . $e->getMessage(), $e->getCode(), $e);
        }
        // Should be decoupled from the specific option at some point
        $dataClass = $options['data_class'] ?? null;
        $builder = $this->newBuilder($name, $dataClass, $factory, $options);
        $builder->setType($this);
        return $builder;
    }
    /**
     * {@inheritdoc}
     */
    public function createView(FormInterface $form, FormView $parent = null)
    {
        return $this->newView($parent);
    }
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (null !== $this->parent) {
            $this->parent->buildForm($builder, $options);
        }
        $this->innerType->buildForm($builder, $options);
        foreach ($this->typeExtensions as $extension) {
            $extension->buildForm($builder, $options);
        }
    }
    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if (null !== $this->parent) {
            $this->parent->buildView($view, $form, $options);
        }
        $this->innerType->buildView($view, $form, $options);
        foreach ($this->typeExtensions as $extension) {
            $extension->buildView($view, $form, $options);
        }
    }
    /**
     * {@inheritdoc}
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        if (null !== $this->parent) {
            $this->parent->finishView($view, $form, $options);
        }
        $this->innerType->finishView($view, $form, $options);
        foreach ($this->typeExtensions as $extension) {
            /* @var FormTypeExtensionInterface $extension */
            $extension->finishView($view, $form, $options);
        }
    }
    /**
     * {@inheritdoc}
     */
    public function getOptionsResolver()
    {
        if (null === $this->optionsResolver) {
            if (null !== $this->parent) {
                $this->optionsResolver = clone $this->parent->getOptionsResolver();
            } else {
                $this->optionsResolver = new OptionsResolver();
            }
            $this->innerType->configureOptions($this->optionsResolver);
            foreach ($this->typeExtensions as $extension) {
                $extension->configureOptions($this->optionsResolver);
            }
        }
        return $this->optionsResolver;
    }
    /**
     * Creates a new builder instance.
     *
     * Override this method if you want to customize the builder class.
     *
     * @return FormBuilderInterface
     */
    protected function newBuilder(string $name, ?string $dataClass, FormFactoryInterface $factory, array $options)
    {
        if ($this->innerType instanceof ButtonTypeInterface) {
            return new ButtonBuilder($name, $options);
        }
        if ($this->innerType instanceof SubmitButtonTypeInterface) {
            return new SubmitButtonBuilder($name, $options);
        }
        return new FormBuilder($name, $dataClass, new EventDispatcher(), $factory, $options);
    }
    /**
     * Creates a new view instance.
     *
     * Override this method if you want to customize the view class.
     *
     * @return FormView
     */
    protected function newView(FormView $parent = null)
    {
        return new FormView($parent);
    }
}
