<?php

declare(strict_types=1);

namespace Drupal\ark_server_status;

use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use GuzzleHttp\Client;

/**
 * @todo Add class description.
 */
final class ArkServerStatusHelper implements ArkServerStatusHelperInterface {

  /**
   * @var \Drupal\Core\Logger\LoggerChannelFactoryInterface
   */
  protected LoggerChannelFactoryInterface $logger;

  /**
   * Constructs an ArkServerStatusHelper object.
   */
  public function __construct(LoggerChannelFactoryInterface $loggerFactory, Client $client) {
    $this->logger = $loggerFactory;
    $this->client = $client;
  }

  /**
   * @inheritDoc
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function getServerList(): string {
    $request = $this->client->request('GET', 'https://cdn2.arkdedicated.com/servers/asa/unofficialserverlist.json');
    return $request->getBody()->getContents();
  }

  /**
   * @inheritDoc
   */
  public function checkServer(string $serverName, string $serverList): bool {
    $serverListArray = json_decode($serverList);
    var_dump($serverListArray);
    return TRUE;
  }

  /**
   * @inheritDoc
   */
  public function logTime(int $time): void {
    // TODO: Implement logTime() method.
  }

  /**
   * @inheritDoc
   */
  public function getLoggedTime(): int {
    // TODO: Implement getLoggedTime() method.
  }

  /**
   * @inheritDoc
   */
  public function sendNotification(array $emails, int $duration): void {
    // TODO: Implement sendNotification() method.
  }


}
