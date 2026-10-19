<?php

declare(strict_types=1);

namespace SBW\WDB\Tests\Unit\Domain\Model;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use SBW\WDB\Domain\Model\Skein;
use SBW\WDB\Domain\Model\Yarn;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

final class SkeinTest extends UnitTestCase
{
    /**
     * @return array<string, array{int, bool}>
     */
    public static function usedUpDataProvider(): array
    {
        return [
            'full skein' => [100, false],
            'barely used' => [1, false],
            'empty' => [0, true],
            'negative weight counts as used up' => [-5, true],
        ];
    }

    #[Test]
    #[DataProvider('usedUpDataProvider')]
    public function isUsedUpDependsOnTheRemainingWeight(int $currentWeight, bool $expected): void
    {
        $skein = new Skein();
        $skein->setCurrentWeight($currentWeight);

        self::assertSame($expected, $skein->isUsedUp());
    }

    #[Test]
    public function initialWeightDoesNotInfluenceUsedUp(): void
    {
        $skein = new Skein();
        $skein->setInitialWeight(100);
        $skein->setCurrentWeight(0);

        self::assertTrue($skein->isUsedUp());
    }

    #[Test]
    public function gettersReturnWhatTheSettersStored(): void
    {
        $yarn = new Yarn();
        $skein = new Skein();
        $skein->setYarn($yarn);
        $skein->setColor('schwarz');
        $skein->setInitialWeight(100);
        $skein->setCurrentWeight(62);

        self::assertSame($yarn, $skein->getYarn());
        self::assertSame('schwarz', $skein->getColor());
        self::assertSame(100, $skein->getInitialWeight());
        self::assertSame(62, $skein->getCurrentWeight());
    }
}
