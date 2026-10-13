<?php

declare(strict_types=1);

namespace SBW\WDB\Domain\Repository;

use SBW\WDB\Domain\Model\Skein;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * @extends Repository<Skein>
 */
class SkeinRepository extends Repository
{
    protected $defaultOrderings = ['color' => QueryInterface::ORDER_ASCENDING];
}
