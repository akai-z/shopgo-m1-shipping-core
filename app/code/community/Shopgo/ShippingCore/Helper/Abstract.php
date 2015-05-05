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
     * Dimensional weight attribute length
     */
    const LENGTH = 'length';

    /**
     * Dimensional weight attribute width
     */
    const WIDTH  = 'width';

    /**
     * Dimensional weight attribute height
     */
    const HEIGHT = 'height';


    /**
     * Log file name
     *
     * @var string
     */
    protected $_logFile = 'shopgo_shipping_core.log';

    /**
     * Cash on delivery enabled shipping methods
     *
     * @var array
     */
    protected $_codEnabledShippingMethods = array();


    /**
     * Improved currency convert method
     *
     * @param float $price
     * @param string $from
     * @param string $to
     * @param string $output
     * @param int $round
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

            if (empty($rates) || empty($rates[$from]) || empty($rates[$to])) {
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

    /**
     * Get dimensional weight attributes names
     *
     * @return array
     */
    public function getDwaNames()
    {
        $attributes = array(
            self::LENGTH,
            self::WIDTH,
            self::HEIGHT
        );

        return $attributes;
    }

    /**
     * Get dimensional weight attributes codes
     *
     * @return array
     */
    public function getDwaCodes()
    {
        $path = 'shipping/dwa/';
        $attributes = $this->getDwaNames();
        $result = array();

        foreach ($attributes as $attr) {
            $result[$attr] = Mage::getStoreConfig($path . $attr);
        }

        return $result;
    }

    /**
     * Get cash on delivery method list
     *
     * @param array $toArray
     * @return array|string
     */
    public function getCodMethodList($toArray = true)
    {
        $methods = Mage::getStoreConfig('shipping/cod_method/list');

        return $toArray ? explode(',', $methods) : $methods;
    }

    /**
     * Get/Set/Unset cash on delivery enabled methods session values
     *
     * @param string $action
     * @param mixed $value
     * @return mixed
     */
    public function codEnabledShippingMethods($action = 'get', $value = null)
    {
        $result  = null;
        $session = Mage::getSingleton('checkout/session');

        switch ($action) {
            case 'get':
                $result = $session->getCodEnabledShippingMethods();
                break;
            case 'set':
                $result = $session->setCodEnabledShippingMethods($value);
                break;
            case 'uns':
                $result = $session->unsCodEnabledShippingMethods();
                break;
        }

        return $result;
    }
}
