<?php
namespace Solusoft\Delivery\Controller\Ajax;

use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\App\Action\Action;
use Solusoft\Delivery\Api\RegionRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Directory\Model\RegionFactory as RegionInformation;
use Magento\Framework\Api\SortOrderBuilder;
use Magento\Framework\Api\SortOrder;

class City extends Action
{
    /**
     * @var JsonFactory $jsonFactory
     */
    protected $jsonFactory;

    /**
     * @var RegionRepositoryInterface $regionRepositoryInterface
     */
    private RegionRepositoryInterface $regionRepositoryInterface;

    /**
     * @var SearchCriteriaBuilder $searchCriteriaBuilder
     */
    private SearchCriteriaBuilder $searchCriteriaBuilder;

    /**
     * @var RegionInformation $regionInformation
     */
    private RegionInformation $regionInformation;

    /**
     * @var SortOrderBuilder $sortOrderBuilder
     */
    private SortOrderBuilder $sortOrderBuilder;

    /**
     * @param Context $context
     * @param JsonFactory $jsonFactory
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param RegionInformation $regionInformation
     * @param SortOrderBuilder $sortOrderBuilder
     */
    public function __construct(
        Context $context,
        JsonFactory $jsonFactory,
        RegionRepositoryInterface $regionRepositoryInterface,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        RegionInformation $regionInformation,
        SortOrderBuilder $sortOrderBuilder
    ) {
        $this->jsonFactory = $jsonFactory;
        $this->regionRepositoryInterface = $regionRepositoryInterface;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->regionInformation = $regionInformation;
        $this->sortOrderBuilder = $sortOrderBuilder;
        parent::__construct($context);
    }

    /**
     * Method to execute upload photo
     */
    public function execute()
    {
        $result = $this->jsonFactory->create();
        $regionId = (int)$this->getRequest()->getParam('regionId');
        
        $sortOrder = $this->sortOrderBuilder
            ->setField('city_label')
            ->setDirection(SortOrder::SORT_ASC)
            ->create();

        $region = $this->regionInformation
            ->create()
            ->load($regionId);
        $code = $region->getCode();
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('code_country_region', $code, 'eq')
            ->addFilter('status', 'A', 'eq')
            ->addSortOrder($sortOrder)
            ->create();
        $resultSearch = $this->regionRepositoryInterface->getList($searchCriteria);
        $items = $resultSearch->getItems();
        $options = [];
        foreach ($items as $item) {
            $label = $item->getCityLabel();
            $id = $item->getId();
            $options[] = [
                'value' => $id,
                'label' => $label
            ];
        }
        $data = [
            'options' => $options
        ];
        return $result->setData($data);
    }
}
