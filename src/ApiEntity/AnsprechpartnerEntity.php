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

class AnsprechpartnerEntity extends AbstractEntity
{
    public $anrede;
    public $vorname;
    public $nachname;
    public $funktion;
    public $stellenbezeichnung;
    public $email;
    public $website;
    public $zimmer;
    public $sortierreihenfolge;
    /** @var BehoerdeEntity|null */
    public $behoerde;
    public $behoerdeId;
    public $behoerdeBezeichnung;
    /** @var GebaeudeEntity|null */
    public $gebaeude;
    public $gebaeudeId;
    public $gebaeudeBezeichnung;
    public $ansprechpartnerId;
    public $telefonLandvorwahl;
    public $telefonOrtsvorwahl;
    public $telefonAnlage;
    public $telefonDurchwahl;
    public $faxLandvorwahl;
    public $faxOrtsvorwahl;
    public $faxAnlage;
    public $faxDurchwahl;
    /** @var OeffnungszeitenEntity|null */
    public $sprechzeiten;
    /** @var array<LeistungEntity> */
    public $leistungen;

    public function hasBasic(): bool
    {
        return null !== $this->vorname || null !== $this->nachname;
    }

    public function hasDetails(): bool
    {
        return null !== $this->telefonLandvorwahl || null !== $this->telefonOrtsvorwahl || null !== $this->telefonAnlage || null !== $this->telefonDurchwahl || null !== $this->faxLandvorwahl || null !== $this->faxOrtsvorwahl || null !== $this->faxAnlage || null !== $this->sprechzeiten || null !== $this->leistungen;
    }

    public static function factory(object $record): AbstractEntity
    {
        $entity = parent::factory($record);

        if (!empty($entity->sprechzeiten)) {
            $entity->sprechzeiten = OeffnungszeitenEntity::factory($entity->sprechzeiten);
        }

        if (!empty($entity->leistungen)) {
            $leistungen = [];

            foreach ($entity->leistungen->lg as $leistung) {
                $leistungen[] = LeistungEntity::factory($leistung);
            }

            $entity->leistungen = $leistungen;
        }

        if (!empty($entity->behoerdeId)) {
            $entity->behoerde = BehoerdeEntity::factory((object) ['id' => $entity->behoerdeId, 'bezeichnung' => $entity->behoerdeBezeichnung]);
        }

        if (!empty($entity->gebaeudeId)) {
            $entity->gebaeude = GebaeudeEntity::factory((object) ['id' => $entity->gebaeudeId, 'bezeichnung' => $entity->gebaeudeBezeichnung]);
        }

        return $entity;
    }
}
