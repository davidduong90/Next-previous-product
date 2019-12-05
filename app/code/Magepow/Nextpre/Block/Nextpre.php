<?php
/*
 * Magepow
 * @category: Magepow
 * @copyright: Copyright (c) 2014 Magepow (http://www.magepow.com/)
 * @license: http://www.magepow.com/license-agreement
 * @Author: DavidDuong
 * @@Create Date: Wednesday, December 4th 2019, 8:43:54 pm
 * @@Modified By: DavidDuong
 * @@Modify Date: Thursday, December 5th 2019, 10:01:09 am
 */

namespace Magepow\Nextpre\Block;

class Nextpre extends \Magento\Framework\View\Element\Template
{
    protected $_objectManager;
    
    /**
     * @var Product
     */
    protected $_product = null;

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\ObjectManagerInterface $objectManager,
     * @param array $data
     */

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        $this->_objectManager = $objectManager;
        parent::__construct($context, $data);
    }

    /**
     * @return Product
     */
    public function getProduct()
    {
        if (!$this->_product) {
            $this->_product = $this->_coreRegistry->registry('product');
        }
        return $this->_product;
    }

    public function getModel($model) {
        return $this->_objectManager->create($model);
    }

    public function getCategoryProductIds($current_category) {
        $category_products = $current_category->getProductCollection()
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('is_saleable', 1, 'left')
            ->addAttributeToSort('position','asc');
        $cat_prod_ids = $category_products->getAllIds();
        
        return $cat_prod_ids;
    }


    public function getPrevProduct($product) {
        $current_category = $product->getCategory();
        if(!$current_category) {
            foreach($product->getCategoryCollection() as $parent_cat) {
                $current_category = $parent_cat;
            }
        }
        if(!$current_category)
            return false;
        $cat_prod_ids = $this->getCategoryProductIds($current_category);
        $_pos = array_search($product->getId(), $cat_prod_ids);
        if (isset($cat_prod_ids[$_pos - 1])) {
            $prev_product = $this->getModel('Magento\Catalog\Model\Product')->load($cat_prod_ids[$_pos - 1]);
            return $prev_product;
        }
        return false;
    }
    
    public function getNextProduct($product) {
        $current_category = $product->getCategory();
        if(!$current_category) {
            foreach($product->getCategoryCollection() as $parent_cat) {
                $current_category = $parent_cat;
            }
        }
        if(!$current_category)
            return false;
        $cat_prod_ids = $this->getCategoryProductIds($current_category);
        $_pos = array_search($product->getId(), $cat_prod_ids);
        if (isset($cat_prod_ids[$_pos + 1])) {
            $next_product = $this->getModel('Magento\Catalog\Model\Product')->load($cat_prod_ids[$_pos + 1]);
            return $next_product;
        }
        return false;
    }

}
