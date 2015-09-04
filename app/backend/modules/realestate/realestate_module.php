<?php

class RealestateModule extends ModuleService
{
    public function __construct()
    {
        parent::__construct(__CLASS__);
    }



    function getRealEstateList($params = array())
    {
        $data = array();

        $this->loadModelRealEstate();
        $this->RealEstateModel->bindModel(array(
            'Client'=>array(
                'type'=>'belongsTo'
            ),
            'Broker'=>array(
                'type'=>'belongsTo'
            )
        ));
        $data['items'] = $this->RealEstateModel->find('all');

        $this->loadModelBroker();
        $data['lists']['Brokers'] = $this->BrokerModel->find('list', array('fields'=>array('BrokerModel.id','BrokerModel.last_name')));

        $this->loadModelClient();
        $data['lists']['Clients'] = $this->ClientModel->find('list', array('fields'=>array('ClientModel.id','ClientModel.last_name')));

        $data['lists']['Types'] = array(1=>'Dum',2=>'Byt',3=>'Pozemek');
        $data['lists']['Subtypes'] = array(1=>'Chata',2=>'Rodinny',3=>'Činžovní',4=>'Na klíč', 5=>'Dřevostavba');
        $data['lists']['Functions'] = array(1=>'Prodej',2=>'Pronajem');

        return $data;
        /*if (count($data) == 0) {
            return ;
        } else {
            return $data;
        }*/
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