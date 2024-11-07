<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Isolated\Symfony\Component\Form\Extension\Core\EventListener;

use Isolated\Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Isolated\Symfony\Component\Form\FormEvent;
use Isolated\Symfony\Component\Form\FormEvents;
use Isolated\Symfony\Component\Form\Util\StringUtil;
/**
 * Trims string data.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class TrimListener implements EventSubscriberInterface
{
    public function preSubmit(FormEvent $event)
    {
        $data = $event->getData();
        if (!\is_string($data)) {
            return;
        }
        $event->setData(StringUtil::trim($data));
    }
    public static function getSubscribedEvents()
    {
        return [FormEvents::PRE_SUBMIT => 'preSubmit'];
    }
}
