<?php

declare(strict_types=1);

return [
    'ctrl' => [
        'title' => 'LLL:EXT:wdb/Resources/Private/Language/locallang.xlf:tx_wdb_domain_model_skein.title',
        'label' => 'color',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'delete' => 'deleted',
        'default_sortby' => 'color',
        'iconfile' => 'EXT:wdb/Resources/Public/Icons/Yarn.svg',
        'searchFields' => 'color',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'languageField' => 'sys_language_uid',
        'translationSource' => 'l10n_source',
        'versioningWS' => true,
    ],
    'types' => [
        '0' => [
            'showitem' => 'yarn, color, initial_weight, current_weight, hidden',
        ],
    ],
    'columns' => [
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:wdb/Resources/Private/Language/locallang.xlf:tx_wdb_domain_model_skein.hidden',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        'label' => '',
                        'invertStateDisplay' => true,
                    ],
                ],
            ],
        ],
        'yarn' => [
            'label' => 'LLL:EXT:wdb/Resources/Private/Language/locallang.xlf:tx_wdb_domain_model_skein.yarn',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_wdb_domain_model_yarn',
                'items' => [
                    ['label' => '', 'value' => 0],
                ],
                'default' => 0,
                'required' => true,
            ],
        ],
        'color' => [
            'label' => 'LLL:EXT:wdb/Resources/Private/Language/locallang.xlf:tx_wdb_domain_model_skein.color',
            'config' => [
                'type' => 'input',
                'size' => 40,
                'max' => 255,
                'eval' => 'trim',
            ],
        ],
        'initial_weight' => [
            'label' => 'LLL:EXT:wdb/Resources/Private/Language/locallang.xlf:tx_wdb_domain_model_skein.initial_weight',
            'config' => [
                'type' => 'number',
                'format' => 'integer',
                'size' => 10,
                'range' => [
                    'lower' => 0,
                ],
                'default' => 0,
            ],
        ],
        'current_weight' => [
            'label' => 'LLL:EXT:wdb/Resources/Private/Language/locallang.xlf:tx_wdb_domain_model_skein.current_weight',
            'config' => [
                'type' => 'number',
                'format' => 'integer',
                'size' => 10,
                'range' => [
                    'lower' => 0,
                ],
                'default' => 0,
            ],
        ],
    ],
];
