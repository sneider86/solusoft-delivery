<?php
namespace Solusoft\Delivery\Api\Data;

interface RegionInterface
{
    const ENTITY_ID = 'entity_id';
    const CODE_COUNTRY_REGION = 'code_country_region';
    const CITY_ID = 'city_id';
    const CITY_LABEL = 'city_label';
    const WIGHT = 'wight';
    const PRICE = 'price';
    const STATUS = 'status';

    /**
     * getId
     * @return int
     */
    public function getId();

    /**
     * setId
     * @param int $data
     * @return int
     */
    public function setId($data);

    /**
     * getCodeCountryRegion
     * @return string
     */
    public function getCodeCountryRegion();

    /**
     * setCodeCountryRegion
     * @param string $data
     * @return string
     */
    public function setCodeCountryRegion($data);

    /**
     * getCityId
     * @return int
     */
    public function getCityId();

    /**
     * setCityId
     * @param int $data
     * @return int
     */
    public function setCityId($data);

    /**
     * getCityLabel
     * @return string
     */
    public function getCityLabel();

    /**
     * setCityLabel
     * @param string $data
     * @return string
     */
    public function setCityLabel($data);

    /**
     * getWight
     * @return double
     */
    public function getWight();

    /**
     * setWight
     * @param double $data
     * @return double
     */
    public function setWight($data);

    /**
     * getPrice
     * @return int
     */
    public function getPrice();

    /**
     * setPrice
     * @param int $data
     * @return int
     */
    public function setPrice($data);

    /**
     * getStatus
     * @return string
     */
    public function getStatus();

    /**
     * setStatus
     * @param string $data
     * @return string
     */
    public function setStatus($data);
}
