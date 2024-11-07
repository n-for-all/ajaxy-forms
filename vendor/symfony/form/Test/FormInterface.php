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

use Isolated\Symfony\Component\Form\FormInterface as BaseFormInterface;
interface FormInterface extends \Iterator, BaseFormInterface
{
}
