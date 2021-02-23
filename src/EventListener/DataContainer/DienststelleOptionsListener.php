<?php

declare(strict_types=1);

/*
 * This file is part of the Contao Bayern Portal extension.
 *
 * (c) inspiredminds
 *
 * @license LGPL-3.0-or-later
 */

namespace InspiredMinds\ContaoBayernPortal\EventListener\DataContainer;

use Contao\CoreBundle\ServiceAnnotation\Callback;
use Contao\DataContainer;
use InspiredMinds\ContaoBayernPortal\Api\BayernPortalApi;
use InspiredMinds\ContaoBayernPortal\Model\BayernPortalConfigModel;

/**
 * Queries the API for the available Dienststellen to return as options for the select.
 *
 * @Callback(table="tl_module", target="fields.bayernportal_dienststelle.options")
 */
class DienststelleOptionsListener
{
    private $api;

    public function __construct(BayernPortalApi $api)
    {
        $this->api = $api;
    }

    public function __invoke(DataContainer $dc): array
    {
        dump($dc->activeRecord->bayernportal_config);
        if (empty($dc->activeRecord->bayernportal_config)) {
            return [];
        }

        $config = BayernPortalConfigModel::findById($dc->activeRecord->bayernportal_config);

        if (null === $config) {
            return [];
        }

        $this->api->setConfig($config);

        $dienststellen = $this->api->getDienststellen();

        if (empty($dienststellen)) {
            return [];
        }

        $options = [];

        foreach ($dienststellen as $dienststelle) {
            $options[$dienststelle->dienststellenschluessel] = $dienststelle->bezeichnung;
        }

        return $options;
    }
}
