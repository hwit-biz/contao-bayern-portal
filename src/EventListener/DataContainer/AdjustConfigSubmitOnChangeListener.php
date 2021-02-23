<?php

declare(strict_types=1);

/*
 * This file is part of the Contao Bayern Portal extension.
 *
 * (c) inspiredminds
 *
 * @license LGPL-3.0-or-later
 */

namespace InspiredMinds\ContaoBayernPortal\EventListener\DataContainer;

use Contao\CoreBundle\ServiceAnnotation\Callback;
use Contao\DataContainer;
use Contao\ModuleModel;
use InspiredMinds\ContaoBayernPortal\Controller\FrontendModule\DienststellenLeistungenController;

/**
 * Adjusts the DCA for certain modules, so that bayernportal_config will submit on change.
 *
 * @Callback(table="tl_module", target="config.onload")
 */
class AdjustConfigSubmitOnChangeListener
{
    private static $modules = [
        DienststellenLeistungenController::TYPE,
    ];

    public function __invoke(DataContainer $dc): void
    {
        $module = ModuleModel::findById($dc->id);

        if (null === $module || !\in_array($module->type, self::$modules, true)) {
            return;
        }

        $GLOBALS['TL_DCA']['tl_module']['fields']['bayernportal_config']['eval']['submitOnChange'] = true;
    }
}
