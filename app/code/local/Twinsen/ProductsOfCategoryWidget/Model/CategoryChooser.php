<?php
class Twinsen_ProductsOfCategoryWidget_Model_CategoryChooser {
    public function toOptionArray()
    {
        $collection = Mage::getResourceModel('catalog/category_collection');

        $collection->addAttributeToSelect('name')
            ->addFieldToFilter('level', array('gteq' => 1))
            ->load();

        $options = array();

        $options[] = array(
            'label' => Mage::helper('twinsen_productsofcategorywidget')->__('-- None --'),
            'value' => ''
        );

        foreach ($collection as $category) {
            $label = $category->getName();
            $padLength = ($category->getLevel() - 1) * 2 + strlen($label);
            $label = str_pad($label, $padLength, '-', STR_PAD_LEFT);
            $options[] = array(
                'label' => $label,
                'value' => $category->getId()
            );
        }

        return $options;
    }
}