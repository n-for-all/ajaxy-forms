<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Isolated\Symfony\Component\Form\Extension\DataCollector\EventListener;

use Isolated\Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Isolated\Symfony\Component\Form\Extension\DataCollector\FormDataCollectorInterface;
use Isolated\Symfony\Component\Form\FormEvent;
use Isolated\Symfony\Component\Form\FormEvents;
/**
 * Listener that invokes a data collector for the {@link FormEvents::POST_SET_DATA}
 * and {@link FormEvents::POST_SUBMIT} events.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class DataCollectorListener implements EventSubscriberInterface
{
    private $dataCollector;
    public function __construct(FormDataCollectorInterface $dataCollector)
    {
        $this->dataCollector = $dataCollector;
    }
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            // High priority in order to be called as soon as possible
            FormEvents::POST_SET_DATA => ['postSetData', 255],
            // Low priority in order to be called as late as possible
            FormEvents::POST_SUBMIT => ['postSubmit', -255],
        ];
    }
    /**
     * Listener for the {@link FormEvents::POST_SET_DATA} event.
     */
    public function postSetData(FormEvent $event)
    {
        if ($event->getForm()->isRoot()) {
            // Collect basic information about each form
            $this->dataCollector->collectConfiguration($event->getForm());
            // Collect the default data
            $this->dataCollector->collectDefaultData($event->getForm());
        }
    }
    /**
     * Listener for the {@link FormEvents::POST_SUBMIT} event.
     */
    public function postSubmit(FormEvent $event)
    {
        if ($event->getForm()->isRoot()) {
            // Collect the submitted data of each form
            $this->dataCollector->collectSubmittedData($event->getForm());
            // Assemble a form tree
            // This is done again after the view is built, but we need it here as the view is not always created.
            $this->dataCollector->buildPreliminaryFormTree($event->getForm());
        }
    }
}
