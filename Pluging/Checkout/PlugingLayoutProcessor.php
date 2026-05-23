<?php
namespace Solusoft\Delivery\Pluging\Checkout;

use Magento\Directory\Model\Currency;
use Magento\Directory\Model\PriceCurrency;
use Magento\Framework\Locale\Format;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use \Magento\Checkout\Block\Checkout\LayoutProcessor;
use Solusoft\Bold\Logger\Logger as customLog;

class PlugingLayoutProcessor
{
    /**
     * @param customLog $customLog
     */
    private customLog $customLog;
    public function __construct(
        customLog $customLog
    ) {
        $this->customLog = $customLog;
    }

    public function afterProcess(
        LayoutProcessor $subject,
        array $jsLayout
    ) {
        $this->customLog->setClassMethodDebug('PlugingLayoutProcessor->afterProcess');
        $obj = $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']['shippingAddress']['children']['shipping-address-fieldset']['children'];
        $this->customLog->debug(json_encode($obj));
        $fieldCode = 'state';
        $fieldLabel = __("Departamento");
        $options = [
            [
                'value' => '1',
                'label' => __('Opción 1')
            ],
            [
                'value' => '2',
                'label' => __('Opción 2')
            ],
            [
                'value' => '3',
                'label' => __('Opción 3')
            ]
        ];
        $department = [
            'component' => 'Solusoft_Delivery/js/form/element/state',
            'config' => [
                'customScope' => 'shippingAddress.custom_attributes',
                'template' => 'ui/form/field',
                'elementTmpl' => 'Solusoft_Delivery/form/element/select',
                'options' => $options,
                'id' => $fieldCode
            ],
            'dataScope' => 'shippingAddress.custom_attributes.'.$fieldCode,
            'label' => $fieldLabel,
            'provider' => 'checkoutProvider',
            'visible' => true,
            'validation' => [
                'required-entry' => true
            ],
            'sortOrder' => 85,
            'id' => $fieldCode
        ];
        $obj[$fieldCode] = $department;
        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']['shippingAddress']['children']['shipping-address-fieldset']['children'] = $obj;
        return $jsLayout;
    }
}
