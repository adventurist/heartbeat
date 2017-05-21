<?php

namespace Drupal\heartbeat\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\Event;
use Drupal\flag\FlagService;
use Drupal\heartbeat\HeartbeatTypeServices;
use Drupal\heartbeat\HeartbeatStreamServices;
use Drupal\heartbeat\HeartbeatService;

/**
 * Class HeartbeatEventSubscriber.
 *
 * @package Drupal\heartbeat
 */
class HeartbeatEventSubscriber implements EventSubscriberInterface {

  /**
   * Drupal\flag\FlagService definition.
   *
   * @var \Drupal\flag\FlagService
   */
  protected $flagService;
  /**
   * Drupal\heartbeat\HeartbeatTypeServices definition.
   *
   * @var \Drupal\heartbeat\HeartbeatTypeServices
   */
  protected $heartbeatTypeService;
  /**
   * Drupal\heartbeat\HeartbeatStreamServices definition.
   *
   * @var \Drupal\heartbeat\HeartbeatStreamServices
   */
  protected $heartbeatStreamService;
  /**
   * Drupal\heartbeat\HeartbeatService definition.
   *
   * @var \Drupal\heartbeat\HeartbeatService
   */
  protected $heartbeatService;

  /**
   * Constructor.
   */
  public function __construct(FlagService $flag, HeartbeatTypeServices $heartbeat_heartbeattype, HeartbeatStreamServices $heartbeatstream, HeartbeatService $heartbeat) {
    $this->flagService = $flag;
    $this->heartbeatTypeService = $heartbeat_heartbeattype;
    $this->heartbeatStreamService = $heartbeatstream;
    $this->heartbeatService = $heartbeat;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events['flag.entity_flagged'] = ['flag_entity_flagged'];
    $events['flag.entity_unflagged'] = ['flag_entity_unflagged'];

    return $events;
  }

  /**
   * This method is called whenever the flag.entity_flagged event is
   * dispatched.
   *
   * @param GetResponseEvent $event
   */
  public function flag_entity_flagged(Event $event) {
    $stophere = null;
    $flagging = $event->getFlagging();
    drupal_set_message('Event flag.entity_flagged thrown by Subscriber in module heartbeat.', 'status', TRUE);
  }
  /**
   * This method is called whenever the flag.entity_unflagged event is
   * dispatched.
   *
   * @param GetResponseEvent $event
   */
  public function flag_entity_unflagged(Event $event) {
    drupal_set_message('Event flag.entity_unflagged thrown by Subscriber in module heartbeat.', 'status', TRUE);
  }

}
