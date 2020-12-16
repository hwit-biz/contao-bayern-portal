<?php

declare(strict_types=1);

/*
 * This file is part of the Contao Bayern Portal extension.
 *
 * (c) inspiredminds
 *
 * @license LGPL-3.0-or-later
 */

use InspiredMinds\ContaoBayernPortal\Maintenance\InvalidateBayernPortalCache;
use InspiredMinds\ContaoBayernPortal\Model\BayernPortalConfigModel;

$GLOBALS['BE_MOD']['system']['bayernportal'] = ['tables' => ['tl_bayernportal_config']];
$GLOBALS['TL_MODELS']['tl_bayernportal_config'] = BayernPortalConfigModel::class;
$GLOBALS['TL_MAINTENANCE'][] = InvalidateBayernPortalCache::class;
