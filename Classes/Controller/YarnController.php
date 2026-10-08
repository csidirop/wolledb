<?php

declare(strict_types=1);

namespace SBW\WDB\Controller;

use Psr\Http\Message\ResponseInterface;
use SBW\WDB\Domain\Model\Yarn;
use SBW\WDB\Domain\Repository\YarnRepository;
// use TYPO3\CMS\Core\Http\PropagateResponseException;
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

    public function indexAction(): ResponseInterface
    {
        $this->view->assign('message', 'Hello world!');
        $this->view->assign('yarns', $this->yarnRepository->findAll());
        return $this->htmlResponse();
    }

}