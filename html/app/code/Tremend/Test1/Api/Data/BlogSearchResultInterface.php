<?php

namespace Tremend\Test1\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface BlogSearchResultInterface extends SearchResultsInterface
{
    /**
     * @return BlogInterface[]
     */
    public function getItems();

    /**
     * @param BlogInterface[] $items
     * @return void
     */
    public function setItems(array $items);
}
