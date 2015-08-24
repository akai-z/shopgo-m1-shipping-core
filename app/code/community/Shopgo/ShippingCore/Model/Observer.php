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
 * Observer model
 *
 * @category    Shopgo
 * @package     Shopgo_ShippingCore
 * @author      Ammar <ammar@shopgo.me>
 */
class Shopgo_ShippingCore_Model_Observer
{
    /**
     * Check whether used shipment CSS and JS files
     * can be included or not
     *
     * @param Varien_Event_Observer $observer
     * @return null
     */
    public function setAdminSalesOrderShipmentNewCssJsFiles(Varien_Event_Observer $observer)
    {
        $request  = Mage::app()->getRequest();
        $shipment = Mage::registry('current_shipment');
        $data = $observer->getEvent()->getData();
        $requestPathParts = array(
            'route'      => 'adminhtml',
            'controller' => 'sales_order_shipment',
            'action'     => 'new'
        );

        //TODO: This could be implemented in a better and cleaner way
        $isCorrectRequest = $request->getRouteName() != $requestPathParts['route']
            || $request->getControllerName() != $requestPathParts['controller']
            || $request->getActionName() != $requestPathParts['action'];

        if ($isCorrectRequest
            || !$data->getCheckUsedMethod()
            || empty($shipment)) {
            $data->setIfconfig(true);
            return;
        }

        $carrierCode = $shipment
            ->getOrder()
            ->getShippingCarrier()
            ->getCarrierCode();

        if ($carrierCode != $data->getCheckUsedMethod()) {
            $data->setIfconfig(false);
            return;
        }

        switch (true) {
            case Mage::getModel('shippingcore/carrier_aramex')->isUsed($carrierCode):
                $data->setIfconfig(true);
                break;
            case Mage::getModel('shippingcore/carrier_skynet')->isUsed($carrierCode):
                $data->setIfconfig(true);
                break;
        }
    }

    /**
     * Remove non cash on delivery methods from checkout page payment step
     *
     * @param Varien_Event_Observer $observer
     * @return null
     */
    public function filterOutNonCodPaymentMethods(Varien_Event_Observer $observer)
    {
        $session = Mage::getSingleton('checkout/session');

        if (!$session->hasQuote()) {
            return;
        }

        $helper = Mage::helper('shippingcore');
        $isUsingCod = false;

        $shippingMethod = $session->getQuote()->getShippingAddress()->getShippingMethod();

        $codFilteringEnabledShippingMethods = $helper->codFilteringEnabledShippingMethods();

        if (is_array($codFilteringEnabledShippingMethods)
            && in_array($shippingMethod, $codFilteringEnabledShippingMethods)) {
            $codMethods = $helper->getCodMethodList();

            $store  = Mage::app()->getStore();
            $result = $observer->getEvent()->getResult();

            $method = $observer->getEvent()->getMethodInstance();
            $methodCode = $method->getCode();

            $isMethodActive = Mage::getStoreConfigFlag(
                'payment/' . $methodCode . '/active', $store
            );

            $result->isAvailable =
                ($isMethodActive && in_array($methodCode, $codMethods))
                ? true : false;
        }
    }
}
