<?php
namespace Tremend\Test1\Controller\Manage;

use Magento\Customer\Controller\AbstractAccount;
use Magento\Framework\App\Action\Context;
use Magento\Customer\Model\Session;

class Save extends AbstractAccount
{
    public $blogFactory;
    public $helper;
    public $messageManager;

    public function __construct(
        Context $context,
        \Tremend\Test1\Model\BlogFactory $blogFactory,
        \Tremend\Test1\Helper\Data $helper,
        \Magento\Framework\Message\ManagerInterface $messageManager
    ) {
        $this->blogFactory = $blogFactory;
        $this->helper = $helper;
        $this->messageManager = $messageManager;
        parent::__construct($context);
    }

    public function execute()
    {
        $data = $this->getRequest()->getParams();
        $customerId = $this->helper->getCustomerId();

        if (isset($data['id']) && $data['id']) {
            $isAuthorised = $this->blogFactory->create()
                ->getCollection()
                ->addFieldToFilter('user_id', $customerId)
                ->addFieldToFilter('entity_id', $data['id'])
                ->getSize();
            if (!$isAuthorised) {
                $this->messageManager->addError(__('You are not authorised to edit this blog.'));
                return $this->resultRedirectFactory->create()->setPath('blog/manage');
            } else {
                $model = $this->blogFactory->create()->load($data['id']);
                $model->setTitle($data['title'])
                    ->setContent($data['content'])
                    ->save();
                $this->messageManager->addSuccess(__('You have updated the blog successfully.'));
            }
        } else {
            $model = $this->blogFactory->create();
            $model->setData($data);
            $model->setUserId($customerId);
            $model->save();
            $this->messageManager->addSuccess(__('Blog saved successfully.'));
        }

        return $this->resultRedirectFactory->create()->setPath('blog/manage');
    }
}
