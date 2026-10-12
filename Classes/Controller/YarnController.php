<?php

declare(strict_types=1);

namespace SBW\WDB\Controller;

use Psr\Http\Message\ResponseInterface;
use SBW\WDB\Domain\Model\Yarn;
use SBW\WDB\Domain\Repository\YarnRepository;
use TYPO3\CMS\Core\Http\PropagateResponseException;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Frontend\Controller\ErrorController;

/**
 * Controller for the main "Yarn" FE plugin.
 */
class YarnController extends ActionController
{
    public function __construct(
        private readonly YarnRepository $yarnRepository,
        private readonly ErrorController $errorController,
    ) {}

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
            $values[] = (string)($item['value'] ?? $item[1] ?? '');
        }

        return array_values(array_filter($values, static fn (string $value): bool => $value !== ''));
    }

}
