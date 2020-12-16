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

use Contao\FrontendTemplate;

class ImageEntity extends AbstractEntity
{
    public $value;
    public $alt;
    public $mimetype;
    public $quelle;
    public $title;

    public function __toString()
    {
        $template = new FrontendTemplate('image');

        $template->setData([
            'caption' => $this->quelle,
            'picture' => [
                'img' => [
                    'src' => 'data:'.$this->mimetype.';base64,'.$this->value,
                ],
                'title' => $this->title,
                'alt' => $this->alt,
            ],
        ]);

        return $template->parse();
    }
}
