<?php

namespace Drupal\event_subscriber\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class extension.
 */
class RemoveXFrameOptionsSubscriber implements EventSubscriberInterface {

  /**
   * Function onKernelResponse.
   */
  public function onKernelResponse(ResponseEvent $event) {
    $response = $event->getResponse();
    $response->headers->remove('X-Generator');
  }

  /**
   * Function getSubscribedEvents.
   */
  public static function getSubscribedEvents() {
    // $events[KernelEvents::RESPONSE][] = array('onKernelResponse', -10);
    // return $events;
    return [
      KernelEvents::RESPONSE => ['onKernelResponse'],

    ];
  }

}
