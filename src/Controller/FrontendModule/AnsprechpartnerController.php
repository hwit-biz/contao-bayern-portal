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

use Contao\CoreBundle\ServiceAnnotation\FrontendModule;
use Contao\Input;
use Contao\ModuleModel;
use Contao\StringUtil;
use Contao\Template;
use InspiredMinds\ContaoBayernPortal\Api\BayernPortalApi;
use InspiredMinds\ContaoBayernPortal\ApiEntity\AnsprechpartnerEntity;
use InspiredMinds\ContaoBayernPortal\Context;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @FrontendModule(AnsprechpartnerController::TYPE, category="bayernportal", template="mod_bayern_portal")
 */
class AnsprechpartnerController extends AbstractBayernPortalModuleController
{
    public const TYPE = 'bayern_portal_ansprechpartner';

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

        $ansprechpartnerId = Input::get(AnsprechpartnerEntity::getType());

        if (null !== $ansprechpartnerId) {
            $ansprechpartner = $this->api->getAnsprechpartner((int) $ansprechpartnerId);
            $this->getPageModel()->pageTitle = strip_tags(StringUtil::stripInsertTags($ansprechpartner->vorname.' '.$ansprechpartner->nachname));
            $this->context->setBehoerdeId((int) $ansprechpartner->behoerdeId);
            $template->headline = null;
            $template->detail = $ansprechpartner;
            $template->parentBlock = 'block_searchable';
            $template->class .= ' mod--detail mod--leistung';
        } else {
            $data = $this->api->getAnsprechpartnerList();

            if ('alphabetical' === $model->bayernportal_sorting) {
                $data = $this->sortData($data, 'nachname');
            }

            $template->list = $data;
            $template->class .= ' list';
        }

        return $template->getResponse();
    }
}
