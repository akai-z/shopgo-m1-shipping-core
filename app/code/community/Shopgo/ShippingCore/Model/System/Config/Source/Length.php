<?php
/**
 * ShopGo
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category    Shopgo
 * @package     Shopgo_ShippingCore
 * @copyright   Copyright (c) 2014 Shopgo. (http://www.shopgo.me)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


/**
 * Source model
 *
 * @category    Shopgo
 * @package     Shopgo_ShippingCore
 * @author      Ammar <ammar@shopgo.me>
 */
class Shopgo_ShippingCore_Model_System_Config_Source_Length
{
    /**
     * Get length attributes
     *
     * @return array
     */
    public function toOptionArray()
    {
        $attributes = Mage::getResourceModel('catalog/product_attribute_collection')
            ->addFieldToFilter('attribute_code', array('like' => '%length%'));

        $options = array(
            array(
                'value' => '',
                'label' => Mage::helper('adminhtml')->__('--Please Select--')
            )
        );

        foreach ($attributes as $attr) {
            $options[] = array(
                'value' => $attr->getAttributeCode(),
                'label' => $attr->getFrontendLabel() . ' (' . $attr->getAttributeCode() . ')'
            );
        }

        return $options;
    }
}
