<?php
namespace Tremend\Test1\Block\Adminhtml\Blog\Edit\Tab;

class RelatedProducts extends \Magento\Backend\Block\Widget\Form\Generic
{
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        array $data = []
    ) {
        $this->productCollectionFactory = $productCollectionFactory;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('blog_data');

        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('blogmanager_');

        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('Related Products'), 'class' => 'fieldset-wide']
        );

        $elProducts = $fieldset->addField(
            'related_products',
            'multiselect',
            [
                'name' => 'related_products',
                'label' => __('Products'),
                'values' => $this->getProductOptions(),
                'id' => 'products',
                'title' => __('Products'),
                'required' => false,
            ]
        );

        $form->setValues($model->getData());

        $dbdata = $model->getData();

        // $elProducts->setValue($dbdata['related_products']);

        $this->setForm($form);

        return parent::_prepareForm();
    }

    private function getProductOptions()
    {
        $collection = $this->productCollectionFactory->create()->addAttributeToSelect('name');
        $products = [];
        foreach ($collection as $model) {
            $products[] = ['value'=>$model->getId(), 'label'=>$model->getName()];
        }
        return $products;
    }
}
