<?php

declare(strict_types=1);

namespace SBW\WDB\Controller;

use Psr\Http\Message\ResponseInterface;
use SBW\WDB\Domain\Model\Skein;
use SBW\WDB\Domain\Model\Yarn;
use SBW\WDB\Domain\Repository\SkeinRepository;
use TYPO3\CMS\Core\Http\RedirectResponse;
use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;
use TYPO3\CMS\Extbase\Annotation\IgnoreValidation;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * Controller for the skeins of a yarn. It belongs to the same plugin as the YarnController,
 * all forms live on the detail page of a yarn.
 *
 * A skein with a remaining weight of 0 counts as used up: it stays in the database (and is shown
 * greyed out in a collapsed section), so it can be put back into stock by entering a weight again.
 */
class SkeinController extends ActionController
{
    private const MAX_WEIGHT = 10000;
    private const MAX_QUANTITY = 50;

    public function __construct(
        private readonly SkeinRepository $skeinRepository,
    ) {
    }

    /**
     * Adds one or more new skeins to a yarn. A new skein is full: remaining weight = initial weight.
     *
     * The values arrive as strings and are checked by hand, so an empty or invalid field leads to a
     * clear message on the detail page instead of a generic Extbase error.
     *
     * Validation of the yarn is ignored so that records which don't meet today's rules can still get skeins.
     */
    #[IgnoreValidation(['argumentName' => 'yarn'])]
    public function createAction(Yarn $yarn, string $color = '', string $weight = '', string $quantity = '1'): ResponseInterface
    {
        $color = trim($color);
        $weightValue = $this->parseInt($weight);
        $quantityValue = $this->parseInt($quantity);

        if ($color === '' || mb_strlen($color) > 255) {
            return $this->failWith($yarn, 'Bitte gib eine Farbe an (maximal 255 Zeichen).');
        }
        if ($weightValue === null || $weightValue < 1 || $weightValue > self::MAX_WEIGHT) {
            return $this->failWith($yarn, 'Das Gewicht muss eine ganze Zahl zwischen 1 und ' . self::MAX_WEIGHT . ' g sein.');
        }
        if ($quantityValue === null || $quantityValue < 1 || $quantityValue > self::MAX_QUANTITY) {
            return $this->failWith($yarn, 'Die Anzahl muss eine ganze Zahl zwischen 1 und ' . self::MAX_QUANTITY . ' sein.');
        }

        for ($i = 0; $i < $quantityValue; $i++) {
            $skein = new Skein();
            $skein->setYarn($yarn);
            $skein->setColor($color);
            $skein->setInitialWeight($weightValue);
            $skein->setCurrentWeight($weightValue);
            $this->skeinRepository->add($skein);
            // Also attach it to the yarn, so the counter column "skeins" of the yarn stays correct.
            $yarn->addSkein($skein);
        }

        $this->addFlashMessage(
            $quantityValue === 1
            ? sprintf('1 Knäuel (%s, %d g) wurde hinzugefügt.', $color, $weightValue)
            : sprintf('%d Knäuel (%s, je %d g) wurden hinzugefügt.', $quantityValue, $color, $weightValue)
        );

        return $this->redirectToYarnOf($yarn);
    }

    /**
     * Sets the remaining weight of a skein. 0 marks it as used up; a weight above 0 puts a used-up
     * skein back into stock (e.g. after frogging a project).
     *
     * Validation of the skein is ignored so that older records can still be updated.
     */
    #[IgnoreValidation(['argumentName' => 'skein'])]
    public function updateWeightAction(Skein $skein, string $weight = ''): ResponseInterface
    {
        $yarn = $skein->getYarn();
        $weightValue = $this->parseInt($weight);

        if ($weightValue === null || $weightValue < 0 || $weightValue > self::MAX_WEIGHT) {
            return $this->failWith($yarn, 'Das Restgewicht muss eine ganze Zahl zwischen 0 und ' . self::MAX_WEIGHT . ' g sein.');
        }

        $wasUsedUp = $skein->isUsedUp();
        $skein->setCurrentWeight($weightValue);
        $this->skeinRepository->update($skein);

        if ($weightValue === 0) {
            $this->addFlashMessage('Das Knäuel (' . $this->label($skein) . ') ist jetzt als verbraucht markiert.');
        } elseif ($wasUsedUp) {
            $this->addFlashMessage('Das Knäuel (' . $this->label($skein) . ') ist wieder im Vorrat.');
        } else {
            $this->addFlashMessage('Das Restgewicht wurde auf ' . $weightValue . ' g gesetzt.');
        }

        return $this->redirectToYarnOf($yarn);
    }

    /**
     * Shortcut for "weight 0": marks the skein as used up.
     */
    #[IgnoreValidation(['argumentName' => 'skein'])]
    public function useUpAction(Skein $skein): ResponseInterface
    {
        $skein->setCurrentWeight(0);
        $this->skeinRepository->update($skein);
        $this->addFlashMessage('Das Knäuel (' . $this->label($skein) . ') ist jetzt als verbraucht markiert.');

        return $this->redirectToYarnOf($skein->getYarn());
    }

    /**
     * Deletes a skein, meant for mistakes when creating one. Only reachable via POST.
     * The TCA has a "deleted" field, so the record is only marked as deleted and can be restored in the backend.
     */
    #[IgnoreValidation(['argumentName' => 'skein'])]
    public function deleteAction(Skein $skein): ResponseInterface
    {
        $yarn = $skein->getYarn();
        $label = $this->label($skein);
        $this->skeinRepository->remove($skein);
        $this->addFlashMessage('Das Knäuel (' . $label . ') wurde gelöscht.');

        return $this->redirectToYarnOf($yarn);
    }

    private function failWith(?Yarn $yarn, string $message): ResponseInterface
    {
        $this->addFlashMessage($message, '', ContextualFeedbackSeverity::ERROR);

        return $this->redirectToYarnOf($yarn);
    }

    /**
     * "schwarz, 100 g" for messages.
     */
    private function label(Skein $skein): string
    {
        $color = trim($skein->getColor());

        return ($color !== '' ? $color : 'ohne Farbe') . ', ' . $skein->getInitialWeight() . ' g';
    }

    /**
     * Whole numbers only; returns null for empty or invalid input ("", "5.5", "abc").
     */
    private function parseInt(string $value): ?int
    {
        $result = filter_var(trim($value), FILTER_VALIDATE_INT);

        return $result === false ? null : $result;
    }

    /**
     * Redirects to the detail page of the yarn, or to the list if the skein has no yarn.
     * The URL is relative (see YarnController::redirectToIndex() for why it is not an absolute one).
     */
    private function redirectToYarnOf(?Yarn $yarn): ResponseInterface
    {
        $uri = $yarn !== null
            ? $this->uriBuilder->reset()->uriFor('show', ['yarn' => $yarn], 'Yarn')
            : $this->uriBuilder->reset()->uriFor('index', [], 'Yarn');

        return new RedirectResponse($uri, 303);
    }
}
