<?php

namespace Drupal\heartbeat\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Database\Database;
use Drupal\heartbeat\HeartbeatTypeServices;
use Drupal\heartbeat\HeartbeatStreamServices;
use Drupal\heartbeat\HeartbeatService;

/**
 * Provides a 'HeartbeatFeedBlock' block.
 *
 * @Block(
 *  id = "heartbeat_feed_block",
 *  admin_label = @Translation("Heartbeat feed block"),
 * )
 */
class HeartbeatFeedBlock extends BlockBase implements ContainerFactoryPluginInterface {

    /**
     * Drupal\heartbeat\HeartbeatTypeServices definition.
     *
     * @var \Drupal\heartbeat\HeartbeatTypeServices
     */
    protected $heartbeatTypeServices;
    /**
     * Drupal\heartbeat\HeartbeatStreamServices definition.
     *
     * @var \Drupal\heartbeat\HeartbeatStreamServices
     */
    protected $heartbeatStreamServices;
    /**
     * Drupal\heartbeat\HeartbeatService definition.
     *
     * @var \Drupal\heartbeat\HeartbeatService
     */
    protected $heartbeatService;
    /**
     * Construct.
     *
     * @param array $configuration
     *   A configuration array containing information about the plugin instance.
     * @param string $plugin_id
     *   The plugin_id for the plugin instance.
     * @param string $plugin_definition
     *   The plugin implementation definition.
     */
    public function __construct(
        array $configuration,
        $plugin_id,
        $plugin_definition
//        HeartbeatTypeServices $heartbeat_heartbeattype,
//        HeartbeatStreamServices $heartbeatstream,
//        HeartbeatService $heartbeat
    ) {

        parent::__construct($configuration, $plugin_id, $plugin_definition);
//        $this->heartbeatTypeServices = $heartbeat_heartbeattype;
//        $this->heartbeatStreamServices = $heartbeatstream;
//        $this->heartbeatService = $heartbeat;
    }
    /**
     * {@inheritdoc}
     */
    public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
        return new static(
            $configuration,
            $plugin_id,
            $plugin_definition
//            $container->get('heartbeat.heartbeattype'),
//            $container->get('heartbeatstream'),
//            $container->get('heartbeat')
        );
    }
  /**
   * {@inheritdoc}
   */
  public function build() {

      return \Drupal::formBuilder()->getForm('Drupal\heartbeat\Form\HeartbeatFeedForm');

//      $messages = array();
//      $query = Database::getConnection()->select('heartbeat_friendship', 'hf')
//          ->fields('hf', ['uid_target'])
//          ->condition('hf.uid', \Drupal::currentUser()->id())->execute();
//
//      if ($result = $query->fetchAll()) {
//          $uids = array();
//          foreach ($result as $uid) {
//              $uids[] = $uid->uid_target;
//          }
//          foreach ($this->heartbeatStreamServices->createStreamForUids($uids) as $heartbeat) {
//              $messages[] = $heartbeat->getMessage()->getValue()[0]['value'];
//          }
//
//      }
//
//      return [
//          '#theme' => 'heartbeat_stream',
//          '#messages' => $messages,
//          '#heartbeat-form' => $form,
//          '#attached' => array('library' => 'heartbeat/heartbeat')
//      ];

  }
}
