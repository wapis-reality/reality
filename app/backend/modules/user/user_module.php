<?php
class UserModule extends ModuleService
{
    public function __construct()
    {
        parent::__construct(__CLASS__);
    }

    /**
     * Login for user. This function check the user is exists and the password is correct if, it's corret it will return true
     * @todo Create permission rules
     * @param $params
     * @param null $password
     * @param bool $hashed
     * @return string
     */
    public function authUser($params, $password = null, $hashed = false){
        $this->loadModelUser();
        //$this->loadComponentPassword();

//        $params = convertObject2Array($params);

        $userPass = $this->UserModel->find('first', $params);

        if ($userPass) {
//            if (PasswordComponent::verify($params["userPassword"], $userPass['UserModel']['password'])) {
                unset($userPass['UserModel']['password']);
                return $userPass;
//            } else {
//                return 'true';
//            }
        } else {
            return 'false';
        }
    }

    /**
     * Check that the email is exists in the database, if it's exists it will allow the generate the new password
     * @param array $params
     * @return bool
     */
    public function forgottenPassword($params = array()) {
        $this->loadModelUser();
        $exists_user = $this->UserModel->find("first",array('conditions'=>array("email"=>$params->userEmail), 'fields'=>array("email")));

        if(!empty($exists_user) && ($params->imgCaptcha == $params->userCaptcha)) {
            return true;
        }   

        return false;     
    }

    /**
     * @param $setting
     * @return mixed
     */
    public function getUserList($setting){
        $this->loadModelUser();
        $this->loadModelUserGroups();
        $this->loadModelClients();

        $users = $this->UserModel->find('all', $setting);

        foreach($users as $key => $user) {
            $group = $this->UserGroupsModel->find("first",array("conditions" => array("id"=>$user["UserModel"]["group_id"])));
            $client = $this->ClientsModel->find("first",array("conditions" => array("id"=>$user["UserModel"]["client_id"])));
            $users[$key]["UserModel"]["permission"] = $group["UserGroupsModel"]["name"];
            $users[$key]["UserModel"]["client"] = $client["ClientsModel"]["name"];
        }

        return $users;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getUserDetail($id){
        $this->loadModelUser();
        return $this->UserModel->read(null, $id);
    }

    public function setNewPassword($params = array())
    {
        $this->loadModelUser();

        if(isset($params[0]["user"]["id"])) {

            if($params[0]["user"]["password"] != $params[0]["user"]["password_again"]) {
                return false;
            }

            $user = $this->UserModel->find("first",array("conditions"=>array("id"=>$params[0]["user"]["id"])));
            if(count($user) >= 1) {
                if($user["UserModel"]["password"] == md5($params[0]["user"]["old_password"])) {

                    if($this->UserModel->save(array("id"=>$params[0]["user"]["id"], "password"=>md5($params[0]["user"]["password"])))) {
                        return true;
                    }
                }
            }

        }

        return false;

    }

    public function getUser($params){

        $this->loadModelUser();
        $this->loadModelUserGroups();
        $this->loadModelClients();

        $user = $this->UserModel->find('first', array("conditions"=>array("id"=>$params[0])));
        $group = $this->UserGroupsModel->find('all');
        $clients = $this->ClientsModel->find("all");

        return array("user"=>$user, "groups"=>$group, "clients"=>$clients);
    }

    /**
     * @param $id
     * @return bool
     */
    public function activateUser($id){
        $this->loadModelUser();
        if ($this->UserModel->save(array(
            'id' => $id,
            'status' => '1'
        ))){
            // user log
            return true;
        } else {
            // user log
            return false;
        }
    }

    /**
     * @param $id
     * @return bool
     */
    public function deactivateUser($id){
        $this->loadModelUser();
        if ($this->UserModel->save(array(
            'id' => $id,
            'status' => '0'
        ))){
            // user log
            return true;
        } else {
            // user log
            return false;
        }
    }

    /**
     * @param $id
     * @return bool
     */
    public function removeUser($id){
        $this->loadModelUser();
        if ($this->UserModel->trash($id)){
            // user log
            return true;
        } else {
            // user log
            return false;
        }
    }

    /**
     * @param $userId
     * @param bool|false $sendEmail
     * @return bool
     */
    public function resetPassword($userId, $sendEmail = false){
        $this->loadModelUser();

        $this->loadComponentPassword();
        $new_password = $this->PasswordComponent->generate();
        $hash = PasswordComponent::hash($new_password);

        /**
         * save into DB
         */
        if($this->UserModel->save(array(
            'id' => $userId,
            'password' => $hash
        ))){
            // user log

            if ($sendEmail == true){
                // send email
            }
            return true;
        } else {
            // user log
            return false;
        }

    }

    public function getPermissions($params) {
        $this->loadModelUserGroups();

        if(isset($params[0])) {
            $group = $this->UserGroupsModel->find('first',array("conditions"=>array("id"=>$params[0])));
        } else {
            $group = $this->UserGroupsModel->find('all');
        }

        return $group;
    }

    public function setPermission($data) {
        $this->loadModelUserGroups();

        if (isset($data["form_data"]["UserGroupsModel"])) {
            if ($this->UserGroupsModel->save($data["form_data"]["UserGroupsModel"])){
                return $this->UserGroupsModel->id;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * @param $data
     * @return bool
     */
    public function saveUser($data){

        $this->loadModelUser();

        if (isset($data["form_data"]["user"]["UserModel"])) {

//            if ($this->UserModel->validate($data)){
                if ($this->UserModel->save($data["form_data"]["user"]["UserModel"])){
                    // user log
                    return $this->UserModel->id;
                } else {
                    // user log
                    return false;
                }
//            } else {
//                return false;
//            }
        } else {
            return false;
        }
    }

    public function findOnlinePhoto($data){

    }


}