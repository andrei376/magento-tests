<?php

namespace Tremend\Test1\Model\ResourceModel;

class Blog extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    public function _construct()
    {
        $this->_init("blogmanager_blog", "entity_id");
    }
}
