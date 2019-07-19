<?php
/**
 * Ecomteck
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the ecomteck.com license that is
 * available through the world-wide-web at this URL:
 * https://ecomteck.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Ecomteck
 * @package     Ecomteck_Smtp
 * @copyright   Copyright (c) 2018 Ecomteck (https://ecomteck.com/)
 * @license     https://ecomteck.com/LICENSE.txt
 */

namespace Ecomteck\Core\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Config\Model\ResourceModel\Config;
use Magento\Framework\App\Config\ReinitableConfigInterface;
use Ecomteck\Core\Helper\Validate;
use Ecomteck\Core\Model\ActivateFactory;

/**
 * Class Activate
 * @package Ecomteck\Core\Controller\Adminhtml\Index
 */
class Activate extends Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Ecomteck_Core::activate';

    /**
     * @var \Ecomteck\Smtp\Model\ActivateFactory
     */
    protected $activateFactory;

    /**
     * @var \Magento\Config\Model\ResourceModel\Config
     */
    protected $resourceConfig;

    /**
     * @var \Ecomteck\Core\Helper\AbstractData
     */
    protected $_coreHelper;

    /**
     * @var string
     */
    protected $_moduleConfigPath;

    /**
     * Application config
     *
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_appConfig;

    /**
     * Activate constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Config\Model\ResourceModel\Config $resourceConfig
     * @param \Magento\Framework\App\Config\ReinitableConfigInterface $config
     * @param \Ecomteck\Core\Helper\Validate $helper
     * @param \Ecomteck\Core\Model\ActivateFactory $activateFactory
     */
    public function __construct(
        Context $context,
        Config $resourceConfig,
        ReinitableConfigInterface $config,
        Validate $helper,
        ActivateFactory $activateFactory
    )
    {
        $this->activateFactory = $activateFactory;
        $this->resourceConfig  = $resourceConfig;
        $this->_appConfig      = $config;
        $this->_coreHelper     = $helper;

        parent::__construct($context);
    }

    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $params = $this->getRequest()->getPost();
        if (!isset($params['extension'])) {
            return $this->jsonResponse([
                'success' => false,
                'message' => __('Invalid data.')
            ]);
        }

        if ($path = $this->_coreHelper->getConfigModulePath($params['extension'])) {
            $this->_moduleConfigPath = $path;
        }

        $activateModel = $this->activateFactory->create();
        $result        = $activateModel->activate($params);
        if ($result['success']) {
            $result['active'] = true;

            $configSave = ['active' => 1];
            if (isset($result['key']) && $result['key']) {
                $configSave['product_key'] = $result['key'];
            }

            if ($this->_coreHelper->getModuleType($params['extension']) == '1') {
                $freeInfo = [
                    'email'     => $params['email'],
                    'name'      => $params['name'],
                    'create'    => $params['create'],
                    'subscribe' => $params['subscribe']
                ];
                foreach ($freeInfo as $code => $value) {
                    $this->saveConfig('free/module/' . $code, $value, true);
                }

                $configSave += $freeInfo;
            }

            $this->saveConfig($configSave);
            $this->_appConfig->reinit();
        }

        return $this->jsonResponse($result);
    }

    /**
     * @param $result
     * @return mixed
     */
    protected function jsonResponse($result)
    {
        return $this->getResponse()->representJson(
            Validate::jsonEncode($result)
        );
    }

    /**
     * @param $pathId
     * @param null $value
     * @param bool $isFullPath
     * @param string $scope
     * @param int $scopeId
     * @return $this
     */
    protected function saveConfig($pathId, $value = null, $isFullPath = false, $scope = 'default', $scopeId = 0)
    {
        if (is_array($pathId)) {
            foreach ($pathId as $path => $pathValue) {
                $this->saveConfig($path, $pathValue, $isFullPath, $scope, $scopeId);
            }

            return $this;
        }

        $this->resourceConfig->saveConfig(
            $isFullPath ? $pathId : $this->buildConfigPath($pathId),
            $value,
            $scope,
            $scopeId
        );

        return $this;
    }

    /**
     * @param $pathId
     * @param bool $isFullPath
     * @param string $scope
     * @param int $scopeId
     * @return $this
     */
    protected function deleteConfig($pathId, $isFullPath = false, $scope = 'default', $scopeId = 0)
    {
        if (is_array($pathId)) {
            foreach ($pathId as $path) {
                $this->deleteConfig($path, $isFullPath, $scope, $scopeId);
            }

            return $this;
        }

        $this->resourceConfig->deleteConfig(
            $isFullPath ? $pathId : $this->buildConfigPath($pathId),
            $scope,
            $scopeId
        );

        return $this;
    }

    /**
     * @param $pathId
     * @return string
     */
    protected function buildConfigPath($pathId)
    {
        return $this->_moduleConfigPath . '/module/' . $pathId;
    }
}
