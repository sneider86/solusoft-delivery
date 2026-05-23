<?php
namespace Solusoft\Delivery\Model;

use Solusoft\Delivery\Model\ResourceModel\Region as resourceModel;
use Solusoft\Delivery\Api\Data\RegionInterface;
use Magento\Framework\Model\AbstractModel;

class Region extends AbstractModel implements RegionInterface
{
    protected function _construct()
    {
        $this->_init(
            resourceModel::class
        );
    }
    
    /**
     * getId
     * @return int
     */
    public function getId()
    {
        return $this->getData(self::ENTITY_ID);
    }

    /**
     * setId
     * @param int $data
     * @return int
     */
    public function setId($data)
    {
        return $this->setData(self::ENTITY_ID, $data);
    }

    /**
     * getCodeCountryRegion
     * @return string
     */
    public function getCodeCountryRegion()
    {
        return $this->getData(self::CODE_COUNTRY_REGION);
    }

    /**
     * setCodeCountryRegion
     * @param string $data
     * @return string
     */
    public function setCodeCountryRegion($data)
    {
        return $this->setData(self::CODE_COUNTRY_REGION, $data);
    }

    /**
     * getCityId
     * @return int
     */
    public function getCityId()
    {
        return $this->getData(self::CITY_ID);
    }

    /**
     * setCityId
     * @param int $data
     * @return int
     */
    public function setCityId($data)
    {
        return $this->setData(self::CITY_ID, $data);
    }

    /**
     * getCityLabel
     * @return string
     */
    public function getCityLabel()
    {
        return $this->getData(self::CITY_LABEL);
    }

    /**
     * setCityLabel
     * @param string $data
     * @return string
     */
    public function setCityLabel($data)
    {
        return $this->setData(self::CITY_LABEL, $data);
    }

    /**
     * getWight
     * @return double
     */
    public function getWight()
    {
        return $this->getData(self::WIGHT);
    }

    /**
     * setWight
     * @param double $data
     * @return double
     */
    public function setWight($data)
    {
        return $this->setData(self::WIGHT, $data);
    }

    /**
     * getPrice
     * @return int
     */
    public function getPrice()
    {
        return $this->getData(self::PRICE);
    }

    /**
     * setPrice
     * @param int $data
     * @return int
     */
    public function setPrice($data)
    {
        return $this->setData(self::PRICE, $data);
    }

    /**
     * getStatus
     * @return string
     */
    public function getStatus()
    {
        return $this->getData(self::STATUS);
    }

    /**
     * setStatus
     * @param string $data
     * @return string
     */
    public function setStatus($data)
    {
        return $this->setData(self::STATUS, $data);
    }
}
