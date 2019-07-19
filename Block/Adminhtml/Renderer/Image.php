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

namespace Ecomteck\Core\Block\Adminhtml\Renderer;

/**
 * Renderer image for admin form
 * Add media path to url
 *
 * Class Image
 * @package Ecomteck\Core\Block\Adminhtml\Renderer
 */
class Image extends \Magento\Framework\Data\Form\Element\Image
{
    /**
     * Get image preview url
     *
     * @return string
     */
    protected function _getUrl()
    {
        $url = parent::_getUrl();

        if ($this->getPath()) {
            $url = $this->getPath() . '/' . trim($url, '/');
        }

        return $url;
    }
}
