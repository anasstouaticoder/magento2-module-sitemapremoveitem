<?php
/**
 * Copyright (c) 2022
 * MIT License
 * Module AnassTouatiCoder_SitemapRemoveItem
 * Author Anass TOUATI anass1touati@gmail.com
 */

declare(strict_types=1);

namespace AnassTouatiCoder\SitemapRemoveItem\Block\Adminhtml\Form\Field;

use AnassTouatiCoder\Base\Block\Adminhtml\Form\FieldArray;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;

class SiteMapCleanerList extends FieldArray
{
    /**
     * @var string
     */
    protected $dataConfig = 'atouati_data_config_sitemapremoveitem';

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
}
