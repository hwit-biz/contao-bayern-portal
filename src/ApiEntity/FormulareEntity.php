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

class FormulareEntity extends AbstractEntity
{
    public $leistungId;
    public $leistungBezeichnung;
    public $leistungUrl;
    /** @var array<FormularEntity> */
    public $formulare;

    public static function factory(object $record): self
    {
        $entity = parent::factory($record);

        if (!empty($entity->formulare)) {
            $formulare = [];

            foreach ($entity->formulare->formular ?? [] as $formular) {
                $formulare[] = FormularEntity::factory($formular);
            }

            $entity->formulare = $formulare;
        }

        return $entity;
    }
}
