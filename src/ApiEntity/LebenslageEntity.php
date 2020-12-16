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

class LebenslageEntity extends AbstractEntity
{
    public $id;
    public $bezeichnung;
    public $kurzbeschreibung;
    public $langbeschreibung;
    public $kategorie;
    public $vorgaengerId;
    public $stand;
    public $synonyme;
    public $leistungen;

    public function hasBasic(): bool
    {
        return null !== $this->id || null !== $this->bezeichnung;
    }

    public static function factory(object $record): AbstractEntity
    {
        $entity = parent::factory($record);

        if (!empty($entity->leistungen)) {
            $leistungen = [];

            foreach ($entity->leistungen->leistung as $leistung) {
                $leistungen[] = LeistungEntity::factory($leistung);
            }

            $entity->leistungen = $leistungen;
        }

        return $entity;
    }
}
