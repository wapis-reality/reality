<?php

class CompanyModule extends ModuleService
{
    public function __construct()
    {
        parent::__construct(__CLASS__);
    }


    function getCompanyList($params = array())
    {

        $this->loadModelCompany();
        $list = $this->CompanyModel->find('all');

        if (count($list) == 0) {
            return;
        } else {
            return $list;
        }
    }

    function getCompanyDetail($params = array())
    {
        $id = $params[0];
        $this->loadModelCompany();

        $detail = $this->CompanyModel->find('first', array(
            'conditions' => array(
                'id' => $id
            )
        ));
        if (count($detail) == 0) {
            return;
        } else {

            $this->loadModelCompanyContact();
            $contacts = $this->CompanyContactModel->find('all', array(
                'conditions' => array(
                    'company_id' => $id
                ),
                'fields' => array(
                    'first_name',
                    'last_name',
                    'email',
                    'phone'
                )
            ));
            $detail['CompanyContactModel'] = array();
            foreach ($contacts as $contact) {
                $detail['CompanyContactModel'][] = $contact['CompanyContactModel'];
            }

            $detail['OrderModel'] = array(
                array(
                    'name' => 'Spravce site',
                    'profession' => 'IT administrator',
                    'city' => 'Ostrava',
                    'status' => 'Probiha'
                ),
                array(
                    'name' => 'FrontEnd developer',
                    'profession' => 'Developer',
                    'city' => 'Ostrava',
                    'status' => 'Probiha'
                ),
                array(
                    'name' => 'External accounter',
                    'profession' => 'Accounter',
                    'city' => 'London',
                    'status' => 'Dokonceno'
                ),
                array(
                    'name' => '.NET MVC developer',
                    'profession' => '.net developer',
                    'city' => 'London',
                    'status' => 'Vyhodnocovani'
                )
            );

            $detail['OrderDoneModel'] = array(
                array(
                    'name' => 'AngularJS developer',
                    'profession' => 'FrontEnd developer',
                    'city' => 'London',
                    'person' => 'Mike Corp'
                ),
                array(
                    'name' => 'CSS expert',
                    'profession' => 'FrontEnd developer',
                    'city' => 'London',
                    'person' => 'Caterina Blow'
                )

            );


            return $detail;
        }
    }

    function saveCompanyDetail($params = array())
    {
        if (isset($params["form_data"]["CompanyModel"])) {
            $this->loadModelCompany();
            $this->loadModelCompanyContact();
            if ($this->CompanyModel->save($params["form_data"]["CompanyModel"])) {
                $company_id = $this->CompanyModel->id;


                /**
                 * @todo ... dont remove items with exisitng ids in post
                 */
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


}