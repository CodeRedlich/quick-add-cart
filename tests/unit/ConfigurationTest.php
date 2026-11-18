<?php declare(strict_types=1);
namespace CodeRedlich\QuickAddCart\Tests;
use CodeRedlich\QuickAddCart\Services\CheckAvailabilityService;
use CodeRedlich\QuickAddCart\Storefront\Controller\QuickAddCartController;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use Symfony\Component\Routing\Loader\PhpFileLoader as RoutingPhpFileLoader;
use Symfony\Component\Routing\RouteCollection;
final class ConfigurationTest extends TestCase
{
    public function testServicesAreAutowiredAndControllerIsPublic(): void
    {
        $container = new ContainerBuilder();
        $loader = new PhpFileLoader($container, new FileLocator(__DIR__ . '/../../src/Resources/config'));
        $loader->load('services.php');
        self::assertTrue($container->getDefinition(CheckAvailabilityService::class)->isAutowired());
        self::assertTrue($container->getDefinition(CheckAvailabilityService::class)->isAutoconfigured());
        self::assertTrue($container->getDefinition(QuickAddCartController::class)->isPublic());
        self::assertFalse($container->hasDefinition('CodeRedlich\\QuickAddCart\\Resources\\config\\services'));
    }
    public function testRoutesImportControllerAttributes(): void
    {
        $path = realpath(__DIR__ . '/../../src/Resources/config/routes.php');
        $loader = $this->createMock(RoutingPhpFileLoader::class);
        $loader->expects(self::once())->method('import')
            ->with('../../Storefront/Controller/*Controller.php', 'attribute', false, $path, null)
            ->willReturn(new RouteCollection());
        $configure = require $path;
        $configure(new RoutingConfigurator(new RouteCollection(), $loader, $path, $path));
    }
}
