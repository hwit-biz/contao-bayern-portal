<?php

declare(strict_types=1);

/*
 * This file is part of the Contao Bayern Portal extension.
 *
 * (c) inspiredminds
 *
 * @license LGPL-3.0-or-later
 */

use Contao\DC_Table;
use Contao\DataContainer;

$GLOBALS['TL_DCA']['tl_bayernportal_config'] = [
    'config' => [
        'dataContainer' => DC_Table::class,
        'enableVersioning' => true,
        'markAsCopy' => 'name',
        'sql' => [
            'keys' => [
                'id' => 'primary',
            ],
        ],
    ],
    'list' => [
        'sorting' => [
            'mode' => DataContainer::MODE_SORTED,
            'flag' => DataContainer::SORT_INITIAL_LETTER_ASC,
            'panelLayout' => 'search,limit',
            'fields' => ['name'],
        ],
        'label' => [
            'fields' => ['name'],
            'format' => '%s',
        ],
        'operations' => [
            'edit' => [
                'href' => 'act=edit',
                'icon' => 'edit.svg',
            ],
            'copy' => [
                'href' => 'act=copy',
                'icon' => 'copy.svg',
            ],
            'delete' => [
                'href' => 'act=delete',
                'icon' => 'delete.svg',
            ],
            'show' => [
                'href' => 'act=show',
                'icon' => 'show.svg',
            ],
        ],
    ],
    'fields' => [
        'id' => [
            'sql' => ['type' => 'integer', 'unsigned' => true, 'autoincrement' => true],
        ],
        'tstamp' => [
            'sql' => ['type' => 'integer', 'unsigned' => true, 'default' => 0],
        ],
        'name' => [
            'exclude' => true,
            'search' => true,
            'inputType' => 'text',
            'eval' => ['tl_class' => 'w50', 'maxlength' => 255, 'mandatory' => true],
            'sql' => ['type' => 'string', 'length' => 255, 'default' => ''],
        ],
        'username' => [
            'exclude' => true,
            'search' => true,
            'inputType' => 'text',
            'eval' => ['tl_class' => 'clr w50', 'maxlength' => 255, 'mandatory' => true, 'decodeEntities' => true],
            'sql' => ['type' => 'string', 'length' => 255, 'default' => ''],
        ],
        'password' => [
            'exclude' => true,
            'inputType' => 'password',
            'eval' => ['tl_class' => 'w50', 'maxlength' => 255, 'mandatory' => true, 'doNotShow' => true, 'decodeEntities' => true],
            'sql' => ['type' => 'string', 'length' => 255, 'default' => ''],
        ],
        'municipality_code' => [
            'exclude' => true,
            'inputType' => 'text',
            'eval' => ['tl_class' => 'w50', 'maxlength' => 255],
            'sql' => ['type' => 'string', 'length' => 255, 'default' => ''],
        ],
    ],
    'palettes' => [
        'default' => '{config_legend},name,username,password,municipality_code',
    ],
];
