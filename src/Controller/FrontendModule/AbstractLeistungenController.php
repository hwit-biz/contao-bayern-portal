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

use InspiredMinds\ContaoBayernPortal\ApiEntity\LeistungEntity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class AbstractLeistungenController extends AbstractBayernPortalModuleController
{
    /**
     * @return array<string,string>
     */
    protected function getAlphabetFilter(array $data, Request $request, TranslatorInterface $translator = null): array
    {
        $list = [];

        foreach ($data as $entry) {
            if (empty($entry->bezeichnung)) {
                continue;
            }

            $c = strtoupper(mb_substr($entry->bezeichnung, 0, 1));

            if (isset($list[$c])) {
                continue;
            }

            $list[$c] = ltrim($request->getPathInfo(), '/').'?filter='.$c;
        }

        ksort($list);

        $allLabel = null !== $translator ? $translator->trans('all', [], 'ContaoBayernPortal') : 'all';
        $list[$allLabel] = ltrim($request->getPathInfo(), '/');

        return $list;
    }

    /**
     * @param array<LeistungEntity> $data
     *
     * @return array<LeistungEntity>
     */
    protected function getFilteredList(array $data, Request $request): array
    {
        if (!$request->query->has('filter')) {
            return $data;
        }

        $filter = $request->query->get('filter');

        if (empty($filter)) {
            return $data;
        }

        $GLOBALS['TL_HEAD'][] = '<link rel="canonical" href="'.ltrim($request->getPathInfo(), '/').'">';

        $filtered = [];

        foreach ($data as $entry) {
            if (empty($entry->bezeichnung)) {
                continue;
            }

            $c = strtoupper(mb_substr($entry->bezeichnung, 0, 1));

            if ($filter === $c) {
                $filtered[] = $entry;
            }
        }

        return $filtered;
    }
}
