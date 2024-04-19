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
    print 'Arkboys server.' . PHP_EOL;
    if ($this->key->getkey(self::TOKEN)) {
      print 'Token found.' . PHP_EOL;
      $authToken = $this->key->getKey(self::TOKEN)->getKeyValue();
      $status = $this->arkServerStatusHelper->checkServer($authToken);
      print 'Server Status is: ' . $status . PHP_EOL;

      if ($status === 'started' || $status === 'restarting') {
        $players = $this->cache->get('ark_players')->data;
        print 'Players online: ' . $players . PHP_EOL;
        if ($players === 0) {
          if (file_exists(self::FILE)) {
            print 'Turning off server.' . PHP_EOL;
            $this->arkServerStatusHelper->serverOff($authToken);
          }
          else {
            print 'File created.' . PHP_EOL;
            fopen(self::FILE, 'w');
          }
        }
        else {
          if (file_exists(self::FILE)) {
            print 'File unlinked.' . PHP_EOL;
            unlink(self::FILE);
          }
        }
      }
      else {
        if (file_exists(self::FILE)) {
          print 'File unlinked.' . PHP_EOL;
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
