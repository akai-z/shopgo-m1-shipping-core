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
class Shopgo_ShippingCore_Model_System_Config_Source_Attributesetgroup
{
    /**
     * Get attribute set groups
     *
     * @param int $attrSet
     * @return array
     */
    public function toOptionArray($attrSet)
    {
        $options = array(
            array(
                'value' => '',
                'label' => Mage::helper('adminhtml')->__('--Not Specified--')
            )
        );

        if (empty($attrSet)) {
            $attrSet = Mage::getStoreConfig('shipping/dwa/attribute_set');

            if (empty($attrSet)) {
                return $options;
            }
        }

        $attrSetGroupCollection = Mage::getResourceModel('eav/entity_attribute_group_collection')
            ->setOrder('attribute_group_id', Varien_Data_Collection::SORT_ORDER_ASC)
            ->setAttributeSetFilter($attrSet)
            ->load();

        $options = array(
            array(
                'value' => '',
                'label' => Mage::helper('adminhtml')->__('--Please Select--')
            )
        );

        foreach ($attrSetGroupCollection as $group) {
            $options[] = array(
               'value' => $group->getId(),
               'label' => $group->getAttributeGroupName()
            );
        }

        return $options;
    }
}
