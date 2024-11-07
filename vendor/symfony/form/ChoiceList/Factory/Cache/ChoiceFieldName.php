<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Isolated\Symfony\Component\Form\ChoiceList\Factory\Cache;

use Isolated\Symfony\Component\Form\FormTypeExtensionInterface;
use Isolated\Symfony\Component\Form\FormTypeInterface;
/**
 * A cacheable wrapper for any {@see FormTypeInterface} or {@see FormTypeExtensionInterface}
 * which configures a "choice_name" callback.
 *
 * @internal
 *
 * @author Jules Pietri <jules@heahprod.com>
 */
final class ChoiceFieldName extends AbstractStaticOption
{
}
