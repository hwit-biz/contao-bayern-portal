<?php

declare(strict_types=1);

/*
 * This file is part of the Contao Bayern Portal extension.
 *
 * (c) inspiredminds
 *
 * @license LGPL-3.0-or-later
 */

namespace InspiredMinds\ContaoBayernPortal\Controller\FrontendModule;

use Contao\CoreBundle\Controller\FrontendModule\AbstractFrontendModuleController;

abstract class AbstractBayernPortalModuleController extends AbstractFrontendModuleController
{
    /**
     * @param array<object> $data
     */
    protected function sortData(array $data, string $property = 'bezeichnung', string $type = 'alphabetically'): array
    {
        usort($data, static function ($a, $b) use ($property, $type): int {
            if ('numerical' === $type) {
                return (int) $a->{$property} - (int) $b->{$property};
            }

            return strcmp($a->{$property}, $b->{$property});
        });

        return $data;
    }
}
