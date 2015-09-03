<?php
    class SystemController extends AppController
    {
        var $name = 'System';
        var $components = array('Session','Soap','Security');


        /**
         *
         */
        function index()
        {
            echo $this->render('index');
        }


        /**
         * @param $service_name
         */
        function angular_request($service_name,$enc_id = null) {

            $args = func_get_args();

            $pass_args = array();
            unset($args[0]);

            if($enc_id !== null && $enc_id !== 'null' && !empty($enc_id)){
                $decrypt = explode("-",encrypt_decrypt("decrypt", $enc_id));
                $pass_args['client_id'] = (int)$decrypt[0];
                $pass_args['scheme_id'] = (int)$decrypt[1];

            }
            unset($args[1]);

            $pass_args = am($pass_args,$args);


            $data = json_decode(file_get_contents("php://input"), 1);

            if (!empty($data)){
                $pass_args['form_data'] = $data;
                $result = $this->Soap->send($service_name, array($pass_args));
            } else {
                if (isset($this->data) && count($this->data)){
                    foreach($this->data as $p=>$v){
                        $pass_args[$p] = $v;
                    }

                  //  $pass_args['form_data'] = $this->data;
                }


                $result = $this->Soap->send($service_name, array($pass_args));
            }

            if($result['status']){
                log::djt($result['data']);
            } else {
                die('Error:' . $result['message']);
            }
        }

        /**
         * Login into the admin panel function
         */
        function login()
        {

            $this->data = json_decode(file_get_contents("php://input"), 1);

            if (isset($this->data)) {


                $user = $this->Soap->send("userAuth", array(array("conditions"=>array("email"=>$this->data["email"], "password"=>md5($this->data["password"])))));

                if(isset($user["data"]) && $user["data"] != "false") {
                    $this->Session->write('user',$user["data"]["UserModel"]);
                    log::djt("/");
                } else {
                    if(isset($user['message'])) {
                        log::djf(strip_tags($user['message']));
                    }else {
                        log::djf("Username or password is not correct");
                    }
                }
            } else {
                $this->layout = 'not_logged';
            }
        }

        /**
         * Logout from the admin panel
         */
        function logout()
        {
            $this->Session->del('user');
            session_destroy();
            $this->redirect('/login');
        }

        /**
         * Download factfind file. The will save to the server, and returning the link
         * @param null $enc_id
         * @return bool
         */
        function downloadFactFind($enc_id = null)
        {
            if($enc_id !== null && $enc_id !== 'null' && !empty($enc_id)){
                $decrypt = explode("-",encrypt_decrypt("decrypt", $enc_id));
                $client_id = (int)$decrypt[0];
                $scheme_id = (int)$decrypt[1];
            }

            $url = $this->Soap->send("downloadFactFind", array(array("scheme_id"=>$scheme_id,"client_id"=>$client_id)));
            $this->redirect("http://rewardr-core:8888/".$url["data"].".xls");
            return true;
        }

        function change_password($enc_id = null) {
            $this->data = json_decode(file_get_contents("php://input"), 1);
            $user = $this->Session->read('user');

            $this->data["user"]["id"] = $user["id"];


            $changePw = $this->Soap->send("setNewPassword", array(array($this->data)));

            log::djt($changePw["data"]);

        }

        function getUserData()
        {
            $user = $this->Session->read('user');
            if(!empty($user)){
                $userData = array(
                    'user_name' => $user['user_name'],
                    'group_id' => $user['group_id']
                );
                log::djt($userData);
            } else {
                log::djf('Error');
            }

        }

        /**
         * Sortable or simple append plus library part. It will generate the html
         */
        function add_append_plus() {
            $option = $this->params["form"]["option"];

            if(!isset($option) || empty($option)) {
                Log::djt(array('error'=>"The option empty, this template is not exists"));
            }
            $detail = $this->params["form"];

            ob_start();
                require_once("./app/system/views/elements/components/append-plus/".$option.".ctp");
//             print_r($this->renderElement("elements/components/append-plus/".$option,array("detail"=>$this->params["form"])));
            $html =ob_get_clean();

            print_r(json_encode(array('html'=>$html)));
            die();
        }
    }
?>