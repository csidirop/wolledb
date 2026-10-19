<?php

declare(strict_types=1);

namespace SBW\WDB\Utility;

use SBW\WDB\Domain\Model\Yarn;

/**
 * Sorts yarns by their hook or needle size.
 *
 * The sizes are stored as free text ("2.5 mm", "12 mm"), so a database sort would put "12 mm" before
 * "2.5 mm". This class pulls the number out of the text and compares numerically. It is pure PHP
 * (no database, no TYPO3), which makes it easy to unit test.
 */
final class SizeSorter
{
    /**
     * Model properties the list may be sorted by.
     */
    private const SORTABLE_FIELDS = ['hookSize', 'needleSize'];

    public static function isSortable(string $field): bool
    {
        return in_array($field, self::SORTABLE_FIELDS, true);
    }

    /**
     * Sorts the yarns by the given size field. Yarns without a size always go to the end, no matter the
     * direction. Yarns with equal sizes keep their original order (the sort is stable).
     * Unknown fields leave the order unchanged.
     *
     * @param list<Yarn> $yarns
     * @param string $direction 'asc' or 'desc'
     * @return list<Yarn>
     */
    public static function sort(array $yarns, string $field, string $direction = 'asc'): array
    {
        if (!self::isSortable($field)) {
            return $yarns;
        }

        $getter = 'get' . ucfirst($field);
        $factor = $direction === 'desc' ? -1 : 1;

        usort($yarns, static function (Yarn $a, Yarn $b) use ($getter, $factor): int {
            $sizeA = self::toNumber($a->$getter());
            $sizeB = self::toNumber($b->$getter());

            if ($sizeA === null || $sizeB === null) {
                return ($sizeA === null) <=> ($sizeB === null);
            }

            return ($sizeA <=> $sizeB) * $factor;
        });

        return $yarns;
    }

    /**
     * First number in the text: "2.5 mm" => 2.5, "3,5 mm" => 3.5, "12 mm" => 12.0, "" => null.
     */
    public static function toNumber(string $size): ?float
    {
        if (preg_match('/\d+(?:[.,]\d+)?/', $size, $matches) !== 1) {
            return null;
        }

        return (float)str_replace(',', '.', $matches[0]);
    }
}
