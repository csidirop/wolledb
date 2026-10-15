<?php

declare(strict_types=1);

namespace SBW\WDB\Controller;

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
}
