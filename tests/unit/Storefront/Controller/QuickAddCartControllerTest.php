<?php declare(strict_types=1);
namespace CodeRedlich\QuickAddCart\Tests\Storefront\Controller;
use CodeRedlich\QuickAddCart\Storefront\Controller\QuickAddCartController;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Content\Product\SalesChannel\Detail\AbstractProductDetailRoute;
use Shopware\Core\Content\Product\SalesChannel\Detail\ProductDetailRouteResponse;
use Shopware\Core\Content\Product\SalesChannel\SalesChannelProductEntity;
use Shopware\Core\Content\Property\PropertyGroupCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
#[CoversClass(QuickAddCartController::class)]
final class QuickAddCartControllerTest extends TestCase
{
    public function testBuyBoxRendersLoadedProductWithUniqueElementIds(): void
    {
        $id = str_repeat('a', 32);
        $request = new Request();
        $context = $this->createStub(SalesChannelContext::class);
        $product = new SalesChannelProductEntity();
        $configurator = new PropertyGroupCollection();
        $detail = new ProductDetailRouteResponse($product, $configurator);
        $route = $this->createMock(AbstractProductDetailRoute::class);
        $route->expects(self::exactly(2))->method('load')
            ->with($id, self::identicalTo($request), self::identicalTo($context), self::callback(
                static fn (Criteria $criteria): bool => $criteria->getIds() === []
            ))->willReturn($detail);
        $controller = $this->getMockBuilder(QuickAddCartController::class)
            ->setConstructorArgs([$route])->onlyMethods(['renderStorefront'])->getMock();
        $ids = [];
        $response = new Response('buy box');
        $controller->expects(self::exactly(2))->method('renderStorefront')
            ->with('@QuickAddCart/storefront/plugin/component/search/variant-modal.html.twig', self::callback(
                static function (array $parameters) use ($product, $configurator, &$ids): bool {
                    self::assertSame($product, $parameters['product']);
                    self::assertSame($configurator, $parameters['configuratorSettings']);
                    self::assertMatchesRegularExpression('/^cr-quick-add-[0-9a-f]{32}$/', $parameters['elementId']);
                    $ids[] = $parameters['elementId'];
                    return true;
                }
            ))->willReturn($response);
        self::assertSame($response, $controller->buyBox($id, $request, $context));
        self::assertSame($response, $controller->buyBox($id, $request, $context));
        self::assertNotSame($ids[0], $ids[1]);
    }
    public function testProductLoadingFailureIsPropagatedWithoutRendering(): void
    {
        $route = $this->createStub(AbstractProductDetailRoute::class);
        $exception = new \RuntimeException('Product unavailable');
        $route->method('load')->willThrowException($exception);
        $controller = $this->getMockBuilder(QuickAddCartController::class)
            ->setConstructorArgs([$route])->onlyMethods(['renderStorefront'])->getMock();
        $controller->expects(self::never())->method('renderStorefront');
        $this->expectExceptionObject($exception);
        $controller->buyBox(str_repeat('a', 32), new Request(), $this->createStub(SalesChannelContext::class));
    }
}

