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

$GLOBALS['TL_LANG']['MOD']['bayernportal'] = ['BayernPortal', 'Einstellungen für die Integration des BayernPortals verwalten.'];
$GLOBALS['TL_LANG']['MOD']['tl_bayernportal_config'] = 'BayernPortal Konfiguration';
$GLOBALS['TL_LANG']['FMD']['bayernportal'] = 'BayernPortal';
$GLOBALS['TL_LANG']['FMD'][BehoerdenController::TYPE] = ['BayernPortal: Behörden', 'Liste und Details von Behörden.'];
$GLOBALS['TL_LANG']['FMD'][LeistungenController::TYPE] = ['BayernPortal: Leistungen', 'Liste und Details von Leistungen.'];
$GLOBALS['TL_LANG']['FMD'][AnsprechpartnerController::TYPE] = ['BayernPortal: Ansprechpartner', 'Liste und Details von Ansprechpartnern.'];
$GLOBALS['TL_LANG']['FMD'][LebenslagenController::TYPE] = ['BayernPortal: Lebenslagen', 'Liste und Details von Lebenslagen.'];
$GLOBALS['TL_LANG']['FMD'][DienststellenController::TYPE] = ['BayernPortal: Dienststellen', 'Liste und Details von Dienststellen.'];
$GLOBALS['TL_LANG']['FMD'][DienststellenLeistungenController::TYPE] = ['BayernPortal: Dienststelle Leistungen', 'Liste und Details von Dienststellenleistungen.'];
