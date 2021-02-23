<?php

declare(strict_types=1);

/*
 * This file is part of the Contao Bayern Portal extension.
 *
 * (c) inspiredminds
 *
 * @license LGPL-3.0-or-later
 */

namespace InspiredMinds\ContaoBayernPortal\Api;

use Contao\Model;
use InspiredMinds\ContaoBayernPortal\ApiEntity\AnsprechpartnerEntity;
use InspiredMinds\ContaoBayernPortal\ApiEntity\BehoerdeEntity;
use InspiredMinds\ContaoBayernPortal\ApiEntity\DienststelleEntity;
use InspiredMinds\ContaoBayernPortal\ApiEntity\FormulareEntity;
use InspiredMinds\ContaoBayernPortal\ApiEntity\FormularEntity;
use InspiredMinds\ContaoBayernPortal\ApiEntity\GebaeudeEntity;
use InspiredMinds\ContaoBayernPortal\ApiEntity\LebenslageEntity;
use InspiredMinds\ContaoBayernPortal\ApiEntity\LeistungEntity;
use InspiredMinds\ContaoBayernPortal\Context;
use InspiredMinds\ContaoBayernPortal\Model\BayernPortalConfigModel;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class BayernPortalApi
{
    public const API = 'https://www.bayernportal-webservices.bayern.de/rest/allgemein/v3/';

    private static $cache = [];

    private $context;
    private $logger;

    private $username;
    private $password;

    /** @var HttpClientInterface */
    private $client;

    public function __construct(Context $context, ?LoggerInterface $logger)
    {
        $this->context = $context;
        $this->logger = $logger;
    }

    /**
     * Sets username and password for the API.
     */
    public function setCredentials(string $username, string $password): self
    {
        $this->username = $username;
        $this->password = $password;
        $this->client = null;

        return $this;
    }

    /**
     * Sets the credentials based on the given config.
     */
    public function setConfig(BayernPortalConfigModel $config): self
    {
        return $this->setCredentials($config->username, $config->password);
    }

    /**
     * Extracts the config via the bayernportal_config variable from the model.
     */
    public function setModel(Model $model): self
    {
        if (empty($model->bayernportal_config)) {
            return $this;
        }

        $config = BayernPortalConfigModel::findById($model->bayernportal_config);

        if (null === $config) {
            return $this;
        }

        return $this->setConfig($config);
    }

    public function getClient(): HttpClientInterface
    {
        if (null !== $this->client) {
            return $this->client;
        }

        $options = [
            'headers' => [
                'User-Agent' => 'Contao Bayern Portal',
                'Accept' => 'application/json',
            ],
        ];

        if (!empty($this->username) && !empty($this->password)) {
            $options['auth_basic'] = [$this->username, $this->password];
        }

        $this->client = HttpClient::createForBaseUri(self::API, $options);

        return $this->client;
    }

    /**
     * @return object|array
     */
    public function get(string $url, array $options = [])
    {
        $cacheKey = $this->getCacheKey($url.md5(json_encode($options)));

        if (!isset(static::$cache[$cacheKey])) {
            $response = $this->getClient()->request(Request::METHOD_GET, $url, $options);
            static::$cache[$cacheKey] = json_decode($response->getContent());

            if (null !== $this->logger) {
                $info = $response->getInfo();
                $this->logger->debug('BayernPortal request: '.json_encode($info));
            }
        }

        return static::$cache[$cacheKey];
    }

    /**
     * @return array<BehoerdeEntity>
     */
    public function getBehoerden(): array
    {
        $data = $this->get('behoerden');

        return $this->collectionFactory($data->behoerde, BehoerdeEntity::class);
    }

    public function getBehoerde(int $id): BehoerdeEntity
    {
        $data = $this->get('behoerden/'.$id);

        $behoerde = BehoerdeEntity::factory($data->behoerde);

        $behoerde->leistungen = function () use ($behoerde) {
            return $this->getBehoerdeLeistungen((int) $behoerde->id);
        };

        $behoerde->ansprechpartner = function () use ($behoerde) {
            return $this->getBehoerdeAnsprechpartner((int) $behoerde->id);
        };

        return $behoerde;
    }

    public function getGebaeude(int $behoerdeId, int $gebaudeId): GebaeudeEntity
    {
        $data = $this->get('behoerden/'.$behoerdeId.'/gebaeude/'.$gebaudeId);

        return GebaeudeEntity::factory($data->behoerdenGebaeude);
    }

    /**
     * @return array<LeistungEntity>
     */
    public function getBehoerdeLeistungen(int $behoerdeId, array $options = []): array
    {
        $options = $this->applyMunicipalityParameter($options);

        $data = $this->get('behoerden/'.$behoerdeId.'/leistungen');

        return $this->collectionFactory($data->leistung, LeistungEntity::class);
    }

    /**
     * @return array<LeistungEntity>
     */
    public function getLeistungen(array $options = []): array
    {
        $options = $this->applyMunicipalityParameter($options);

        $data = $this->get('leistungen', $options);

        return $this->collectionFactory($data->leistung, LeistungEntity::class);
    }

    public function getLeistung(int $leistungId, array $options = []): LeistungEntity
    {
        $options = $this->applyMunicipalityParameter(['query' => ['mitRegionalenErgaenzungen' => true]]);

        $data = $this->get('leistungsbeschreibungen/'.$leistungId, $options);

        if (!empty($data->regionaleErgaenzungen) && !empty($data->regionaleErgaenzungen->regionaleErgaenzung)) {
            $addition = $this->get('leistungsbeschreibungen/'.$data->regionaleErgaenzungen->regionaleErgaenzung[0]->id, $this->applyMunicipalityParameter());

            foreach ($addition as $property => $value) {
                if (!isset($data->{$property})) {
                    $data->{$property} = $value;
                }
            }
        }

        return LeistungEntity::factory($data);
    }

    /**
     * @return array<AnsprechpartnerEntity>
     */
    public function getBehoerdeAnsprechpartner(int $behoerdeId): array
    {
        $data = $this->get('behoerden/'.$behoerdeId.'/ansprechpartner');

        return $this->collectionFactory($data->ansprechpartner->ap, AnsprechpartnerEntity::class);
    }

    /**
     * @return array<AnsprechpartnerEntity>
     */
    public function getAnsprechpartnerList(): array
    {
        $data = $this->get('ansprechpartner');

        return $this->collectionFactory($data->ap, AnsprechpartnerEntity::class);
    }

    public function getAnsprechpartner(int $ansprechpartnerId): AnsprechpartnerEntity
    {
        $data = $this->get('ansprechpartner/'.$ansprechpartnerId);

        return AnsprechpartnerEntity::factory($data->ap[0]);
    }

    /**
     * @return array<LeistungEntity>
     */
    public function getAnsprechpartnerLeistungen(int $ansprechpartnerId, array $options = []): array
    {
        $options = $this->applyMunicipalityParameter($options);

        $data = $this->get('ansprechpartner/'.$ansprechpartnerId.'/leistungen');

        return $this->collectionFactory($data->leistung, LeistungEntity::class);
    }

    /**
     * @return array<LebenslageEntity>
     */
    public function getLebenslagen(): array
    {
        $data = $this->get('lebenslagen');

        return $this->collectionFactory($data->lebenslage, LebenslageEntity::class);
    }

    public function getLebenslage(int $lebenslageId): LebenslageEntity
    {
        $data = $this->get('lebenslagen/'.$lebenslageId);

        return LebenslageEntity::factory($data);
    }

    /**
     * @return array<DienststelleEntity>
     */
    public function getDienststellen(): array
    {
        $data = $this->get('dienststellen');

        return $this->collectionFactory($data->dienststelle, DienststelleEntity::class);
    }

    public function getDienststelle(int $dienststelleId, array $options = []): DienststelleEntity
    {
        $data = $this->get('dienststellen/'.$dienststelleId);

        /** @var DienststelleEntity $dienststelle */
        $dienststelle = DienststelleEntity::factory($data->dienststelle[0]);

        $dienststelle->leistungen = function () use ($dienststelle): array {
            $options = $this->applyMunicipalityParameter(['query' => ['mitRegionalenErgaenzungen' => true]]);

            $data = $this->get('dienststellen/'.$dienststelle->dienststellenschluessel.'/leistungsbeschreibungen', $options);

            return $this->collectionFactory($data->leistungsbeschreibung, LeistungEntity::class);
        };

        $dienststelle->formulare = function () use ($dienststelle): array {
            return $this->getDienststelleFormulare($dienststelle->dienststellenschluessel);
        };

        $dienststelle->lebenslagen = function () use ($dienststelle): array {
            $data = $this->get('dienststellen/'.$dienststelle->dienststellenschluessel.'/lebenslagen');

            return $this->collectionFactory($data->lebenslage, LebenslageEntity::class);
        };

        return $dienststelle;
    }

    public function getDienststelleLeistungen(string $dienststellenschluessel, array $options = []): array
    {
        $options = $this->applyMunicipalityParameter($options);

        $data = $this->get('dienststellen/'.$dienststellenschluessel.'/leistungen', $options);

        return $this->collectionFactory($data->leistung, LeistungEntity::class);
    }

    public function getDienststelleFormulare(string $dienststellenschluessel, array $options = []): array
    {
        $options = $this->applyMunicipalityParameter($options);

        $data = $this->get('dienststellen/'.$dienststellenschluessel.'/formulare');

        $formulare = [];

        if (!empty($data->formular)) {
            $formulare[] = FormularEntity::factory($data->formular);
        }

        if (!empty($data->leistungMitFormularen) && \is_array($data->leistungMitFormularen)) {
            foreach ($data->leistungMitFormularen as $formular) {
                $formulare[] = FormulareEntity::factory($formular);
            }
        }

        return $formulare;
    }

    /**
     * @param array<object>
     *
     * @return array<AbstractEntity>
     */
    public function collectionFactory(array $records, string $class): array
    {
        $collection = [];

        foreach ($records as $record) {
            $collection[] = $class::factory($record);
        }

        // Sort the collection if we have a "sortierreihenfolge" property
        if (!empty($collection) && property_exists($class, 'sortierreihenfolge')) {
            usort($collection, function ($a, $b) {
                /* @var AbstractEntity $a */
                /* @var AbstractEntity $b */
                return (int) $a->sortierreihenfolge - (int) $b->sortierreihenfolge;
            });
        }

        return $collection;
    }

    private function getCacheKey(string $url): string
    {
        return md5(implode(',', [$this->username, $this->password, $url]));
    }

    private function applyMunicipalityParameter(array $options = []): array
    {
        $config = $this->context->getConfig();

        if (null === $config) {
            return $options;
        }

        if (empty($config->municipality_code)) {
            return $options;
        }

        return array_merge_recursive(['query' => ['gemeindekennziffer' => $config->municipality_code]], $options);
    }
}
