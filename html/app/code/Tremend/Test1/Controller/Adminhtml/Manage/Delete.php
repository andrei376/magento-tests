<?php
namespace Tremend\Test1\Controller\Adminhtml\Manage;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;

class Delete extends Action
{
    public $blogFactory;

    public function __construct(
        Context $context,
        \Tremend\Test1\Model\BlogFactory $blogFactory
    ) {
        $this->blogFactory = $blogFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $id = $this->getRequest()->getParam('id');
        try {
            $blogModel = $this->blogFactory->create();
            $blogModel->load($id);
            $blogModel->delete();
            $this->messageManager->addSuccessMessage(__('You deleted the blog.'));
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }
        return $resultRedirect->setPath('*/*/');
    }

    public function _isAllowed()
    {
        return $this->_authorization->isAllowed('Tremend_Test1::delete');
    }
}
