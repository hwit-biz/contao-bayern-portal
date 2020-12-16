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

class DienststelleEntity extends AbstractEntity
{
    public $bezeichnung;
    public $behoerdenart;
    public $email;
    public $deMail;
    public $website;
    public $behoerdengruppe;
    public $sortierreihenfolge;
    public $id;
    public $dienststellenschluessel;
    public $dienststelleLfdNr;

    public function hasBasic(): bool
    {
        return null !== $this->bezeichnung;
    }
}
