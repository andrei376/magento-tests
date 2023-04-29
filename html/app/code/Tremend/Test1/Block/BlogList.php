<?php
namespace Tremend\Test1\Block;

use Magento\Customer\Model\Session;

class BlogList extends \Magento\Framework\View\Element\Template
{
    public $blogCollection;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Tremend\Test1\Model\ResourceModel\Blog\CollectionFactory $blogCollection,
        \Tremend\Test1\Helper\Data $helper,
        \Tremend\Test1\Model\Blog\Status $blogStatus,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $date,
        array $data = []
    ) {
        $this->blogCollection = $blogCollection;
        $this->helper = $helper;
        $this->blogStatus = $blogStatus;
        $this->date = $date;

        parent::__construct($context, $data);
    }

    public function getBlogs()
    {
        $customerId = $this->helper->getCustomerId();

        $collection = $this->blogCollection->create();
        $collection->addFieldToFilter('user_id', ['eq'=>$customerId])
                    ->setOrder('updated_at', 'DESC');

        return $collection;
    }

    public function getStatuses()
    {
        $statuses = [];
        foreach ($this->blogStatus->toOptionArray() as $status) {
            $statuses[$status['value']] = $status['label'];
        }
        return $statuses;
    }
    public function getFormattedDate($date)
    {
        return $this->date->date($date)->format('d/m/y H:i');
    }
}
