<?php
namespace Tremend\Test1\Model\ResourceModel\BlogProduct;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'id';

    public function _construct()
    {
        $this->_init(
            \Tremend\Test1\Model\BlogProduct::class,
            \Tremend\Test1\Model\ResourceModel\BlogProduct::class
        );
        // $this->_map['fields']['entity_id'] = 'blogmanager_blog_products.entity_id';
        $this->_map['fields']['entity_id'] = 'main_table.id';
    }
}
