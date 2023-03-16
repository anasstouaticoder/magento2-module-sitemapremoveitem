<?php
/**
 * Copyright (c) 2023
 * MIT License
 * Module AnassTouatiCoder_SitemapRemoveItem
 * Author Anass TOUATI anass1touati@gmail.com
 */
declare(strict_types=1);

namespace AnassTouatiCoder\SitemapRemoveItem\Model;

use Magento\Framework\App\Config\ConfigResource\ConfigInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Serialize\Serializer\Json;

class Config
{
    public const SCOPE_TYPE_WEBSITES = 'websites';

    public const SCOPE_TYPE_STORES = 'stores';
    private const XML_PATH_IGNORED_URI_LIST = 'anasstouaticoder_sitemap/general/ignored_uri_list';

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var ConfigInterface
     */
    protected $configResource;

    /**
     * @var Json|mixed
     */
    protected $serializer;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param ConfigInterface $configResource
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        ConfigInterface $configResource,
        Json $serializer = null
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->configResource = $configResource;
        $this->serializer = $serializer ?: ObjectManager::getInstance()->get(Json::class);
    }

    /**
     * @param int $websiteId
     * @param int $storeId
     * @return mixed
     */
    public function getCSVContent(int $websiteId, int $storeId)
    {
        $value = '';
        if ($websiteId) {
            $value = $this->getConfigValue(
                self::SCOPE_TYPE_WEBSITES,
                $websiteId
            );
        } elseif ($storeId) {
            $value = $this->getConfigValue(
                self::SCOPE_TYPE_STORES,
                $storeId
            );
        } else {
            $value = $this->getConfigValue();
        }

        return $value;
    }

    /**
     * @param array $data
     * @param int $websiteId
     * @param int $storeId
     * @return void
     */
    public function saveCSVContent(array $data, int $websiteId, int $storeId)
    {
        if ($websiteId) {
            $this->saveConfigValue($data, self::SCOPE_TYPE_WEBSITES, $websiteId);
        } elseif ($storeId) {
            $this->saveConfigValue($data, self::SCOPE_TYPE_STORES, $storeId);
        } else {
            $this->saveConfigValue($data);
        }
        // Clear the scope config cache
        $this->scopeConfig->clean();
    }

    /**
     * @param string $scope
     * @param null $scopeCode
     * @return array
     */
    protected function getConfigValue(string $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeCode = null)
    {
        $value = $this->scopeConfig->getValue(self::XML_PATH_IGNORED_URI_LIST, $scope, $scopeCode);
        return json_decode($value, true);
    }

    /**
     * @param $data
     * @param string $scope
     * @param int $scopeCode
     * @return void
     */
    protected function saveConfigValue(
        $data,
        string $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
        int $scopeCode = 0
    ): void {
        $data = $this->serializer->serialize($data);
        $this->configResource->saveConfig(
            self::XML_PATH_IGNORED_URI_LIST,
            $data,
            $scope,
            $scopeCode
        );
    }
}
