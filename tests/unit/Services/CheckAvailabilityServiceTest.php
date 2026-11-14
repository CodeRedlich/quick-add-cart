<?php
declare(strict_types=1);

namespace CodeRedlich\Tests\Services;

use CodeRedlich\Interface\CheckAvailabilityInterface;
use CodeRedlich\Services\CheckAvailabilityService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Core\System\SystemConfig\SystemConfigService;

#[CoversClass(CheckAvailabilityService::class)]
final class CheckAvailabilityServiceTest extends TestCase
{
    #[DataProvider('availabilityCases')]
    public function testChecksRuleForCurrentSalesChannel(string $configuredRule, array $activeRules, bool $expected): void
    {
        $context = $this->createStub(SalesChannelContext::class);
        $context->method('getSalesChannelId')->willReturn('test-sales-channel');
        $context->method('getRuleIds')->willReturn($activeRules);

        $config = $this->createMock(SystemConfigService::class);
        $config->expects(self::once())
            ->method('getString')
            ->with('QuickAddCart.config.availabilityRule', 'test-sales-channel')
            ->willReturn($configuredRule);

        $service = new CheckAvailabilityService($config);

        self::assertSame($expected, $service->isEnabled($context));
    }

    public function testAssertInterfaceImplementation(): void
    {
        $service = new CheckAvailabilityService($this->createStub(SystemConfigService::class));
        self::assertInstanceOf(CheckAvailabilityInterface::class, $service);
    }

    public static function availabilityCases(): iterable
    {
        yield 'no configured rule' => ['', [], true];
        yield 'configured rule is active' => ['configured-rule', ['other-rule', 'configured-rule'], true];
        yield 'configured rule is absent' => ['configured-rule', ['other-rule'], false];
        yield 'no active rules' => ['configured-rule', [], false];
    }
}
