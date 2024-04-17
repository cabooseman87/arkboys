<?php

namespace Drupal\ark_server_status\Drush;

/**
 * Class StaticBatchService.
 */
class StaticBatchService {

  /**
   * Batch process callback.
   *
   * @param array<array> $chunk
   */
  public static function getServerData(array $chunk, &$context): void {
    $playerCountExpiration = time() + (15*60);
    foreach ($chunk as $server) {
      if ($server['Name'] === "BCV Tribe Server") {
        \Drupal::cache()->set('ark_players', $server['NumPlayers'], $playerCountExpiration, []);
        exit();
      }
      \Drupal::cache()->set('ark_players', 0, $playerCountExpiration, []);
    }
  }

  /**
   * Batch Finished callback.
   *
   * @param bool $success
   *   Success of the operation.
   * @param array $results
   *   Array of results for post-processing.
   * @param array $operations
   *   Array of operations.
   */
  public function getServerDataFinished(bool $success, array $results, array $operations): void {
    $logger = \Drupal::logger('Batch Service Logging');
    if ($success) {
      $logger->info('@count results processed.', ['@count' => count($results)]);
    }
    else {
      $error_operation = reset($operations);
      $logger->info('An error occurred while processing @operation with arguments : @args', [
          '@operation' => $error_operation[0],
          '@args' => print_r($error_operation[0], TRUE),
        ]
      );
    }
  }

  /**
   * Batch Finished callback.
   *
   * @param bool $success
   *   Success of the operation.
   * @param array $results
   *   Array of results for post-processing.
   * @param array $operations
   *   Array of operations.
   */
  public function zeroAllProductsFinished(bool $success, array $results, array $operations): void {
    $logger = \Drupal::logger('Batch Service Logging');
    if ($success) {
      $logger->info('@count results processed.', ['@count' => count($results)]);
    }
    else {
      $error_operation = reset($operations);
      $logger->info('An error occurred while processing @operation with arguments : @args', [
          '@operation' => $error_operation[0],
          '@args' => print_r($error_operation[0], TRUE),
        ]
      );
    }
  }

}
