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
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Request\Http as HttpRequest;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\File\Csv;
use Magento\Framework\Filesystem\Io\File;

class Export extends DataMigration
{
    /**
     * @return \Magento\Framework\App\ResponseInterface|ResultInterface|void
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function execute()
    {
        $fileName = 'Sitemap.csv';

        // Retrieve the data from core_config_data
        $data = $this->config->getCSVContent($this->websiteId, $this->storeId);

        $csvData[] = ['URI Value','URI Type'];

        foreach ($data as $value) {
            $csvData[] = [$value['uri'], implode(',', $value['uri_type'])];
        }

        $this->csv->appendData(DirectoryList::MEDIA . '/' . $fileName, $csvData);

        $content = [
            'type' => 'filename',
            'value' => $fileName,
            'rm' => true
        ];

        $this->fileFactory->create($fileName, $content, DirectoryList::MEDIA);
    }
}
