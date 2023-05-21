<?php
namespace Tremend\Test1\Model\Blog;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Backend\Model\UrlInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Variable\Model\VariableFactory;

use PhpParser\Node\Expr\Empty_;
use Tremend\Test1\Model\ResourceModel\BlogProduct\CollectionFactory as BlogProductCollectionFactory;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\Catalog\Helper\Product;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    protected $loadedData;

    public function __construct(
        \Magento\Framework\View\Element\UiComponent\ContextInterface $context,
        UrlInterface $urlBuilder,
        RequestInterface $requestInterface,
        $name,
        $primaryFieldName,
        $requestFieldName,
        \Tremend\Test1\Model\ResourceModel\Blog\CollectionFactory $blogCollectionFactory,
        BlogProductCollectionFactory $BlogProductCollectionFactory,
        ProductCollectionFactory $ProductCollectionFactory,
        Product $productHelper,
        array $meta = [],
        array $data = []
    ) {
        // var_dump($name);
        // var_dump($primaryFieldName);
        // var_dump($requestFieldName);
        $this->context = $context;
        $this->_urlBuilder = $urlBuilder;
        $this->_request = $requestInterface;
        $this->collection = $blogCollectionFactory->create();

        $this->BlogProductCollectionFactory = $BlogProductCollectionFactory;
        $this->ProductCollectionFactory = $ProductCollectionFactory;
        $this->productHelper = $productHelper;

        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        foreach ($items as $blog) {
            $this->loadedData[$blog->getId()] = $blog->getData();

            //important for insertlisting showing how many are selected
            $this->loadedData[$blog->getId()]['related_products'] = [];


            //de testat edit blog cu 0 produse

            $blog_id = $blog->getId();

            // var_dump($blog_id);


            //get products for blog $blog_id
            $data = $this->BlogProductCollectionFactory->create()
                ->addFieldToFilter('blog_id', $blog_id);

            $products = $data->getColumnValues('product_id');

            // var_dump($products);

            if (!empty($products)) {
                //got info for each product
                foreach ($products as $product_id) {
                    $product = $this->ProductCollectionFactory->create()
                    ->addAttributeToSelect('thumbnail')
                    ->addAttributeToSelect('name')
                    ->addFieldToFilter('entity_id', $product_id);


                    $product_info = $product->getFirstItem();
                    // $product_info = $product->getItems();

                    // var_dump($product_info);

                    $this->loadedData[$blog->getId()]['related_products'][] = [
                        'entity_id' => $product_info['entity_id'],
                        'sku' => $product_info['sku'],
                        'thumbnail_src' => $this->productHelper->getThumbnailUrl($product_info),
                        'name' => $product_info['name']
                    ];
                }
            }

            // $this->loadedData[$blog->getId()]['related_products'][] = [
            //     'entity_id' => 1,
            //     'sku' => 'test',
            //     'thumbnail_src' => 'url',
            //     'name' => 'test'
            // ];

            // $this->loadedData[$blog->getId()]['test_modal_1'] = [];
            // $this->loadedData[$blog->getId()]['blogmanager_blog_product_listing'] = [];
            // $this->loadedData[$blog->getId()]['blog_list'] = [];
        }

        return $this->loadedData;
    }

    public function getMeta()
    {
        $meta = parent::getMeta();

        $id = $this->_request->getParam('id');

        // $meta['blog_list']['children']['blogmanager_blog_listing']['arguments']['data']['config']['render_url'] = $this->_urlBuilder->getUrl('mui/index/render/products/'.$id);
        // $meta['related_products_2']['children']['blogmanager_blog_product_listing']['arguments']['data']['config']['render_url'] = $this->_urlBuilder->getUrl('mui/index/render/products/'.$id);
        $meta['test_modal_1']['children']['blogmanager_blog_product_listing']['arguments']['data']['config']['render_url'] = $this->_urlBuilder->getUrl('mui/index/render/products/'.$id);

        return $meta;
    }
}
