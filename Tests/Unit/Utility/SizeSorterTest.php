<?php

declare(strict_types=1);

namespace SBW\WDB\Tests\Unit\Utility;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use SBW\WDB\Domain\Model\Yarn;
use SBW\WDB\Utility\SizeSorter;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

final class SizeSorterTest extends UnitTestCase
{
    /**
     * @return array<string, array{string, float|null}>
     */
    public static function sizeDataProvider(): array
    {
        return [
            'decimal point' => ['2.5 mm', 2.5],
            'decimal comma' => ['3,5 mm', 3.5],
            'whole number' => ['12 mm', 12.0],
            'surrounding spaces' => ['  4 ', 4.0],
            'only the first number counts' => ['US 8 (5 mm)', 8.0],
            'empty text' => ['', null],
            'no number' => ['mm', null],
        ];
    }

    #[Test]
    #[DataProvider('sizeDataProvider')]
    public function toNumberExtractsTheFirstNumber(string $size, ?float $expected): void
    {
        self::assertSame($expected, SizeSorter::toNumber($size));
    }

    #[Test]
    public function sortsNumericallyAndNotAlphabetically(): void
    {
        $yarns = [
            $this->yarn('a', hookSize: '12 mm'),
            $this->yarn('b', hookSize: '2.5 mm'),
            $this->yarn('c', hookSize: '10 mm'),
        ];

        // Alphabetically, "10 mm" and "12 mm" would come before "2.5 mm".
        self::assertSame(['b', 'c', 'a'], $this->namesOf(SizeSorter::sort($yarns, 'hookSize')));
    }

    #[Test]
    public function sortsDescendingWhenAsked(): void
    {
        $yarns = [
            $this->yarn('a', hookSize: '2.5 mm'),
            $this->yarn('b', hookSize: '12 mm'),
            $this->yarn('c', hookSize: '10 mm'),
        ];

        self::assertSame(['b', 'c', 'a'], $this->namesOf(SizeSorter::sort($yarns, 'hookSize', 'desc')));
    }

    #[Test]
    public function yarnsWithoutSizeGoLastInBothDirections(): void
    {
        $yarns = [
            $this->yarn('none', hookSize: ''),
            $this->yarn('big', hookSize: '10 mm'),
            $this->yarn('small', hookSize: '2.5 mm'),
        ];

        self::assertSame(['small', 'big', 'none'], $this->namesOf(SizeSorter::sort($yarns, 'hookSize', 'asc')));
        self::assertSame(['big', 'small', 'none'], $this->namesOf(SizeSorter::sort($yarns, 'hookSize', 'desc')));
    }

    #[Test]
    public function yarnsWithEqualSizeKeepTheirOriginalOrder(): void
    {
        $yarns = [
            $this->yarn('first', hookSize: '4 mm'),
            $this->yarn('second', hookSize: '4 mm'),
            $this->yarn('third', hookSize: '4 mm'),
        ];

        self::assertSame(['first', 'second', 'third'], $this->namesOf(SizeSorter::sort($yarns, 'hookSize', 'asc')));
        self::assertSame(['first', 'second', 'third'], $this->namesOf(SizeSorter::sort($yarns, 'hookSize', 'desc')));
    }

    #[Test]
    public function sortsByNeedleSizeIndependentlyOfHookSize(): void
    {
        $yarns = [
            $this->yarn('a', hookSize: '2 mm', needleSize: '9 mm'),
            $this->yarn('b', hookSize: '9 mm', needleSize: '2 mm'),
        ];

        self::assertSame(['a', 'b'], $this->namesOf(SizeSorter::sort($yarns, 'hookSize')));
        self::assertSame(['b', 'a'], $this->namesOf(SizeSorter::sort($yarns, 'needleSize')));
    }

    #[Test]
    public function unknownFieldLeavesTheOrderUnchanged(): void
    {
        $yarns = [
            $this->yarn('b', hookSize: '2 mm'),
            $this->yarn('a', hookSize: '9 mm'),
        ];

        self::assertSame(['b', 'a'], $this->namesOf(SizeSorter::sort($yarns, 'name')));
        self::assertSame(['b', 'a'], $this->namesOf(SizeSorter::sort($yarns, '')));
    }

    #[Test]
    public function anEmptyListStaysEmpty(): void
    {
        self::assertSame([], SizeSorter::sort([], 'hookSize'));
    }

    #[Test]
    public function onlyTheSizeFieldsAreSortable(): void
    {
        self::assertTrue(SizeSorter::isSortable('hookSize'));
        self::assertTrue(SizeSorter::isSortable('needleSize'));
        self::assertFalse(SizeSorter::isSortable('name'));
        self::assertFalse(SizeSorter::isSortable('getName'));
    }

    private function yarn(string $name, string $hookSize = '', string $needleSize = ''): Yarn
    {
        $yarn = new Yarn();
        $yarn->setName($name);
        $yarn->setHookSize($hookSize);
        $yarn->setNeedleSize($needleSize);

        return $yarn;
    }

    /**
     * @param list<Yarn> $yarns
     * @return list<string>
     */
    private function namesOf(array $yarns): array
    {
        return array_map(static fn (Yarn $yarn): string => $yarn->getName(), $yarns);
    }
}
