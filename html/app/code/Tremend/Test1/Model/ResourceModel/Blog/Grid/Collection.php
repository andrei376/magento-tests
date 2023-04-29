<?php

namespace Tremend\Test1\Model\ResourceModel\Blog\Grid;

use Magento\Framework\Api\Search\SearchResultInterface;
use Magento\Framework\Search\AggregationInterface;
use Magento\Variable\Model\VariableFactory;
use Tremend\Test1\Model\ResourceModel\Blog\Collection as BlogCollection;
use Magento\Framework\Data\Collection\EntityFactoryInterface;
use Psr\Log\LoggerInterface;
use Magento\Framework\Data\Collection\Db\FetchStrategyInterface;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Tremend\Test1\Model\ResourceModel\BlogProduct\CollectionFactory as BlogProductCollectionFactory;

class Collection extends BlogCollection implements SearchResultInterface
{
    public function __construct(
        \Magento\Framework\View\Element\UiComponent\ContextInterface $context,
        EntityFactoryInterface $entityFactory,
        BlogProductCollectionFactory $BlogProductCollectionFactory,
        LoggerInterface $logger,
        FetchStrategyInterface $fetchStrategy,
        ManagerInterface $eventManager,
                               $mainTable,
                               $resourceModel,
                               $model = \Magento\Framework\View\Element\UiComponent\DataProvider\Document::class,
        \Magento\Framework\DB\Adapter\AdapterInterface $connection = null,
        AbstractDb $resource = null
    ) {
        $this->context = $context;
        $this->BlogProductCollectionFactory = $BlogProductCollectionFactory;
        // var_dump(__CLASS__.':'.__FUNCTION__);

        parent::__construct(
            $entityFactory,
            $logger,
            $fetchStrategy,
            $eventManager,
            $connection,
            $resource
        );
        $this->_init($model, $resourceModel);
        $this->setMainTable($mainTable);
    }

    public function getAggregations()
    {
        return $this->aggregations;
    }

    public function setAggregations($aggregations)
    {
        $this->aggregations = $aggregations;
    }

    public function getSearchCriteria()
    {
        return null;
    }

    public function setSearchCriteria(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria = null
    ) {
        return $this;
    }

    public function getTotalCount()
    {
        return $this->getSize();
    }

    public function setTotalCount($totalCount)
    {
        return $this;
    }

    public function setItems(array $items = null)
    {
        return $this;
    }

    protected function _renderFiltersBefore()
    {
        $cgfTable = $this->getTable('customer_grid_flat');
        $this->getSelect()->joinLeft(
            $cgfTable.' as cgf',
            'main_table.user_id = cgf.entity_id',
            []
        )
            ->columns('IFNULL(cgf.name, "Admin") AS user_name');

        // $entity_id = $this->context->getRequestParam("select_products");
        $entity_id = $this->context->getRequestParam("products");

        $params = $this->context->getRequestParams();

        if (isset($params["related_products"])) {
            $this->getSelect()->reset(\Zend_Db_Select::WHERE);
        } elseif ($entity_id) {
            //remove products filter
            // var_dump(__CLASS__.'::'.__FUNCTION__);
            // var_dump($this->getselect()->getPart(\Zend_Db_Select::WHERE));
            $this->getSelect()->reset(\Zend_Db_Select::WHERE);

            //get products for blog $entity_id
            $data = $this->BlogProductCollectionFactory->create()
                ->addFieldToFilter('blog_id', $entity_id);

            $products = $data->getColumnValues('product_id');

            $this->addFieldToFilter("entity_id", $products);
        }

        parent::_renderFiltersBefore();
    }

    protected function _initSelect()
    {
        parent::_initSelect();
        $this->addFilterToMap('user_name', 'cgf.name');

        return $this;
    }
}
