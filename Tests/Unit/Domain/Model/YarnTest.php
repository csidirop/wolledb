<?php

declare(strict_types=1);

namespace SBW\WDB\Tests\Unit\Domain\Model;

use PHPUnit\Framework\Attributes\Test;
use SBW\WDB\Domain\Model\Skein;
use SBW\WDB\Domain\Model\Yarn;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

final class YarnTest extends UnitTestCase
{
    #[Test]
    public function newYarnHasNoSkeinsAndNoStock(): void
    {
        $yarn = new Yarn();

        self::assertCount(0, $yarn->getSkeins());
        self::assertSame(0, $yarn->getStockCount());
        self::assertSame(0, $yarn->getStockWeight());
        self::assertSame([], $yarn->getStockColors());
        self::assertSame([], $yarn->getSortedSkeins());
    }

    #[Test]
    public function addAndRemoveSkeinChangeTheSkeinStorage(): void
    {
        $yarn = new Yarn();
        $skein = $this->skein('schwarz', 100);

        $yarn->addSkein($skein);
        self::assertCount(1, $yarn->getSkeins());

        $yarn->removeSkein($skein);
        self::assertCount(0, $yarn->getSkeins());
    }

    #[Test]
    public function stockCountIgnoresUsedUpSkeins(): void
    {
        $yarn = $this->yarnWith(
            $this->skein('schwarz', 100),
            $this->skein('schwarz', 62),
            $this->skein('gelb', 0),
        );

        self::assertSame(2, $yarn->getStockCount());
    }

    #[Test]
    public function stockWeightSumsTheRemainingWeightOfAvailableSkeinsOnly(): void
    {
        $yarn = $this->yarnWith(
            $this->skein('schwarz', 100),
            $this->skein('schwarz', 62),
            $this->skein('blau', 200),
            $this->skein('gelb', 0),
        );

        self::assertSame(362, $yarn->getStockWeight());
    }

    #[Test]
    public function stockColorsGroupColorsIgnoringCaseAndSurroundingSpaces(): void
    {
        $yarn = $this->yarnWith(
            $this->skein('schwarz', 100),
            $this->skein('Schwarz ', 100),
            $this->skein('blau', 100),
        );

        // The first spelling that was seen is used as the label.
        self::assertSame(
            [
                ['color' => 'schwarz', 'count' => 2],
                ['color' => 'blau', 'count' => 1],
            ],
            $yarn->getStockColors()
        );
    }

    #[Test]
    public function stockColorsIgnoreUsedUpSkeins(): void
    {
        $yarn = $this->yarnWith(
            $this->skein('schwarz', 100),
            $this->skein('gelb', 0),
        );

        self::assertSame([['color' => 'schwarz', 'count' => 1]], $yarn->getStockColors());
    }

    #[Test]
    public function stockColorsAreSortedByCountThenAlphabetically(): void
    {
        $yarn = $this->yarnWith(
            $this->skein('rot', 100),
            $this->skein('blau', 100),
            $this->skein('schwarz', 100),
            $this->skein('schwarz', 100),
        );

        self::assertSame(
            [
                ['color' => 'schwarz', 'count' => 2],
                ['color' => 'blau', 'count' => 1],
                ['color' => 'rot', 'count' => 1],
            ],
            $yarn->getStockColors()
        );
    }

    #[Test]
    public function stockColorsWithEqualCountAreSortedAlphabeticallyIgnoringCase(): void
    {
        // Compared as written, the uppercase "W" would come before the lowercase "s".
        $yarn = $this->yarnWith(
            $this->skein('Weiß', 100),
            $this->skein('schwarz', 100),
        );

        self::assertSame(
            ['schwarz', 'Weiß'],
            array_column($yarn->getStockColors(), 'color')
        );
    }

    #[Test]
    public function colorNamesThatLookLikeNumbersAreGroupedLikeAnyOtherColor(): void
    {
        $yarn = $this->yarnWith(
            $this->skein('5', 100),
            $this->skein('5', 100),
            $this->skein('12', 100),
        );

        self::assertSame(
            [
                ['color' => '5', 'count' => 2],
                ['color' => '12', 'count' => 1],
            ],
            $yarn->getStockColors()
        );
    }

    #[Test]
    public function skeinsWithoutColorAreGroupedTogether(): void
    {
        $yarn = $this->yarnWith(
            $this->skein('', 100),
            $this->skein('  ', 100),
        );

        self::assertSame([['color' => '', 'count' => 2]], $yarn->getStockColors());
    }

    #[Test]
    public function sortedSkeinsPutUsedUpSkeinsLast(): void
    {
        $yarn = $this->yarnWith(
            $this->skein('aaa', 0),
            $this->skein('zzz', 100),
        );

        self::assertSame(['zzz', 'aaa'], $this->colorsOf($yarn->getSortedSkeins()));
    }

    #[Test]
    public function sortedSkeinsAreSortedByColorIgnoringCaseAndSurroundingSpaces(): void
    {
        $yarn = $this->yarnWith(
            $this->skein('weiß', 100),
            $this->skein(' Blau', 100),
            $this->skein('grün', 100),
        );

        self::assertSame([' Blau', 'grün', 'weiß'], $this->colorsOf($yarn->getSortedSkeins()));
    }

    #[Test]
    public function sortedSkeinsPutTheHeavierSkeinFirstWithinOneColor(): void
    {
        $yarn = $this->yarnWith(
            $this->skein('schwarz', 62),
            $this->skein('schwarz', 100),
            $this->skein('schwarz', 80),
        );

        $weights = array_map(
            static fn (Skein $skein): int => $skein->getCurrentWeight(),
            $yarn->getSortedSkeins()
        );

        self::assertSame([100, 80, 62], $weights);
    }

    #[Test]
    public function availableAndUsedUpSkeinsArePartitionedAndKeepTheSortOrder(): void
    {
        $yarn = $this->yarnWith(
            $this->skein('gelb', 0),
            $this->skein('schwarz', 62),
            $this->skein('blau', 0),
            $this->skein('rot', 100),
        );

        self::assertSame(['rot', 'schwarz'], $this->colorsOf($yarn->getSortedAvailableSkeins()));
        self::assertSame(['blau', 'gelb'], $this->colorsOf($yarn->getSortedUsedUpSkeins()));
    }

    private function skein(string $color, int $currentWeight): Skein
    {
        $skein = new Skein();
        $skein->setColor($color);
        $skein->setInitialWeight(100);
        $skein->setCurrentWeight($currentWeight);

        return $skein;
    }

    private function yarnWith(Skein ...$skeins): Yarn
    {
        $yarn = new Yarn();
        foreach ($skeins as $skein) {
            $yarn->addSkein($skein);
        }

        return $yarn;
    }

    /**
     * @param list<Skein> $skeins
     * @return list<string>
     */
    private function colorsOf(array $skeins): array
    {
        return array_map(static fn (Skein $skein): string => $skein->getColor(), $skeins);
    }
}
