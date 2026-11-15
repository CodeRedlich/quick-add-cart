<?php
declare(strict_types=1);

namespace CodeRedlich\QuickAddCart\Storefront\Twig;

use CodeRedlich\QuickAddCart\Interface\CheckAvailabilityInterface;
use CodeRedlich\QuickAddCart\Interface\TwigFunctionTagInterface;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class CheckAvailabilityTwigFunction extends AbstractExtension implements TwigFunctionTagInterface
{
    public function __construct(
        private readonly CheckAvailabilityInterface $checkAvailabilityService
    )
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('quickAddIsEnabled', [$this, 'isEnabled'])
        ];
    }

    public function isEnabled(SalesChannelContext $salesChannelContext): bool
    {
        return $this->checkAvailabilityService->isEnabled($salesChannelContext);
    }
}