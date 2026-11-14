<?php
declare(strict_types=1);

namespace CodeRedlich\Services;

use CodeRedlich\Interface\CheckAvailabilityInterface;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Core\System\SystemConfig\SystemConfigService;

readonly class CheckAvailabilityService implements CheckAvailabilityInterface
{
    public function __construct(
        private SystemConfigService $systemConfigService
    )
    {
    }

    public function isEnabled(SalesChannelContext $context): bool
    {
        $ruleId = $this->systemConfigService->getString(
            'QuickAddCart.config.availabilityRule',
            $context->getSalesChannelId()
        );

        return $ruleId === '' || in_array($ruleId, $context->getRuleIds(), true);
    }
}
