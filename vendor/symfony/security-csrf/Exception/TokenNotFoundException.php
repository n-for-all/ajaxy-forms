<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Isolated\Symfony\Component\Security\Csrf\Exception;

use Isolated\Symfony\Component\Security\Core\Exception\RuntimeException;
/**
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class TokenNotFoundException extends RuntimeException
{
}
