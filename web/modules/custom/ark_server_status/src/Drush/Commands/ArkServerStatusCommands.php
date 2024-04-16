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
        $players = $this->arkServerStatusHelper->checkPlayers(self::SERVICE_ID, $authToken);
        print $players;

        if ($players === 0) {
          if (file_exists(self::FILE)) {
            $this->arkServerStatusHelper->serverOff($authToken);
          }
          else {
            fopen(self::FILE, 'w');
          }
        }
      }
      else {
        if (file_exists(self::FILE)) {
         unlink(self::FILE);
        }
      }
    }
  }

}
