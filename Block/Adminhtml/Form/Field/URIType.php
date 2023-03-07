<?php
/**
 * Copyright (c) 2023
 * MIT License
 * Module AnassTouatiCoder_SitemapRemoveItem
 * Author Anass TOUATI anass1touati@gmail.com
 */

declare(strict_types=1);

namespace AnassTouatiCoder\SitemapRemoveItem\Block\Adminhtml\Form\Field;

use Magento\Framework\View\Element\Html\Select;

class URIType extends Select
{
    /**
     * Add select options
     *
     * @return array
     */
    public function addOptions(): array
    {
        return [
            ['value' => 'contains', 'label' => __('Contains')],
            ['value' => 'equals', 'label' => __('Equals')]
        ];
    }

    /**
     * {@inheritdoc }
     */
    protected function _toHtml()
    {
        if (!$this->getOptions()) {
            $this->setOptions($this->addOptions());
        }

        return parent::_toHtml();
    }

    /**
     * Set inputName to apply it in request params
     *
     * @param string $value
     * @return mixed
     */
    public function setInputName($value)
    {
        return $this->setName($value . '[]');
    }
}
