<?php

declare(strict_types=1);

use SBW\WDB\Controller\YarnController;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') or die('Access denied.');

// This makes the plugin available for front-end rendering.
ExtensionUtility::configurePlugin(
    // extension name, matching the PHP namespaces (but without the vendor)
    'Wdb',
    // arbitrary, but unique plugin name (not visible in the BE)
    'YarnIndex',
    // all actions
    [
        YarnController::class => 'index, show',
    ],
    // The list reacts to filter/sort parameters, so it must not be cached with the page.
    [
        YarnController::class => 'index',
    ]

);
