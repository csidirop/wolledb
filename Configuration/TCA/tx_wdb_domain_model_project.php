<?php

declare(strict_types=1);

$ll = 'LLL:EXT:wdb/Resources/Private/Language/locallang.xlf:';

return [
    'ctrl' => [
        'title' => $ll . 'tx_wdb_domain_model_project.title',
        'label' => 'name',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'delete' => 'deleted',
        'default_sortby' => 'name',
        'iconfile' => 'EXT:wdb/Resources/Public/Icons/Yarn.svg',
        'searchFields' => 'name, kind, notes',
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
            'showitem' => 'name, status, kind, start_date, end_date, needle_size, pattern_url, notes, hidden',
        ],
    ],
    'columns' => [
        'hidden' => [
            'exclude' => true,
            'label' => $ll . 'tx_wdb_domain_model_project.hidden',
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
        'name' => [
            'label' => $ll . 'tx_wdb_domain_model_project.name',
            'config' => [
                'type' => 'input',
                'size' => 40,
                'max' => 255,
                'eval' => 'trim',
                'required' => true,
            ],
        ],
        'status' => [
            'label' => $ll . 'tx_wdb_domain_model_project.status',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['label' => $ll . 'tx_wdb_domain_model_project.status.planned', 'value' => 'planned'],
                    ['label' => $ll . 'tx_wdb_domain_model_project.status.in_progress', 'value' => 'in_progress'],
                    ['label' => $ll . 'tx_wdb_domain_model_project.status.finished', 'value' => 'finished'],
                ],
                'default' => 'planned',
            ],
        ],
        'kind' => [
            'label' => $ll . 'tx_wdb_domain_model_project.kind',
            'config' => [
                'type' => 'input',
                'size' => 40,
                'max' => 255,
                'eval' => 'trim',
            ],
        ],
        'start_date' => [
            'label' => $ll . 'tx_wdb_domain_model_project.start_date',
            'config' => [
                'type' => 'datetime',
                'format' => 'date',
                'dbType' => 'date',
                'nullable' => true,
                'default' => null,
            ],
        ],
        'end_date' => [
            'label' => $ll . 'tx_wdb_domain_model_project.end_date',
            'config' => [
                'type' => 'datetime',
                'format' => 'date',
                'dbType' => 'date',
                'nullable' => true,
                'default' => null,
            ],
        ],
        'needle_size' => [
            'label' => $ll . 'tx_wdb_domain_model_project.needle_size',
            'config' => [
                'type' => 'input',
                'size' => 40,
                'max' => 255,
                'eval' => 'trim',
            ],
        ],
        'pattern_url' => [
            'label' => $ll . 'tx_wdb_domain_model_project.pattern_url',
            'config' => [
                'type' => 'input',
                'size' => 40,
                'max' => 2048,
                'eval' => 'trim',
            ],
        ],
        'notes' => [
            'label' => $ll . 'tx_wdb_domain_model_project.notes',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 6,
            ],
        ],
    ],
];
