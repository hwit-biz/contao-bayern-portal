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

abstract class AbstractEntity
{
    public function hasBasic(): bool
    {
        return true;
    }

    public function hasDetails(): bool
    {
        return true;
    }

    public static function getType(): string
    {
        $shortName = (new \ReflectionClass(static::class))->getShortName();

        return strtolower(str_replace('Entity', '', $shortName));
    }

    public static function factory(object $record): self
    {
        $entity = new static();

        foreach ($record as $key => $value) {
            $entity->{$key} = $value;
        }

        if (!empty($entity->logo)) {
            $entity->logo = ImageEntity::factory($entity->logo);
        }

        if (\is_object($entity->synonyme)) {
            $synonyme = [];

            foreach ($entity->synonyme->synonym as $synonym) {
                $synonyme[] = $synonym->value;
            }

            $entity->synonyme = $synonyme;
        }

        return $entity;
    }
}
