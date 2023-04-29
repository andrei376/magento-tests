<?php
declare(strict_types=1);

namespace Tremend\Test1\Controller\Page;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\ResultFactory;
use Tremend\Test1\Helper\Data;

class View extends Action
{
    protected $dataHelper;

    public function __construct(
        Context $context,
        Data $dataHelper
    ) {
        $this->dataHelper = $dataHelper;
        parent::__construct($context);
    }

    public function execute()
    {
        /** @var Json $jsonResult */
        $jsonResult = $this->resultFactory->create(ResultFactory::TYPE_JSON);

        $vars = [
            'enable',
            'display_text',
            'text_area',
            'select',
            'button',
            'obscure',
            'time'
        ];

        $data = [
            'message' => 'My First Page',
        ];

        foreach ($vars as $var) {
            $data[$var] = $this->dataHelper->getGeneralConfig('general/' . $var);
        }


        $jsonResult->setData($data);
        return $jsonResult;
    }
}
