<?php

declare(strict_types=1);

namespace Drupal\ark_server_status;

/**
 * Helper service for Ark Server Status.
 */
interface ArkServerStatusHelperInterface {

   /**
   * Checks to see if the server is live.
   *
   * @param string $authToken
   *
   * @return string
   */
  public function checkServer(string $authToken): string;

  /**
   * Check to see if someone is online.
   *
   * @param int $serviceId
   * @param string $authToken
   *
   * @return int
   */
  public function checkPlayers(int $serviceId, string $authToken): int;

  /**
   * Shuts that bitch down.
   *
   * @param string $authToken
   */
  public function serverOff(string $authToken): void;

  /**
   * Get an array of who is online.
   *
   * @param int $serviceId
   * @param string $authToken
   *
   * @return array
   */
  public function getPlayers(int $serviceId, string $authToken): array;

}
