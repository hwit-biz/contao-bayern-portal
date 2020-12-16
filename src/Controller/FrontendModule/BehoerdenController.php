<?php

declare(strict_types=1);

/*
 * This file is part of the Contao Bayern Portal extension.
 *
 * (c) inspiredminds
 *
 * @license LGPL-3.0-or-later
 */

namespace InspiredMinds\ContaoBayernPortal\Controller\FrontendModule;

use Contao\CoreBundle\Controller\FrontendModule\AbstractFrontendModuleController;
use Contao\CoreBundle\ServiceAnnotation\FrontendModule;
use Contao\Input;
use Contao\ModuleModel;
use Contao\StringUtil;
use Contao\Template;
use InspiredMinds\ContaoBayernPortal\Api\BayernPortalApi;
use InspiredMinds\ContaoBayernPortal\ApiEntity\AnsprechpartnerEntity;
use InspiredMinds\ContaoBayernPortal\ApiEntity\BehoerdeEntity;
use InspiredMinds\ContaoBayernPortal\ApiEntity\GebaeudeEntity;
use InspiredMinds\ContaoBayernPortal\ApiEntity\LeistungEntity;
use InspiredMinds\ContaoBayernPortal\Context;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @FrontendModule(BehoerdenController::TYPE, category="bayernportal", template="mod_bayern_portal")
 */
class BehoerdenController extends AbstractFrontendModuleController
{
    public const TYPE = 'bayern_portal_behoerden';

    private $api;
    private $context;

    public function __construct(BayernPortalApi $api, Context $context)
    {
        $this->api = $api;
        $this->context = $context;
    }

    protected function getResponse(Template $template, ModuleModel $model, Request $request): Response
    {
        $this->context->setModel($model);
        $this->api->setModel($model);

        $template->parentBlock = 'block_unsearchable';

        $gebaeudeId = Input::get(GebaeudeEntity::getType());
        $behoerdeId = Input::get(BehoerdeEntity::getType());
        $leistungId = Input::get(LeistungEntity::getType());
        $ansprechpartnerId = Input::get(AnsprechpartnerEntity::getType());

        if (null !== $behoerdeId) {
            $this->context->setBehoerdeId((int) $behoerdeId);

            global $objPage;

            if (null !== $ansprechpartnerId) {
                $template->headline = null;
                $template->detail = $this->api->getAnsprechpartner((int) $ansprechpartnerId);
                $objPage->pageTitle = strip_tags(StringUtil::stripInsertTags($$template->detail->vorname.' '.$template->detail->nachname));
                $template->parentBlock = 'block_searchable';
                $template->class .= ' mod--detail mod--ansprechpartner';
                $template->bayernportal_detail_template = null;
            } elseif (null !== $leistungId) {
                $template->headline = null;
                $template->detail = $this->api->getLeistung((int) $leistungId);
                $objPage->pageTitle = strip_tags(StringUtil::stripInsertTags($template->detail->bezeichnung));
                $template->parentBlock = 'block_searchable';
                $template->class .= ' mod--detail mod--leistung';
                $template->bayernportal_detail_template = null;
            } elseif (null !== $gebaeudeId) {
                $template->headline = null;
                $template->detail = $this->api->getGebaeude((int) $behoerdeId, (int) $gebaeudeId);
                $objPage->pageTitle = strip_tags(StringUtil::stripInsertTags($template->detail->bezeichnung));
                $template->parentBlock = 'block_searchable';
                $template->class .= ' mod--detail mod--gebaeude';
                $template->bayernportal_detail_template = null;
            } else {
                $template->headline = null;
                $template->detail = $this->api->getBehoerde((int) $behoerdeId);
                $objPage->pageTitle = strip_tags(StringUtil::stripInsertTags($template->detail->bezeichnung));
                $template->parentBlock = 'block_searchable';
                $template->class .= ' detail behoerde';
            }
        } else {
            $template->list = $this->api->getBehoerden();
            $template->class .= ' list';
        }

        return $template->getResponse();
    }
}