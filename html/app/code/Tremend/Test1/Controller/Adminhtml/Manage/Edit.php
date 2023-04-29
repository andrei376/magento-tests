<?php

namespace Tremend\Test1\Controller\Adminhtml\Manage;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;

class Edit extends Action
{
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Tremend\Test1\Model\BlogFactory $blogFactory
    ) {
        parent::__construct($context);
        $this->coreRegistry = $coreRegistry;
        $this->blogFactory = $blogFactory;
    }

    public function execute()
    {
        /*$resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->getConfig()->getTitle()->prepend(__("Edit Blog"));
        return $resultPage;*/

        $blogId = $this->getRequest()->getParam('id');
        $blogModel = $this->blogFactory->create()->load($blogId);
        $this->coreRegistry->register('blog_data', $blogModel);
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->getConfig()->getTitle()->prepend(__("Edit Blog"));
        return $resultPage;
    }

    public function _isAllowed()
    {
        return $this->_authorization->isAllowed('Tremend_Test1::edit');
    }
}
