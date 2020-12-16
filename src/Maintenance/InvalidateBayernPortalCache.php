<?php

declare(strict_types=1);

/*
 * This file is part of the Contao Bayern Portal extension.
 *
 * (c) inspiredminds
 *
 * @license LGPL-3.0-or-later
 */

namespace InspiredMinds\ContaoBayernPortal\Maintenance;

use Contao\BackendTemplate;
use Contao\CheckBox;
use Contao\Input;
use Contao\System;
use Contao\Widget;
use FOS\HttpCacheBundle\CacheManager;
use InspiredMinds\ContaoBayernPortal\ApiEntity\AnsprechpartnerEntity;
use InspiredMinds\ContaoBayernPortal\ApiEntity\BehoerdeEntity;
use InspiredMinds\ContaoBayernPortal\ApiEntity\GebaeudeEntity;
use InspiredMinds\ContaoBayernPortal\ApiEntity\LebenslageEntity;
use InspiredMinds\ContaoBayernPortal\ApiEntity\LeistungEntity;
use Symfony\Contracts\Translation\TranslatorInterface;

class InvalidateBayernPortalCache implements \executable
{
    public function run(): string
    {
        $entitiesWidget = $this->generateEntitiesWidget();

        if ('invalidate_bayernportal_cache' === Input::get('act')) {
            $entitiesWidget->validate();

            if (!$entitiesWidget->hasErrors()) {
                $tags = array_map(function ($entity): string {
                    return 'bp.'.$entity;
                }, $entitiesWidget->value ?: []);

                $tags = array_filter(array_unique($tags));

                /** @var CacheManager $cacheManager */
                $cacheManager = System::getContainer()->get('fos_http_cache.cache_manager');
                $cacheManager->invalidateTags($tags);
            }
        }

        $template = new BackendTemplate('be_invalidate_bayernportal_cache');
        $template->entitiesWidget = $entitiesWidget;

        return $template->parse();
    }

    public function isActive(): bool
    {
        return false;
    }

    private function generateEntitiesWidget(): Widget
    {
        /** @var TranslatorInterface $translator */
        $translator = System::getContainer()->get('translator');

        $name = 'invalidate_bayernportal_cache_entities';
        $widget = new CheckBox();
        $widget->id = $name;
        $widget->name = $name;
        $widget->label = $translator->trans('tl_maintenance.bayernportal_entities.0', [], 'contao_tl_maintenance');
        $widget->mandatory = true;
        $widget->multiple = true;
        $widget->setInputCallback(function () use ($name): ?array {
            return Input::get($name);
        });

        $options = [];

        $entityTypes = [
            AnsprechpartnerEntity::getType(),
            BehoerdeEntity::getType(),
            GebaeudeEntity::getType(),
            LebenslageEntity::getType(),
            LeistungEntity::getType(),
        ];

        foreach ($entityTypes as $entityType) {
            $options[] = [
                'value' => $entityType,
                'label' => $translator->trans('BayernPortal.entity.'.$entityType, [], 'contao_default'),
                'default' => false,
            ];
        }

        if (1 === \count($options)) {
            $options[0]['default'] = true;
        }

        $widget->options = $options;

        return $widget;
    }
}
