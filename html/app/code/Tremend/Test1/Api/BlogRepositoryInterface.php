<?php

namespace Tremend\Test1\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Tremend\Test1\Api\Data\BlogInterface;
use Tremend\Test1\Api\Data\BlogSearchResultInterface;

interface BlogRepositoryInterface
{
    /**
     * @param int $id
     * @return BlogInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($id);

    /**
     * @param BlogInterface $blog
     * @return BlogInterface
     */
    public function save(BlogInterface $blog);

    /**
     * @param BlogInterface $blog
     * @return void
     */
    public function delete(BlogInterface $blog);

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return BlogSearchResultInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);
}
