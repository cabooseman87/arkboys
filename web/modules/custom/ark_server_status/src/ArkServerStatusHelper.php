<?php

declare(strict_types=1);

namespace Drupal\ark_server_status;

use Drupal\Core\Logger\LoggerChannelFactoryInterface;

/**
 * @todo Add class description.
 */
final class ArkServerStatusHelper implements ArkServerStatusHelperInterface {

  /**
   * Constructs an ArkServerStatusHelper object.
   */
  public function __construct(
    private readonly LoggerChannelFactoryInterface $loggerFactory,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function doSomething(): void {
    // @todo Place your code here.
  }

}
