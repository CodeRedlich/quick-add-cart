<?php declare(strict_types=1);

use CodeRedlich\QuickAddCart\Storefront\Controller\QuickAddCartController;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services->load('CodeRedlich\\QuickAddCart\\', '../../')
        ->exclude('../../{Resources,Migration}');

    $services->set(QuickAddCartController::class)
        ->public();
};