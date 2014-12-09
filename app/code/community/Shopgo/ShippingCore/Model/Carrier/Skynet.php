<?php

class Shopgo_ShippingCore_Model_Carrier_Skynet extends Shopgo_ShippingCore_Model_Carrier_Abstract
{
    const MODULE_NAME  = 'Shopgo_SkynetShipping';
    const CARRIER_CODE = 'skynet';

    public function isEnabled()
    {
        return Mage::helper('core')->isModuleEnabled(self::MODULE_NAME);
    }

    public function isUsed($carrierCode)
    {
        return $this->isEnabled() && $carrierCode == self::CARRIER_CODE;
    }

    public function saveShipment($shipment, $data)
    {
        // TODO
    }
}
