<?php



class Soap extends Component {

    //var $wsdl = 'http://hr:8888/app/backend/service.wsdl';
    var $wsdl = 'http://localhost/app/backend/service.wsdl';
    var $valid_Soap_connection = true;
    var $soap_connection_error_message = '';

    function __construct($base = null, $start = true)
    {

    }

    public function startup(&$controller)
    {

        $this->controller = &$controller;
        try {

            $this->conn = new SoapClient($this->wsdl, array('cache_wsdl' => WSDL_CACHE_NONE, 'trace' => 1));

        } catch(SoapFault $exception){

            /**
             * If there is any problem in connecting the the Soap server, set $valid_Soap_connection to false.
             * This will stop any requests being sent
             */
            $this->valid_Soap_connection = false;
            $this->soap_connection_error_message = $exception->getMessage();
            $result = array(
                'parameters' => array(
                    'status' => 0,
                    'message' => '<b>Message</b>: '.$exception->getMessage().', <b>Faultcode</b>: '.$exception->faultcode.', <b>Faultstring</b>: '.$exception->faultstring
                ));

        }
    }

    public function send($service, $params, $debug = false){

        /**
         * If the Soap connection wasn't successful, we cannot proceed with sending a request.
         * Return an error message
         */
        if($this->valid_Soap_connection === false){
            $result = array(
                'parameters' => array(
                    'status' => 0,
                    'message' => 'There has been a problem initializing the Soap connection to the server. <br/><b>Error</b>: '.$this->soap_connection_error_message
                )
            );
            return $result['parameters'];
        }

        try {

            /**
             * Convert the params to json before sending them in the Soap Request
             */

            $params = array("parameters" => json_encode($params));

            $xml = new SimpleXMLElement('<root/>');
            $this->array2Xml($xml, $params);
            $name = "parameters";
            $xml = $xml->$name->asXML();

            $params = new SoapVar($xml, XSD_ANYXML);
            $result = $this->conn->__soapCall($service, array($params));

            /**
             * Convert result back to PHP array
             */
            $result = json_decode($result, 1);

            if($debug){
                echo 'Last Request:<br/><textarea style="width: 800px; height: 800px;">'.$this->conn->__getLastRequest().'</textarea>';
                echo 'Last Response:<br/><textarea style="width: 800px; height: 800px;">'.$this->conn->__getLastResponse().'</textarea>';
            }

        } catch(SoapFault $exception){

            $result = array(
                'parameters' => array(
                    'status' => 0,
                    'message' => '<b>Message</b>: '.$exception->getMessage().', <b>Faultcode</b>: '.$exception->faultcode.', <b>Faultstring</b>: '.$exception->faultstring
            ));

        }

        return $result['parameters'];

    }

    /**
     *  Convert PHP array to XML
     */
    private function array2Xml(SimpleXMLElement $object, array $data)
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $new_object = $object->addChild($key);
                $this->array2Xml($new_object, $value);
            } else {
                $object->addChild($key, $value);
            }
        }
    }

    function object_to_array($obj) {
        if(is_object($obj)) $obj = (array) $obj;
        if(is_array($obj)) {
            $new = array();
            foreach($obj as $key => $val) {
                $new[$key] = $this->object_to_array($val);
            }
        }
        else $new = $obj;
        return $new;
    }


}
