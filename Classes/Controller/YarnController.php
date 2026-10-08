<?php

declare(strict_types=1);

namespace SBW\WDB\Controller;

use Psr\Http\Message\ResponseInterface;
// use TTN\Tea\Domain\Model\Tea;
// use TTN\Tea\Domain\Repository\TeaRepository;
// use TYPO3\CMS\Core\Http\PropagateResponseException;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Frontend\Controller\ErrorController;

/**
 * Controller for the main "Yarn" FE plugin.
 */
class YarnController extends ActionController
{
    public function indexAction(): ResponseInterface
    {
        // $this->view->assign('yarns', $this->yarnRepository->findAll());
        $this->view->assign('message', 'Hello world!');
        return $this->htmlResponse();
    }

}