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
use InspiredMinds\ContaoBayernPortal\ApiEntity\DienststelleEntity;
use InspiredMinds\ContaoBayernPortal\Context;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @FrontendModule(DienststellenController::TYPE, category="bayernportal", template="mod_bayern_portal")
 */
class DienststellenController extends AbstractBayernPortalModuleController
{
    public const TYPE = 'bayern_portal_dienststellen';

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

        $dienststelleId = Input::get(DienststelleEntity::getType());

        if (null !== $dienststelleId) {
            $template->headline = null;
            $template->detail = $this->api->getDienststelle((int) $dienststelleId);
            $this->getPageModel()->pageTitle = strip_tags(StringUtil::stripInsertTags($template->detail->bezeichnung));
            $template->parentBlock = 'block_searchable';
            $template->class .= ' mod--detail mod--dienststelle';
        } else {
            $data = $this->api->getDienststellen();

            if ('alphabetical' === $model->bayernportal_sorting) {
                $data = $this->sortData($data);
            } elseif ('custom' === $model->bayernportal_sorting) {
                $data = $this->sortData($data, 'sortierreihenfolge', 'numerical');
            }

            $template->list = $data;
            $template->class .= ' list';
        }

        return $template->getResponse();
    }
}
