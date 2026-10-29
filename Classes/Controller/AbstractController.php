<?php

declare(strict_types=1);

namespace SBW\WDB\Controller;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Http\RedirectResponse;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 * Base class of the plugin's controllers, with a shortcut for translated texts (e.g. flash messages).
 */
abstract class AbstractController extends ActionController
{
    /**
     * Translated text from this extension's locallang.xlf. Placeholders (%s, %d, %1$s ...) are filled
     * with the arguments. Falls back to the key if there is no translation.
     *
     * @param list<string|int> $arguments
     */
    protected function translate(string $key, array $arguments = []): string
    {
        return LocalizationUtility::translate($key, 'Wdb', $arguments === [] ? null : $arguments) ?? $key;
    }

    /**
     * Redirects to another action (303) with a relative URL.
     *
     * $this->redirect() would build an absolute URL with the scheme, host and port TYPO3 detects,
     * which can be wrong behind a reverse proxy (e.g. ddev). The browser adds its own scheme, host
     * and port to a relative URL, so this always fits.
     *
     * @param array<string, mixed> $arguments
     */
    protected function redirectToAction(string $action, array $arguments = [], ?string $controller = null): ResponseInterface
    {
        $uri = $this->uriBuilder->reset()->uriFor($action, $arguments, $controller);

        return new RedirectResponse($uri, 303);
    }
}
