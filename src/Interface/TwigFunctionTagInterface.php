<?php
declare(strict_types=1);

namespace CodeRedlich\Interface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('twig.extension')]
interface TwigFunctionTagInterface
{
    public function getFunctions(): array;
}