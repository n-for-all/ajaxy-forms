<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Isolated\Symfony\Component\Form\Test;

use Isolated\PHPUnit\Framework\TestCase;
use Isolated\Symfony\Component\Form\FormFactoryInterface;
use Isolated\Symfony\Component\Form\Forms;
/**
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
abstract class FormIntegrationTestCase extends TestCase
{
    /**
     * @var FormFactoryInterface
     */
    protected $factory;
    protected function setUp() : void
    {
        $this->factory = Forms::createFormFactoryBuilder()->addExtensions($this->getExtensions())->addTypeExtensions($this->getTypeExtensions())->addTypes($this->getTypes())->addTypeGuessers($this->getTypeGuessers())->getFormFactory();
    }
    protected function getExtensions()
    {
        return [];
    }
    protected function getTypeExtensions()
    {
        return [];
    }
    protected function getTypes()
    {
        return [];
    }
    protected function getTypeGuessers()
    {
        return [];
    }
}
