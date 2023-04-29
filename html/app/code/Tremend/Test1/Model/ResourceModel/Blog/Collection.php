<?php
namespace Tremend\Test1\Model\ResourceModel\Blog;

use Magento\Variable\Model\VariableFactory;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'entity_id';

    public function _construct()
    {
        $this->_init(
            \Tremend\Test1\Model\Blog::class,
            \Tremend\Test1\Model\ResourceModel\Blog::class
        );
        $this->_map['fields']['entity_id'] = 'main_table.entity_id';
    }
}
