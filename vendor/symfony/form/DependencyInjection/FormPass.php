<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Isolated\Symfony\Component\Form\DependencyInjection;

use Isolated\Symfony\Component\DependencyInjection\Argument\ArgumentInterface;
use Isolated\Symfony\Component\DependencyInjection\Argument\IteratorArgument;
use Isolated\Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Isolated\Symfony\Component\DependencyInjection\Compiler\PriorityTaggedServiceTrait;
use Isolated\Symfony\Component\DependencyInjection\Compiler\ServiceLocatorTagPass;
use Isolated\Symfony\Component\DependencyInjection\ContainerBuilder;
use Isolated\Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Isolated\Symfony\Component\DependencyInjection\Reference;
/**
 * Adds all services with the tags "form.type", "form.type_extension" and
 * "form.type_guesser" as arguments of the "form.extension" service.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class FormPass implements CompilerPassInterface
{
    use PriorityTaggedServiceTrait;
    private $formExtensionService;
    private $formTypeTag;
    private $formTypeExtensionTag;
    private $formTypeGuesserTag;
    private $formDebugCommandService;
    public function __construct(string $formExtensionService = 'form.extension', string $formTypeTag = 'form.type', string $formTypeExtensionTag = 'form.type_extension', string $formTypeGuesserTag = 'form.type_guesser', string $formDebugCommandService = 'console.command.form_debug')
    {
        if (0 < \func_num_args()) {
            trigger_deprecation('symfony/http-kernel', '5.3', 'Configuring "%s" is deprecated.', __CLASS__);
        }
        $this->formExtensionService = $formExtensionService;
        $this->formTypeTag = $formTypeTag;
        $this->formTypeExtensionTag = $formTypeExtensionTag;
        $this->formTypeGuesserTag = $formTypeGuesserTag;
        $this->formDebugCommandService = $formDebugCommandService;
    }
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition($this->formExtensionService)) {
            return;
        }
        $definition = $container->getDefinition($this->formExtensionService);
        $definition->replaceArgument(0, $this->processFormTypes($container));
        $definition->replaceArgument(1, $this->processFormTypeExtensions($container));
        $definition->replaceArgument(2, $this->processFormTypeGuessers($container));
    }
    private function processFormTypes(ContainerBuilder $container) : Reference
    {
        // Get service locator argument
        $servicesMap = [];
        $namespaces = ['Isolated\\Symfony\\Component\\Form\\Extension\\Core\\Type' => \true];
        // Builds an array with fully-qualified type class names as keys and service IDs as values
        foreach ($container->findTaggedServiceIds($this->formTypeTag, \true) as $serviceId => $tag) {
            // Add form type service to the service locator
            $serviceDefinition = $container->getDefinition($serviceId);
            $servicesMap[$formType = $serviceDefinition->getClass()] = new Reference($serviceId);
            $namespaces[\substr($formType, 0, \strrpos($formType, '\\'))] = \true;
        }
        if ($container->hasDefinition($this->formDebugCommandService)) {
            $commandDefinition = $container->getDefinition($this->formDebugCommandService);
            $commandDefinition->setArgument(1, \array_keys($namespaces));
            $commandDefinition->setArgument(2, \array_keys($servicesMap));
        }
        return ServiceLocatorTagPass::register($container, $servicesMap);
    }
    private function processFormTypeExtensions(ContainerBuilder $container) : array
    {
        $typeExtensions = [];
        $typeExtensionsClasses = [];
        foreach ($this->findAndSortTaggedServices($this->formTypeExtensionTag, $container) as $reference) {
            $serviceId = (string) $reference;
            $serviceDefinition = $container->getDefinition($serviceId);
            $tag = $serviceDefinition->getTag($this->formTypeExtensionTag);
            $typeExtensionClass = $container->getParameterBag()->resolveValue($serviceDefinition->getClass());
            if (isset($tag[0]['extended_type'])) {
                $typeExtensions[$tag[0]['extended_type']][] = new Reference($serviceId);
                $typeExtensionsClasses[] = $typeExtensionClass;
            } else {
                $extendsTypes = \false;
                $typeExtensionsClasses[] = $typeExtensionClass;
                foreach ($typeExtensionClass::getExtendedTypes() as $extendedType) {
                    $typeExtensions[$extendedType][] = new Reference($serviceId);
                    $extendsTypes = \true;
                }
                if (!$extendsTypes) {
                    throw new InvalidArgumentException(\sprintf('The getExtendedTypes() method for service "%s" does not return any extended types.', $serviceId));
                }
            }
        }
        foreach ($typeExtensions as $extendedType => $extensions) {
            $typeExtensions[$extendedType] = new IteratorArgument($extensions);
        }
        if ($container->hasDefinition($this->formDebugCommandService)) {
            $commandDefinition = $container->getDefinition($this->formDebugCommandService);
            $commandDefinition->setArgument(3, $typeExtensionsClasses);
        }
        return $typeExtensions;
    }
    private function processFormTypeGuessers(ContainerBuilder $container) : ArgumentInterface
    {
        $guessers = [];
        $guessersClasses = [];
        foreach ($container->findTaggedServiceIds($this->formTypeGuesserTag, \true) as $serviceId => $tags) {
            $guessers[] = new Reference($serviceId);
            $serviceDefinition = $container->getDefinition($serviceId);
            $guessersClasses[] = $serviceDefinition->getClass();
        }
        if ($container->hasDefinition($this->formDebugCommandService)) {
            $commandDefinition = $container->getDefinition($this->formDebugCommandService);
            $commandDefinition->setArgument(4, $guessersClasses);
        }
        return new IteratorArgument($guessers);
    }
}
