<?php
namespace Solusoft\Delivery\Model;

use Solusoft\Delivery\Api\RegionRepositoryInterface as RepositoryInterface;
use Solusoft\Delivery\Api\Data\RegionInterface as DataInterface;
use Solusoft\Delivery\Model\ResourceModel\Region as resource;
use Solusoft\Delivery\Model\ResourceModel\Region\CollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchResults;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Solusoft\Delivery\Model\RegionFactory as ModelFactory;
use Magento\Framework\Api\SearchResultsFactory;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Api\SortOrderBuilder;

class RegionRepository implements RepositoryInterface
{
    /**
     * @var resource $resource
     */
    private resource $resource;

    /**
     * @var ModelFactory $modelFactory
     */
    private ModelFactory $modelFactory;

    /**
     * @var CollectionFactory $collectionFactory
     */
    private CollectionFactory $collectionFactory;

    /**
     * @var CollectionProcessorInterface $collectionProcessor
     */
    private CollectionProcessorInterface $collectionProcessor;

    /**
     * @var SearchResultsFactory $searchResultsFactory
     */
    private SearchResultsFactory $searchResultsFactory;

    /**
     * @var SearchCriteriaBuilder $searchCriteriaBuilder
     */
    private SearchCriteriaBuilder $searchCriteriaBuilder;

    /**
     * @var SortOrderBuilder $sortOrderBuilder
     */
    private SortOrderBuilder $sortOrderBuilder;

    /**
     * @param resource $resource
     * @param ModelFactory $modelFactory
     * @param CollectionFactory $collectionFactory
     * @param CollectionProcessorInterface $collectionProcessor
     * @param SearchResultsFactory $searchResultsFactory
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param SortOrderBuilder $sortOrderBuilder
     */
    public function __construct(
        resource $resource,
        ModelFactory $modelFactory,
        CollectionFactory $collectionFactory,
        CollectionProcessorInterface $collectionProcessor,
        SearchResultsFactory $searchResultsFactory,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        SortOrderBuilder $sortOrderBuilder
    ) {
        $this->resource                 = $resource;
        $this->modelFactory             = $modelFactory;
        $this->collectionFactory        = $collectionFactory;
        $this->collectionProcessor      = $collectionProcessor;
        $this->searchResultsFactory     = $searchResultsFactory;
        $this->searchCriteriaBuilder    = $searchCriteriaBuilder;
        $this->sortOrderBuilder         = $sortOrderBuilder;
    }

    /**
     * Method save
     * @param DataInterface $dataInterface
     * @return DataInterface
     */
    public function save(DataInterface $dataInterface)
    {
        try {
            $this->resource->save($dataInterface);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        }
        return $dataInterface;
    }

    /**
     * Method getById
     * @param int $entityId
     * @return DataInterface
     */
    public function getById($entityId)
    {
        $modelFactory = $this->modelFactory->create();
        $this->resource->load($modelFactory, $entityId);
        if (!$modelFactory->getId()) {
            throw new NoSuchEntityException(__('modelFactory with id "%1" does not exist.', $entityId));
        }
        return $modelFactory;
    }

    /**
     * Method getList
     * @param SearchCriteriaInterface $criteria
     * @return SearchResults
     */
    public function getList(SearchCriteriaInterface $criteria)
    {
        $collection = $this->collectionFactory->create();
        $this->collectionProcessor->process($criteria, $collection);
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * Method getListByStatus
     * @param string $status
     * @return SearchResults
     */
    public function getListByStatus(string $status)
    {
        $collection = $this->collectionFactory->create();
        $this->searchCriteriaBuilder->addFilter('status', $status);
        $sortOrder = $this->sortOrderBuilder
        ->setField('sord')
        ->setDirection(SortOrder::SORT_ASC)
        ->create();

        $criteria = $this->searchCriteriaBuilder
            ->addSortOrder($sortOrder)
            ->create();

        $this->collectionProcessor->process($criteria, $collection);
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * Method delete
     * @param DataInterface $dataInterface
     * @return DataInterface
     */
    public function delete(DataInterface $dataInterface)
    {
        try {
            $this->resource->delete($dataInterface);
        } catch (\Exception $e) {
            throw new CouldNotDeleteException(__($e->getMessage()));
        }
        return true;
    }

    /**
     * Method deleteById
     * @param int $entityId
     * @return DataInterface
     */
    public function deleteById($entityId)
    {
        return $this->delete($this->getById($entityId));
    }
}
