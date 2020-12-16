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

class GebaeudeEntity extends AbstractEntity
{
    // Identifier
    public $id;

    // Basic
    public $bezeichnung;
    public $hausanschriftPLZ;
    public $hausanschriftOrt;
    public $hausanschriftStrasse;
    public $postanschriftPLZ;
    public $postanschriftOrt;
    public $postanschriftStrasse;
    public $logo;
    public $sortierreihenfolge;
    public $telefonLandvorwahl;
    public $telefonOrtsvorwahl;
    public $telefonAnlage;
    public $telefonDurchwahl;
    public $faxLandvorwahl;
    public $faxOrtsvorwahl;
    public $faxAnlage;
    public $faxDurchwahl;
    /** @var OeffnungszeitenEntity */
    public $oeffnungszeiten;
    public $behoerdeId;
    public $gebaeudeId;

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

        if (!empty($entity->gebaeudeId)) {
            $entity->id = $entity->gebaeudeId;
        }

        if (!empty($entity->oeffnungszeiten)) {
            $entity->oeffnungszeiten = OeffnungszeitenEntity::factory($entity->oeffnungszeiten);
        }

        return $entity;
    }
}
