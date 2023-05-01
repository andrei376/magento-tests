<?php
namespace Tremend\Test1\Model\Blog;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Backend\Model\UrlInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Variable\Model\VariableFactory;

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
