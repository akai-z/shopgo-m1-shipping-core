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
class Shopgo_ShippingCore_Model_System_Config_Source_Attributeset
{
    /**
     * Get attribute sets
     *
     * @return array
     */
    public function toOptionArray()
    {
        $entityTypeId = Mage::getModel('eav/entity')
            ->setType(Mage_Catalog_Model_Product::ENTITY)->getTypeId();

        $attrSetCollection = Mage::getResourceModel('eav/entity_attribute_set_collection')
            ->setEntityTypeFilter($entityTypeId)->load();

        $options = array(
            array(
                'value' => '',
                'label' => Mage::helper('adminhtml')->__('--Please Select--')
            )
        );

        foreach ($attrSetCollection as $set) {
            $options[] = array(
                'value' => $set->getAttributeSetId(),
                'label' => $set->getAttributeSetName()
            );
        }

        return $options;
    }
}
