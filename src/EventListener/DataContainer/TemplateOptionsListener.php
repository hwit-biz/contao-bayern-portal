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

use Contao\Controller;
use Contao\CoreBundle\ServiceAnnotation\Callback;
use Contao\DataContainer;
use InspiredMinds\ContaoBayernPortal\ApiEntity\AnsprechpartnerEntity;
use InspiredMinds\ContaoBayernPortal\ApiEntity\BehoerdeEntity;
use InspiredMinds\ContaoBayernPortal\ApiEntity\DienststelleEntity;
use InspiredMinds\ContaoBayernPortal\ApiEntity\LebenslageEntity;
use InspiredMinds\ContaoBayernPortal\ApiEntity\LeistungEntity;
use InspiredMinds\ContaoBayernPortal\Controller\FrontendModule\AnsprechpartnerController;
use InspiredMinds\ContaoBayernPortal\Controller\FrontendModule\BehoerdenController;
use InspiredMinds\ContaoBayernPortal\Controller\FrontendModule\DienststellenController;
use InspiredMinds\ContaoBayernPortal\Controller\FrontendModule\DienststellenLeistungenController;
use InspiredMinds\ContaoBayernPortal\Controller\FrontendModule\LebenslagenController;
use InspiredMinds\ContaoBayernPortal\Controller\FrontendModule\LeistungenController;

class TemplateOptionsListener
{
    private static $mapping = [
        BehoerdenController::TYPE => BehoerdeEntity::class,
        LeistungenController::TYPE => LeistungEntity::class,
        AnsprechpartnerController::TYPE => AnsprechpartnerEntity::class,
        LebenslagenController::TYPE => LebenslageEntity::class,
        DienststellenController::TYPE => DienststelleEntity::class,
        DienststellenLeistungenController::TYPE => LeistungEntity::class,
    ];

    /**
     * @Callback(table="tl_module", target="fields.bayernportal_list_template.options")
     */
    public function onListTemplatesOptions(DataContainer $dc): array
    {
        return $this->getTemplateOptions($dc->activeRecord->type, 'list');
    }

    /**
     * @Callback(table="tl_module", target="fields.bayernportal_detail_template.options")
     */
    public function onDetailTemplatesOptions(DataContainer $dc): array
    {
        return $this->getTemplateOptions($dc->activeRecord->type, 'detail');
    }

    public function getTemplateName(string $entityType, string $templateType): string
    {
        return 'bayernportal_'.$entityType.'_'.$templateType;
    }

    private function getTemplateOptions(string $moduleType, string $templateType): array
    {
        if (!isset(self::$mapping[$moduleType])) {
            throw new \InvalidArgumentException('Module type "'.$moduleType.'" has no associated BayernPortal entity.');
        }

        $entityClass = self::$mapping[$moduleType];
        $entityType = $entityClass::getType();
        $templateName = $this->getTemplateName($entityType, $templateType);

        return Controller::getTemplateGroup($templateName);
    }
}
