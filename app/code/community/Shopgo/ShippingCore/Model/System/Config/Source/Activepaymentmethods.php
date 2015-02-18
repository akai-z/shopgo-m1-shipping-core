<?php
/**
 * ShopGo
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the GNU General Public License (GPLv2)
 * that is bundled with this package in the file COPYING.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-2.0.html
 *
 * @category    Shopgo
 * @package     Shopgo_ShippingCore
 * @copyright   Copyright (c) 2014 Shopgo. (http://www.shopgo.me)
 * @license     http://www.gnu.org/licenses/gpl-2.0.html  GNU General Public License (GPLv2)
 */


/**
 * Source model
 *
 * @category    Shopgo
 * @package     Shopgo_ShippingCore
 * @author      Ammar <ammar@shopgo.me>
 */
class Shopgo_ShippingCore_Model_System_Config_Source_Activepaymentmethods
{
    /**
     * Get active payment methods
     *
     * @return array
     */
    public function toOptionArray($isMultiSelect = false)
    {
        $options = Mage::helper('payment')->getPaymentMethodList(true, true, true);

        if ($isMultiSelect) {
            array_unshift($options, array('value' => '', 'label' => Mage::helper('adminhtml')->__('--Please Select--')));
        }

        return $options;
    }
}
