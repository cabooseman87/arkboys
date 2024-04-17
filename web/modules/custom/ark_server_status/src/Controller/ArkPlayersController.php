<?php

declare(strict_types=1);

namespace Drupal\ark_server_status\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\ark_server_status\ArkServerStatusHelperInterface;
use Drupal\key\KeyRepositoryInterface;

/**
 * Returns responses for Ark Server Status routes.
 */
final class ArkPlayersController extends ControllerBase {

  const TOKEN = 'nitrado_token';
  const SERVICE_ID = 15083592;

  /**
   * The Ark server status helper service.
   *
   * @var \Drupal\ark_server_status\ArkServerStatusHelperInterface
   */
  protected ArkServerStatusHelperInterface $arkServerStatus;

  /**
   * The Key Repository service.
   *
   * @var \Drupal\key\KeyRepositoryInterface
   */
  protected KeyRepositoryInterface $keyRepository;

  /**
   * The controller constructor.
   */
  public function __construct(protected $loggerFactory, ArkServerStatusHelperInterface $arkServerStatusHelper, KeyRepositoryInterface $keyRepository) {
    $this->arkServerStatus = $arkServerStatusHelper;
    $this->keyRepository = $keyRepository;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): self {
    return new self(
      $container->get('logger.factory'),
      $container->get('ark_server_status.helper'),
      $container->get('key.repository'),
    );
  }

  /**
   * Builds the response.
   */
  public function __invoke(): array {
    $authToken = $this->keyRepository->getKey(self::TOKEN)->getKeyValue();
    $players = [];
    if ($this->arkServerStatus->checkPlayers(self::SERVICE_ID, $authToken) > 0) {
      $players = $this->arkServerStatus->getPlayers(self::SERVICE_ID, $authToken);
      var_dump($players);
    }
    $build['player_label'] = [
      '#type' => 'item',
      '#markup' => $this->t('Players!'),
    ];

//    foreach ($players as $player) {
//      $build['player'][] = [
//        '#type' => 'item',
//        '#markup' => $player,
//      ];
//    }
    return $build;
  }

}
