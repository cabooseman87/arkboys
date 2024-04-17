<?php

namespace Drupal\ark_server_status\Drush\Commands;

use Drupal\ark_server_status\ArkServerStatusHelperInterface;
use Drush\Commands\DrushCommands;
use Drupal\key\KeyRepositoryInterface;

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
   * Constructs an ArkServerStatusCommands object.
   */
  public function __construct(ArkServerStatusHelperInterface $arkServerStatusHelper, KeyRepositoryInterface $key) {
    parent::__construct();
    $this->arkServerStatusHelper = $arkServerStatusHelper;
    $this->key = $key;
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
    if ($this->key->getkey(self::TOKEN)) {
      $authToken = $this->key->getKey(self::TOKEN)->getKeyValue();
      $status = $this->arkServerStatusHelper->checkServer($authToken);
      print $status;

      if ($status === 'started') {
        $players = \Drupal::cache()->get('ark_players');
        var_dump($players->data);

        if ($players === 0) {
          if (file_exists(self::FILE)) {
            $this->logger->notice('Would turn off the server.');
//            $this->arkServerStatusHelper->serverOff($authToken);
          }
          else {
            $this->logger->notice('Would create a file.');
//            fopen(self::FILE, 'w');
          }
        }
      }
      else {
        if (file_exists(self::FILE)) {
          $this->logger->notice('Would unlink file.');
//         unlink(self::FILE);
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
