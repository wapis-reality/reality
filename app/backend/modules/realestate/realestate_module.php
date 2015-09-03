<?php

class RealestateModule extends ModuleService
{
    public function __construct()
    {
        parent::__construct(__CLASS__);
    }


    function getRealEstateSetting($params = array())
    {
        $lists = array();

        $this->loadModelBroker();
        $lists['Broker'] = $this->BrokerModel->find('list', array('fields'=>array('id','last_name')));

        return $lists;
    }
    function getRealEstateList($params = array())
    {

        $this->loadModelRealEstate();
        $data = $this->RealEstateModel->find('all');
       // $str = debug_pr($data);
       //$this->RealEstateModel->last_sql

        if (count($data) == 0) {
            return ;
        } else {
            return $data;
        }
    }

    function getRealEstateDetail($params = array())
    {

        $id = $params[0];
        $this->loadModelRealEstate();

        $detail = $this->RealEstateModel->find('first', array(
            'conditions' => array(
                'id' => $id
            )
        ));
        if (!$detail) {
            return;
        } else {


            return $detail;
        }
    }

    function saveRealEstateDetail($params = array())
    {

        if (isset($params["form_data"]["RealEstateModel"])) {
            $this->loadModelRealEstate();
            $toSave = $params["form_data"]["RealEstateModel"];

            if ($this->RealEstateModel->save($toSave)) {
                $toSave['id'] = $this->RealEstateModel->id;
                return $toSave;
            }else{
                return false;
            }
        }
        return false;
    }


}