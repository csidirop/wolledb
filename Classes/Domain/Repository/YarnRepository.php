<?php

declare(strict_types=1);

namespace SBW\WDB\Domain\Repository;

use SBW\WDB\Domain\Model\Yarn;
use SBW\WDB\Utility\SizeSorter;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * @extends Repository<Yarn>
 */
class YarnRepository extends Repository
{
    protected $defaultOrderings = ['name' => QueryInterface::ORDER_ASCENDING];

    /**
     * Returns yarns, optionally filtered by main fiber / yarn weight and sorted by a size field.
     *
     * Filtering happens in the database. Sorting by size happens in PHP (see SizeSorter), because the
     * sizes are stored as text ("2.5 mm", "12 mm") and a database sort would put "12 mm" before "2.5 mm".
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

        return SizeSorter::sort($yarns, $sortBy, $sortDirection);
    }
}
