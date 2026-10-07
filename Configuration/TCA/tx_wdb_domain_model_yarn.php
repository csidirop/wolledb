<?php

declare(strict_types=1);

return [
    'ctrl' => [
        'title' => 'LLL:EXT:wdb/Resources/Private/Language/locallang.xlf:tx_wdb_domain_model_yarn.title',
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
            'label' => 'LLL:EXT:wdb/Resources/Private/Language/locallang.xlf:tx_wdb_domain_model_yarn.hook_size',
            'config' => [
                'type' => 'input',
                'size' => 40,
                'max' => 255,
                'eval' => 'trim',
            ],
        ],
        'needle_size' => [
            'label' => 'LLL:EXT:wdb/Resources/Private/Language/locallang.xlf:tx_wdb_domain_model_yarn.needle_size',
            'config' => [
                'type' => 'input',
                'size' => 40,
                'max' => 255,
                'eval' => 'trim',
            ],
        ],
        'yardage'=> [
            'label' => 'LLL:EXT:wdb/Resources/Private/Language/locallang.xlf:tx_wdb_domain_model_yarn.yardage',
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
            'label' => 'LLL:EXT:wdb/Resources/Private/Language/locallang.xlf:tx_wdb_domain_model_yarn.mainfiber',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['LLL:EXT:wdb/Resources/Private/Language/locallang.xlf:tx_wdb_domain_model_yarn.mainfiber.wool', 'wool'],
                    ['LLL:EXT:wdb/Resources/Private/Language/locallang.xlf:tx_wdb_domain_model_yarn.mainfiber.cotton', 'cotton'],
                    ['LLL:EXT:wdb/Resources/Private/Language/locallang.xlf:tx_wdb_domain_model_yarn.mainfiber.acrylic', 'acrylic'],
                    ['LLL:EXT:wdb/Resources/Private/Language/locallang.xlf:tx_wdb_domain_model_yarn.mainfiber.silk', 'silk'],
                    ['LLL:EXT:wdb/Resources/Private/Language/locallang.xlf:tx_wdb_domain_model_yarn.mainfiber.linen', 'linen'],
                    ['LLL:EXT:wdb/Resources/Private/Language/locallang.xlf:tx_wdb_domain_model_yarn.mainfiber.alpaca', 'alpaca'],
                    ['LLL:EXT:wdb/Resources/Private/Language/locallang.xlf:tx_wdb_domain_model_yarn.mainfiber.cashmere', 'cashmere'],
                    ['LLL:EXT:wdb/Resources/Private/Language/locallang.xlf:tx_wdb_domain_model_yarn.mainfiber.bamboo', 'bamboo'],
                    ['LLL:EXT:wdb/Resources/Private/Language/locallang.xlf:tx_wdb_domain_model_yarn.mainfiber.hemp', 'hemp'],
                    ['LLL:EXT:wdb/Resources/Private/Language/locallang.xlf:tx_wdb_domain_model_yarn.mainfiber.nylon', 'nylon'],
                    ['LLL:EXT:wdb/Resources/Private/Language/locallang.xlf:tx_wdb_domain_model_yarn.mainfiber.polyester', 'polyester'],
                    ['LLL:EXT:wdb/Resources/Private/Language/locallang.xlf:tx_wdb_domain_model_yarn.mainfiber.other', 'other'],
                ],
                'default' => 'other',
                'required' => true,
            ],
        ],
        'fibercomposition' => [
            'label' => 'LLL:EXT:wdb/Resources/Private/Language/locallang.xlf:tx_wdb_domain_model_yarn.fibercomposition',
            'config' => [
                'type' => 'input',
                'size' => 40,
                'max' => 255,
                'eval' => 'trim',
            ],
        ],
        'yarn_weight' => [
            'label' => 'LLL:EXT:wdb/Resources/Private/Language/locallang.xlf:tx_wdb_domain_model_yarn.yarn_weight',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['LLL:EXT:wdb/Resources/Private/Language/locallang.xlf:tx_wdb_domain_model_yarn.yarn_weight.lace', 'lace'],
                    ['LLL:EXT:wdb/Resources/Private/Language/locallang.xlf:tx_wdb_domain_model_yarn.yarn_weight.fingering', 'fingering'],
                    ['LLL:EXT:wdb/Resources/Private/Language/locallang.xlf:tx_wdb_domain_model_yarn.yarn_weight.sport', 'sport'],
                    ['LLL:EXT:wdb/Resources/Private/Language/locallang.xlf:tx_wdb_domain_model_yarn.yarn_weight.dk', 'dk'],
                    ['LLL:EXT:wdb/Resources/Private/Language/locallang.xlf:tx_wdb_domain_model_yarn.yarn_weight.aran', 'aran'],
                    ['LLL:EXT:wdb/Resources/Private/Language/locallang.xlf:tx_wdb_domain_model_yarn.yarn_weight.bulky', 'bulky'],
                    ['LLL:EXT:wdb/Resources/Private/Language/locallang.xlf:tx_wdb_domain_model_yarn.yarn_weight.super_bulky', 'super_bulky'],
                    ['LLL:EXT:wdb/Resources/Private/Language/locallang.xlf:tx_wdb_domain_model_yarn.yarn_weight.jumbo', 'jumbo'],
                ],
            ],
        ],
        'source' => [
            'label' => 'LLL:EXT:wdb/Resources/Private/Language/locallang.xlf:tx_wdb_domain_model_yarn.source',
            'config' => [
                'type' => 'input',
                'size' => 40,
                'max' => 255,
                'eval' => 'trim',
            ],
        ],
    ],
];
