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

use Isolated\Symfony\Component\Form\Exception\ExceptionInterface;
use Isolated\Symfony\Component\Form\Exception\InvalidArgumentException;
use Isolated\Symfony\Component\Form\Exception\LogicException;
use Isolated\Symfony\Component\Form\Exception\UnexpectedTypeException;
/**
 * The central registry of the Form component.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class FormRegistry implements FormRegistryInterface
{
    /**
     * @var FormExtensionInterface[]
     */
    private $extensions = [];
    /**
     * @var ResolvedFormTypeInterface[]
     */
    private $types = [];
    /**
     * @var FormTypeGuesserInterface|false|null
     */
    private $guesser = \false;
    /**
     * @var ResolvedFormTypeFactoryInterface
     */
    private $resolvedTypeFactory;
    private $checkedTypes = [];
    /**
     * @param FormExtensionInterface[] $extensions
     *
     * @throws UnexpectedTypeException if any extension does not implement FormExtensionInterface
     */
    public function __construct(array $extensions, ResolvedFormTypeFactoryInterface $resolvedTypeFactory)
    {
        foreach ($extensions as $extension) {
            if (!$extension instanceof FormExtensionInterface) {
                throw new UnexpectedTypeException($extension, FormExtensionInterface::class);
            }
        }
        $this->extensions = $extensions;
        $this->resolvedTypeFactory = $resolvedTypeFactory;
    }
    /**
     * {@inheritdoc}
     */
    public function getType(string $name)
    {
        if (!isset($this->types[$name])) {
            $type = null;
            foreach ($this->extensions as $extension) {
                if ($extension->hasType($name)) {
                    $type = $extension->getType($name);
                    break;
                }
            }
            if (!$type) {
                // Support fully-qualified class names
                if (!\class_exists($name)) {
                    throw new InvalidArgumentException(\sprintf('Could not load type "%s": class does not exist.', $name));
                }
                if (!\is_subclass_of($name, FormTypeInterface::class)) {
                    throw new InvalidArgumentException(\sprintf('Could not load type "%s": class does not implement "Symfony\\Component\\Form\\FormTypeInterface".', $name));
                }
                $type = new $name();
            }
            $this->types[$name] = $this->resolveType($type);
        }
        return $this->types[$name];
    }
    /**
     * Wraps a type into a ResolvedFormTypeInterface implementation and connects it with its parent type.
     */
    private function resolveType(FormTypeInterface $type) : ResolvedFormTypeInterface
    {
        $parentType = $type->getParent();
        $fqcn = \get_class($type);
        if (isset($this->checkedTypes[$fqcn])) {
            $types = \implode(' > ', \array_merge(\array_keys($this->checkedTypes), [$fqcn]));
            throw new LogicException(\sprintf('Circular reference detected for form type "%s" (%s).', $fqcn, $types));
        }
        $this->checkedTypes[$fqcn] = \true;
        $typeExtensions = [];
        try {
            foreach ($this->extensions as $extension) {
                $typeExtensions[] = $extension->getTypeExtensions($fqcn);
            }
            return $this->resolvedTypeFactory->createResolvedType($type, \array_merge([], ...$typeExtensions), $parentType ? $this->getType($parentType) : null);
        } finally {
            unset($this->checkedTypes[$fqcn]);
        }
    }
    /**
     * {@inheritdoc}
     */
    public function hasType(string $name)
    {
        if (isset($this->types[$name])) {
            return \true;
        }
        try {
            $this->getType($name);
        } catch (ExceptionInterface $e) {
            return \false;
        }
        return \true;
    }
    /**
     * {@inheritdoc}
     */
    public function getTypeGuesser()
    {
        if (\false === $this->guesser) {
            $guessers = [];
            foreach ($this->extensions as $extension) {
                $guesser = $extension->getTypeGuesser();
                if ($guesser) {
                    $guessers[] = $guesser;
                }
            }
            $this->guesser = !empty($guessers) ? new FormTypeGuesserChain($guessers) : null;
        }
        return $this->guesser;
    }
    /**
     * {@inheritdoc}
     */
    public function getExtensions()
    {
        return $this->extensions;
    }
}
