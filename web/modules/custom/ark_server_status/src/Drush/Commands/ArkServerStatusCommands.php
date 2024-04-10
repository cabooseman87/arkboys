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
  public function __construct(
    private readonly Token $token,
    private readonly LoggerChannelFactoryInterface $loggerFactory,
  ) {
    parent::__construct();
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('token'),
      $container->get('logger.factory'),
    );
  }

  /**
   * Command description here.
   */
  #[CLI\Command(name: 'ark_server_status:command-name', aliases: ['foo'])]
  #[CLI\Argument(name: 'arg1', description: 'Argument description.')]
  #[CLI\Option(name: 'option-name', description: 'Option description')]
  #[CLI\Usage(name: 'ark_server_status:command-name foo', description: 'Usage description')]
  public function commandName($arg1, $options = ['option-name' => 'default']) {
    $this->logger()->success(dt('Achievement unlocked.'));
  }

  /**
   * An example of the table output format.
   */
  #[CLI\Command(name: 'ark_server_status:token', aliases: ['token'])]
  #[CLI\FieldLabels(labels: [
    'group' => 'Group',
    'token' => 'Token',
    'name' => 'Name'
  ])]
  #[CLI\DefaultTableFields(fields: ['group', 'token', 'name'])]
  #[CLI\FilterDefaultField(field: 'name')]
  public function token($options = ['format' => 'table']): RowsOfFields {
    $all = $this->token->getInfo();
    foreach ($all['tokens'] as $group => $tokens) {
      foreach ($tokens as $key => $token) {
        $rows[] = [
          'group' => $group,
          'token' => $key,
          'name' => $token['name'],
        ];
      }
    }
    return new RowsOfFields($rows);
  }

}
