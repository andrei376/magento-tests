<?php

namespace Tremend\Test1\Model;

class BlogProduct extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
    const NOROUTE_ENTITY_ID = 'no-route';
    const ENTITY_ID = 'id';
    const CACHE_TAG = 'blogmanager_blog_products';
    protected $_cacheTag = 'blogmanager_blog_products';
    protected $_eventPrefix = 'blogmanager_blog_products';

    public function _construct()
    {
        $this->_init(\Tremend\Test1\Model\ResourceModel\BlogProduct::class);
    }

    public function load($id, $field = null)
    {
        if ($id === null) {
            return $this->noRoute();
        }
        return parent::load($id, $field);
    }

    public function noRoute()
    {
        return $this->load(self::NOROUTE_ENTITY_ID, $this->getIdFieldName());
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG.'_'.$this->getId()];
    }

    /*public function getId()
    {
        return parent::getData(self::ENTITY_ID);
    }*/

    public function setId($id)
    {
        return $this->setData(self::ENTITY_ID, $id);
    }
}
