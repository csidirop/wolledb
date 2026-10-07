<?php

declare(strict_types=1);

return [
    'ctrl' => [
        'title' => 'Yarn',
        'label' => 'name',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'delete' => 'deleted',
        'default_sortby' => 'name',
        'iconfile' => 'EXT:wdb/Resources/Public/Icons/Extension.svg',
        'searchFields' => 'name, mainfiber, yarn_weight',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'transOrigPointerField' => 'l18n_parent',
        'transOrigDiffSourceField' => 'l18n_diffsource',
        'languageField' => 'sys_language_uid',
        'translationSource' => 'l10n_source',
        'versioningWS' => true,
    ],
    'types' => [
        '0' => [
            'showitem' => 'name, mainfiber, fibercomposition, yarn_weight, yardage, hook_size, needle_size, source, hidden',
        ],
    ],
    'columns' => [
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:wdb/Resources/Private/Language/locallang.xlf:tx_wdb_domain_model_yarn.hidden',
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
            'label' => 'LLL:EXT:wdb/Resources/Private/Language/locallang.xlf:tx_wdb_domain_model_yarn.name',
            'config' => [
                'type' => 'input',
                'size' => 40,
                'max' => 255,
                'eval' => 'trim',
                'required' => true,
            ],
        ],
        'hook_size' => [
            'label' => 'Hook Size',
            'config' => [
                'type' => 'input',
                'size' => 40,
                'max' => 255,
                'eval' => 'trim',
            ],
        ],
        'needle_size' => [
            'label' => 'Needle Size',
            'config' => [
                'type' => 'input',
                'size' => 40,
                'max' => 255,
                'eval' => 'trim',
            ],
        ],
        'yardage'=> [
            'label' => 'Yardage (m per 100g)',
            'config' => [
                'type' => 'number',
                'format' => 'integer',
                'size' => 10,
                'range' => [
                    'lower' => 100,
                    'upper' => 800,
                ],
                'slider' => [
                    'step' => 5,
                ],
                'default' => 0,
            ],
        ],
        'mainfiber' => [
            'label' => 'Main Fiber',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['Wool', 'wool'],
                    ['Cotton', 'cotton'],
                    ['Acrylic', 'acrylic'],
                    ['Silk', 'silk'],
                    ['Linen', 'linen'],
                    ['Alpaca', 'alpaca'],
                    ['Cashmere', 'cashmere'],
                    ['Bamboo', 'bamboo'],
                    ['Hemp', 'hemp'],
                    ['Nylon', 'nylon'],
                    ['Polyester', 'polyester'],
                    ['Other', 'other'],
                ],
                'default' => 'other',
                'required' => true,
            ],
        ],
        'fibercomposition' => [
            'label' => 'Fiber Composition',
            'config' => [
                'type' => 'input',
                'size' => 40,
                'max' => 255,
                'eval' => 'trim',
            ],
        ],
        'yarn_weight' => [
            'label' => 'Yarn Weight',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['Lace', 'lace'],
                    ['Fingering', 'fingering'],
                    ['Sport', 'sport'],
                    ['DK', 'dk'],
                    ['Aran', 'aran'],
                    ['Bulky', 'bulky'],
                    ['Super Bulky', 'super_bulky'],
                    ['Jumbo', 'jumbo'],
                ],
            ],
        ],
        'source' => [
            'label' => 'Source',
            'config' => [
                'type' => 'input',
                'size' => 40,
                'max' => 255,
                'eval' => 'trim',
            ],
        ],
    ],
];
