<?php

namespace Tremend\Test1\Observer;

use Magento\Framework\Event\Observer;
use PhpParser\Node\Expr\Empty_;
use Tremend\Test1\Model\ResourceModel\BlogProduct;

class BlogProductSave implements \Magento\Framework\Event\ObserverInterface
{
    private $logger;
    private $blogProduct;

    public function __construct(
        BlogProduct $blogProduct,
    ) {
        $this->blogProduct = $blogProduct;

        $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/custom.log');
        $logger = new \Zend_Log();
        $logger->addWriter($writer);

        $this->logger = $logger;
    }

    public function execute(Observer $observer)
    {
        $this->logger->debug(__CLASS__.'::'.__FUNCTION__);

        $blog = $observer->getEvent()->getObject();

        $blogId = $blog->getEntityId();

        $this->logger->debug('save blogid=' . $blogId);

        // $relatedProducts = $blog->getProducts();
        $relatedProducts = $blog->getRelatedProducts();

        $this->logger->debug('save products=' . print_r($relatedProducts, true));


        //clear old values
        $this->blogProduct->deleteByBlogId($blogId);

        if (!empty($relatedProducts)) {
            //$products = explode(',', $relatedProducts);

            foreach ($relatedProducts as $product) {

                $data[] = [
                    'blog_id' => $blogId,
                    'product_id' => $product['entity_id'] ?? $product
                ];
            }

            $this->logger->debug('insert data=' . print_r($data, true));

            $this->blogProduct->insertMultiple($data);
        }

        return $this;
    }
}
