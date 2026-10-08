<?php

declare(strict_types=1);

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') or die();

ExtensionUtility::registerPlugin(
    'Wdb',
    'YarnIndex',
    'LLL:EXT:wdb/Resources/Private/Language/locallang.xlf:plugin.yarnindex.title',
    'EXT:wdb/Resources/Public/Icons/Yarn.svg',
    'plugins',
    'LLL:EXT:wdb/Resources/Private/Language/locallang.xlf:plugin.yarnindex.description',
);
