<?php
namespace Tremend\Test1\Block\Adminhtml\System\Config;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

class Button extends Field
{
    /**
     * Get the button Run
     *
     * @param AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        $url = "'https://www.google.com'";
        return '<button onclick="location.href=' . $url . '" type="button">' . __('Google') . '</button>';
    }
}
