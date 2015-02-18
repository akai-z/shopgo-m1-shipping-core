<?php
/**
 * ShopGo
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the GNU General Public License (GPLv2)
 * that is bundled with this package in the file COPYING.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-2.0.html
 *
 * @category    Shopgo
 * @package     Shopgo_ShippingCore
 * @copyright   Copyright (c) 2014 Shopgo. (http://www.shopgo.me)
 * @license     http://www.gnu.org/licenses/gpl-2.0.html  GNU General Public License (GPLv2)
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
     * @param object $observer
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
}
