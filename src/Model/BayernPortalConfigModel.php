<?php

declare(strict_types=1);

/*
 * This file is part of the Contao Bayern Portal extension.
 *
 * (c) inspiredminds
 *
 * @license LGPL-3.0-or-later
 */

namespace InspiredMinds\ContaoBayernPortal\Model;

use Contao\Model;

/**
 * @property int    $id
 * @property int    $tstamp
 * @property string $username
 * @property string $password
 * @property string $municipality_code
 */
class BayernPortalConfigModel extends Model
{
    protected static $strTable = 'tl_bayernportal_config';
}
