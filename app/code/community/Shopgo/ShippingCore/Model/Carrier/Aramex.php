<?php

class Shopgo_ShippingCore_Model_Carrier_Aramex extends Shopgo_ShippingCore_Model_Carrier_Abstract
{
    const MODULE_NAME  = 'aramexshipping';
    const CARRIER_CODE = 'aramex';

    public function isEnabled()
    {
        return Mage::helper('core')->isModuleEnabled(self::MODULE_NAME);
    }

    public function isUsed($carrierCode)
    {
        return $this->isEnabled() && $carrierCode == self::CARRIER_CODE;
    }

    public function saveShipment($shipment, $data, $controller)
    {
        $aramexShipment = true;

        if (isset($data['aramex'])) {
            $aramexShipment = Mage::getModel('aramexshipping/shipment')
                ->prepareShipment(
                    $shipment/*Mage::getModel('sales/order')->load($shipment->getOrder()->getId())*/,
                    $data['aramex']
                 );
        }

        if (!$aramexShipment) {
            if (isset($data['aramex']['shipment'])) {
                Mage::getSingleton('adminhtml/session')
                    ->setShipAramexShipmentData($shopgoData['aramex']['shipment']);
            }

            if (isset($data['aramex']['pickup']['enabled'])
                && $data['aramex']['pickup']['enabled']) {
                Mage::getSingleton('adminhtml/session')
                    ->setShipAramexPickupData($data['aramex']['pickup']);
            }

            $controller->_getSession()->addError($controller->__('Cannot save shipment.'));
            $controller->_redirect('*/*/new', array('order_id' => $controller->getRequest()->getParam('order_id')));
            return false;
        }
    }
}
