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

namespace Ecomteck\Core\Block\Adminhtml;

use Magento\Framework\App\Cache\Type\Config;
use Magento\Framework\Module\FullModuleList;
use Magento\Framework\View\Element\Template\Context;

/**
 * Class Extensions
 */
class Extensions extends \Magento\Framework\View\Element\Template
{
    /**
     * Cache group Tag
     */
    const CACHE_GROUP = Config::TYPE_IDENTIFIER;

    /**
     * Prefix for cache key of block
     */
    const CACHE_KEY_PREFIX = 'ecomteck_';

    /**
     * Cache tag
     */
    const CACHE_TAG = 'extensions';

    /**
     * Ecomteck api url to get extension json
     */
    const API_URL = 'https://ecomteck.com/api/getVersions.json';

    /**
     * @var string
     */
    protected $_template = 'extensions.phtml';

    /**
     * @var \Magento\Framework\Module\FullModuleList
     */
    private $moduleList;

    /**
     * Extensions constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\Module\FullModuleList $moduleList
     * @param array $data
     */
    public function __construct(
        Context $context,
        FullModuleList $moduleList,
        array $data = []
    )
    {
        parent::__construct($context, $data);

        $this->moduleList = $moduleList;

        $this->addData(
            ['cache_lifetime' => 86400, 'cache_tags' => [self::CACHE_TAG]]
        );
    }

    /**
     * @return array
     */
    public function getInstalledModules()
    {
        $ecomteck_modules = array();
        foreach ($this->moduleList->getAll() as $moduleName => $info) {
            if (strpos($moduleName, 'Ecomteck') !== false) {
                $ecomteck_modules[$moduleName] = $info['setup_version'];
            }
        }

        return $ecomteck_modules;
    }

    /**
     * @return bool|mixed|string
     */
    public function getAvailableModules()
    {
        $result = $this->_loadCache();
        if (!$result) {
            try {
                $result = file_get_contents(self::API_URL);
                $this->_saveCache($result);
            } catch (\Exception $e) {
                return false;
            }
        }

        $result = json_decode($result, true); //true return array otherwise object

        return $result;
    }
}
