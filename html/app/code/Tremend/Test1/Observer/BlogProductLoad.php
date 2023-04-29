<?php

namespace Tremend\Test1\Observer;

use Magento\Variable\Model\VariableFactory;
use Tremend\Test1\Model\ResourceModel\BlogProduct;
use Tremend\Test1\Model\BlogProductFactory;
use Tremend\Test1\Model\ResourceModel\BlogProduct\CollectionFactory as BlogProductCollectionFactory;

class BlogProductLoad implements \Magento\Framework\Event\ObserverInterface
{
    private $blogProductResource;
    private $blogProductFactory;
    private $collectionFactory;
    private $collection;

    public function __construct(
        BlogProduct $blogProduct,
        BlogProductFactory $blogProductFactory,
        BlogProduct\Collection $collection,
        BlogProductCollectionFactory $collectionFactory
    ) {
        $this->blogProductResource = $blogProduct;
        $this->blogProductFactory = $blogProductFactory;
        $this->collectionFactory = $collectionFactory;
        $this->collection = $collection;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $blog = $observer->getEvent()->getObject();

        $blogId = $blog->getEntityId();


        if (is_null($blogId)) {
            return $this;
        }


        //get products related to blog
        //$data = $this->blogProductResource->load(null, $blogId, 'blog_id');
        // $model = $this->blogProductFactory->create();
        // $data = $model->load($blogId, 'blog_id');

        /*$collection = $this->collectionFactory->create()
            ->addAttributeToSelect('name')
            ->addAttributeToFilter('blog_id', $blogId);*/

        $data = $this->collectionFactory->create()
            ->addFieldToFilter('blog_id', $blogId);

        // echo($data->getSelect());

        // var_dump($data);

        $products = [];

        foreach ($data as $product) {
            $products[] = $product->getProductId();
        }

        // var_dump($products);

        $blog->setData('related_products', $products);

        $blog->setDataChanges(false);

        return $this;
    }
}
