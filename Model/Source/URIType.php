<?php
/**
 * Copyright (c) 2023
 * MIT License
 * Module AnassTouatiCoder_SitemapRemoveItem
 * Author Anass TOUATI anass1touati@gmail.com
 */
declare(strict_types=1);

namespace AnassTouatiCoder\SitemapRemoveItem\Model\Source;

class URIType implements \Magento\Framework\Data\OptionSourceInterface
{
    public const URI_TYPE_LIST =
        [
            'contains' => 'Contains',
            'equals' => 'Equals'
        ];

    /**
     * @inheritDoc
     */
    public function toOptionArray()
    {
        $toOptionArray = [];
        foreach (self::URI_TYPE_LIST as $value => $label) {
            $toOptionArray[] = ['value' => $value, 'label' => __($label)];
        }

        return $toOptionArray;
    }
}
