<?php

declare(strict_types=1);

namespace Drupal\ark_server_status;

use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use GuzzleHttp\ClientInterface;
use Nitrapi\Nitrapi;
use Exception;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

/**
 * @todo Add class description.
 */
final class ArkServerStatusHelper implements ArkServerStatusHelperInterface {

  /**
   * @var \Drupal\Core\Logger\LoggerChannelFactoryInterface
   */
  protected LoggerChannelFactoryInterface $logger;

  /**
   * @var \GuzzleHttp\ClientInterface
   */
  protected ClientInterface $client;

  /**
   * Constructs an ArkServerStatusHelper object.
   */
  public function __construct(LoggerChannelFactoryInterface $loggerFactory, ClientInterface $client) {
    $this->logger = $loggerFactory;
    $this->client = $client;
  }

  /**
   * @inheritDoc
   */
  public function checkServer(string $authToken): string {
    $serverStatus = '';
    try {
      $api = new Nitrapi($authToken);
      $serverStatus = $api->getServices()[0]->getDetails()->getStatus();
    } catch (Exception $exception) {
      $this->logger->get('Ark Server Status')->error($exception->getMessage());
    }

    return $serverStatus;
  }

  /**
   * @inheritDoc
   */
  public function checkPlayers(int $serviceId, string $authToken): int {
    $headers = [
      'Authorization' => $authToken,
      'Cookie' => '7c7a3581f78104008dff0e4df875b9c9=825924c9d72ff2baadeb92b30e16129c',
    ];
    $request = new Request('GET', 'https://api.nitrado.net/services/' . $serviceId . '/gameservers/games/players', $headers);
    $response = $this->client->sendAsync($request)->wait();
    $json = $response->getBody()->__toString();
    var_dump($json);
    $responseArray = json_decode($json, TRUE);
    $players = $responseArray['data']['players'];
    var_dump($players);
    return count($players);
  }

  /**
   * @inheritDoc
   */
  public function serverOff($authToken): void {
    try {
      $api = new Nitrapi($authToken);
      $api->getServices()[0]->doStop();
    } catch (Exception $exception) {
      $this->logger->get('Ark Server Status')->error($exception->getMessage());
    }
  }

  /**
   * @inheritDoc
   */
  public function getPlayers(int $serviceId, string $authToken): array {

    $request = new Request('GET', 'https://cdn2.arkdedicated.com/servers/asa/unofficialserverlist.json');
    $response = $this->client->sendAsync($request)->wait();
    $json = $response->getBody()->__toString();
    $responseArray = json_decode($json, TRUE);

    $chunks = array_chunk($responseArray, 25, TRUE);
    $numOperations = 0;
    $operations = [];
    $batchId = 1;
    foreach ($chunks as $chunk) {
      $operations[] = [
        '\Drupal\ark_server_status\Drush\StaticBatchService::getServerData',
        [
          $chunk,
        ],
      ];
      $batchId++;
      $numOperations++;
    }
    $batch = [
      'title' => t('Deleting @num FFL(s)', ['@num' => $numOperations]),
      'operations' => $operations,
      'finished' => '\Drupal\ark_server_data\Drush\StaticBatchService::getServerDataFinished',
    ];
    batch_set($batch);
    drush_backend_batch_process();
    return [];
  }

}
