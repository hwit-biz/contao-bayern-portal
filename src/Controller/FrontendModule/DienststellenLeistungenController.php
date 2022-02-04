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
use InspiredMinds\ContaoBayernPortal\ApiEntity\BehoerdeEntity;
use InspiredMinds\ContaoBayernPortal\ApiEntity\LeistungEntity;
use InspiredMinds\ContaoBayernPortal\Context;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @FrontendModule(DienststellenLeistungenController::TYPE, category="bayernportal", template="mod_bayern_portal")
 */
class DienststellenLeistungenController extends AbstractLeistungenController
{
    public const TYPE = 'bayern_portal_dienststellen_leistungen';

    private $api;
    private $context;
    private $translator;

    public function __construct(BayernPortalApi $api, Context $context, TranslatorInterface $translator)
    {
        $this->api = $api;
        $this->context = $context;
        $this->translator = $translator;
    }

    protected function getResponse(Template $template, ModuleModel $model, Request $request): Response
    {
        if (empty($model->bayernportal_dienststelle)) {
            return new Response();
        }

        $this->context->setModel($model);
        $this->context->setLeistungenPage($this->getPageModel());
        $this->api->setModel($model);

        $template->parentBlock = 'block_unsearchable';

        $leistungId = Input::get(LeistungEntity::getType());
        $behoerdeId = Input::get(BehoerdeEntity::getType());
        $currentPage = $this->getPageModel();

        if (null !== $leistungId) {
            $template->headline = null;
            $template->detail = $this->api->getLeistung((int) $leistungId);
            $currentPage->pageTitle = strip_tags(StringUtil::stripInsertTags($template->detail->bezeichnung));
            $template->parentBlock = 'block_searchable';
            $template->class .= ' mod--detail mod--leistung';
        } elseif (null !== $behoerdeId) {
            $this->context->setBehoerdeId((int) $behoerdeId);
            $template->headline = null;
            $template->detail = $this->api->getBehoerde((int) $behoerdeId);
            $currentPage->pageTitle = strip_tags(StringUtil::stripInsertTags($template->detail->bezeichnung));
            $template->parentBlock = 'block_searchable';
            $template->class .= ' detail behoerde';
            $template->bayernportal_detail_template = null;
        } else {
            $data = $this->api->getDienststelleLeistungen($model->bayernportal_dienststelle);

            if ('alphabetical' === $model->bayernportal_sorting) {
                $data = $this->sortData($data);
            } elseif ('custom' === $model->bayernportal_sorting) {
                $data = $this->sortData($data, 'sortierreihenfolge', 'numerical');
            }

            $filter = $this->getAlphabetFilter($data, $request, $this->translator);
            $template->filter = $filter;
            $template->list = $this->getFilteredList($data, $request);
            $template->class .= ' list';
        }

        return $template->getResponse();
    }
}
