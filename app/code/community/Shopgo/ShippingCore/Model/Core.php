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
 * Core model
 *
 * @category    Shopgo
 * @package     Shopgo_ShippingCore
 * @author      Ammar <ammar@shopgo.me>
 */
class Shopgo_ShippingCore_Model_Core extends Mage_Core_Model_Abstract
{
    /**
     * Save shipment
     *
     * @param Mage_Sales_Model_Order_Shipment $shipment
     * @param array $data
     * @return bool
     */
    public function saveShipment($shipment, $data)
    {
        $result = false;
        $carrierCode = $shipment->getOrder()->getShippingCarrier()->getCarrierCode();

        switch (true) {
            case Mage::getModel('shippingcore/carrier_aramex')->isUsed($carrierCode):
                $result = Mage::getModel('shippingcore/carrier_aramex')
                    ->saveShipment($shipment, $data);
                break;
            case Mage::getModel('shippingcore/carrier_skynet')->isUsed($carrierCode):
                $result = Mage::getModel('shippingcore/carrier_skynet')
                    ->saveShipment($shipment, $data);
                break;
        }

        return $result;
    }

    /**
     * Process shipment tracking info
     *
     * @param array $trackingInfo
     * @param string $carrierCode
     * @return array
     */
    public function processTrackingInfo($trackingInfo, $carrierCode)
    {
        switch (true) {
            case Mage::getModel('shippingcore/carrier_aramex')->isUsed($carrierCode):
                $trackingInfo = Mage::getModel('shippingcore/carrier_aramex')
                    ->processTrackingInfo($trackingInfo);
                break;
            case Mage::getModel('shippingcore/carrier_skynet')->isUsed($carrierCode):
                $trackingInfo = Mage::getModel('shippingcore/carrier_skynet')
                    ->processTrackingInfo($trackingInfo);
                break;
        }

        return $trackingInfo;
    }

    /**
     * Process shipment tracking info by track ID
     *
     * @param array $trackingInfo
     * @param string $carrierCode
     * @return array
     */
    public function processTrackingInfoByTrackId($trackingInfo, $carrierCode)
    {
        switch (true) {
            case Mage::getModel('shippingcore/carrier_aramex')->isUsed($carrierCode):
                $trackingInfo = Mage::getModel('shippingcore/carrier_aramex')
                    ->processTrackingInfoByTrackId($trackingInfo);
                break;
            case Mage::getModel('shippingcore/carrier_skynet')->isUsed($carrierCode):
                $trackingInfo = Mage::getModel('shippingcore/carrier_skynet')
                    ->processTrackingInfoByTrackId($trackingInfo);
                break;
            default:
                $trackingInfo = array(array($trackingInfo));
        }

        return $trackingInfo;
    }
}
