<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Isolated\Symfony\Component\Form\Test\Traits;

use Isolated\Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Isolated\Symfony\Component\Form\Test\TypeTestCase;
use Isolated\Symfony\Component\Validator\ConstraintViolationList;
use Isolated\Symfony\Component\Validator\Mapping\ClassMetadata;
use Isolated\Symfony\Component\Validator\Validator\ValidatorInterface;
trait ValidatorExtensionTrait
{
    /**
     * @var ValidatorInterface|null
     */
    protected $validator;
    protected function getValidatorExtension() : ValidatorExtension
    {
        if (!\interface_exists(ValidatorInterface::class)) {
            throw new \Exception('In order to use the "ValidatorExtensionTrait", the symfony/validator component must be installed.');
        }
        if (!$this instanceof TypeTestCase) {
            throw new \Exception(\sprintf('The trait "ValidatorExtensionTrait" can only be added to a class that extends "%s".', TypeTestCase::class));
        }
        $this->validator = $this->createMock(ValidatorInterface::class);
        $metadata = $this->getMockBuilder(ClassMetadata::class)->setConstructorArgs([''])->setMethods(['addPropertyConstraint'])->getMock();
        $this->validator->expects($this->any())->method('getMetadataFor')->will($this->returnValue($metadata));
        $this->validator->expects($this->any())->method('validate')->will($this->returnValue(new ConstraintViolationList()));
        return new ValidatorExtension($this->validator, \false);
    }
}
