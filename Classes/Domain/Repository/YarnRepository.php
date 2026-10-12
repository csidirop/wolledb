<?php

declare(strict_types=1);

namespace SBW\WDB\Domain\Repository;

use SBW\WDB\Domain\Model\Yarn;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * @extends Repository<Yarn>
 */
class YarnRepository extends Repository
{
    protected $defaultOrderings = ['name' => QueryInterface::ORDER_ASCENDING];

    private const SORTABLE_FIELDS = ['hookSize', 'needleSize'];

    /**
     * Returns yarns, optionally filtered by main fiber / yarn weight and sorted by a size field.
     *
     * Filtering happens in the database. Sorting by size happens in PHP, because the sizes are stored
     * as strings ("2.5 mm", "12 mm") and a database sort would put "12 mm" before "2.5 mm".
     *
     * @return list<Yarn>
     */
    public function findFiltered(
        string $mainfiber = '',
        string $yarnWeight = '',
        string $sortBy = 'name',
        string $sortDirection = 'asc',
    ): array {
        $query = $this->createQuery();

        $constraints = [];
        if ($mainfiber !== '') {
            $constraints[] = $query->equals('mainfiber', $mainfiber);
        }
        if ($yarnWeight !== '') {
            $constraints[] = $query->equals('yarnWeight', $yarnWeight);
        }
        if ($constraints !== []) {
            $query->matching($query->logicalAnd(...$constraints));
        }

        $yarns = array_values($query->execute()->toArray());

        if (in_array($sortBy, self::SORTABLE_FIELDS, true)) {
            $getter = 'get' . ucfirst($sortBy);
            $direction = $sortDirection === 'desc' ? -1 : 1;

            // usort() is stable (PHP 8+), so yarns with equal sizes keep their name order.
            usort($yarns, function (Yarn $a, Yarn $b) use ($getter, $direction): int {
                $sizeA = $this->sizeToNumber($a->$getter());
                $sizeB = $this->sizeToNumber($b->$getter());

                // Yarns without a size always go to the end, no matter the direction.
                if ($sizeA === null || $sizeB === null) {
                    return ($sizeA === null) <=> ($sizeB === null);
                }

                return ($sizeA <=> $sizeB) * $direction;
            });
        }

        return $yarns;
    }

    /**
     * "2.5 mm" => 2.5, "3,5 mm" => 3.5, "12 mm" => 12.0, "" => null
     */
    private function sizeToNumber(string $size): ?float
    {
        if (preg_match('/\d+(?:[.,]\d+)?/', $size, $matches) !== 1) {
            return null;
        }

        return (float)str_replace(',', '.', $matches[0]);
    }

}
