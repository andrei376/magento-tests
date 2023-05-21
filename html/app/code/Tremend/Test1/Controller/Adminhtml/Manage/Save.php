<?php

namespace Tremend\Test1\Controller\Adminhtml\Manage;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Cache\Manager as CacheManager;
use Magento\Framework\App\Cache\TypeListInterface as CacheTypeListInterface;
use Magento\Framework\App\CacheInterface as CacheInterface;

class Save extends Action
{
    public $blogFactory;
    protected $cache;
    protected $cacheManager;
    protected $cacheInterface;

    public function __construct(
        Context $context,
        \Tremend\Test1\Model\BlogFactory $blogFactory,
        CacheTypeListInterface $cache,
        CacheManager $cacheManager,
        \Psr\Log\LoggerInterface $logger,
        CacheInterface $cacheInterface
    ) {
        $this->blogFactory = $blogFactory;
        $this->cache = $cache;
        $this->cacheManager = $cacheManager;
        $this->cacheInterface = $cacheInterface;

        $this->logger = $logger;

        parent::__construct($context);
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getParams();

        if (!isset($data['related_products'])) {
            $data['related_products'] = [];
        }

        // var_dump($data);

        if (isset($data['entity_id']) && $data['entity_id']) {
            $model = $this->blogFactory->create()->load($data['entity_id']);

            $model->addData($data);
            $model->save();
            $this->messageManager->addSuccess(__('You have updated the blog successfully.'));
        } else {
            $model = $this->blogFactory->create();
            $model->setTitle($data['title'])
                ->setContent($data['content'])
                ->setStatus($data['status'])
                ->setUserId($data['user_id']);
            if (isset($data['products']) && is_array($data['products'])) {
                $model->setProducts(implode(',', $data['products']));
            } else {
                $model->setProducts('');
            }
            $model->save();
            $this->messageManager->addSuccess(__('You have successfully created the blog.'));
        }

        // $availableCacheTypes = $this->cache->getTypes();

        // var_dump($availableCacheTypes);

        // $this->cache->invalidate('full_page');
        // $this->cache->cleanType('full_page');
        // $this->cache->cleanType('block_html');

        /*$this->cacheContext->registerTags([NavigationScreen::CACHE_TAG]);
        $this->_eventManager->dispatch('clean_cache_by_tags', ['object' => $this->cacheContext]);
        private \Magento\Framework\Indexer\CacheContext $cacheContext;
        ^ pentru clean cache by tags pentru full pache cache built-in && Varnish

        */


        $this->cacheInterface->clean(['tremend_blogmanager_blog_'.$model->getId()]);

        return $resultRedirect->setPath('*/*/');
    }

    public function _isAllowed()
    {
        return $this->_authorization->isAllowed('Tremend_Test1::save');
    }
}
