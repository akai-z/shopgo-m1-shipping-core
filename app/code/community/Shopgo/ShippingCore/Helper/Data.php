<?php

class Shopgo_ShippingCore_Helper_Data extends Shopgo_Core_Helper_Abstract
{
    protected $_logFile = 'shopgo_shipping_core.log';

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

    public function convertRateCurrency($price, $priceCurrencyCode)
    {
        $baseCurrencyCode = Mage::app()->getStore()->getBaseCurrencyCode();
        $result = array('price' => $price, 'currency' => $priceCurrencyCode);

        if ($priceCurrencyCode != $baseCurrencyCode) {
            $result = $this->currencyConvert($price, $priceCurrencyCode, $baseCurrencyCode);
        }

        return $result;
    }
}
