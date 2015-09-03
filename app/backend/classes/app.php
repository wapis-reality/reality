<?php

class AppClass
{

    var $params = array();

    /**
     * @param $name
     * @param $parameters
     */
    final public function __call($name, $parameters)
    {
        echo 'You called non-existing method "' . $name . '" with following parameters:<br/>';
    }
}


class ServiceHandlerClass
{
    public function __call($method = null, $params = null)
    {


        if($method === null || $params === null){

            $output = array("parameters" => array(
                "status" => 0,
                "data" => array(),
                "message" => "Incorrect method/parameters"
            ));
            return json_encode($output);
        }

        /**
         * Convert params to array before passing them to the ServiceRegistry
         */

        $params = json_decode($params[0], 1);

        /**
         * Receive data from specific module
         */
        $data = ServiceRegistry::getInstance()->run($method, $params);

       // if(!empty($data)) {

            /**
             * If ServiceRegistry returned data, send back to client
             */
            $output = array("parameters" => array(
                "status" => 1,
                "data" => $data
            ));
            return json_encode($output);

//        } else {
//
//            /**
//             * If ServiceRegistry returned nothing, send error message
//             */
//            $output = array("parameters" => array(
//                "status" => 0,
//                "data" => $data,
//                "message" => "Something went wrong in generating the data"
//            ));
//            return json_encode($output);
//        }

    }

    /**
     *  Create response to insert into the body of the SOAP response message
     */
    private function createResponse($name, $data)
    {
        $xml = new SimpleXMLElement('<root/>');
        $this->array2Xml($xml, $data);
        $result = $xml->$name->asXML();
        return $result;
    }

    /**
     *  Convert PHP array to XML
     */
    private function array2Xml(SimpleXMLElement $object, array $data)
    {
        foreach ($data as $key => $value) {
            if(is_numeric($key)) { //If the key is number I can't generate xml if the tagname is integer
                $this->array2Xml($object,$value);
            } else {
                if (is_array($value)) {
                    $new_object = $object->addChild($key);
                    $this->array2Xml($new_object, $value);
                } else {
                    $object->addChild($key, $value);
                }
            }
        }
    }


}


class App extends AppClass
{

    /**
     *
     */
    function __construct()
    {

        if (isset($_GET['url'])) {
            $url = trim($_GET['url'], '/');
            $this->params = router($url);
            $params = isset($this->params['pass']) ? $this->params['pass'] : array();
            $method = $this->params['action'];

            if ($this->params['controller'] == 'app') {
                switch (count($params)) {
                    case 0:
                        return $this->{$method}();
                    case 1:
                        return $this->{$method}($params[0]);
                    case 2:
                        return $this->{$method}($params[0], $params[1]);
                    case 3:
                        return $this->{$method}($params[0], $params[1], $params[2]);
                    case 4:
                        return $this->{$method}($params[0], $params[1], $params[2], $params[3]);
                    case 5:
                        return $this->{$method}($params[0], $params[1], $params[2], $params[3], $params[4]);
                    default:
                        return call_user_func_array(array(&$this, $method), $params);
                        break;
                }
            }
        }
    }

    function server()
    {
        ini_set("soap.wsdl_cache_enabled", "0");
        $server = new SoapServer("service.wsdl");
        $server->setClass("ServiceHandlerClass");
        $server->handle();
    }

    function widgets(){

        echo ServiceRegistry::getInstance()->run('widget_setting', array());
    }

    function zbynekRun(){

        echo 'sasasaas';

        $params = array(
            "email"=>"test@test",
            "password"=>md5("test")
        );

        $data = ServiceRegistry::getInstance()->run('userAuth', array($params));

        pr($data);
    }

    /**
     *
     */
    function run()
    {
//        $params = array();
//
//        $params['client_id'] = 99;
//        $params['scheme_id'] = 99;
//
//        // Benefits
//        $params['BenefitModel'] = array(
//            'name' => 'test_benefit',
//            'alias' => 'benefit_alias',
//            'category' => '1'
//        );
//
//        // Benefit Eligiblities
//        $params['BenefitEligibilityModel'] = array(
//            array(
//                'variable' => 'employee.age',
//                'symbol' => '>',
//                'value' => 40
//            ),
//            array(
//                'variable' => 'employee.salary',
//                'symbol' => '<',
//                'value' => 30000
//            )
//        );
//
//        // Benefit Covers
//        $params['BenefitCoversModel'] = array(
//            array(
//                'name' => 'Cover1',
//                'band' => 'Cover1 Band',
//                'validation' => 'Employee Only',
//                'rate' => array(
//                    'ee_rate' => 10,
//                    'er_rate' => 15
//                ),
//                'parameters' => array(
//                    'gender' => array(
//                        'value1' => 'M'
//                    ),
//                    'age' => array(
//                        'value1' => '18',
//                        'value2' => '22',
//                    )
//                )
//            ),
//            array(
//                'name' => 'Cover2',
//                'band' => 'Cover2 Band',
//                'validation' => 'Employee Only',
//                'rate' => array(
//                    'ee_rate' => 5,
//                    'er_rate' => 5
//                ),
//                'parameters' => array(
//                    'gender' => array(
//                        'value1' => 'F'
//                    ),
//                    'age' => array(
//                        'value1' => '18',
//                        'value2' => '22',
//                    )
//                )
//            ),
//        );
//
//        pr("----- INPUT -----");
//        pr($params);
//
//        $data = ServiceRegistry::getInstance()->run('saveBenefitItem', array($params));
//
//        pr("----- OUTPUT -----");
//        pr($data);

        $params = array();
        $params['client_id'] = 41;
        $params['scheme_id'] = 405;
        $params['form_data'] = array(
            'effective_date' => '2015-11-01',
            'name' => 'Test renewal'
        );

        pr("----- INPUT -----");
        pr($params);

        pr("----- SERVICE -----");
        $data = ServiceRegistry::getInstance()->run('createRenewal', array($params));

        pr("----- OUTPUT -----");
        pr($data);


    }

    function run1()
    {


        $params = array();
        $params['client_id'] = 99;
        $params['scheme_id'] = 99;
        $params['id'] = 31;

        pr("----- INPUT -----");
        pr($params);

        $data = ServiceRegistry::getInstance()->run('findBenefitItem', array($params));

        pr("----- OUTPUT -----");
        pr($data);
        pr(json_encode($data));


    }


    //I don't want conflict do not use mine :D :D use yours :D
    function run_laszlo()
    {
        $params = array();
        $params['client_id'] = 59;
        $params['scheme_id'] = 540;
        $params["form_data"] = array("BenefitModel"=>array("benefit_type"=>1,"calc"=>"Other","other_calc"=>"","category"=>15,"name"=>"ss","provider"=>"asf"));
//        $params['client_id'] = 40;
//        $params['scheme_id'] = 340;
//        $params['id'] = 31;

//        pr("----- INPUT -----");
//        $params = array("module"=>"trs");

//        $data = ServiceRegistry::getInstance()->run('saveBenefitItem', array($params));
//        $data = ServiceRegistry::getInstance()->run('downloadFactFind', array($params));
        $data = ServiceRegistry::getInstance()->run('calculateQuestions', array(array("scheme_id"=>"509","client_id"=>1,"form_data"=>array("action"=>"updateStatus","module"=>"faq"))));

        pr("----- OUTPUT -----");
        pr($data);
        pr(json_encode($data));
        die("end");
    }

    function run_client_scheme()
    {
        $params = array();
        $params[0] = 41;
        $params['form_data'] = array(
            'Modules' => array(
                array(
                    'name' => 'Home',
                    'key' => 'home'
                ),
                array(
                    'name' => 'Reports',
                    'key' => 'reports'
                ),
                array(
                    'name' => 'Flex',
                    'key' => 'flex'
                ),
                array(
                    'name' => 'TRS',
                    'key' => 'trs'
                )
            ),
            'ClientScheme' => array(
                'name' => 'test_scheme_name',
                'effective_date' => '2015-12-25'
            )

        );

        pr("----- INPUT -----");
        pr($params);

        pr("----- FUNCTION -----");
        $data = ServiceRegistry::getInstance()->run('saveClientScheme', array($params));

        pr("----- OUTPUT -----");
        pr($data);
    }

    function runEnrico()
    {
//        $json_resp = '{"result":true,"data":{"0":"71","form_data":{"Modules":{"modul":[{"name":"Flex","key":"flex","value":true},{"name":"TRS","key":"trs","value":true},{"name":"Reports","key":"reports","value":false},{"name":"Data Validation","key":"data_validation","value":false},{"name":"CMS","key":"cms","value":false},{"name":"FAQ","key":"faq","value":false},{"name":"Absence planner","key":"absence_planner","value":false},{"name":"AE","key":"pension","value":false},{"name":"Shares","key":"shares","value":false},{"name":"P60","key":"p60","value":false},{"name":"Payslips","key":"payslips","value":false}]},"selected_benefit_type":{"1":1,"10":true,"11":true,"12":true},"selected_benefits":{"1":[{"BenefitModel":{"id":"101","name":"sadfasdfdsaf"},"BenefitType":{"id":"1","name":"Car"},"Client":{"name":"Mercedes Benz","id":"59"}}],"2":[],"3":[],"4":[],"5":[],"6":[],"7":[],"8":[],"9":[],"10":[],"11":[],"12":[],"13":[],"14":[],"15":[],"16":[],"17":[],"18":[]},"ClientScheme":{"life_events_effective_date":"1stFollowingM","payroll_frequency":"monthly","security":{"reset_frequency":90,"incorrect_attempts":5,"first_login":"random","username":"email","distribution":"email","annual_reset":"yes","password_uppercase":true,"password_lowercase":1,"password_numbers":1,"password_spec_character":1},"name":"scheme client 2","effective_date":"2015-08-22","email":"contact email","phone_number":"phone number"}}}}';
//        $params = json_decode($json_resp, 1);

        $json_resp = '{"result":true,"data":{"client_id":71,"scheme_id":595,"form_data":{"BenefitModel":{"id":"215","name":"Good Benefit","benefit_type":"1","category":"16","calc":"Value \/ 12","other_calc":"","provider":"asf","flex":"1","selection_type":"Cover Levels","display_option":"Radio Buttons","renewal_selection":"Default","termination":"ProRata","charging":"Annually","client_id":"71","scheme_id":"595","kos":"0","status":"1","created":"2015-08-24 15:38:45","updated":"2015-08-25 17:23:40","salarySacChecked":"0","taxFreeChecked":"0","nIFreeChecked":"0","p11DReportChecked":"0","spouseBenefitChecked":"0","sameLevel":"","stepRestrictionChecked":"0","stepType":"","step_up_per_year":"0","step_up_per_enrol":"0","step_down_per_year":"0","step_down_per_enrol":"0","fundingChecked":"0","tradeDownChecked":"0","trade":"","hideIneligibleChecked":"0","trsSelect":"Yes","trsDescription":"","description":"","tooltip_description":"","default":"asf","benefit_unique_id":"","core_amount":"","penSalaryDefinition":"","holSalaryDefinition":"","initialDefault":"asd","eeNIFreeChecked":"0","erNIFreeChecked":"0","penAdvancedChecked":"0","holAdvancedChecked":"0","minimumTotalDays":"0","maximumTotalDays":"0","increment":"0","minimum":"0","maximum":"0","ageBasis":"At Scheme Start","total_question":"12","answered_question":"12"},"files":[{"BenefitFilesModel":{"id":"107","created_by":null,"benefit_id":"215","filename":"links_a56ff1a3s.txt","row":"0","status":"1","kos":"0","created":"2015-08-25 17:23:40","updated":"2015-08-25 17:23:40"}},{"BenefitFilesModel":{"id":"108","created_by":null,"benefit_id":"215","filename":"Payroll giving amend 15_cd55701c5.pdf","row":"0","status":"1","kos":"0","created":"2015-08-25 17:23:40","updated":"2015-08-25 17:23:40"}},{"BenefitFilesModel":{"id":"109","created_by":null,"benefit_id":"215","filename":"Payroll giving amend 15(1)_a8722d2c).pdf","row":"0","status":"1","kos":"0","created":"2015-08-25 17:23:40","updated":"2015-08-25 17:23:40"}}],"BenefitRate":{"ages":[],"rates":[],"validation":[],"price":[],"rates_list":[],"covers":[{"coverName":"test","validation":"Employee Only","key":1440519968000},{"coverName":"test","validation":"Couple","key":1440519974086}],"gender":["None"],"eligibilityGroup":["All"],"rateData":{"All":{"1440519968000":{"None":{"ee":"1","er":"1"}},"1440519974086":{"None":{"ee":"2","er":"2"}}}}},"selectOptions":{"benefit_categories":{"1":"Protection","2":"Wellbeing","15":"Lifestyle","16":"Finance"},"benefit_types":{"1":"Car","2":"Childcare Vouchers","3":"Cycle2Work","4":"Dental","5":"Gourmet Society","6":"Gym","7":"Health Cash","8":"Health Screen","9":"Holiday trading","10":"Income protection","11":"Life assurance","12":"Loan","13":"Mobile","14":"Pension","15":"PMI","16":"Tecnology scheme","17":"Travel insurance","18":"Workplace giving"}},"items":["links_a56ff1a3s.txt","Payroll giving amend 15_cd55701c5.pdf","Payroll giving amend 15(1)_a8722d2c).pdf"],"BenefitEligibilityModel":[],"BenefitCoversModel":[],"BenefitPensionModel":[]}}}';
        $params = json_decode($json_resp, 1);
        pr($params);


//        $test = '{ "ages": [], "rates": [], "validation": [], "price": [], "rates_list": [], "covers": [ { "coverName": "test", "validation": "None", "key": 1440518434978 }, { "coverName": "test", "validation": "Employee Only", "key": 1440518579502 }, { "coverName": "test", "validation": "Couple", "key": 1440518579964 } ], "gender": [ "Male", "Female" ], "eligibilityGroup": [ "Directors" ] } ';
//        $params = json_decode($test, 1);
//        pr($params);
//
//        $test1 = '{ "All": { "1440518434978": { "None": { "ee": "1", "er": "1" } }, "1440518579502": { "None": { "ee": "2", "er": "2" } }, "1440518579964": { "None": { "ee": "3", "er": "3" } } }, "Directors": { "1440518434978": { "None": { "ee": "4", "er": "4" }, "Male": { "ee": "1", "er": "1" }, "Female": { "ee": "2", "er": "2" } }, "1440518579502": { "None": { "ee": "5", "er": "5" }, "Male": { "ee": "3", "er": "3" }, "Female": { "ee": "4", "er": "4" } }, "1440518579964": { "None": { "ee": "6", "er": "6" }, "Male": { "ee": "5", "er": "5" }, "Female": { "ee": "6", "er": "6" } } } } ';
//        $params = json_decode($test1, 1);
//        pr($params);


//        $params = array();
//        $params['scheme_id'] = 410;
//        $params['client_id'] = 41;
//        $params['benefit_id'] = 10;

        die();

        pr("----- INPUT -----");
        pr($params);

        pr("----- FUNCTION -----");
        $data = ServiceRegistry::getInstance()->run('saveClientScheme', array($params));

        pr("----- OUTPUT -----");
        pr($data);
        var_dump($data);
    }

    function runJoe()
    {
        $params = array();
        $params['scheme_id'] = 340;
        $params['client_id'] = 40;
        $params[0] = 5;

        pr("----- INPUT -----");
        pr($params);

        pr("----- FUNCTION -----");
        $data = ServiceRegistry::getInstance()->run('findBenefitItem', array($params));

        pr("----- OUTPUT -----");
        pr($data);
    }


}