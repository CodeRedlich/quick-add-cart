<?php
declare(strict_types=1);

namespace CodeRedlich\Tests\Storefront\Twig;

use CodeRedlich\Interface\CheckAvailabilityInterface;
use CodeRedlich\Interface\TwigFunctionTagInterface;
use CodeRedlich\Storefront\Twig\CheckAvailabilityTwigFunction;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Twig\Environment;
use Twig\Loader\ArrayLoader;

#[CoversClass(CheckAvailabilityTwigFunction::class)]
final class CheckAvailabilityTwigFunctionTest extends TestCase
{
    #[DataProvider('availabilityCases')]
    public function testTwigFunctionPassesContextToAvailabilityService(bool $enabled, string $expected): void
    {
        $context = $this->createStub(SalesChannelContext::class);
        $service = $this->createMock(CheckAvailabilityInterface::class);
        $service->expects(self::once())
            ->method('isEnabled')
            ->with(self::identicalTo($context))
            ->willReturn($enabled);


        $twig = new Environment(new ArrayLoader([
            'availability' => '{% if quickAddIsEnabled(context) %}enabled{% else %}disabled{% endif %}',
        ]));

        $extension = new CheckAvailabilityTwigFunction($service);
        $twig->addExtension($extension);

        self::assertSame($expected, $twig->render('availability', ['context' => $context]));
    }

    public function testInterfaceImplementation(): void
    {
        $extension = new CheckAvailabilityTwigFunction($this->createStub(CheckAvailabilityInterface::class));
        self::assertInstanceOf(TwigFunctionTagInterface::class, $extension);
    }

    public static function availabilityCases(): iterable
    {
        yield 'enabled' => [true, 'enabled'];
        yield 'disabled' => [false, 'disabled'];
    }
}
