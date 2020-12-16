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

class ZeitangabeEntity extends AbstractEntity
{
    public $typ;
    public $vonVormittags;
    public $bisVormittags;
    public $vonNachmittags;
    public $bisNachmittags;

    public function hasBeforeNoon(): bool
    {
        return $this->vonVormittags || $this->bisVormittags;
    }

    public function hasAfterNoon(): bool
    {
        return $this->vonNachmittags || $this->bisNachmitvonNachmittags;
    }

    public function getBeforeNoon(): string
    {
        return implode('–', array_filter([$this->vonVormittags, $this->bisVormittags]));
    }

    public function getAfterNoon(): string
    {
        return implode('–', array_filter([$this->vonNachmittags, $this->bisNachmittags]));
    }
}
