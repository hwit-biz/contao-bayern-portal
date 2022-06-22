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
        $transliterator = self::getTransliterator();

        usort($data, static function ($a, $b) use ($property, $type, $transliterator): int {
            if ('numerical' === $type) {
                return (int) $a->{$property} - (int) $b->{$property};
            }

            return strnatcasecmp($transliterator->transliterate($a->{$property}), $transliterator->transliterate($b->{$property}));
        });

        return $data;
    }

    protected static function getTransliterator(): \Transliterator
    {
        return \Transliterator::createFromRules(':: Any-Latin; :: Latin-ASCII; :: NFD; :: [:Nonspacing Mark:] Remove; :: Upper(); :: NFC;');
    }
}
