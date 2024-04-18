<?php

namespace Drupal\ark_server_status\Drush\Commands;

use Drupal\ark_server_status\ArkServerStatusHelperInterface;
use Drush\Commands\DrushCommands;
use Drupal\key\KeyRepositoryInterface;
use Drupal\Core\Cache\CacheBackendInterface;

/**
 * A Drush commandfile.
 */
final class ArkServerStatusCommands extends DrushCommands {

  const TOKEN = 'nitrado_token';

  const SERVICE_ID = '15083592';

  const FILE = '/var/www/Arkboys/serverOn.txt';

  /**
   * The Ark Server Status Helper.
   *
   * @var \Drupal\ark_server_status\ArkServerStatusHelperInterface
   */
  protected ArkServerStatusHelperInterface $arkServerStatusHelper;

  /**
   * The Key repository service.
   *
   * @var \Drupal\key\KeyRepositoryInterface
   */
  protected KeyRepositoryInterface $key;

  /**
   * The Cache bin service.
   *
   * @var \Drupal\Core\Cache\CacheBackendInterface
   */
  protected CacheBackendInterface $cache;

  /**
   * Constructs an ArkServerStatusCommands object.
   */
  public function __construct(ArkServerStatusHelperInterface $arkServerStatusHelper, KeyRepositoryInterface $key, CacheBackendInterface $cache) {
    parent::__construct();
    $this->arkServerStatusHelper = $arkServerStatusHelper;
    $this->key = $key;
    $this->cache = $cache;
  }

  /**
   * Provides server status.
   *
   * @usage ark_server_status-get-ark-server-status
   *
   * @command ark_server_status:status_notifier
   * @aliases ass:sn
   */
  public function getArkServerStatus() {
    print 'Starting service.';
    if ($this->key->getkey(self::TOKEN)) {
      print 'Token found.';
      $authToken = $this->key->getKey(self::TOKEN)->getKeyValue();
      $status = $this->arkServerStatusHelper->checkServer($authToken);
      print $status;

      if ($status === 'started' || $status === 'restarting') {
        $players = $this->cache->get('ark_players')->data;
        print $players;
        if ($players === 0) {
          if (file_exists(self::FILE)) {
            $this->logger->notice('Would turn off the server.');
            var_dump('Would turn off the server.');
            $this->arkServerStatusHelper->serverOff($authToken);
          }
          else {
            $this->logger->notice('Would create a file.');
            var_dump('Would create a file.');
            fopen(self::FILE, 'w');
          }
        }
        else {
          if (file_exists(self::FILE)) {
            $this->logger->notice('Would unlink file.');
            var_dump('Would unlink file.');
            unlink(self::FILE);
          }
        }
      }
      else {
        if (file_exists(self::FILE)) {
          $this->logger->notice('Would unlink file.');
          var_dump('Would unlink file.');
          unlink(self::FILE);
        }
      }
    }
  }

  /**
   * Provides server list.
   *
   * @usage ark_server_status-get-server-list
   *
   * @command ark_server_status:get_list
   * @aliases ass:gl
   */
  public function getServerList() {
    $serverList = $this->arkServerStatusHelper->getPlayers(0, '');

  }


}
