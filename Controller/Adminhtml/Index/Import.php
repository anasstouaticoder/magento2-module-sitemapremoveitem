<?php
/**
 * Copyright (c) 2023
 * MIT License
 * Module AnassTouatiCoder_SitemapRemoveItem
 * Author Anass TOUATI anass1touati@gmail.com
 */
declare(strict_types=1);

namespace AnassTouatiCoder\SitemapRemoveItem\Controller\Adminhtml\Index;

use AnassTouatiCoder\SitemapRemoveItem\Block\Adminhtml\Form\Field\URIType;
use AnassTouatiCoder\SitemapRemoveItem\Model\Config;
use Magento\Framework\App\Request\Http as HttpRequest;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\File\Csv;
use Magento\Framework\Message\ManagerInterface;

class Import extends DataMigration
{
    /**
     * @var JsonFactory
     */
    protected $jsonFactory;

    /**
     * @var ManagerInterface
     */
    protected $messageManager;

    /**
     * @param HttpRequest $request
     * @param FileFactory $fileFactory
     * @param Csv $csv
     * @param Config $config
     * @param JsonFactory $jsonFactory
     * @param ManagerInterface $messageManager
     */
    public function __construct(
        HttpRequest      $request,
        FileFactory      $fileFactory,
        Csv              $csv,
        Config           $config,
        JsonFactory      $jsonFactory,
        ManagerInterface $messageManager
    )
    {
        $this->jsonFactory = $jsonFactory;
        $this->messageManager = $messageManager;
        parent::__construct(
            $request,
            $fileFactory,
            $csv,
            $config
        );
    }

    public function execute()
    {
        $result = $this->jsonFactory->create();
        if (!$this->request->isPost()) {
            $message = 'No file uploaded';
            $this->messageManager->addErrorMessage(__($message));

        } else {
            try {
                // Get uploaded file
                $file = $this->request->getFiles('import_file')['tmp_name'];
                $importData = $this->csv->getData($file);

                if (!empty($importData) && isset($importData[0][0]) && $importData[0][0] === 'URI Value') {
                    array_shift($importData);
                }

                $dataToImport = [];
                foreach ($importData as $key => $data) {
                    if ((isset($data[1]) && key_exists($data[1], URIType::URI_TYPE_LIST))) {
                        $dataToImport[uniqid('_')] = ['uri'=> $data[0],'uri_type' => [$data[1]]];
                    }
                }
                if ($items = count($dataToImport) > 1) {
                    $this->config->saveCSVContent($dataToImport, $this->websiteId, $this->storeId);
                    $message = 'Items have been imported.';
                    $this->messageManager->addSuccessMessage(__($message));
                } else {
                    $message = 'No valid items were found';
                    $this->messageManager->addErrorMessage(__($message));
                }
            } catch (LocalizedException|\Exception $e) {
                $message = 'An error occurred';
                $this->messageManager->addErrorMessage(__('An error occurred while importing CSV file: %1', $e->getMessage()));
            }
        }
        return $result->setData([$message]);
    }
}
