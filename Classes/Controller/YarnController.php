<?php

declare(strict_types=1);

namespace SBW\WDB\Controller;

use Psr\Http\Message\ResponseInterface;
use SBW\WDB\Domain\Model\Yarn;
use SBW\WDB\Domain\Repository\YarnRepository;
use TYPO3\CMS\Core\Http\PropagateResponseException;
use TYPO3\CMS\Extbase\Annotation\IgnoreValidation;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\CMS\Frontend\Controller\ErrorController;

/**
 * Controller for the main "Yarn" FE plugin.
 */
class YarnController extends AbstractController
{
    public function __construct(
        private readonly YarnRepository $yarnRepository,
        private readonly ErrorController $errorController,
    ) {
    }

    public function indexAction(
        string $mainfiber = '',
        string $yarnWeight = '',
        string $sortBy = 'name',
        string $sortDirection = 'asc',
    ): ResponseInterface {
        $this->view->assignMultiple([
            'yarns' => $this->yarnRepository->findFiltered($mainfiber, $yarnWeight, $sortBy, $sortDirection),
            // Options for the filter dropdowns, taken from the TCA so they always match the backend form.
            'mainfibers' => $this->getSelectValues('mainfiber'),
            'yarnWeights' => $this->getSelectValues('yarn_weight'),
            // Current state, so the form and the sort links can keep it.
            'filter' => [
                'mainfiber' => $mainfiber,
                'yarnWeight' => $yarnWeight,
                'sortBy' => $sortBy,
                'sortDirection' => $sortDirection,
            ],
            // A click on an already ascending column sorts it descending, otherwise ascending.
            'nextDirection' => [
                'hookSize' => ($sortBy === 'hookSize' && $sortDirection === 'asc') ? 'desc' : 'asc',
                'needleSize' => ($sortBy === 'needleSize' && $sortDirection === 'asc') ? 'desc' : 'asc',
            ],
        ]);
        return $this->htmlResponse();
    }

    /**
     * @throws PropagateResponseException
     */
    public function showAction(?Yarn $yarn): ResponseInterface
    {
        $this->view->assign('yarn', $yarn);
        return $this->htmlResponse();
    }

    /**
     * Shows the form for a new yarn.
     *
     * The yarn argument is only filled when the form is shown again after a failed validation,
     * so the already entered values are kept. Validation must be ignored here, otherwise showing
     * the form again would trigger the validation of the incomplete object once more.
     */
    #[IgnoreValidation(['argumentName' => 'yarn'])]
    public function newAction(?Yarn $yarn = null): ResponseInterface
    {
        $this->view->assignMultiple([
            'yarn' => $yarn,
            'mainfibers' => $this->getSelectOptions('mainfiber'),
            'yarnWeights' => $this->getSelectOptions('yarn_weight'),
        ]);
        return $this->htmlResponse();
    }

    /**
     * Saves a new yarn. The model's validators run before this method is called.
     * On errors, Extbase shows the "new" form again.
     */
    public function createAction(Yarn $yarn): ResponseInterface
    {
        // The record is stored on the page set in plugin.tx_wdb.persistence.storagePid.
        $this->yarnRepository->add($yarn);
        $this->addFlashMessage($this->translate('frontend.flash.yarn.created', [$yarn->getName()]));

        return $this->redirectToAction('index');
    }

    /**
     * Shows the form to edit an existing yarn.
     *
     * Validation is ignored so the form also opens for records that don't meet the current rules,
     * and so it can be shown again after a failed update.
     */
    #[IgnoreValidation(['argumentName' => 'yarn'])]
    public function editAction(Yarn $yarn): ResponseInterface
    {
        $this->view->assignMultiple([
            'yarn' => $yarn,
            'mainfibers' => $this->getSelectOptions('mainfiber'),
            'yarnWeights' => $this->getSelectOptions('yarn_weight'),
        ]);
        return $this->htmlResponse();
    }

    /**
     * Saves the changes. The model's validators run before this method is called;
     * on errors Extbase shows the "edit" form again.
     */
    public function updateAction(Yarn $yarn): ResponseInterface
    {
        $this->yarnRepository->update($yarn);
        $this->addFlashMessage($this->translate('frontend.flash.yarn.updated', [$yarn->getName()]));

        return $this->redirectToAction('index');
    }

    /**
     * Deletes a yarn. Only reachable via POST (see the form in the list), never via a plain link.
     *
     * Validation is ignored on purpose: an existing record that does not meet today's validation rules
     * (e.g. imported data) must still be deletable.
     */
    #[IgnoreValidation(['argumentName' => 'yarn'])]
    public function deleteAction(Yarn $yarn): ResponseInterface
    {
        $name = $yarn->getName();
        // The TCA has a "deleted" field, so this only marks the record as deleted (it can be restored in the backend).
        $this->yarnRepository->remove($yarn);
        $this->addFlashMessage($this->translate('frontend.flash.yarn.deleted', [$name]));

        return $this->redirectToAction('index');
    }


    /**
     * Reads the allowed values of a select field from the TCA.
     *
     * @return list<string>
     */
    private function getSelectValues(string $field): array
    {
        $items = $GLOBALS['TCA']['tx_wdb_domain_model_yarn']['columns'][$field]['config']['items'] ?? [];
        $values = [];
        foreach ($items as $item) {
            // Newer TYPO3 versions use ['label' => ..., 'value' => ...], older ones [label, value].
            $values[] = (string) ($item['value'] ?? $item[1] ?? '');
        }

        return array_values(array_filter($values, static fn(string $value): bool => $value !== ''));
    }

    /**
     * Select options as value => translated label, e.g. ['wool' => 'Wolle'].
     *
     * @return array<string, string>
     */
    private function getSelectOptions(string $field): array
    {
        $options = [];
        foreach ($this->getSelectValues($field) as $value) {
            $options[$value] = LocalizationUtility::translate(
                'tx_wdb_domain_model_yarn.' . $field . '.' . $value,
                'Wdb'
            ) ?? $value;
        }

        return $options;
    }

}
