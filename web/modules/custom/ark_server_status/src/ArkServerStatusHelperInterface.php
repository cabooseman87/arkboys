<?php

declare(strict_types=1);

namespace Drupal\ark_server_status;

/**
 * Helper service for Ark Server Status.
 */
interface ArkServerStatusHelperInterface {

  /**
   * Get unofficial server list.
   *
   * @return array
   */
  public function getServerList(): array;

  /**
   * Checks to see if the server is live.
   *
   * @param string $serverName
   * @param array $serverList
   *
   * @return bool
   */
  public function checkServer(string $serverName, array $serverList): bool;

  /**
   * Logs the time when we discover the server on.
   *
   * @param int $time
   */
  public function logTime(int $time): void;

  /**
   * Get current logged time.
   *
   * @return int
   */
  public function getLoggedTime(): int;

  /**
   * Sends notification.
   *
   * @param array $emails
   * @param int $duration
   */
  public function sendNotification(array $emails, int $duration): void;

}
