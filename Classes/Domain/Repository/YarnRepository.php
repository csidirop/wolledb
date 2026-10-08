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

}
