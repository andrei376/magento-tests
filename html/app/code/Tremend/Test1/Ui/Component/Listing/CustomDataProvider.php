<?php

declare(strict_types=1);

namespace Tremend\Test1\Ui\Component\Listing;

use Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider;

/**
 * Class CustomDataProvider
 */
class CustomDataProvider extends DataProvider
{
    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        return [
            'items' => [
                [
                    'id' => 1,
                    'name' => 'First Item'
                ],
                [
                    'id' => 2,
                    'name' => 'Second Item'
                ],
                [
                    'id' => 3,
                    'name' => 'Third Item'
                ],
                [
                    'id' => 4,
                    'name' => 'Fourth Item'
                ]
            ],
            'totalRecords' => 4
        ];
    }
}
