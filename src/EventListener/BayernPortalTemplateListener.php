<?php

declare(strict_types=1);

/*
 * This file is part of the Contao Bayern Portal extension.
 *
 * (c) inspiredminds
 *
 * @license LGPL-3.0-or-later
 */

namespace InspiredMinds\ContaoBayernPortal\EventListener;

use Contao\CoreBundle\ServiceAnnotation\Hook;
use Contao\Date;
use Contao\FrontendTemplate;
use Contao\Template;
use FOS\HttpCache\ResponseTagger;
use InspiredMinds\ContaoBayernPortal\Api\BayernPortalApi;
use InspiredMinds\ContaoBayernPortal\ApiEntity\AbstractEntity;
use InspiredMinds\ContaoBayernPortal\ApiEntity\AnsprechpartnerEntity;
use InspiredMinds\ContaoBayernPortal\ApiEntity\BehoerdeEntity;
use InspiredMinds\ContaoBayernPortal\ApiEntity\GebaeudeEntity;
use InspiredMinds\ContaoBayernPortal\ApiEntity\ImageEntity;
use InspiredMinds\ContaoBayernPortal\ApiEntity\LebenslageEntity;
use InspiredMinds\ContaoBayernPortal\ApiEntity\LeistungEntity;
use InspiredMinds\ContaoBayernPortal\Context;
use InspiredMinds\ContaoBayernPortal\EntityLinkCreator;
use InspiredMinds\ContaoBayernPortal\EventListener\DataContainer\TemplateOptionsListener;

/**
 * @Hook("parseTemplate")
 */
class BayernPortalTemplateListener
{
    private $templateOptions;
    private $api;
    private $context;
    private $entityLinkCreator;
    private $responseTagger;

    public function __construct(TemplateOptionsListener $templateOptions, BayernPortalApi $api, Context $context, EntityLinkCreator $entityLinkCreator, ResponseTagger $responseTagger)
    {
        $this->templateOptions = $templateOptions;
        $this->api = $api;
        $this->context = $context;
        $this->entityLinkCreator = $entityLinkCreator;
        $this->responseTagger = $responseTagger;
    }

    public function __invoke(Template $template): void
    {
        if (0 !== stripos($template->getName(), 'mod_bayern_portal') && 0 !== stripos($template->getName(), 'bayernportal_') && 0 !== stripos($template->getName(), 'ce_bayern_portal')) {
            return;
        }

        $template->renderDetail = function (AbstractEntity $entity) use ($template): string {
            if (!$entity->hasDetails()) {
                $entity = $this->loadDetails($entity);
            }

            $templateName = $template->bayernportal_detail_template ?: $this->templateOptions->getTemplateName($entity->getType(), 'detail');

            return $this->renderEntity($entity, $templateName, $template->context);
        };

        $template->renderList = function (AbstractEntity $entity) use ($template): string {
            if (!$entity->hasBasic()) {
                $entity = $this->loadDetails($entity);
            }

            $templateName = $template->bayernportal_list_template ?: $this->templateOptions->getTemplateName($entity->getType(), 'list');

            return $this->renderEntity($entity, $templateName, $template->context);
        };

        $template->renderImage = function (ImageEntity $entity) use ($template): string {
            return $this->renderImage($entity, $template->context);
        };
    }

    private function renderEntity(AbstractEntity $entity, string $templateName): string
    {
        $template = new FrontendTemplate($templateName);

        // Fill template with variables from entity
        foreach ($entity as $key => $value) {
            $template->{$key} = $value;
        }

        global $objPage;

        // Change some template variables
        switch (\get_class($entity)) {
            case LeistungEntity::class:
                if ($template->letzteAenderung) {
                    $template->letzteAenderung = Date::parse($objPage->datimFormat, strtotime($template->letzteAenderung));
                }
                if ($template->stand) {
                    $template->stand = Date::parse($objPage->datimFormat, strtotime($template->stand));
                }
                break;
            case LebenslageEntity::class:
                if ($template->stand) {
                    $template->stand = Date::parse($objPage->datimFormat, strtotime($template->stand));
                }
                break;
        }

        $template->link = function (AbstractEntity $customEntity = null) use ($entity): ?string {
            return $this->entityLinkCreator->generateLink($customEntity ?? $entity);
        };

        $template->entity = $entity;

        // Add response tags for caching
        $this->responseTagger->addTags(['bp', 'bp.'.$entity->getType()]);

        return $template->parse();
    }

    private function loadDetails(AbstractEntity $entity): AbstractEntity
    {
        switch (\get_class($entity)) {
            case BehoerdeEntity::class:
                return $this->api->getBehoerde((int) $entity->id);
            case GebaeudeEntity::class:
                if (null === $this->context->behoerdeId) {
                    throw new \RuntimeException('Behoerde ID missing in context.');
                }

                return $this->api->getGebaeude($this->context->behoerdeId, (int) $entity->id ?: $entity->gebaeudeId);
            case AnsprechpartnerEntity::class:
                return $this->api->getAnsprechpartner((int) $entity->id);
            case LeistungEntity::class:
                return $this->api->getLeistung((int) $entity->id);
            case LebenslageEntity::class:
                return $this->api->getLebenslage((int) $entity->id);
        }

        throw new \RuntimeException('Cannot load details for entity of type "'.$entity->getType().'".');
    }

    private function renderImage(ImageEntity $entity): string
    {
        return (string) $entity;
    }
}
