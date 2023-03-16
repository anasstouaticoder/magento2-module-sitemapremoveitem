<?php
/**
 * Copyright (c) 2023
 * MIT License
 * Module AnassTouatiCoder_SitemapRemoveItem
 * Author Anass TOUATI anass1touati@gmail.com
 */
declare(strict_types=1);

namespace AnassTouatiCoder\SitemapRemoveItem\Controller\Adminhtml\Index;

use AnassTouatiCoder\SitemapRemoveItem\Model\Config;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Request\Http as HttpRequest;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\File\Csv;

abstract class DataMigration implements HttpPostActionInterface
{
    /**
     * @var int
     */
    protected $websiteId;

    /**
     * @var int
     */
    protected $storeId;

    /**
     * @var HttpRequest
     */
    protected $request;

    /**
     * @var FileFactory
     */
    protected $fileFactory;

    /**
     * @var Csv
     */
    protected $csv;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @param HttpRequest $request
     * @param FileFactory $fileFactory
     * @param Csv $csv
     * @param Config $config
     */
    public function __construct(
        HttpRequest   $request,
        FileFactory   $fileFactory,
        Csv           $csv,
        Config        $config
    ) {
        $this->request = $request;
        $this->fileFactory = $fileFactory;
        $this->csv = $csv;
        $this->config = $config;
        $this->initScopeIds();
    }

    protected function initScopeIds(): void
    {
        $this->websiteId = (int) $this->request->getParam('webiste_id');
        $this->storeId = (int) $this->request->getParam('store_id');
    }
}
