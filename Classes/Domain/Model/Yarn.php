<?php

declare(strict_types=1);

namespace SBW\WDB\Domain\Model;

use TYPO3\CMS\Extbase\Annotation as Extbase;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\Generic\LazyLoadingProxy;

/**
 * This class represents a yarn (type), e.g., "Cotton".
 */
class Yarn extends AbstractEntity
{
    #[Extbase\Validate(['validator' => 'StringLength', 'options' => ['maximum' => 255]])]
    #[Extbase\Validate(['validator' => 'NotEmpty'])]
    protected string $name = '';

    #[Extbase\Validate(['validator' => 'StringLength', 'options' => ['maximum' => 255]])]
    protected string $hookSize = '';

    #[Extbase\Validate(['validator' => 'StringLength', 'options' => ['maximum' => 255]])]
    protected string $needleSize = '';

    #[Extbase\Validate(['validator' => 'Integer'])]
    protected int $yardage = 0;

    #[Extbase\Validate(['validator' => 'StringLength', 'options' => ['maximum' => 255]])]
    protected string $mainfiber = '';

    #[Extbase\Validate(['validator' => 'StringLength', 'options' => ['maximum' => 255]])]
    protected string $fibercomposition = '';

    #[Extbase\Validate(['validator' => 'StringLength', 'options' => ['maximum' => 255]])]
    protected string $yarn_weight = '';

    #[Extbase\Validate(['validator' => 'StringLength', 'options' => ['maximum' => 255]])]
    protected string $source = '';

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getHookSize(): string
    {
        return $this->hookSize;
    }

    public function setHookSize(string $hookSize): void
    {
        $this->hookSize = $hookSize;
    }

    public function getNeedleSize(): string
    {
        return $this->needleSize;
    }

    public function setNeedleSize(string $needleSize): void
    {
        $this->needleSize = $needleSize;
    }

    public function getYardage(): int
    {
        return $this->yardage;
    }

    public function setYardage(int $yardage): void
    {
        $this->yardage = $yardage;
    }

    public function getMainFiber(): string
    {
        return $this->mainfiber;
    }

    public function setMainFiber(string $mainfiber): void
    {
        $this->mainfiber = $mainfiber;
    }

    public function getFiberComposition(): string
    {
        return $this->fibercomposition;
    }

    public function setFiberComposition(string $fibercomposition): void
    {
        $this->fibercomposition = $fibercomposition;
    }

    public function getYarnWeight(): string
    {
        return $this->yarn_weight;
    }

    public function setYarnWeight(string $yarn_weight): void
    {
        $this->yarn_weight = $yarn_weight;
    }

    public function getSource(): string
    {
        return $this->source;
    }

    public function setSource(string $source): void
    {
        $this->source = $source;
    }
}
