<?php

namespace Tremend\Test1\Model\ResourceModel\Product\Grid;

use Magento\Framework\Api\Search\SearchResultInterface;
use Magento\Framework\Data\Collection\EntityFactoryInterface;
use Magento\Framework\Search\AggregationInterface;
use Magento\Variable\Model\VariableFactory;
use Tremend\Test1\Model\ResourceModel\Blog\Collection as BlogCollection;
use Magento\Catalog\Model\ResourceModel\Product\Collection as ProductCollection;


use Psr\Log\LoggerInterface;
use Magento\Framework\Data\Collection\Db\FetchStrategyInterface;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Tremend\Test1\Model\ResourceModel\BlogProduct\CollectionFactory as BlogProductCollectionFactory;

class Collection extends ProductCollection implements SearchResultInterface
{
    protected $_idFieldName = 'entity_id';
    protected $_eventPrefix = 'catalog_product_grid_collection';
    protected $_eventObject = 'product_grid_collection';

    public function __construct(
        \Magento\Framework\Data\Collection\EntityFactory $entityFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Eav\Model\Config $eavConfig,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\Eav\Model\EntityFactory $eavEntityFactory,
        \Magento\Catalog\Model\ResourceModel\Helper $resourceHelper,
        \Magento\Framework\Validator\UniversalFactory $universalFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Module\Manager $moduleManager,
        \Magento\Catalog\Model\Indexer\Product\Flat\State $catalogProductFlatState,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Catalog\Model\Product\OptionFactory $productOptionFactory,
        \Magento\Catalog\Model\ResourceModel\Url $catalogUrl,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Stdlib\DateTime $dateTime,
        \Magento\Framework\App\State $appState,
        \Magento\Customer\Api\GroupManagementInterface $groupManagement,
        \Magento\Framework\View\Element\UiComponent\ContextInterface $context,
        BlogProductCollectionFactory $BlogProductCollectionFactory,
        \Magento\Framework\DB\Adapter\AdapterInterface $connection = null
    ) {
        $this->context = $context;
        $this->BlogProductCollectionFactory = $BlogProductCollectionFactory;

        $this->logger = $logger;


        parent::__construct(
            $entityFactory,
            $logger,
            $fetchStrategy,
            $eventManager,
            $eavConfig,
            $resource,
            $eavEntityFactory,
            $resourceHelper,
            $universalFactory,
            $storeManager,
            $moduleManager,
            $catalogProductFlatState,
            $scopeConfig,
            $productOptionFactory,
            $catalogUrl,
            $localeDate,
            $customerSession,
            $dateTime,
            $groupManagement,
            $connection
        );

        // $this->getSelect()->reset(\Zend_Db_Select::WHERE);

        $params = $this->context->getRequestParams();

        // var_dump(__CLASS__.':'.__FUNCTION__);

        if (isset($params["related_products"])) {
            // var_dump('clear');
        } else {
            // var_dump('exista');

        }

        $entity_id = $this->context->getRequestParam("products");

        if ($entity_id) {
            // var_dump('am id');

            // var_dump($this->_filters);
        }

        // $this->_init($model, $resourceModel);
        // $this->setMainTable($mainTable);
    }

    /*public function __construct(
        \Magento\Framework\View\Element\UiComponent\ContextInterface $context,
        EntityFactoryInterface $entityFactory,
        BlogProductCollectionFactory $BlogProductCollectionFactory,
        LoggerInterface $logger,
        FetchStrategyInterface $fetchStrategy,
        ManagerInterface $eventManager,
        ProductCollectionFactory $ProductCollectionFactory,
                               $mainTable,
                               $resourceModel,
                               $model = \Magento\Framework\View\Element\UiComponent\DataProvider\Document::class,
        \Magento\Framework\DB\Adapter\AdapterInterface $connection = null,
        AbstractDb $resource = null
    ) {
        $this->context = $context;
        $this->ProductCollectionFactory = $ProductCollectionFactory;
        $this->BlogProductCollectionFactory = $BlogProductCollectionFactory;

        // var_dump(__CLASS__.':'.__FUNCTION__);
        // var_dump('tab='.$mainTable);

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
    }*/
    /*public function _construct()
    {
        $this->_init('Magento\Catalog\Model\Product', 'Magento\Catalog\Model\ResourceModel\Product');
        $this->_map['fields']['entity_id'] = 'main_table.entity_id';

        $this->addAttributeToSelect('name');


        parent::_construct(); // TODO: Change the autogenerated stub
    }*/


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
        $this->logger->error(__CLASS__.'::'.__FUNCTION__);

        // var_dump($this->_filters);

        // $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        // $this->context = $objectManager->get('\Magento\Framework\View\Element\UiComponent\ContextInterface');

        $entity_id = $this->context->getRequestParam("products");

        $params = $this->context->getRequestParams();


        // $this->addFieldToSelect('*');
        $this->addFieldToSelect(['name', 'thumbnail']);
        // $this->addFieldToSelect('sku');

        // $this->addAttributeToSelect('sku');

        // $this->ProductCollectionFactory->create()->addAttributeToSelect('name');

        if (isset($params["related_products"])) {
            // $this->getSelect()->reset(\Zend_Db_Select::WHERE);
        } elseif ($entity_id) {
            //remove products filter
            // var_dump(__CLASS__.'::'.__FUNCTION__);
            // var_dump($this->getselect()->getPart(\Zend_Db_Select::WHERE));
            $this->getSelect()->reset(\Zend_Db_Select::WHERE);

            //get products for blog $entity_id
            $data = $this->BlogProductCollectionFactory->create()
                ->addFieldToFilter('blog_id', $entity_id);

            $products = $data->getColumnValues('product_id');

            if (!empty($products)) {
                $this->addFieldToFilter("entity_id", $products);
            }
        }

        // var_dump($this->getSelect()->__toString());

        $this->logger->error($this->getSelectSql(true));

        parent::_renderFiltersBefore();
    }

    protected function _initSelect()
    {
        parent::_initSelect();
        // $this->addFilterToMap('user_name', 'cgf.name');

        $this->addFieldToSelect('sku');
        $this->addAttributeToSelect('name');




        // $this->getCollection()->addAttributeToSelect('name');

        return $this;
    }
}
