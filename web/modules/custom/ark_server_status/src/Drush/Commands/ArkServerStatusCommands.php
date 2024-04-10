<?php

namespace Drupal\ark_server_status\Drush\Commands;

use Consolidation\OutputFormatters\StructuredData\RowsOfFields;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Utility\Token;
use Drush\Attributes as CLI;
use Drush\Commands\DrushCommands;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * A Drush commandfile.
 */
final class ArkServerStatusCommands extends DrushCommands {

  /**
   * Constructs an ArkServerStatusCommands object.
   */
  public function __construct(Token $token, LoggerChannelFactoryInterface $loggerFactory) {
    parent::__construct();
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): ArkServerStatusCommands {
    return new ArkServerStatusCommands(
      $container->get('token'),
      $container->get('logger.factory'),
    );
  }

  /**
   * Provides server status.
   */
  public function getArkServerStatus() {
    //Checks to see if the server is on.
    //Send alert email.
    //Document that the server is on.
  }
}
