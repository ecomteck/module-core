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

namespace Ecomteck\Core\Model\Config\Structure;

use Magento\Config\Model\Config\Structure\Data as StructureData;
use Magento\Framework\UrlInterface;
use Ecomteck\Core\Helper\Validate as ConfigHelper;

/**
 * Plugin to add 'Module Information' group to each modules (before general group)
 *
 * Class Data
 * @package Ecomteck\Core\Model\Config\Structure
 */
class Data
{
    const DEV_ENV = ['localhost', 'dev', '127.0.0.1', '192.168.'];

    /**
     * @var UrlInterface
     */
    protected $_url;

    /**
     * @var \Ecomteck\Core\Helper\Validate
     */
    protected $_helper;

    /**
     * Data constructor.
     * @param UrlInterface $url
     * @param ConfigHelper $helper
     */
    public function __construct(
        UrlInterface $url,
        ConfigHelper $helper
    )
    {
        $this->_url = $url;
        $this->_helper = $helper;
    }

    /**
     * @param $moduleName
     * @param $sectionName
     * @return mixed
     */
    protected function getDynamicConfigGroups($moduleName, $sectionName)
    {
        $defaultFieldOptions = [
            'type' => 'text',
            'showInDefault' => '1',
            'showInWebsite' => '0',
            'showInStore' => '0',
            'sortOrder' => 1,
            'module_name' => $moduleName,
            'module_type' => $this->_helper->getModuleType($moduleName),
            'validate' => 'required-entry',
            '_elementType' => 'field',
            'path' => $sectionName . '/module'
        ];

        $fields = [];
        foreach ($this->getFieldList() as $id => $option) {
            $fields[$id] = array_merge($defaultFieldOptions, ['id' => $id], $option);
        }

        $dynamicConfigGroups['module'] = [
            'id' => 'module',
            'label' => __('Module Information'),
            'showInDefault' => '1',
            'showInWebsite' => '0',
            'showInStore' => '0',
            'sortOrder' => 1000,
            "_elementType" => "group",
            'path' => $sectionName,
            'children' => $fields
        ];

        return $dynamicConfigGroups;
    }

    /**
     * @param \Magento\Config\Model\Config\Structure\Data $object
     * @param array $config
     * @return array
     */
    public function beforeMerge(StructureData $object, array $config)
    {
        // temporary disable check key
        return [$config];

        $hostName = $this->_url->getBaseUrl();
        foreach (self::DEV_ENV as $env) {
            if (strpos($hostName, $env) !== false) {
                return [$config];
            }
        }

        if (isset($config['config']['system'])) {
            $sections = $config['config']['system']['sections'];
            foreach ($sections as $sectionId => $section) {
                if (isset($section['tab']) && ($section['tab'] == 'ecomteck') && ($section['id'] != 'ecomteck')) {
                    foreach ($this->_helper->getModuleList() as $moduleName) {
                        
                        if ($section['id'] != $this->_helper->getConfigModulePath($moduleName)) {
                            continue;
                        }

                        if (!$this->_helper->needActive($moduleName)) {
                            continue;
                        }

                        $dynamicGroups = $this->getDynamicConfigGroups($moduleName, $section['id']);
                        if (!empty($dynamicGroups)) {
                            $config['config']['system']['sections'][$sectionId]['children'] = $dynamicGroups + $section['children'];
                        }
                        break;
                    }
                }
            }
        }

        return [$config];
    }

    /**
     * @return array
     */
    protected function getFieldList()
    {
        return [
            'notice' => [
                'frontend_model' => 'Ecomteck\Core\Block\Adminhtml\System\Config\Message'
            ],
            'version' => [
                'type' => 'label',
                'label' => __('Version'),
                'frontend_model' => 'Ecomteck\Core\Block\Adminhtml\System\Config\Form\Field\Version'
            ],
//            'domain'      => [
//                'label'          => __('Register Domain'),
//                'frontend_class' => 'ecomteck-module-active-domain'
//            ],
            'name' => [
                'label' => __('Register Name'),
                'frontend_class' => 'ecomteck-module-active-field-free ecomteck-module-active-name'
            ],
            'email' => [
                'label' => __('Register Email'),
                'validate' => 'required-entry validate-email',
                'frontend_class' => 'ecomteck-module-active-field-free ecomteck-module-active-email',
                'comment' => 'This email will be used to create a new account at Ecomteck.com, Ecomteck help desk (to get premium support).'
            ],
            'product_key' => [
                'label' => __('Product Key'),
                'frontend_class' => 'ecomteck-module-active-field-key'
            ],
            'button' => [
                'frontend_model' => 'Ecomteck\Core\Block\Adminhtml\System\Config\Button'
            ]
        ];
    }
}