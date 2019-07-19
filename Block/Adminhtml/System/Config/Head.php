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

namespace Ecomteck\Core\Block\Adminhtml\System\Config;

/**
 * Class Head
 * @package Ecomteck\Core\Block\Adminhtml\System\Config
 */
class Head extends \Magento\Config\Block\System\Config\Form\Field
{
	/**
	 * Render text
	 *
	 * @param  \Magento\Framework\Data\Form\Element\AbstractElement $element
	 * @return string
	 */
	public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
	{
		$html = '';
		if ($element->getComment()) {
			$html .= '<div style="margin: auto; width: 40%;padding: 10px;">' . $element->getComment() . '</div>';
		}

		return $html;
	}

	/**
	 * Return element html
	 *
	 * @param  \Magento\Framework\Data\Form\Element\AbstractElement $element
	 * @return string
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
	{
		return $this->_toHtml();
	}
}
