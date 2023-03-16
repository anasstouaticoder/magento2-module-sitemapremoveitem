<?php
/**
 * Copyright (c) 2022
 * MIT License
 * Module AnassTouatiCoder_SitemapRemoveItem
 * Author Anass TOUATI anass1touati@gmail.com
 */

declare(strict_types=1);

namespace AnassTouatiCoder\SitemapRemoveItem\Block\Adminhtml\Form\Field;

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;

class SiteMapCleanerList extends AbstractFieldArray
{
    /*
     * Additional buttons
     */
    private const ADDITIONAL_BUTTON_LIST = [
        'sitemap_remove_item_import_button' => [
            'label' => 'Import',
            'class' => 'action-default scalable',
            'data_attribute' => [
                'mage-init' => [
                    'AnassTouatiCoder_SitemapRemoveItem/js/import-button' => [
                        'url' => 'atouatiSitemap/index/import',
                        'content' => '<p>All existing items in the list will be replaced with csv file content.</p>'
                    ]
                ]
            ]
        ],
        'sitemap_remove_item_export_button' => [
            'label' => 'Export',
            'class' => 'action-default scalable secondary',
            'data_attribute' => [
                'mage-init' => [
                    'AnassTouatiCoder_SitemapRemoveItem/js/export-button' => [
                        'url' => 'atouatiSitemap/index/export'
                    ]
                ]
            ]
        ]
    ];

    /**
     * @var URIType
     */
    private $uriTypeRenderer;

    /**
     * Prepare rendering the new field by adding all the needed columns
     */
    protected function _prepareToRender()
    {
        $this->addColumn('uri', [
            'label' => __('URI'),
            'class' => 'required-entry',
            'size' => '40px',
        ]);
        $this->addColumn('uri_type', [
            'label' => __('URI Match Type'),
            'renderer' => $this->getURITypeRenderer(),

        ]);
        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add New URI');
    }

    /**
     * Get URI type renderer block
     *
     * @return URIType
     * @throws LocalizedException
     */
    protected function getURITypeRenderer(): URIType
    {
        if (!$this->uriTypeRenderer) {
            $this->uriTypeRenderer = $this->getLayout()->createBlock(
                URIType::class,
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
            $this->uriTypeRenderer->setClass('URI_match_select admin__control-select');
        }
        return $this->uriTypeRenderer;
    }

    /**
     * {@inheritDoc }
     */
    protected function _prepareArrayRow(DataObject $row)
    {
        $optionExtraAttr = [];
        $optionExtraAttr['option_' . $this->getURITypeRenderer()->calcOptionHash($row->getData('uri_type')[0])] =
            'selected="selected"';
        $row->setData(
            'option_extra_attrs',
            $optionExtraAttr
        );
    }

    /**
     * Prepare the layout
     *
     * @return $this
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        foreach (self::ADDITIONAL_BUTTON_LIST as $buttonAlias => $buttonData) {
            $this->getGenerateComponentData($buttonData);
            $this->setChild(
                $buttonAlias,
                $this->getLayout()->createBlock(\Magento\Backend\Block\Widget\Button::class)->setData($buttonData)
            );
        }

        return $this;
    }

    /**
     * Add import export buttons
     * @return string
     * @throws \Exception
     */
    protected function _toHtml()
    {
        return parent::_toHtml() . $this->getChildHtml();
    }

    /**
     * Generate dynamic data for the component
     *
     * @param array $element
     * @return void
     */
    private function getGenerateComponentDAta(array &$element): void
    {
        $jsComponentName = array_key_first($element['data_attribute']['mage-init']);
        $element['data_attribute']['mage-init'][$jsComponentName]['url'] =
            $this->getUrl($element['data_attribute']['mage-init'][$jsComponentName]['url']);

        $element['data_attribute']['mage-init'][$jsComponentName]['webSiteId'] =
            $this->getRequest()->getParam('website');
        $element['data_attribute']['mage-init'][$jsComponentName]['storeId'] =
            $this->getRequest()->getParam('store');
        $element['data_attribute']['mage-init'][$jsComponentName]['formKey'] = $this->getFormKey();
    }
}
