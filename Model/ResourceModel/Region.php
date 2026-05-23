<?php
namespace Solusoft\Delivery\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Region extends AbstractDb
{
    protected function _construct()
    {
        $this->_init(
            'solusoft_region_city',
            'entity_id'
        );
    }

    /**
     * Method saveMultiple
     * @param array $rows
     * @param int $batchSize
     */
    public function saveMultiple($rows = [], $batchSize = 1000)
    {
        if (empty($rows)) {
            return;
        }

        $connection = $this->getConnection();
        $table = $this->getMainTable();

        try {
            $connection->beginTransaction();
            $chunks = array_chunk($rows, $batchSize);
            foreach ($chunks as $chunk) {
                $connection->insertMultiple($table, $chunk);
            }
            $connection->commit();
        } catch (\Exception $e) {
            $connection->rollBack();
            throw new LocalizedException(__('Error inserting multiple rows: %1', $e->getMessage()));
        }
    }
}
