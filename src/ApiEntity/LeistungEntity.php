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

class LeistungEntity extends AbstractEntity
{
    public $id;
    public $bezeichnung;
    public $url;
    public $synonyme;
    public $lebenslagen;
    public $letzteAenderung;
    public $status;
    public $kurzbeschreibung;
    public $langbeschreibung;
    public $links;
    public $verwandteLeistungen;
    public $stand;
    public $verantwortlicheBehoerde;

    public function hasBasic(): bool
    {
        return null !== $this->bezeichnung;
    }

    public function hasDetails(): bool
    {
        return null !== $this->url || null !== $this->synonyme || null !== $this->lebenslagen || null !== $this->letzteAenderung;
    }

    public static function factory(object $record): self
    {
        $entity = parent::factory($record);

        if (\is_object($entity->bezeichnung)) {
            $entity->bezeichnung = $entity->bezeichnung->value ?? null;
        }

        if (!empty($entity->lebenslagen)) {
            $lebenslagen = [];

            foreach ($entity->lebenslagen->lebenslage as $lebenslage) {
                $lebenslagen[] = LebenslageEntity::factory($lebenslage);
            }

            $entity->lebenslagen = $lebenslagen;
        }

        if (!empty($entity->kurzbeschreibung)) {
            $entity->kurzbeschreibung = $entity->kurzbeschreibung->value ?? null;
        }

        if (!empty($entity->langbeschreibung)) {
            $entity->langbeschreibung = $entity->langbeschreibung->value ?? null;
        }

        if (!empty($entity->status)) {
            $entity->status = $entity->status->value ?? null;
        }

        if (!empty($entity->verantwortlicheBehoerde)) {
            $entity->verantwortlicheBehoerde = $entity->verantwortlicheBehoerde->value ?? null;
        }

        if (!empty($entity->stand)) {
            $entity->stand = $entity->stand->value ?? null;
        }

        if (!empty($entity->links)) {
            $links = [];

            foreach ($entity->links->link as $link) {
                $links[] = LinkEntity::factory($link);
            }

            $entity->links = $links;
        }

        if (!empty($entity->verwandteLeistungen)) {
            $leistungen = [];

            foreach ($entity->verwandteLeistungen->verwandteLeistung as $leistung) {
                $leistungen[] = self::factory($leistung);
            }

            $entity->verwandteLeistungen = $leistungen;
        }

        if (!empty($entity->rechtsvorschriften)) {
            $rechtsvorschriften = [];

            foreach ($entity->rechtsvorschriften->rechtsvorschrift as $link) {
                $rechtsvorschriften[] = LinkEntity::factory($link);
            }

            $entity->rechtsvorschriften = $rechtsvorschriften;
        }

        if (!empty($entity->onlineVerfahren)) {
            $onlineVerfahren = [];

            foreach ($entity->onlineVerfahren->onlineVerfahren as $link) {
                $onlineVerfahren[] = LinkEntity::factory($link);
            }

            $entity->onlineVerfahren = $onlineVerfahren;
        }

        return $entity;
    }
}
