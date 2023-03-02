<?php
/**
 * Copyright (c) 2022
 * MIT License
 * Module AnassTouatiCoder_SitemapRemoveItem
 * Author Anass TOUATI anass1touati@gmail.com
 */

declare(strict_types=1);

namespace AnassTouatiCoder\SitemapRemoveItem\Rewrite\Magento\Sitemap\Model;

use Magento\Config\Model\Config\Reader\Source\Deployed\DocumentRoot;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Escaper;
use Magento\Framework\Filesystem;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Sitemap\Helper\Data;
use Magento\Sitemap\Model\ItemProvider\ItemProviderInterface;
use Magento\Sitemap\Model\ResourceModel\Catalog\CategoryFactory;
use Magento\Sitemap\Model\ResourceModel\Catalog\ProductFactory;
use Magento\Sitemap\Model\ResourceModel\Cms\PageFactory;
use Magento\Sitemap\Model\SitemapConfigReaderInterface;
use Magento\Sitemap\Model\SitemapItemInterfaceFactory;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

class Sitemap extends \Magento\Sitemap\Model\Sitemap
{
    public const XML_PATH_IGNORED_URLS = 'anasstouaticoder_sitemap/general/ignored_url_list';
    /**
     * @var ItemProviderInterface|mixed
     */
    protected $itemProvider;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @var SitemapConfigReaderInterface|mixed
     */
    protected $configReader;

    /**
     * @var SitemapItemInterfaceFactory|mixed
     */
    protected $sitemapItemFactory;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param Escaper $escaper
     * @param Data $sitemapData
     * @param Filesystem $filesystem
     * @param CategoryFactory $categoryFactory
     * @param ProductFactory $productFactory
     * @param PageFactory $cmsFactory
     * @param DateTime $modelDate
     * @param StoreManagerInterface $storeManager
     * @param RequestInterface $request
     * @param \Magento\Framework\Stdlib\DateTime $dateTime
     * @param ScopeConfigInterface $scopeConfig
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     * @param DocumentRoot|null $documentRoot
     * @param ItemProviderInterface|null $itemProvider
     * @param SitemapConfigReaderInterface|null $configReader
     * @param SitemapItemInterfaceFactory|null $sitemapItemFactory
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function __construct(
        Context $context,
        Registry $registry,
        Escaper $escaper,
        Data $sitemapData,
        Filesystem $filesystem,
        CategoryFactory $categoryFactory,
        ProductFactory $productFactory,
        PageFactory $cmsFactory,
        DateTime $modelDate,
        StoreManagerInterface $storeManager,
        RequestInterface $request,
        \Magento\Framework\Stdlib\DateTime $dateTime,
        ScopeConfigInterface $scopeConfig,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = [],
        DocumentRoot $documentRoot = null,
        ItemProviderInterface $itemProvider = null,
        SitemapConfigReaderInterface $configReader = null,
        SitemapItemInterfaceFactory $sitemapItemFactory = null
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->_escaper = $escaper;
        $this->_sitemapData = $sitemapData;
        $this->filesystem = $filesystem;
        $this->_directory = $filesystem->getDirectoryWrite(DirectoryList::PUB);
        $this->_categoryFactory = $categoryFactory;
        $this->_productFactory = $productFactory;
        $this->_cmsFactory = $cmsFactory;
        $this->_dateModel = $modelDate;
        $this->_storeManager = $storeManager;
        $this->_request = $request;
        $this->dateTime = $dateTime;
        $this->itemProvider = $itemProvider ?: ObjectManager::getInstance()->get(ItemProviderInterface::class);
        $this->configReader = $configReader ?: ObjectManager::getInstance()->get(SitemapConfigReaderInterface::class);
        $this->sitemapItemFactory = $sitemapItemFactory ?: ObjectManager::getInstance()->get(
            SitemapItemInterfaceFactory::class
        );

        parent::__construct(
            $context,
            $registry,
            $escaper,
            $sitemapData,
            $filesystem,
            $categoryFactory,
            $productFactory,
            $cmsFactory,
            $modelDate,
            $storeManager,
            $request,
            $dateTime,
            $resource,
            $resourceCollection,
            $data,
            $documentRoot,
            $itemProvider,
            $configReader,
            $sitemapItemFactory
        );
    }
    /**
     * {@inheritdoc }
     */
    protected function _getSitemapRow($url, $lastmod = null, $changefreq = null, $priority = null, $images = null)
    {
        $url = $this->_getUrl($url);
        $row = '<loc>' . $this->_escaper->escapeUrl($url) . '</loc>';
        if ($lastmod) {
            $row .= '<lastmod>' . $this->_getFormattedLastmodDate($lastmod) . '</lastmod>';
        }
        if ($changefreq) {
            $row .= '<changefreq>' . $this->_escaper->escapeHtml($changefreq) . '</changefreq>';
        }
        if ($priority) {
            $row .= sprintf('<priority>%.1f</priority>', $this->_escaper->escapeHtml($priority));
        }
        if ($images) {
            // Add Images to sitemap
            foreach ($images->getCollection() as $image) {

                // Adding NullSafe Logic
                $title = $images->getTitle() === null ? $images->getTitle() : '';
                $row .= '<image:image>';
                $row .= '<image:loc>' . $this->_escaper->escapeUrl($image->getUrl()) . '</image:loc>';
                $row .= '<image:title>' . $this->escapeXmlText($title) . '</image:title>';
                if ($image->getCaption()) {
                    $row .= '<image:caption>' . $this->escapeXmlText($image->getCaption()) . '</image:caption>';
                }
                $row .= '</image:image>';
            }
            // Add PageMap image for Google web search
            $row .= '<PageMap xmlns="http://www.google.com/schemas/sitemap-pagemap/1.0"><DataObject type="thumbnail">';
            $row .= '<Attribute name="name" value="' . $this->_escaper->escapeHtmlAttr($title) . '"/>';
            $row .= '<Attribute name="src" value="' . $this->_escaper->escapeUrl($images->getThumbnail()) . '"/>';
            $row .= '</DataObject></PageMap>';
        }

        return '<url>' . $row . '</url>';
    }

    /**
     * I just changed the visibility
     * {@inheritDoc}
     */
    protected function escapeXmlText(string $text): string
    {
        $doc = new \DOMDocument('1.0', 'UTF-8');
        $fragment = $doc->createDocumentFragment();
        $fragment->appendChild($doc->createTextNode($text));
        return $doc->saveXML($fragment);
    }

    /**
     * Sitemap item mapper for backwards compatibility
     *
     * @return array
     */
    protected function mapToSitemapItem()
    {
        $items = [];

        foreach ($this->_sitemapItems as $data) {
            foreach ($data->getCollection() as $item) {
                $items[] = $this->sitemapItemFactory->create(
                    [
                        'url' => $item->getUrl(),
                        'updatedAt' => $item->getUpdatedAt(),
                        'images' => $item->getImages(),
                        'priority' => $data->getPriority(),
                        'changeFrequency' => $data->getChangeFrequency(),
                    ]
                );
            }
        }

        return $items;
    }

    /**
     * {@inheritdoc }
     */
    protected function _initSitemapItems()
    {
        $sitemapItems = $this->itemProvider->getItems($this->getStoreId());
        $mappedItems = $this->mapToSitemapItem();
        $this->_sitemapItems = array_merge($sitemapItems, $mappedItems);

        $this->filterSitemapItemsByConfig();

        $this->_tags = [
            self::TYPE_INDEX => [
                self::OPEN_TAG_KEY => '<?xml version="1.0" encoding="UTF-8"?>' .
                    PHP_EOL .
                    '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' .
                    PHP_EOL,
                self::CLOSE_TAG_KEY => '</sitemapindex>',
            ],
            self::TYPE_URL => [
                self::OPEN_TAG_KEY => '<?xml version="1.0" encoding="UTF-8"?>' .
                    PHP_EOL .
                    '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"' .
                    ' xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">' .
                    PHP_EOL,
                self::CLOSE_TAG_KEY => '</urlset>',
            ],
        ];
    }

    /**
     * @return void
     */
    protected function filterSitemapItemsByConfig()
    {
        $ignoredURLs = $this->getIgnoredUrls();

        $ignoredURLs = !empty($ignoredURLs) ? json_decode($ignoredURLs, true) : false;
        if ($ignoredURLs) {
            foreach ($this->_sitemapItems as $key => $item) {
                foreach ($ignoredURLs as $URLObject) {
                    if ($item->getUrl() === $URLObject['url']) {
                        unset($this->_sitemapItems[$key]);
                    }
                }
            }
        }
    }

    /**
     * Get Ignored Url List
     * @return mixed
     */
    protected function getIgnoredUrls()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_IGNORED_URLS,
            ScopeInterface::SCOPE_STORE
        );
    }
}
