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
 * Dimensional weight attributes model
 *
 * @category    Shopgo
 * @package     Shopgo_ShippingCore
 * @author      Ammar <ammar@shopgo.me>
 */
class Shopgo_ShippingCore_Model_Dwa extends Mage_Core_Model_Abstract
{
    /**
     * CONFIG path constant: LENGTH
    */
    const XML_PATH_DWA_LENGTH = 'shipping/dwa/length';

    /**
     * CONFIG path constant: WIDTH
    */
    const XML_PATH_DWA_WIDTH  = 'shipping/dwa/width';

    /**
     * CONFIG path constant: HEIGHT
    */
    const XML_PATH_DWA_HEIGHT = 'shipping/dwa/height';


    /**
     * Set dimensional weight attributes
     *
     * @param string $attrSet
     * @param string $attrSetGroup
     * @param Mage_Catalog_Model_Resource_Setup $setup
     * @return bool
     */
    public function setDwAttributes($attrSet, $attrSetGroup, $setup)
    {
        $result = true;

        $attributes = array(
            array(
                'code'     => 'length',
                'label'    => 'Length',
                'config_path' => self::XML_PATH_DWA_LENGTH
            ),
            array(
                'code'     => 'width',
                'label'    => 'Width',
                'config_path' => self::XML_PATH_DWA_WIDTH
            ),
            array(
                'code'     => 'height',
                'label'    => 'Height',
                'config_path' => self::XML_PATH_DWA_HEIGHT
            )
        );

        foreach ($attributes as $attrData) {
            $result = $result
                & $this->_addAttribute($attrData, $attrSet, $attrSetGroup, $setup);
        }

        return $result;
    }

    /**
     * Add attribute
     *
     * @param array $data
     * @param string $attrSet
     * @param string $attrSetGroup
     * @param Mage_Catalog_Model_Resource_Setup|null $setup
     * @return bool
     */
    private function _addAttribute($data, $attrSet = 'default', $attrSetGroup = 'General', $setup = null)
    {
        $result = true;

        if (empty($setup)) {
            $setup = Mage::getResourceModel('catalog/setup', 'catalog_setup');
        }

        if ($setup->getAttributeId(Mage_Catalog_Model_Product::ENTITY, $data['code'])) {
            Mage::getModel('core/config')->saveConfig($data['config_path'], $data['code']);
            return $result;
        }

        $productTypes = array(
            Mage_Catalog_Model_Product_Type::TYPE_SIMPLE,
            Mage_Catalog_Model_Product_Type::TYPE_BUNDLE
        );
        $productTypes = join(',', $productTypes);

        try {
            $setup->addAttribute('catalog_product', $data['code'], array(
                'attribute_set'                 => $attrSet,
                'group'                         => $attrSetGroup,
                'global'                        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
                'type'                          => 'decimal',
                'input'                         => 'text',
                'unique'                        => false,
                'required'                      => false,
                'frontend_class'                => 'validate-number',
                'class'                         => '',
                'apply_to'                      => $productTypes,
                'label'                         => $data['label'],
                'searchable'                    => false,
                'visible_in_advanced_search'    => false,
                'comparable'                    => false,
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

            Mage::getModel('core/config')->saveConfig($data['config_path'], $data['code']);
        } catch (Exception $e) {
            Mage::helper('shippingcore')->log($e->getMessage());
            $result = false;
        }

        return $result;
    }
}
