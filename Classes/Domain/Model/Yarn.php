<?php

declare(strict_types=1);

namespace SBW\WDB\Domain\Model;

use TYPO3\CMS\Extbase\Annotation as Extbase;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\Generic\LazyLoadingProxy;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;


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
    protected string $yarnWeight = '';

    #[Extbase\Validate(['validator' => 'StringLength', 'options' => ['maximum' => 255]])]
    protected string $source = '';

    /**
     * The skeins of this yarn. The relation is defined by the "skeins" column in the TCA
     * (inline, foreign field "yarn" in the skein table). Removing a yarn also removes its skeins.
     *
     * @var ObjectStorage<Skein>
     */
    #[Lazy]
    #[Cascade(['value' => 'remove'])]
    protected ObjectStorage $skeins;

    public function __construct()
    {
        $this->initializeObject();
    }

    /**
     * Called by Extbase after the object was loaded from the database as well.
     */
    public function initializeObject(): void
    {
        $this->skeins ??= new ObjectStorage();
    }


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
        return $this->yarnWeight;
    }

    public function setYarnWeight(string $yarnWeight): void
    {
        $this->yarnWeight = $yarnWeight;
    }

    public function getSource(): string
    {
        return $this->source;
    }

    public function setSource(string $source): void
    {
        $this->source = $source;
    }
    /**
     * @return ObjectStorage<Skein>
     */
    public function getSkeins(): ObjectStorage
    {
        return $this->skeins;
    }

    /**
     * @param ObjectStorage<Skein> $skeins
     */
    public function setSkeins(ObjectStorage $skeins): void
    {
        $this->skeins = $skeins;
    }

    public function addSkein(Skein $skein): void
    {
        $this->skeins->attach($skein);
    }

    public function removeSkein(Skein $skein): void
    {
        $this->skeins->detach($skein);
    }

    /**
     * All skeins for display: available ones first, used up ones at the end;
     * within each group sorted by color (ignoring case), then by remaining weight (heaviest first).
     *
     * @return list<Skein>
     */
    public function getSortedSkeins(): array
    {
        $skeins = array_values($this->skeins->toArray());
        usort($skeins, static fn(Skein $a, Skein $b): int => [
            $a->isUsedUp(),
            mb_strtolower(trim($a->getColor())),
            -$a->getCurrentWeight(),
        ] <=> [
            $b->isUsedUp(),
            mb_strtolower(trim($b->getColor())),
            -$b->getCurrentWeight(),
        ]);

        return $skeins;
    }

    /**
     * Skeins that are not used up yet (remaining weight above 0).
     *
     * @return list<Skein>
     */
    public function getAvailableSkeins(): array
    {
        return array_values(array_filter(
            $this->skeins->toArray(),
            static fn(Skein $skein): bool => $skein->getCurrentWeight() > 0
        ));
    }
    /**
     * Number of skeins that are not used up yet.
     */
    public function getStockCount(): int
    {
        return count($this->getAvailableSkeins());
    }

    /**
     * Total remaining weight in grams of all skeins that are not used up yet.
     */
    public function getStockWeight(): int
    {
        return array_sum(array_map(
            static fn(Skein $skein): int => $skein->getCurrentWeight(),
            $this->getAvailableSkeins()
        ));
    }

    /**
     * Skeins that are used up (remaining weight 0), sorted as in getSortedSkeins().
     *
     * @return list<Skein>
     */
    public function getSortedUsedUpSkeins(): array
    {
        return array_values(array_filter(
            $this->getSortedSkeins(),
            static fn(Skein $skein): bool => $skein->isUsedUp()
        ));
    }

    /**
     * Skeins that are not used up yet, sorted as in getSortedSkeins().
     *
     * @return list<Skein>
     */
    public function getSortedAvailableSkeins(): array
    {
        return array_values(array_filter(
            $this->getSortedSkeins(),
            static fn(Skein $skein): bool => !$skein->isUsedUp()
        ));
    }

    /**
     * Available skeins grouped by color, most frequent first, e.g.
     * [['color' => 'schwarz', 'count' => 3], ['color' => 'blau', 'count' => 1]].
     * Colors are compared ignoring case and surrounding spaces ("Schwarz" = "schwarz ").
     *
     * @return list<array{color: string, count: int}>
     */
    public function getStockColors(): array
    {
        $groups = [];
        foreach ($this->getAvailableSkeins() as $skein) {
            $label = trim($skein->getColor());
            $key = mb_strtolower($label);
            if (!isset($groups[$key])) {
                $groups[$key] = ['key' => $key, 'color' => $label, 'count' => 0];
            }
            $groups[$key]['count']++;
        }

        $groups = array_values($groups);
        usort($groups, static fn(array $a, array $b): int => [$b['count'], $a['key']] <=> [$a['count'], $b['key']]);

        return array_map(
            static fn(array $group): array => ['color' => $group['color'], 'count' => $group['count']],
            $groups
        );
    }

}
