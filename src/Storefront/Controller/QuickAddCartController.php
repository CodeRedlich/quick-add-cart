<?php declare(strict_types=1);

namespace CodeRedlich\QuickAddCart\Storefront\Controller;

use Shopware\Core\Content\Product\SalesChannel\Detail\AbstractProductDetailRoute;
use Shopware\Core\Content\Product\SalesChannel\Detail\ProductDetailRoute;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\PlatformRequest;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Controller\StorefrontController;
use Shopware\Storefront\Framework\Routing\StorefrontRouteScope;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(defaults: [PlatformRequest::ATTRIBUTE_ROUTE_SCOPE => [StorefrontRouteScope::ID]])]
class QuickAddCartController extends StorefrontController
{
    public const QUICK_ADD_ELEMENT_ID_PREFIX = 'cr-quick-add-';
    public function __construct(
        #[Autowire(service: ProductDetailRoute::class)]
        private readonly AbstractProductDetailRoute $productDetailRoute
    )
    {
    }

    #[Route('/quick-add-cart/buy-box/{productId}', name: 'frontend.quick_add_cart.buy_box', requirements: ['productId' => '[0-9a-f]{32}'], defaults: ['XmlHttpRequest' => true], methods: [Request::METHOD_GET])]
    public function buyBox(string $productId, Request $request, SalesChannelContext $context): Response
    {
        $detail = $this->productDetailRoute->load($productId, $request, $context, new Criteria());

        return $this->renderStorefront('@QuickAddCart/storefront/plugin/component/search/variant-modal.html.twig', [
            'product' => $detail->getProduct(),
            'configuratorSettings' => $detail->getConfigurator(),
            'elementId' => self::QUICK_ADD_ELEMENT_ID_PREFIX . Uuid::randomHex(),
        ]);
    }
}
