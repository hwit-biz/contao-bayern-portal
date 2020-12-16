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

class FormularEntity extends AbstractEntity
{
    public $id;
    public $kurzbeschreibung;
    public $url;
    public $langbeschreibung;
    public $schriftformErfordernis;
    public $vorausfuellbar;

    public static function factory(object $record): self
    {
        $entity = parent::factory($record);

        if (!empty($entity->schriftformErfordernis)) {
            $entity->schriftformErfordernis = $entity->schriftformErfordernis->beschreibung ?? null;
        }

        return $entity;
    }
}
