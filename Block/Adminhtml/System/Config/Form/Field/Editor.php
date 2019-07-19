<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ecomteck\Core\Block\Adminhtml\System\Config\Form\Field;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Backend\Block\Template\Context;
use Magento\Cms\Model\Wysiwyg\Config as WysiwygConfig;

class Editor extends Field
{
    /**
     * @var WysiwygConfig
     */
    private $wysiwygConfig;

    /**
     * @param Context $context
     * @param WysiwygConfig $wysiwygConfig
     * @param array $data
     */
    public function __construct(Context $context, WysiwygConfig $wysiwygConfig, array $data = [])
    {
        parent::__construct($context, $data);
        $this->wysiwygConfig = $wysiwygConfig;
    }

    /**
     * Enable wysiwyg editor for the element.
     *
     * @param AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        $element->addData([
            'wysiwyg' => true,
            'config'  => $this->wysiwygConfig->getConfig($element)
        ]);
        return parent::_getElementHtml($element);
    }
}
