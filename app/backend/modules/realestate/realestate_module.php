<?php

class RealestateModule extends ModuleService
{
    public function __construct()
    {
        parent::__construct(__CLASS__);
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
        $this->RealEstateModel();

        $detail = $this->RealEstateModel->find('first', array(
            'conditions' => array(
                'id' => $id
            )
        ));
        if (count($detail) == 0) {
            return;
        } else {


            return $detail;
        }
    }
/*
    function saveCompanyDetail($params = array())
    {
        if (isset($params["form_data"]["CompanyModel"])) {
            $this->loadModelCompany();
            $this->loadModelCompanyContact();
            if ($this->CompanyModel->save($params["form_data"]["CompanyModel"])) {
                $company_id = $this->CompanyModel->id;



                if (isset($params["form_data"]["CompanyContactModel"])) {
                    $this->CompanyContactModel->deleteAll(array("company_id" => $company_id));
                }

                $saved = array();

                foreach ($params["form_data"]["CompanyContactModel"] as $contact) {
                    $contact['company_id'] = $company_id;
                    if (!$this->CompanyContactModel->save($contact)) {
                        return $this->CompanyContactModel->last_query;
                    }
                    $this->CompanyContactModel->id = null;
                    $this->CompanyContactModel->data = null;

                    $saved[] = $contact;
                }

                return $saved;

//                //Add files into database
//                $this->RequestFilesModel->deleteAll(array("request_id" => $request_id));
//                $files = $params["form_data"]["RequestsModel"]["files"];
//                if($files){
//                    foreach($files as $file){
//                        $data = array("request_id" => $request_id, "filename" => $file);
//                        $this->RequestFilesModel->save($data);
//                        //upload file from other server
//                        //copy("http://rewardr_sip:8888/app/modules/request/php/files/".$file,"modules/request/files/".$file);
//                    }
//                }
                return $company_id;
            }
        }
        return false;
    }
*/

}