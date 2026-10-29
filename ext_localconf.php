<?php

declare(strict_types=1);

use SBW\WDB\Controller\YarnController;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use SBW\WDB\Controller\SkeinController;

defined('TYPO3') or die('Access denied.');

// This makes the plugin available for front-end rendering.
ExtensionUtility::configurePlugin(
    // extension name, matching the PHP namespaces (but without the vendor)
    'Wdb',
    // arbitrary, but unique plugin name (not visible in the BE)
    'YarnIndex',
    // all actions
    [
        YarnController::class => 'index, show, new, create, edit, update, delete',
        SkeinController::class => 'create, updateWeight, useUp, delete',
    ],
    // The list reacts to filter/sort parameters, so it must not be cached with the page.
    [
        YarnController::class => 'index, show, new, create, edit, update, delete',
        SkeinController::class => 'create, updateWeight, useUp, delete',
    ]

);

ExtensionUtility::configurePlugin(
    'Wdb',
    'ProjectIndex',
    // all actions
    [
        ProjectController::class => 'index, show, new, create, edit, update, delete',
    ],
    // none of them is cached: forms handle input and a cached detail page would show old data after an edit
    [
        ProjectController::class => 'index, show, new, create, edit, update, delete',
    ]
);
