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
class Shopgo_ShippingCore_Model_System_Config_Source_Codcurrency
{
    /**
     * Base currency option value
     */
    const BASE     = 1;

    /**
     * Order currency option value
     */
    const ORDER    = 2;

    /**
     * Display/Currency currency option value
     */
    const CURRENT  = 3;

    /**
     * Specific currency option value
     */
    const SPECIFIC = 4;


    /**
     * Get cash on delivery currency options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $adminhtmlHelper = Mage::helper('adminhtml');

        $options = array(
            array(
                'value' => self::BASE,
                'label' => $adminhtmlHelper->__('Base Currency')
            ),
            array(
                'value' => self::ORDER,
                'label' => $adminhtmlHelper->__('Order Currency')
            ),
            array(
                'value' => self::CURRENT,
                'label' => $adminhtmlHelper->__('Default Display Currency')
            ),
            array(
                'value' => self::SPECIFIC,
                'label' => $adminhtmlHelper->__('Specific Currency')
            )
        );

        return $options;
    }
}
