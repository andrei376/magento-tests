<?php

namespace Tremend\Test1\Controller\Index;


use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\Phrase;
use Magento\Framework\View\Result\PageFactory;
use Tremend\Test1\Model\BlogRepository;

class View extends \Magento\Framework\App\Action\Action
{
    protected $resultPageFactory;
    private $logger;
    protected $blogRepository;

    public function __construct(
        Context $context,
        \Psr\Log\LoggerInterface $logger = null,
        \Tremend\Test1\Model\BlogFactory $blogFactory,
        BlogRepository $blogRepository,
        PageFactory $resultPageFactory,
    ) {
        parent::__construct($context);

        $this->resultPageFactory = $resultPageFactory;
        $this->blogFactory = $blogFactory;
        $this->blogRepository = $blogRepository;

        $this->logger = $logger ?: ObjectManager::getInstance()
            ->get(\Psr\Log\LoggerInterface::class);
    }

    public function execute()
    {
        $blogId = (int) $this->getRequest()->getParam('id');

        // throw new NotFoundException(new Phrase("blog $blogId not found"));


        try {
            // $blog = $this->blogFactory->create()->load($blogId);
            $blog = $this->blogRepository->getById($blogId);

            // var_dump($blog->getId());
            // var_dump($blog);

            /*if (is_null($blog->getId())) {
                throw new NoSuchEntityException(new Phrase("blog $blogId not found"));
            }*/
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
            $this->logger->error($e);
            // return 'No id';
            $this->messageManager->addError(__('Invalid blog entry.'));
            return $this->resultRedirectFactory->create()->setPath('blog/index');
        } catch (\Exception $e) {
            $this->logger->critical($e);

            return 'Exception';
        }

        // Render page
        try {
            $page = $this->resultPageFactory->create();
            // $this->viewHelper->prepareAndRender($page, $blogId, $this, $params);
            return $page;
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
            $this->logger->error($e);
            return 'No id';
        } catch (\Exception $e) {
            $this->logger->critical($e);

            return 'Exception';
        }
    }
}
