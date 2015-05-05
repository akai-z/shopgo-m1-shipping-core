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
 * @author      Ammar <ammar@shopgo.me>
 * @copyright   Copyright (c) 2014 Shopgo. (http://www.shopgo.me)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


/* @var $installer Mage_Catalog_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

/**
 * Add dimensional weight attributes
 */
Mage::getModel('shippingcore/dwa')->setDwAttributes('', '', $installer);

$productTypes = array(
    Mage_Catalog_Model_Product_Type::TYPE_SIMPLE,
    Mage_Catalog_Model_Product_Type::TYPE_BUNDLE
);
$productTypes = join(',', $productTypes);

/**
 * Add harmonized system code attribute
 */
$installer->addAttribute('catalog_product', 'hs_code', array(
    'attribute_set'                 => 'Default',
    'group'                         => 'General',
    'global'                        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'type'                          => 'varchar',
    'input'                         => 'text',
    'unique'                        => false,
    'required'                      => false,
    'class'                         => '',
    'apply_to'                      => $productTypes,
    'label'                         => 'HS Code',
    'searchable'                    => true,
    'visible_in_advanced_search'    => true,
    'comparable'                    => true,
    'filterable'                    => false,
    'filterable_in_search'          => false,
    'used_for_promo_rules'          => true,
    'html_allowed_on_front'         => false,
    'visible_on_front'              => false,
    'backend'                       => '',
    'visible'                       => true,
    'user_defined'                  => true,
    'used_in_product_listing'       => false,
    'used_for_sort_by'              => false
));

$installer->endSetup();
