<?php

declare(strict_types=1);

namespace SBW\WDB\Domain\Model;

use TYPO3\CMS\Extbase\Annotation as Extbase;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * This class represents a single skein of a yarn, e.g., "100 g, black".
 */
class Skein extends AbstractEntity
{
    /**
     * The yarn this skein belongs to (column "yarn" holds the uid of the yarn).
     */
    protected ?Yarn $yarn = null;

    #[Extbase\Validate(['validator' => 'StringLength', 'options' => ['maximum' => 255]])]
    protected string $color = '';

    /**
     * Weight in grams when the skein was new.
     */
    protected int $initialWeight = 0;

    /**
     * Remaining weight in grams. 0 means the skein is used up.
     */
    protected int $currentWeight = 0;

    public function getYarn(): ?Yarn
    {
        return $this->yarn;
    }

    public function setYarn(?Yarn $yarn): void
    {
        $this->yarn = $yarn;
    }

    public function getColor(): string
    {
        return $this->color;
    }

    public function setColor(string $color): void
    {
        $this->color = $color;
    }

    public function getInitialWeight(): int
    {
        return $this->initialWeight;
    }

    public function setInitialWeight(int $initialWeight): void
    {
        $this->initialWeight = $initialWeight;
    }

    public function getCurrentWeight(): int
    {
        return $this->currentWeight;
    }

    public function setCurrentWeight(int $currentWeight): void
    {
        $this->currentWeight = $currentWeight;
    }

    public function isUsedUp(): bool
    {
        return $this->currentWeight <= 0;
    }
}
