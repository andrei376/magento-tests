<?php

declare(strict_types=1);

namespace Tremend\Test1\Ui\Component\Listing;

use Magento\Backend\Model\UrlInterface;
use Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;

use Tremend\Test1\Model\ResourceModel\Product\Grid\Collection as ProductGridCollection;

use Magento\Framework\App\RequestInterface;
use Magento\Variable\Model\VariableFactory;
use PhpParser\Node\Expr\Empty_;
use Tremend\Test1\Model\ResourceModel\BlogProduct\CollectionFactory as BlogProductCollectionFactory;

use Magento\Framework\Api\Search\ReportingInterface;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\Api\FilterBuilder;

/**
 * Class CustomDataProvider
 */
class ProductDataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    protected $loadedData;

    /**
     * foobar collection
     *
     * @var \[Namespace]\[Module]\Model\FooBar\Collection
     */
    protected $collection;

    /**
     * @var \Magento\Ui\DataProvider\AddFieldToCollectionInterface[]
     */
    protected $addFieldStrategies;

    /**
     * @var \Magento\Ui\DataProvider\AddFilterToCollectionInterface[]
     */
    protected $addFilterStrategies;

    /**
     * Construct
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\UiComponent\ContextInterface $context,
        ReportingInterface $reporting,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        FilterBuilder $filterBuilder,
        BlogProductCollectionFactory $BlogProductCollectionFactory,
        ProductGridCollection $ProductGridCollection,
        UrlInterface $urlBuilder,
        RequestInterface $requestInterface,
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        \Tremend\Test1\Model\ResourceModel\Blog\CollectionFactory $blogCollectionFactory,
        array $meta = [],
        array $data = []
    ) {
        $this->context = $context;
        $this->BlogProductCollectionFactory = $BlogProductCollectionFactory;
        $this->_urlBuilder = $urlBuilder;
        $this->_request = $requestInterface;

        $this->collection = $ProductGridCollection;

        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Get collection
     *
     * @return \[Namespace]\[Module]\Model\FooBar\Collection
     */
    public function getCollection()
    {
        return $this->collection;

    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (!$this->getCollection()->isLoaded()) {
            $entity_id = $this->context->getRequestParam("products");

            $params = $this->context->getRequestParams();

            if (isset($params["related_products"])) {
                $this->getCollection()->getSelect()->reset(\Zend_Db_Select::WHERE);
            } elseif ($entity_id) {
                //remove products filter
                $this->getCollection()->getSelect()->reset(\Zend_Db_Select::WHERE);

                //get products for blog $entity_id
                $data = $this->BlogProductCollectionFactory->create()
                    ->addFieldToFilter('blog_id', $entity_id);

                $products = $data->getColumnValues('product_id');

                $this->getCollection()->addFieldToFilter("entity_id", $products);
            }

            $this->getCollection()->load();
        }
        $products = $this->getCollection()->toArray();

        return ['totalRecords' => $this->getCollection()->getSize(), 'items' => array_values($products)];
    }

    public function getMeta()
    {
        $meta = parent::getMeta();

        $id = $this->_request->getParam('products');

        $this->data['config']['render_url'] = $this->_urlBuilder->getUrl('mui/index/render/products/' . $id);
        $this->data['config']['update_url'] = $this->_urlBuilder->getUrl('mui/index/render/products/' . $id);

        return $meta;
    }
}
