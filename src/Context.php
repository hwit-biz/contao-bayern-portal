<?php

declare(strict_types=1);

/*
 * This file is part of the Contao Bayern Portal extension.
 *
 * (c) inspiredminds
 *
 * @license LGPL-3.0-or-later
 */

namespace InspiredMinds\ContaoBayernPortal;

use Contao\Model;
use Contao\PageModel;

class Context
{
    /** @var Model */
    public $model;

    /** @var int */
    public $behoerdeId;

    public function setModel(Model $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function setBehoerdeId(int $behoerdeId): self
    {
        $this->behoerdeId = $behoerdeId;

        return $this;
    }

    public function getLeistungenPage(): ?PageModel
    {
        if (null === $this->model || empty($this->model->bayernportal_leistungen_page)) {
            return null;
        }

        return PageModel::findByPk($this->model->bayernportal_leistungen_page);
    }

    public function getLebenslagenPage(): ?PageModel
    {
        if (null === $this->model || empty($this->model->bayernportal_lebenslagen_page)) {
            return null;
        }

        return PageModel::findByPk($this->model->bayernportal_lebenslagen_page);
    }

    public function getBehoerdenPage(): ?PageModel
    {
        if (null === $this->model || empty($this->model->bayernportal_behoerden_page)) {
            return null;
        }

        return PageModel::findByPk($this->model->bayernportal_behoerden_page);
    }
}
