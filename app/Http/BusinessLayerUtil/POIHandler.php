<?php
/**
 * Created by PhpStorm.
 * User: mok00
 * Date: 12/15/2017
 * Time: 11:25 AM
 */

namespace App\Http;


class POIHandler implements  IPOIHandler
{
    private $ipoi_dao;
    const TYPE_SUSPECT = 0;
    const TYPE_CRIMINAL = self::TYPE_SUSPECT + 1;
    const TYPE_VICTIM = self::TYPE_CRIMINAL + 1;
    const TYPE_WITNESS = self::TYPE_VICTIM + 1;
    /**
     * POIHandler constructor.
     * @param $ipoi_dao
     */
    public function __construct(IPOIDaoHandler $ipoi_dao)
    {
        $this->ipoi_dao = $ipoi_dao;
    }

    /**
     * POIHandler constructor.
     * @param $ipolice_dao
     */
    public function getPersonOfInterest($case_id)
    {
        $detail_array = [];
        $poi_list = $this->ipoi_dao->getPersonOfInterest($case_id);
        foreach ($poi_list as $poi){
            $detail = $this->ipoi_dao->getPersonOfInterestDetail($poi['poi_id']);
            array_push ( $detail_array , $detail);
        }
        return $detail_array;
    }


    public function addTestimony($poi_id, $type, $date, $statement)
    {
        // TODO: Implement addTestimony() method.
        return $this->ipoi_dao->addTestimony($poi_id, $type, $date, $statement);
    }

    public function addPersonOfInterest($case_id, $name, $surname, $address, $date)
    {
        // TODO: Implement addPersonOfInterest() method.
        return $this->ipoi_dao->addPersonOfInterest($case_id, $name, $surname, $address, $date);
    }

    public function deletePersonOfInterest($id)
    {
        // TODO: Implement deletePersonOfInterest() method.
    }

    public function modifyPersonOfInterest($poi_id, $type)
    {
        // TODO: Implement modifyPersonOfInterest() method.
        switch ($type){
            case self::TYPE_SUSPECT:
                return $this->ipoi_dao->setSuspect($poi_id);
                break;
            case self::TYPE_CRIMINAL:
                return $this->ipoi_dao->setCriminal($poi_id);
                break;
            case self::TYPE_VICTIM:
                return $this->ipoi_dao->setVictim($poi_id);
                break;
            case self::TYPE_WITNESS:
                return $this->ipoi_dao->setWitness($poi_id);
                break;
            default:
                return null;
        }
    }

    public function getPersonOfInterestRole($poi_id)
    {
        // TODO: Implement getPersonOfInterestRole() method.
        $role_array = array();
        array_push ( $role_array , ["witness" => $this->ipoi_dao->getWitness($poi_id)]);
        array_push ( $role_array , ["criminal" => $this->ipoi_dao->getCriminal($poi_id)]);
        array_push ( $role_array , ["victim" => $this->ipoi_dao->getVictim($poi_id)]);
        array_push ( $role_array , ["suspect" => $this->ipoi_dao->getSuspect($poi_id)]);
        return $role_array;
    }

    public function getTestimony($poi_id)
    {
        // TODO: Implement getTestimony() method.
        return $this->ipoi_dao->getTestimony($poi_id);
    }

    public function getPersonOfInterestDetail($poi_id)
    {
        // TODO: Implement getPersonOfInterestDetail() method.
        return $this->ipoi_dao->getPersonOfInterestDetail($poi_id)->toArray();
    }
}

