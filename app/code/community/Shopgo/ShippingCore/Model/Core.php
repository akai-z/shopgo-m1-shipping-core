<?php

class Shopgo_ShippingCore_Model_Core extends Mage_Core_Model_Abstract
{
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
