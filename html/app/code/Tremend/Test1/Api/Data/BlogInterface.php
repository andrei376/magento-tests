<?php

namespace Tremend\Test1\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

interface BlogInterface extends ExtensibleDataInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @param int $id
     * @return void
     */
    public function setId($id);

    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $name
     * @return void
     */
    public function setName($name);

    /**
     * @return \Amasty\Example\Api\Data\IngredientInterface[]
     */
    public function getIngredients();

    /**
     * @param \Amasty\Example\Api\Data\ObjectInterface[] $ingredients
     * @return void
     */
    public function setIngredients(array $ingredients);

}
