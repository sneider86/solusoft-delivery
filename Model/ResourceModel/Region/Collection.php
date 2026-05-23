<?php
namespace Solusoft\Delivery\Model\ResourceModel\Region;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Solusoft\Delivery\Model\Region as Model;
use Solusoft\Delivery\Model\ResourceModel\Region as Resource;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'entity_id';

    protected function _construct()
    {
        $this->_init(
            Model::class,
            Resource::class
        );
    }
}
