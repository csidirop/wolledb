<?php

declare(strict_types=1);

namespace SBW\WDB\Domain\Model;

use SBW\WDB\Domain\Validator\HttpUrlValidator;
use SBW\WDB\Domain\Validator\TcaSelectItemValidator;
use TYPO3\CMS\Extbase\Annotation as Extbase;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * A knitting or crochet project
 *
 * Rules that involve more than one property (end date not before start date, status changes)
 * are checked by the ProjectValidator.
 */
class Project extends AbstractEntity
{
    #[Extbase\Validate(['validator' => 'NotEmpty'])]
    #[Extbase\Validate(['validator' => 'StringLength', 'options' => ['maximum' => 255]])]
    protected string $name = '';

    #[Extbase\Validate([
        'validator' => TcaSelectItemValidator::class,
        'options' => ['table' => 'tx_wdb_domain_model_project', 'field' => 'status', 'allowEmpty' => false],
    ])]
    protected string $status = ProjectStatus::PLANNED;

    /**
     * What is being made, e.g. "Pullover" or "Hat".
     */
    #[Extbase\Validate(['validator' => 'StringLength', 'options' => ['maximum' => 255]])]
    protected string $kind = '';

    protected ?\DateTime $startDate = null;

    protected ?\DateTime $endDate = null;

    /**
     * Link to the pattern. Only http(s) links are accepted, so it is safe to use as a href.
     */
    #[Extbase\Validate(['validator' => HttpUrlValidator::class])]
    protected string $patternUrl = '';

    /**
     * Needle or hook size used, free text so ranges like "3.5-4.5 mm" are possible.
     */
    #[Extbase\Validate(['validator' => 'StringLength', 'options' => ['maximum' => 255]])]
    protected string $needleSize = '';

    #[Extbase\Validate(['validator' => 'StringLength', 'options' => ['maximum' => 10000]])]
    protected string $notes = '';

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function isFinished(): bool
    {
        return $this->status === ProjectStatus::FINISHED;
    }

    public function getKind(): string
    {
        return $this->kind;
    }

    public function setKind(string $kind): void
    {
        $this->kind = $kind;
    }

    public function getStartDate(): ?\DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(?\DateTime $startDate): void
    {
        $this->startDate = $startDate;
    }

    public function getEndDate(): ?\DateTime
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTime $endDate): void
    {
        $this->endDate = $endDate;
    }

    public function getPatternUrl(): string
    {
        return $this->patternUrl;
    }

    public function setPatternUrl(string $patternUrl): void
    {
        $this->patternUrl = $patternUrl;
    }

    public function getNeedleSize(): string
    {
        return $this->needleSize;
    }

    public function setNeedleSize(string $needleSize): void
    {
        $this->needleSize = $needleSize;
    }

    public function getNotes(): string
    {
        return $this->notes;
    }

    public function setNotes(string $notes): void
    {
        $this->notes = $notes;
    }
}
