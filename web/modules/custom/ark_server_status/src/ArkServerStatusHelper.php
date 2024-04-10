<?php

declare(strict_types=1);

namespace Drupal\ark_server_status;

use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use GuzzleHttp\ClientInterface;
use Drupal\Core\Queue\QueueFactoryInterface;

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
   * @var \Drupal\Core\Queue\QueueFactoryInterface
   */
  protected QueueFactoryInterface $queueFactory;

  /**
   * Constructs an ArkServerStatusHelper object.
   */
  public function __construct(LoggerChannelFactoryInterface $loggerFactory, ClientInterface $client, QueueFactoryInterface $queueFactory) {
    $this->logger = $loggerFactory;
    $this->client = $client;
    $this->queueFactory = $queueFactory;
  }

  /**
   * @inheritDoc
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function getServerList(): array {
    $serverNameList = [];
    $request = $this->client->request('GET', 'https://cdn2.arkdedicated.com/servers/asa/unofficialserverlist.json');
    $queue = $this->queueFactory->get('ark_servers')->createQueue();
    $servers = json_decode($request->getBody()->getContents());

    foreach ($servers as $server) {
      $queue->createItem($server);
    }

    return $serverNameList;
  }

  /**
   * @inheritDoc
   */
  public function checkServer(string $serverName, array $serverList): bool {
    var_dump($serverList);
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
