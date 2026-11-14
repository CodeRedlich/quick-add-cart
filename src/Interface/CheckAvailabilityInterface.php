<?php
declare(strict_types=1);

namespace CodeRedlich\Interface;

use Shopware\Core\System\SalesChannel\SalesChannelContext;

interface CheckAvailabilityInterface
{
    public function isEnabled(SalesChannelContext $context): bool;
}