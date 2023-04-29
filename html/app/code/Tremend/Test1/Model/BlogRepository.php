<?php

namespace Tremend\Test1\Model;

use Tremend\Test1\Api\BlogRepositoryInterface;
use Tremend\Test1\Api\Data\BlogInterface;
use Tremend\Test1\Api\Data\BlogSearchResultInterface;
use Tremend\Test1\Api\Data\BlogSearchResultInterfaceFactory;
use Tremend\Test1\Model\ResourceModel\Blog\CollectionFactory as BlogCollectionFactory;
use Tremend\Test1\Model\ResourceModel\Blog\Collection;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\NoSuchEntityException;

class BlogRepository implements BlogRepositoryInterface
{
    /**
     * @var BlogFactory
     */
    private $blogFactory;

    /**
     * @var BlogCollectionFactory
     */
    private $blogCollectionFactory;

    /**
     * @var BlogSearchResultInterfaceFactory
     */
    private $searchResultFactory;

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    public function __construct(
        BlogFactory $blogFactory,
        BlogCollectionFactory $blogCollectionFactory,
        BlogSearchResultInterfaceFactory $blogSearchResultInterfaceFactory,
        CollectionProcessorInterface $collectionProcessor,
        \Tremend\Test1\Model\ResourceModel\Blog $resourceBlog
    ) {
        $this->blogFactory = $blogFactory;
        $this->resourceBlog = $resourceBlog;
        $this->blogCollectionFactory = $blogCollectionFactory;
        $this->searchResultFactory = $blogSearchResultInterfaceFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    // ... getById, save and delete methods listed above ...
    public function getById($id)
    {
        $blog = $this->blogFactory->create();
        // $blog->getResource()->load($blog, $id);
        $this->resourceBlog->load($blog, $id);

        if (! $blog->getId()) {
            throw new NoSuchEntityException(__('Unable to find blog with ID "%1"', $id));
        }

        return $blog;
    }

    public function save(BlogInterface $blog)
    {
        $blog->getResource()->save($blog);

        return $blog;
    }

    public function delete(BlogInterface $blog)
    {
        $blog->getResource()->delete($blog);
    }


    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->blogCollectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);

        $searchResults = $this->searchResultFactory->create();

        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());

        return $searchResults;
    }
}
