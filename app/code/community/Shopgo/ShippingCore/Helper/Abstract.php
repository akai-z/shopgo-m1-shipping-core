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
 * Abstract helper class
 *
 * @category    Shopgo
 * @package     Shopgo_ShippingCore
 * @author      Ammar <ammar@shopgo.me>
 */
abstract class Shopgo_ShippingCore_Helper_Abstract extends Shopgo_Core_Helper_Abstract
{
    /**
     * Log file name
     *
     * @var string
     */
    protected $_logFile = 'shopgo_shipping_core.log';

    /**
     * Improved currency convert method
     *
     * @param float $price
     * @param string $from
     * @param string $to
     * @param string $output
     * @param integer $round
     * @return array
     */
    public function currencyConvert($price, $from, $to, $output = '', $round = null)
    {
        $from = strtoupper($from);
        $to   = strtoupper($to);

        $baseCurrencyCode    = Mage::app()->getStore()->getBaseCurrencyCode();
        $currentCurrencyCode = Mage::app()->getStore()->getCurrentCurrencyCode();

        switch ($from) {
            case '_BASE_':
                $from = $baseCurrencyCode;
                break;
            case '_CURRENT_':
                $from = $currentCurrencyCode;
                break;
        }

        switch ($to) {
            case '_BASE_':
                $to = $baseCurrencyCode;
                break;
            case '_CURRENT_':
                $to = $currentCurrencyCode;
                break;
        }

        $output = strtolower($output);

        $error  = false;
        $result = array('price' => $price, 'currency' => $from);

        if ($from != $to) {
            $allowedCurrencies = Mage::getModel('directory/currency')
                ->getConfigAllowCurrencies();
            $rates = Mage::getModel('directory/currency')
                ->getCurrencyRates($baseCurrencyCode, array_values($allowedCurrencies));

            if (empty($rates) || !isset($rates[$from]) || !isset($rates[$to])) {
                $error = true;
            } elseif (empty($rates[$from]) || empty($rates[$to])) {
                $error = true;
            }

            if ($error) {
                $this->log(
                    sprintf('Currency conversion error: From "%s %s" To "%s"',
                        $price, $from, $to)
                );

                if (isset($result[$output])) {
                    return $result[$output];
                } else {
                    return $result;
                }
            }

            $result = array(
                'price'    => ($price * $rates[$to]) / $rates[$from],
                'currency' => $to
            );
        }

        if (is_int($round)) {
            $result['price'] = round($result['price'], $round);
        }

        if (isset($result[$output])) {
            return $result[$output];
        }

        return $result;
    }

    /**
     * Convert shipping rate to base currency
     *
     * @param float $price
     * @param string $priceCurrencyCode
     * @return array
     */
    public function convertRateCurrency($price, $priceCurrencyCode)
    {
        $baseCurrencyCode = Mage::app()->getStore()->getBaseCurrencyCode();
        $result = array('price' => $price, 'currency' => $priceCurrencyCode);

        if ($priceCurrencyCode != $baseCurrencyCode) {
            $result = $this->currencyConvert($price, $priceCurrencyCode, $baseCurrencyCode);
        }

        return $result;
    }

    /**
     * Check if order's shipping method is a Shopgo shipping method
     *
     * @param string $carrierCode
     * @return boolean
     */
    public function isShopgoShippingMethod($carrierCode)
    {
        $result = false;

        switch (true) {
            case Mage::getModel('shippingcore/carrier_aramex')->isUsed($carrierCode):
                $result = $carrierCode;
                break;
            case Mage::getModel('shippingcore/carrier_skynet')->isUsed($carrierCode):
                $result = $carrierCode;
                break;
        }

        return $result;
    }

    /**
     * Get order's shipping method adminhtml ship page forms
     *
     * @param string $carrierCode
     * @param object $block
     * @return string
     */
    public function getAdminhtmlShipmentForms($carrierCode, $block)
    {
        $html = "";

        switch ($carrierCode) {
            case 'aramex':
                $html = Mage::helper('aramexshipping')->_getAdminhtmlShipmentForms($block);
                break;
            case 'skynet':
                $html = Mage::helper('skynetshipping')->_getAdminhtmlShipmentForms($block);
                break;
        }

        return $html;
    }
}
