<?php

declare(strict_types=1);

/*
 * This file is part of the Contao Bayern Portal extension.
 *
 * (c) inspiredminds
 *
 * @license LGPL-3.0-or-later
 */

use InspiredMinds\ContaoBayernPortal\Controller\FrontendModule\AnsprechpartnerController;
use InspiredMinds\ContaoBayernPortal\Controller\FrontendModule\BehoerdenController;
use InspiredMinds\ContaoBayernPortal\Controller\FrontendModule\DienststellenController;
use InspiredMinds\ContaoBayernPortal\Controller\FrontendModule\DienststellenLeistungenController;
use InspiredMinds\ContaoBayernPortal\Controller\FrontendModule\LebenslagenController;
use InspiredMinds\ContaoBayernPortal\Controller\FrontendModule\LeistungenController;

$GLOBALS['TL_DCA']['tl_module']['fields']['bayernportal_config'] = [
    'exclude' => true,
    'inputType' => 'select',
    'foreignKey' => 'tl_bayernportal_config.name',
    'eval' => ['tl_class' => 'w50', 'includeBlankOption' => true, 'mandatory' => true],
    'sql' => ['type' => 'integer', 'unsigned' => true, 'default' => 0],
];

$GLOBALS['TL_DCA']['tl_module']['fields']['bayernportal_list_template'] = [
    'exclude' => true,
    'inputType' => 'select',
    'eval' => ['chosen' => true, 'tl_class' => 'w50'],
    'sql' => ['type' => 'string', 'length' => 96, 'default' => ''],
];

$GLOBALS['TL_DCA']['tl_module']['fields']['bayernportal_detail_template'] = [
    'exclude' => true,
    'inputType' => 'select',
    'eval' => ['chosen' => true, 'tl_class' => 'w50'],
    'sql' => ['type' => 'string', 'length' => 96, 'default' => ''],
];

$GLOBALS['TL_DCA']['tl_module']['fields']['bayernportal_behoerden_page'] = [
    'exclude' => true,
    'inputType' => 'pageTree',
    'foreignKey' => 'tl_page.title',
    'eval' => ['fieldType' => 'radio'],
    'sql' => ['type' => 'integer', 'unsigned' => true, 'default' => 0],
    'relation' => ['type' => 'hasOne', 'load' => 'lazy'],
];

$GLOBALS['TL_DCA']['tl_module']['fields']['bayernportal_leistungen_page'] = [
    'exclude' => true,
    'inputType' => 'pageTree',
    'foreignKey' => 'tl_page.title',
    'eval' => ['fieldType' => 'radio'],
    'sql' => ['type' => 'integer', 'unsigned' => true, 'default' => 0],
    'relation' => ['type' => 'hasOne', 'load' => 'lazy'],
];

$GLOBALS['TL_DCA']['tl_module']['fields']['bayernportal_lebenslagen_page'] = [
    'exclude' => true,
    'inputType' => 'pageTree',
    'foreignKey' => 'tl_page.title',
    'eval' => ['fieldType' => 'radio'],
    'sql' => ['type' => 'integer', 'unsigned' => true, 'default' => 0],
    'relation' => ['type' => 'hasOne', 'load' => 'lazy'],
];

$GLOBALS['TL_DCA']['tl_module']['fields']['bayernportal_dienststelle'] = [
    'exclude' => true,
    'inputType' => 'select',
    'eval' => ['chosen' => 'true', 'tl_class' => 'w50', 'mandatory' => true, 'includeBlankOption' => true],
    'sql' => ['type' => 'string', 'length' => 255, 'default' => ''],
];

$GLOBALS['TL_DCA']['tl_module']['palettes'][BehoerdenController::TYPE] =
    '{title_legend},name,headline,type;{config_legend},bayernportal_config;{redirect_legend},bayernportal_leistungen_page;{template_legend:hide},bayernportal_list_template,bayernportal_detail_template,customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID'
;

$GLOBALS['TL_DCA']['tl_module']['palettes'][LeistungenController::TYPE] =
    '{title_legend},name,headline,type;{config_legend},bayernportal_config;{redirect_legend},bayernportal_lebenslagen_page;{template_legend:hide},bayernportal_list_template,bayernportal_detail_template,customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID'
;

$GLOBALS['TL_DCA']['tl_module']['palettes'][AnsprechpartnerController::TYPE] =
    '{title_legend},name,headline,type;{config_legend},bayernportal_config;{redirect_legend},bayernportal_behoerden_page;{template_legend:hide},bayernportal_list_template,bayernportal_detail_template,customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID'
;

$GLOBALS['TL_DCA']['tl_module']['palettes'][LebenslagenController::TYPE] =
    '{title_legend},name,headline,type;{config_legend},bayernportal_config;{redirect_legend},bayernportal_leistungen_page;{template_legend:hide},bayernportal_list_template,bayernportal_detail_template,customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID'
;

$GLOBALS['TL_DCA']['tl_module']['palettes'][DienststellenController::TYPE] =
    '{title_legend},name,headline,type;{config_legend},bayernportal_config;{redirect_legend},bayernportal_leistungen_page,bayernportal_lebenslagen_page;{template_legend:hide},bayernportal_list_template,bayernportal_detail_template,customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID'
;

$GLOBALS['TL_DCA']['tl_module']['palettes'][DienststellenLeistungenController::TYPE] =
    '{title_legend},name,headline,type;{config_legend},bayernportal_config,bayernportal_dienststelle;{redirect_legend},bayernportal_leistungen_page;{template_legend:hide},bayernportal_list_template,bayernportal_detail_template,customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID'
;
