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
use InspiredMinds\ContaoBayernPortal\Controller\FrontendModule\LebenslagenController;
use InspiredMinds\ContaoBayernPortal\Controller\FrontendModule\LeistungenController;

$GLOBALS['TL_LANG']['MOD']['bayernportal'] = ['BayernPortal', 'Manage settings for the BayernPortal integration.'];
$GLOBALS['TL_LANG']['MOD']['tl_bayernportal_config'] = 'BayernPortal Configuration';
$GLOBALS['TL_LANG']['FMD']['bayernportal'] = 'BayernPortal';
$GLOBALS['TL_LANG']['FMD'][BehoerdenController::TYPE] = ['BayernPortal: Behörden', 'List and Details of Behörden.'];
$GLOBALS['TL_LANG']['FMD'][LeistungenController::TYPE] = ['BayernPortal: Leistungen', 'List and Details of Leistungen.'];
$GLOBALS['TL_LANG']['FMD'][AnsprechpartnerController::TYPE] = ['BayernPortal: Ansprechpartner', 'List and Details of Ansprechpartner.'];
$GLOBALS['TL_LANG']['FMD'][LebenslagenController::TYPE] = ['BayernPortal: Lebenslagen', 'List and Details of Lebenslagen.'];
$GLOBALS['TL_LANG']['FMD'][DienststellenController::TYPE] = ['BayernPortal: Dienststellen', 'List and Details of Dienststellen.'];
