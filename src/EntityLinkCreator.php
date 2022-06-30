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

use Contao\PageModel;
use InspiredMinds\ContaoBayernPortal\ApiEntity\AbstractEntity;
use InspiredMinds\ContaoBayernPortal\ApiEntity\AnsprechpartnerEntity;
use InspiredMinds\ContaoBayernPortal\ApiEntity\BehoerdeEntity;
use InspiredMinds\ContaoBayernPortal\ApiEntity\DienststelleEntity;
use InspiredMinds\ContaoBayernPortal\ApiEntity\GebaeudeEntity;
use InspiredMinds\ContaoBayernPortal\ApiEntity\LebenslageEntity;
use InspiredMinds\ContaoBayernPortal\ApiEntity\LeistungEntity;

class EntityLinkCreator
{
    private $context;

    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    public function generateLink(AbstractEntity $entity): ?string
    {
        return $this->{'generate'.ucfirst($entity->getType())}($entity);
    }

    private function generateAnsprechpartner(AnsprechpartnerEntity $entity): ?string
    {
        if (null === $entity->ansprechpartnerId) {
            return null;
        }

        $page = $this->getCurrentPage();
        $params = '/'.$entity->getType().'/'.$entity->ansprechpartnerId;

        if (null !== $this->context->behoerdeId) {
            $page = $this->context->getBehoerdenPage() ?? $page;
            $params = '/'.BehoerdeEntity::getType().'/'.$this->context->behoerdeId.$params;
        }

        return $page->getFrontendUrl($params);
    }

    private function generateBehoerde(BehoerdeEntity $entity): ?string
    {
        if (null === $entity->id) {
            return null;
        }

        $page = $this->context->getBehoerdenPage() ?: $this->getCurrentPage();

        return $page->getFrontendUrl('/'.$entity->getType().'/'.$entity->id);
    }

    private function generateGebaeude(GebaeudeEntity $entity): ?string
    {
        if (null === $entity->id) {
            return null;
        }

        if (null === $this->context->behoerdeId) {
            throw new \RuntimeException('Behoerde ID missing from context.');
        }

        $page = $this->context->getBehoerdenPage() ?: $this->getCurrentPage();

        return $page->getFrontendUrl('/'.BehoerdeEntity::getType().'/'.$this->context->behoerdeId.'/'.$entity->getType().'/'.$entity->id);
    }

    private function generateLebenslage(LebenslageEntity $entity): ?string
    {
        if (null === $entity->id) {
            return null;
        }

        $page = $this->context->getLebenslagenPage() ?: $this->getCurrentPage();

        return $page->getFrontendUrl('/'.$entity->getType().'/'.$entity->id);
    }

    private function generateLeistung(LeistungEntity $entity): ?string
    {
        if (null === $entity->id) {
            return null;
        }

        $page = $this->getCurrentPage();
        $params = '/'.$entity->getType().'/'.$entity->id;

        if (null !== ($leistungenPage = $this->context->getLeistungenPage())) {
            return $leistungenPage->getFrontendUrl($params);
        }

        if (null !== $this->context->behoerdeId) {
            $page = $this->context->getBehoerdenPage() ?? $page;
            $params = '/'.BehoerdeEntity::getType().'/'.$this->context->behoerdeId.$params;
        }

        return $page->getFrontendUrl($params);
    }

    private function generateDienststelle(DienststelleEntity $entity): ?string
    {
        if (null === $entity->dienststellenschluessel) {
            return null;
        }

        return $this->getCurrentPage()->getFrontendUrl('/'.$entity->getType().'/'.$entity->dienststellenschluessel);
    }

    private function getCurrentPage(): PageModel
    {
        global $objPage;

        return $objPage;
    }
}
