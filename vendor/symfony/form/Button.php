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

use Isolated\Symfony\Component\Form\Exception\AlreadySubmittedException;
use Isolated\Symfony\Component\Form\Exception\BadMethodCallException;
/**
 * A form button.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 *
 * @implements \IteratorAggregate<string, FormInterface>
 */
class Button implements \IteratorAggregate, FormInterface
{
    /**
     * @var FormInterface|null
     */
    private $parent;
    /**
     * @var FormConfigInterface
     */
    private $config;
    /**
     * @var bool
     */
    private $submitted = \false;
    /**
     * Creates a new button from a form configuration.
     */
    public function __construct(FormConfigInterface $config)
    {
        $this->config = $config;
    }
    /**
     * Unsupported method.
     *
     * @param string $offset
     *
     * @return bool
     */
    #[\ReturnTypeWillChange]
    public function offsetExists($offset)
    {
        return \false;
    }
    /**
     * Unsupported method.
     *
     * This method should not be invoked.
     *
     * @param string $offset
     *
     * @return FormInterface
     *
     * @throws BadMethodCallException
     */
    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        throw new BadMethodCallException('Buttons cannot have children.');
    }
    /**
     * Unsupported method.
     *
     * This method should not be invoked.
     *
     * @param string        $offset
     * @param FormInterface $value
     *
     * @return void
     *
     * @throws BadMethodCallException
     */
    #[\ReturnTypeWillChange]
    public function offsetSet($offset, $value)
    {
        throw new BadMethodCallException('Buttons cannot have children.');
    }
    /**
     * Unsupported method.
     *
     * This method should not be invoked.
     *
     * @param string $offset
     *
     * @return void
     *
     * @throws BadMethodCallException
     */
    #[\ReturnTypeWillChange]
    public function offsetUnset($offset)
    {
        throw new BadMethodCallException('Buttons cannot have children.');
    }
    /**
     * {@inheritdoc}
     */
    public function setParent(FormInterface $parent = null)
    {
        if ($this->submitted) {
            throw new AlreadySubmittedException('You cannot set the parent of a submitted button.');
        }
        $this->parent = $parent;
        return $this;
    }
    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return $this->parent;
    }
    /**
     * Unsupported method.
     *
     * This method should not be invoked.
     *
     * @throws BadMethodCallException
     */
    public function add($child, string $type = null, array $options = [])
    {
        throw new BadMethodCallException('Buttons cannot have children.');
    }
    /**
     * Unsupported method.
     *
     * This method should not be invoked.
     *
     * @throws BadMethodCallException
     */
    public function get(string $name)
    {
        throw new BadMethodCallException('Buttons cannot have children.');
    }
    /**
     * Unsupported method.
     *
     * @return bool
     */
    public function has(string $name)
    {
        return \false;
    }
    /**
     * Unsupported method.
     *
     * This method should not be invoked.
     *
     * @throws BadMethodCallException
     */
    public function remove(string $name)
    {
        throw new BadMethodCallException('Buttons cannot have children.');
    }
    /**
     * {@inheritdoc}
     */
    public function all()
    {
        return [];
    }
    /**
     * {@inheritdoc}
     */
    public function getErrors(bool $deep = \false, bool $flatten = \true)
    {
        return new FormErrorIterator($this, []);
    }
    /**
     * Unsupported method.
     *
     * This method should not be invoked.
     *
     * @param mixed $modelData
     *
     * @return $this
     */
    public function setData($modelData)
    {
        // no-op, called during initialization of the form tree
        return $this;
    }
    /**
     * Unsupported method.
     */
    public function getData()
    {
        return null;
    }
    /**
     * Unsupported method.
     */
    public function getNormData()
    {
        return null;
    }
    /**
     * Unsupported method.
     */
    public function getViewData()
    {
        return null;
    }
    /**
     * Unsupported method.
     *
     * @return array
     */
    public function getExtraData()
    {
        return [];
    }
    /**
     * Returns the button's configuration.
     *
     * @return FormConfigInterface
     */
    public function getConfig()
    {
        return $this->config;
    }
    /**
     * Returns whether the button is submitted.
     *
     * @return bool
     */
    public function isSubmitted()
    {
        return $this->submitted;
    }
    /**
     * Returns the name by which the button is identified in forms.
     *
     * @return string
     */
    public function getName()
    {
        return $this->config->getName();
    }
    /**
     * Unsupported method.
     */
    public function getPropertyPath()
    {
        return null;
    }
    /**
     * Unsupported method.
     *
     * @throws BadMethodCallException
     */
    public function addError(FormError $error)
    {
        throw new BadMethodCallException('Buttons cannot have errors.');
    }
    /**
     * Unsupported method.
     *
     * @return bool
     */
    public function isValid()
    {
        return \true;
    }
    /**
     * Unsupported method.
     *
     * @return bool
     */
    public function isRequired()
    {
        return \false;
    }
    /**
     * {@inheritdoc}
     */
    public function isDisabled()
    {
        if ($this->parent && $this->parent->isDisabled()) {
            return \true;
        }
        return $this->config->getDisabled();
    }
    /**
     * Unsupported method.
     *
     * @return bool
     */
    public function isEmpty()
    {
        return \true;
    }
    /**
     * Unsupported method.
     *
     * @return bool
     */
    public function isSynchronized()
    {
        return \true;
    }
    /**
     * Unsupported method.
     */
    public function getTransformationFailure()
    {
        return null;
    }
    /**
     * Unsupported method.
     *
     * @throws BadMethodCallException
     */
    public function initialize()
    {
        throw new BadMethodCallException('Buttons cannot be initialized. Call initialize() on the root form instead.');
    }
    /**
     * Unsupported method.
     *
     * @param mixed $request
     *
     * @throws BadMethodCallException
     */
    public function handleRequest($request = null)
    {
        throw new BadMethodCallException('Buttons cannot handle requests. Call handleRequest() on the root form instead.');
    }
    /**
     * Submits data to the button.
     *
     * @param array|string|null $submittedData Not used
     * @param bool              $clearMissing  Not used
     *
     * @return $this
     *
     * @throws Exception\AlreadySubmittedException if the button has already been submitted
     */
    public function submit($submittedData, bool $clearMissing = \true)
    {
        if ($this->submitted) {
            throw new AlreadySubmittedException('A form can only be submitted once.');
        }
        $this->submitted = \true;
        return $this;
    }
    /**
     * {@inheritdoc}
     */
    public function getRoot()
    {
        return $this->parent ? $this->parent->getRoot() : $this;
    }
    /**
     * {@inheritdoc}
     */
    public function isRoot()
    {
        return null === $this->parent;
    }
    /**
     * {@inheritdoc}
     */
    public function createView(FormView $parent = null)
    {
        if (null === $parent && $this->parent) {
            $parent = $this->parent->createView();
        }
        $type = $this->config->getType();
        $options = $this->config->getOptions();
        $view = $type->createView($this, $parent);
        $type->buildView($view, $this, $options);
        $type->finishView($view, $this, $options);
        return $view;
    }
    /**
     * Unsupported method.
     *
     * @return int
     */
    #[\ReturnTypeWillChange]
    public function count()
    {
        return 0;
    }
    /**
     * Unsupported method.
     *
     * @return \EmptyIterator
     */
    #[\ReturnTypeWillChange]
    public function getIterator()
    {
        return new \EmptyIterator();
    }
}
