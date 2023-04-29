<?php
namespace Tremend\Test1\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Customer\Model\Session;

class Data extends AbstractHelper
{
    public $customerSession;
    protected $scopeConfig;

    const XML_PATH_HELLOWORLD = 'helloworld/';

    public function __construct(
        Session $customerSession,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $date,
        Context $context,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig  = $scopeConfig;
        $this->date = $date;
        $this->customerSession = $customerSession;
        parent::__construct($context);
    }

    public function getConfigValue($field)
    {
        return $this->scopeConfig->getValue(
            $field, ScopeInterface::SCOPE_STORE
        );
    }


    public function getGeneralConfig($code)
    {
        return $this->getConfigValue(self::XML_PATH_HELLOWORLD . $code);
    }

    public function getCustomerId()
    {
        $customerId = $this->customerSession->getCustomerId();
        return $customerId;
    }

    public function getFormattedDate($date='')
    {
        return $this->date->date($date)->format('d/m/y h:i A');
    }
}
