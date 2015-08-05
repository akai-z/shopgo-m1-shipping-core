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
            $result = $this->currencyConvert(
                $price, $priceCurrencyCode, $baseCurrencyCode
            );
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
     * @param Mage_Adminhtml_Block_Sales_Order_Shipment_Create_Form $block
     * @return string
     */
    public function getAdminhtmlShipmentForms($carrierCode, $block)
    {
        $html = "";

        switch ($carrierCode) {
            case 'aramex':
                $html = Mage::helper('aramexshipping')
                    ->_getAdminhtmlShipmentForms($block);
                break;
            case 'skynet':
                $html = Mage::helper('skynetshipping')
                    ->_getAdminhtmlShipmentForms($block);
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
        $dwaSettings = $this->getShippingSettings('dwa');
        $attributes  = $this->getDwaNames();
        $result = array();

        foreach ($attributes as $attr) {
            $result[$attr] = $dwaSettings[$attr];
        }

        return $result;
    }

    /**
     * Get cash on delivery methods list
     *
     * @param array $toArray
     * @return array|string
     */
    public function getCodMethodList($toArray = true)
    {
        $codSettings = $this->getShippingSettings('cod');
        $methods     = $codSettings['payment_methods'];

        return $toArray ? explode(',', $methods) : $methods;
    }

    /**
     * Get/Set/Unset cash on delivery filtering enabled shipping methods session values
     *
     * @param string $action
     * @param array $value
     * @return mixed
     */
    public function codFilteringEnabledShippingMethods($action = 'get', $value = array())
    {
        $result  = null;
        $session = Mage::getSingleton('checkout/session');

        switch ($action) {
            case 'get':
                $result = $session->getCodFilteringEnabledShippingMethods();
                break;
            case 'set':
                $result = $session->setCodFilteringEnabledShippingMethods($value);
                break;
            case 'uns':
                $result = $session->unsCodFilteringEnabledShippingMethods();
                break;
        }

        return $result;
    }

    /**
     * Convert street address to a single line
     *
     * @param string $address
     * @return string
     */
    public function getSingleLineStreetAddress($address)
    {
        return is_string($address)
            ? trim(preg_replace('/\s+/', ' ', $address)) // Replace newlines with spaces
            : $address;
    }

    /**
     * Get cash on delivery currency
     *
     * @param Mage_Sales_Model_Order|null $order
     * @return string
     */
    public function getCodCurrency($order = null)
    {
        $currency    = Mage::app()->getStore()->getBaseCurrencyCode();
        $codSettings = $this->getShippingSettings('cod');

        switch ($codSettings['currency']) {
            case Shopgo_ShippingCore_Model_System_Config_Source_Codcurrency::ORDER:
                if (is_object($order) && $order instanceof Mage_Sales_Model_Order) {
                    $currency = $order->getOrderCurrencyCode();
                }
                break;
            case Shopgo_ShippingCore_Model_System_Config_Source_Codcurrency::CURRENT:
                $currency = Mage::app()->getStore()->getCurrentCurrencyCode();
                break;
            case Shopgo_ShippingCore_Model_System_Config_Source_Codcurrency::SPECIFIC:
                if ($codSettings['specific_currency']) {
                    $allowedCurrencies =
                        Mage::getModel('shippingcore/system_config_source_allowedcurrencies')
                            ->toOptionArray();

                    foreach ($allowedCurrencies as $ac) {
                        if ($codSettings['specific_currency'] == $ac['value']) {
                            $currency = $codSettings['specific_currency'];
                            break;
                        }
                    }
                }
                break;
        }

        return $currency;
    }

    /**
     * Get system configuration sales shipping settings
     *
     * @param string|array $group
     * @return array
     */
    public function getShippingSettings($group = '')
    {
        $settings = array(
            'origin' => array('street_line3'),
            'additional_info' => array(
                'person_title',  'person_name',
                'company',       'store_description',
                'phone_number',  'phone_number_ext',
                'phone_number2', 'phone_number2_ext',
                'faxnumber',     'email',
                'cellphone'
            ),
            'dwa' => array(
                'length', 'width', 'height'
            ),
            'cod' => array(
                'payment_methods', 'currency', 'specific_currency'
            )
        );

        switch (true) {
            case is_string($group) && isset($settings[$group]):
                $settings = $settings[$group];

                foreach ($settings as $field) {
                    $settings[$field] = Mage::getStoreConfig(
                        'shipping/' . $group . '/' . $field
                    );
                }

                break;
            case is_array($group) && !empty($group):
                foreach ($settings as $_group => $fields) {
                    if (!in_array($_group, $group)) {
                        continue;
                    }
                    foreach ($fields as $field) {
                        $settings[$_group][$field] = Mage::getStoreConfig(
                            'shipping/' . $_group . '/' . $field
                        );
                    }
                }

                break;
            default:
                foreach ($settings as $_group => $fields) {
                    foreach ($fields as $field) {
                        $settings[$_group][$field] = Mage::getStoreConfig(
                            'shipping/' . $_group . '/' . $field
                        );
                    }
                }
        }

        return $settings;
    }
}
