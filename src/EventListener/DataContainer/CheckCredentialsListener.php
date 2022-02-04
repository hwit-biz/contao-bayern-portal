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

use Contao\CoreBundle\ServiceAnnotation\Callback;
use Contao\DataContainer;
use InspiredMinds\ContaoBayernPortal\Api\BayernPortalApi;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Callback(table="tl_bayernportal_config", target="fields.password.save")
 */
class CheckCredentialsListener
{
    private $requestStack;
    private $api;
    private $translator;

    public function __construct(RequestStack $requestStack, BayernPortalApi $api, TranslatorInterface $translator)
    {
        $this->requestStack = $requestStack;
        $this->api = $api;
        $this->translator = $translator;
    }

    public function __invoke($password, DataContainer $dc)
    {
        $username = $this->requestStack->getCurrentRequest()->request->get('username');

        if (empty($username) || empty($password)) {
            return $password;
        }

        try {
            $this->api->setCredentials($username, $password)->get('behoerden');
        } catch (ClientExceptionInterface $e) {
            if (401 === $e->getResponse()->getStatusCode()) {
                throw new \Exception($this->translator->trans('invalid_credentials', [], 'ContaoBayernPortal'));
            }

            throw new \Exception(strip_tags($e->getResponse()->getContent(false)));
        }

        return $password;
    }
}
