<?php
/**
 * Ecomteck
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Ecomteck.com license that is
 * available through the world-wide-web at this URL:
 * https://ecomteck.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Ecomteck
 * @package     Ecomteck_Core
 * @copyright   Copyright (c) 2018 Ecomteck (https://ecomteck.com/)
 * @license     https://ecomteck.com/LICENSE.txt
 */

namespace Ecomteck\Core\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

/**
 * Class NoticeType
 * @package Ecomteck\Core\Model\Config\Source
 */
class NoticeType implements ArrayInterface
{
    const TYPE_ANNOUNCEMENT = 'announcement';
    const TYPE_NEWUPDATE = 'new_update';
    const TYPE_MARKETING = 'marketing';

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $options = [];

        foreach ($this->toArray() as $value => $label) {
            $options[] = [
                'value' => $value,
                'label' => $label
            ];
        }

        return $options;
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return [
            self::TYPE_ANNOUNCEMENT => __('Announcement'),
            self::TYPE_NEWUPDATE    => __('New & Update extensions'),
            self::TYPE_MARKETING    => __('Promotions ')
        ];
    }
}
