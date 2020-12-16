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

class BehoerdeEntity extends AbstractEntity
{
    // Identifier
    public $id;
    public $bezeichnung;
    public $behoerdenart;
    public $email;
    public $website;
    public $behoerdengruppe;
    public $sortierreihenfolge;
    public $logo;
    /** @var array<GebaeudeEntity> */
    public $behoerdenGebaeudeZuordnungen;
    public $kurzbeschreibung;
    /** @var callable */
    public $leistungen;

    public function hasBasic(): bool
    {
        return null !== $this->bezeichnung || null !== $this->behoerdenart || null !== $this->email || null !== $this->behoerdengruppe || null !== $this->sortierreihenfolge;
    }

    public function hasDetails(): bool
    {
        return null !== $this->logo || null !== $this->behoerdenGebaeudeZuordnungen;
    }

    public static function factory(object $record): self
    {
        $entity = parent::factory($record);

        if (!empty($entity->behoerdenGebaeudeZuordnungen)) {
            $behoerdenGebaeudeZuordnungen = [];

            foreach ($entity->behoerdenGebaeudeZuordnungen->gebaeude as $gebaeude) {
                $behoerdenGebaeudeZuordnungen[] = GebaeudeEntity::factory($gebaeude);
            }

            $entity->behoerdenGebaeudeZuordnungen = $behoerdenGebaeudeZuordnungen;
        }

        return $entity;
    }
}
