<?php

declare(strict_types=1);

namespace Drupal\ark_server_status\Plugin\QueueWorker;

use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Queue\QueueWorkerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Defines 'ark_server_status_parse_server_list' queue worker.
 *
 * @QueueWorker(
 *   id = "ark_server_status_parse_server_list",
 *   title = @Translation("ParseServerList"),
 *   cron = {"time" = 60},
 * )
 */
final class ParseServerList extends QueueWorkerBase implements ContainerFactoryPluginInterface {

  /**
   * Constructs a new ParseServerList instance.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    LoggerChannelFactoryInterface $loggerFactory,
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition): self {
    return new self(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('logger.factory'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function processItem($data): void {
    // @todo Process data here.
  }

}
