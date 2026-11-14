<?php
declare(strict_types=1);

namespace CodeRedlich\Storefront\Twig;

use CodeRedlich\Interface\CheckAvailabilityInterface;
use CodeRedlich\Interface\TwigFunctionTagInterface;
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