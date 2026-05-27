<?php

namespace Solusoft\Delivery\Model\Carrier;

use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Shipping\Model\Carrier\AbstractCarrier;
use Magento\Shipping\Model\Carrier\CarrierInterface;
use Magento\Shipping\Model\Rate\Result;
use Magento\Quote\Model\Quote\Address\RateResult\Method;
use Magento\Shipping\Model\Rate\ResultFactory;
use Magento\Quote\Model\Quote\Address\RateResult\MethodFactory;
use Solusoft\Delivery\Api\RegionRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;

class ShippingMethod extends AbstractCarrier implements CarrierInterface
{
    /**
     * Code of the carrier
     */
    public const CODE = 'solusoftshipping';
    protected $_code = self::CODE;

    protected $rateResultFactory;
    protected $rateMethodFactory;

    /**
     *  @var RegionRepositoryInterface $regionRepositoryInterface
     */
    private RegionRepositoryInterface $regionRepositoryInterface;

    /**
     * @var SearchCriteriaBuilder $searchCriteriaBuilder
     */
    private SearchCriteriaBuilder $searchCriteriaBuilder;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory,
        \Psr\Log\LoggerInterface $logger,
        ResultFactory $rateResultFactory,
        MethodFactory $rateMethodFactory,
        RegionRepositoryInterface $regionRepositoryInterface,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        array $data = []
    ) {
        parent::__construct(
            $scopeConfig,
            $rateErrorFactory,
            $logger,
            $data
        );

        $this->rateResultFactory = $rateResultFactory;
        $this->rateMethodFactory = $rateMethodFactory;
        $this->regionRepositoryInterface = $regionRepositoryInterface;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    public function collectRates(RateRequest $request)
    {
        if (!$this->getConfigFlag('active')) {
            return false;
        }

        /** @var Result $result */
        $result = $this->rateResultFactory->create();

        /** @var Method $method */
        $method = $this->rateMethodFactory->create();

        $method->setCarrier($this->_code);
        $method->setCarrierTitle($this->getConfigData('title'));

        $method->setMethod($this->_code);
        $method->setMethodTitle($this->getConfigData('name'));

        $amount = (float)$this->getConfigData('price');

        $code = $request->getDestRegionCode();
        $city = $request->getDestCity();

        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('code_country_region', $code, 'eq')
            ->addFilter('city_label', $city, 'eq')
            ->addFilter('status', 'A', 'eq')
            ->create();

        $resultSearch = $this->regionRepositoryInterface->getList($searchCriteria);
        $items = $resultSearch->getItems();
        if (count($items)==0) {
            return false;
        }
        foreach ($items as $item) {
            $amount = (float)$item->getPrice();
        }

        $method->setPrice($amount);
        $method->setCost($amount);

        $result->append($method);

        return $result;
    }

    public function getAllowedMethods()
    {
        return [
            $this->_code => $this->getConfigData('name')
        ];
    }
}