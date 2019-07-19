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

namespace Ecomteck\Core\Controller\Adminhtml\Index;

/**
 * Class Index
 * @package Ecomteck\Core\Controller\Adminhtml\Index
 */
class Index extends \Magento\Backend\App\Action
{
	/**
	 * Authorization level of a basic admin session
	 */
	const ADMIN_RESOURCE = 'Ecomteck_Core::partners';

	/**
	 * @var \Magento\Framework\View\Result\PageFactory
	 */
	protected $resultPageFactory;

	/**
	 * @param \Magento\Backend\App\Action\Context $context
	 * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
	 */
	public function __construct(
		\Magento\Backend\App\Action\Context $context,
		\Magento\Framework\View\Result\PageFactory $resultPageFactory
	)
	{
		$this->resultPageFactory = $resultPageFactory;

		parent::__construct($context);
	}

	/**
	 * @return \Magento\Backend\Model\View\Result\Page
	 */
	public function execute()
	{
		/** @var \Magento\Backend\Model\View\Result\Page $resultPage */
		$resultPage = $this->resultPageFactory->create();
		$resultPage->setActiveMenu('Ecomteck_Core::partners');
		$resultPage->addBreadcrumb(__('Partners'), __('Partners'));
		$resultPage->getConfig()->getTitle()->prepend(__('Ecomteck Marketplace'));

		return $resultPage;
	}
}
