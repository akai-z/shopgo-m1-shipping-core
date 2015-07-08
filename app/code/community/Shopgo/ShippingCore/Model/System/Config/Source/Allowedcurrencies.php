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
class Shopgo_ShippingCore_Model_System_Config_Source_Allowedcurrencies
{
    /**
     * CONFIG path constant: ALLOW
    */
    const XML_PATH_CURRENCY_ALLOW = 'currency/options/allow';


    /**
     * Retrieve allowed currencies according to config
     *
     * @return array
     */
    public function toOptionArray()
    {
        $options = array();
        $allowedCurrencies = explode(
            ',', Mage::getStoreConfig(self::XML_PATH_CURRENCY_ALLOW)
        );
        $appBaseCurrencyCode = Mage::app()->getBaseCurrencyCode();

        if (!in_array($appBaseCurrencyCode, $allowedCurrencies)) {
            $allowedCurrencies[] = $appBaseCurrencyCode;
        }

        foreach (Mage::app()->getStores() as $store) {
            $code = $store->getBaseCurrencyCode();
            if (!in_array($code, $allowedCurrencies)) {
                $allowedCurrencies[] = $code;
            }
        }

        foreach ($allowedCurrencies as $currency) {
            $options[] = array(
                'value' => $currency,
                'label' => Mage::app()->getLocale()->currency($currency)->getName()
            );
        }

        return $options;
    }
}
