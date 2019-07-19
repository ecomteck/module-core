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

namespace Ecomteck\Core\Observer;

use Magento\Framework\Event\ObserverInterface;

/**
 * Class PredispatchAdminActionControllerObserver
 * @package Ecomteck\Core\Observer
 */
class PredispatchAdminActionControllerObserver implements ObserverInterface
{
	/**
	 * @type \Ecomteck\Core\Model\FeedFactory
	 */
	protected $_feedFactory;

	/**
	 * @type \Magento\Backend\Model\Auth\Session
	 */
	protected $_backendAuthSession;

	/**
	 * @param \Ecomteck\Core\Model\FeedFactory $feedFactory
	 * @param \Magento\Backend\Model\Auth\Session $backendAuthSession
	 */
	public function __construct(
		\Ecomteck\Core\Model\FeedFactory $feedFactory,
		\Magento\Backend\Model\Auth\Session $backendAuthSession
	)
	{
		$this->_feedFactory        = $feedFactory;
		$this->_backendAuthSession = $backendAuthSession;
	}

	/**
	 * @param \Magento\Framework\Event\Observer $observer
	 */
	public function execute(\Magento\Framework\Event\Observer $observer)
	{
		if ($this->_backendAuthSession->isLoggedIn()) {
			/* @var $feedModel \Ecomteck\Core\Model\Feed */
			$feedModel = $this->_feedFactory->create();
			$feedModel->checkUpdate();
		}
	}
}
