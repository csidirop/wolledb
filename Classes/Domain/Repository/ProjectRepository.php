<?php

declare(strict_types=1);

namespace SBW\WDB\Domain\Repository;

use SBW\WDB\Domain\Model\Project;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * @extends Repository<Project>
 */
class ProjectRepository extends Repository
{
    protected $defaultOrderings = ['name' => QueryInterface::ORDER_ASCENDING];
}
