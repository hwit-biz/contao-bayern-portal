<?php

declare(strict_types=1);

/*
 * This file is part of the Contao Bayern Portal extension.
 *
 * (c) inspiredminds
 *
 * @license LGPL-3.0-or-later
 */

namespace InspiredMinds\ContaoBayernPortal\ApiEntity;

class OeffnungszeitenEntity extends AbstractEntity
{
    public $nachVereinbarung = false;
    /** @var array<ZeitangabeEntity> */
    public $sonderzeiten;
    /** @var array<ZeitangabeEntity> */
    public $oeffnungszeit;

    public function hasBasic(): bool
    {
        return null !== $this->bezeichnung;
    }

    public function hasDetails(): bool
    {
        return $this->hasBasic();
    }

    public static function factory(object $record): self
    {
        $entity = parent::factory($record);

        foreach (['sonderzeiten', 'oeffnungszeit'] as $property) {
            if (empty($entity->{$property}) || !\is_array($entity->{$property})) {
                continue;
            }

            foreach ($entity->{$property} as &$oeffnungszeit) {
                $oeffnungszeit = ZeitangabeEntity::factory($oeffnungszeit->zeitangabe[0]);
            }
        }

        return $entity;
    }
}
