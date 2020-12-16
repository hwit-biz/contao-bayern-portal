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
use InspiredMinds\ContaoBayernPortal\ApiEntity\LeistungEntity;
use InspiredMinds\ContaoBayernPortal\Context;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @FrontendModule(LeistungenController::TYPE, category="bayernportal", template="mod_bayern_portal")
 */
class LeistungenController extends AbstractFrontendModuleController
{
    public const TYPE = 'bayern_portal_leistungen';

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

        $leistungId = Input::get(LeistungEntity::getType());

        if (null !== $leistungId) {
            $template->headline = null;
            $template->detail = $this->api->getLeistung((int) $leistungId);
            global $objPage;
            $objPage->pageTitle = strip_tags(StringUtil::stripInsertTags($template->detail->bezeichnung));
            $template->parentBlock = 'block_searchable';
            $template->class .= ' mod--detail mod--leistung';
        } else {
            $template->list = $this->api->getLeistungen();
            $template->class .= ' list';
        }

        return $template->getResponse();
    }
}
