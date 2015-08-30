<?php

/**
 * Class Twinsen_ProductsOfCategoryWidget_Block_ProductsOfCategory
 * Token from : http://magento.stackexchange.com/questions/21204/get-new-products-list-from-specific-category-with-widget-in-cms
 */
class Twinsen_ProductsOfCategoryWidget_Block_ProductsOfCategory extends Mage_Catalog_Block_Product_Widget_New
{
    //category member var
    protected $_category = null;

//load the category
    public function getCategory()
    {

        if (is_null($this->_category)) {
            if ($this->hasData('category')) {
                $category = Mage::getModel('catalog/category')->setStoreId(Mage::app()->getStore()->getId())->load($this->getData('category'));
                if ($category->getId()) {
                    $this->_category = $category;
                }
            }
            //if the category is not valid set it to false to avoid loading it again.
            if (is_null($this->_category)) {
                $this->_category = false;
            }
        }
        return $this->_category;
    }

    /**
     * Prepare and return product collection
     *
     * @return Mage_Catalog_Model_Resource_Product_Collection|Object|Varien_Data_Collection
     */
    protected function _getProductCollection()
    {

        /** @var $collection Mage_Catalog_Model_Resource_Product_Collection */

        $collection = Mage::getResourceModel('catalog/product_collection');
        $collection->setVisibility(Mage::getSingleton('catalog/product_visibility')->getVisibleInCatalogIds());
        $collection = $this->_addProductAttributesAndPrices($collection);
        //if a category is specified filter by it.
        if ($this->getCategory()) {
            $collection->addCategoryFilter($this->getCategory());
        }

        $collection->setPageSize($this->getProductsCount())
            ->setCurPage(1);

        return $collection;


    }

    //Change the cache key so you won't get the same products for different categories when the cache is on
    public function getCacheKeyInfo()
    {
        //$cacheKeyInfo = parent::getCacheKeyInfo();
        if ($this->getCategory()) {
            $cacheKeyInfo[] = $this->getCategory()->getId();
        } else {
            $cacheKeyInfo[] = 0;
        }

        return $cacheKeyInfo;
    }


}