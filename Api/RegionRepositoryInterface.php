<?php
namespace Solusoft\Delivery\Api;

use Solusoft\Delivery\Api\Data\RegionInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;

interface RegionRepositoryInterface
{
    public function save(RegionInterface $region);

    public function getById($entityId);

    public function getList(SearchCriteriaInterface $criteria);

    public function delete(RegionInterface $region);

    public function deleteById($entityId);
}
