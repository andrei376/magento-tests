<?php

namespace Tremend\Test1\Model\ResourceModel;

class BlogProduct extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    public function _construct()
    {
        $this->_init("blogmanager_blog_products", "id");
    }

    public function insertMultiple($data)
    {
        try {
            return $this->getConnection()->insertMultiple($this->getMainTable(), $data);
        } catch (\Exception $e) {
            $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/custom.log');
            $logger = new \Zend_Log();
            $logger->addWriter($writer);
            $logger->err('Error save, message: ' . $e->getMessage());
        }
    }

    public function deleteByBlogId($blogId)
    {
        try {
            return $this->getConnection()->delete($this->getMainTable(), ['blog_id = ?' => $blogId]);
        } catch (\Exception $e) {
            $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/custom.log');
            $logger = new \Zend_Log();
            $logger->addWriter($writer);
            $logger->err('Error delete, message: ' . $e->getMessage());
        }
    }
}
