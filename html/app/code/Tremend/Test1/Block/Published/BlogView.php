<?php

namespace Tremend\Test1\Block\Published;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Phrase;

class BlogView extends \Magento\Framework\View\Element\Template implements IdentityInterface
{
    public $blogFactory;
    private $logger;

    const CACHE_TAG = 'tremend_blogmanager_blog';

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Tremend\Test1\Model\BlogFactory $blogFactory,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Psr\Log\LoggerInterface $logger = null,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Framework\Controller\Result\RedirectFactory $redirectFactory,
        array $data = []
    ) {
        $this->blogFactory = $blogFactory;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->productFactory = $productFactory;
        $this->customerFactory = $customerFactory;

        $this->logger = $logger ?: ObjectManager::getInstance()
            ->get(\Psr\Log\LoggerInterface::class);

        $this->messageManager = $messageManager;
        $this->redirectFactory = $redirectFactory;

        parent::__construct($context, $data);
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG.'_'.$this->getBlog()->getId()];
    }

    public function getCacheKeyInfo()
    {
        $info = parent::getCacheKeyInfo();

        $info['id'] = $this->getBlog()->getId();

        return $info;
    }

    public function getWelcomeText()
    {
        return 'Hello World';
    }

    public function getBlog()
    {
        try {
            $blogId = $this->getRequest()->getParam('id');
            $blog = $this->blogFactory->create()->load($blogId);

            // var_dump($blog->getId());
            // var_dump($blog);

            if (is_null($blog->getId())) {
                throw new NoSuchEntityException(new Phrase("blog $blogId not found"));
            }

            return $blog;
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
            $this->logger->error($e);
            // return 'No id';
            $this->messageManager->addError(__('Invalid blog entry.'));
            return $this->redirectFactory->create()->setPath('blog/index');
        } catch (\Exception $e) {
            $this->logger->critical($e);

            return 'Exception';
        }
    }

    public function getProduct($id)
    {
        $product = $this->productFactory->create()->load($id);

        return $product->getName();
    }

    public function getAuthor($userId)
    {
        if ($userId) {
            $customer = $this->customerFactory->create()->load($userId);
            if ($customer && $customer->getId()) {
                return $customer->getName();
            }
        }
        return __('Admin');
    }

    public function getRelatedProducts($ids)
    {
        $collection = $this->productCollectionFactory->create()
            ->addAttributeToSelect('name')
            ->addAttributeToFilter('entity_id', ['in' => $ids]);

        echo($collection->getSelect());

        $products = [];
        foreach ($collection as $model) {
            $products[] = ['value'=>$model->getId(), 'label'=>$model->getName()];
        }

        return $products;
    }
}
