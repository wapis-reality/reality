<?php

//App::import('Vendor', 'phpmailer', array('file' => 'phpmailer' . DS . 'class.phpmailer.php'));
require_once ROOT . 'app/components/phpmailer/class.phpmailer.php';

class BBCode
{

    function replace_text($text_original, $data = array(), $from = 'data')
    {
        if ($from == 'data')
            preg_match_all('/##(.*)##/ismU', $text_original, $text2);
        else {
            preg_match_all('/##' . $from . '.(.*)##/ismU', $text_original, $text2);
        }
        foreach ($text2[1] as $key => $item) {
            if (strpos($item, '.')) {
                $aKeys1 = explode('.', $item);
                $data2 = $data[$from];
                $bOk1 = true;
                foreach ($aKeys1 as $sKey1) {
                    if (key_exists($sKey1, $data2)) {
                        $data2 = $data2[$sKey1];
                    } else {
                        $bOk1 = false;
                    }
                }
                if ($bOk1) {
                    $text2[1][$key] = $data2;
                } else {
                    $text2[1][$key] = '';
                }

                //                list($class, $col, ) = explode('.', $item);
                //                if (isset($data[$from][$class][$col])){
                //                    $text2[1][$key] = $data[$from][$class][$col];
                //                }else
                //                    $text2[1][$key] = '';
            } else {
                $text2[1][$key] = $data[$from][$item];
            }
        }

        foreach ($text2[0] as $k => $t) {
            $text_original = str_replace($t, $text2[1][$k], $text_original);
        }
        return str_replace($text2[0], $text2[1], $text_original);
    }

    function get_text($text, $data)
    {
        preg_match_all('/##FOREACH(.*)ENDFOREACH##/ismU', $text, $text2);
        foreach ($text2[1] as &$foreach_item):
            $position_close_zav = strpos($foreach_item, '}');
            if ($position_close_zav) {
                $text_to_foreach = substr($foreach_item, $position_close_zav + 1);
                list($variable, $sub) = explode('|', trim(substr($foreach_item, 1, $position_close_zav - 1)));
                //                echo "$variable<br/>";
                if (isset($data[$variable])):
                    $foreach_item = '';
                    foreach ($data[$variable] as $ssub) {
                        $foreach_item .= $this->replace_text($text_to_foreach, array($sub => $ssub), $sub);
                    }
                endif;
            }
        endforeach;
        $template = str_replace($text2[0], $text2[1], $text);
        return $this->replace_text($template, array('data' => $data), 'data');
    }

}

class Email
{

    private $ftp_host = '178.238.40.88';
    private $ftp_user = 'wapis_wapis';
    private $ftp_heslo = 'wapis123';
    public $tamp = 'asdasd';
    private $from = 'info@wapis.cz';
    private $fromName = 'Wapis Group';
    private $smtpUserName = 'smtp@wapis.cz'; // SMTP username
    private $smtpPassword = 'selfsmtp'; // SMTP password
    private $smtpHostNames = 'pop3.wapis.cz'; // specify main and backup server
    private $text_body = null;
    private $html_body = null;
    private $to = null;
    private $toName = null;
    private $subject = null;
    private $cc = null;
    private $bcc = null;
    private $template = null;
    private $attachments = null;
    private $layout = 'email';
    private $controller;
    private $replaceData = array();

    /*
     * function initialize objects:
     * -controller
     * -phpmailer
     * -bbcode
     * function start when class Email is initialized (similiar to __construct())
     *
     * @params: $controller - object of controller
     *
     * @return nothing
     *
     */
    public function startup(&$controller)
    {
        $this->controller = &$controller;
        $this->mail = new PHPMailer();
        $this->bbcode = new BBCode();
    }

    /*
    * function runs internal phpmailer function AddStringAttachment
    * @params - $string - file to be attached
    *           $filename - name of file
    *           $encoding - encoding used
    *           $type - type of attachment
    * @return nothing
    */
    private function AddStringAttachment($string, $filename, $encoding = "base64", $type = "application/octet-stream")
    {
        //pr($string);
        $this->mail->AddStringAttachment($string, $filename, $encoding, $type);
    }

    public function setFrom($fromEmail = null, $fromName = null)
    {
        if ($fromEmail != null) {
            $this->from = $fromEmail;
        }

        if ($fromName != null) {
            $this->fromName = $fromName;
        }

        return;
    }

    /*
     * function create attachment from action of controller
     * @params:  //reqired params:
     *           $filename- name of attachment,
     *           $fn - string with name of controller, action, and params (the same like url)
     *           //optional params:
     *           $layout - any special layout for view
     *           $encoding - type of encoding
     *           $type - type of attachment
     * @return nothing
     */

    private function addAttachFromFunc($filename = 'attach.txt', $fn = null, $layout = '', $encoding = "base64", $type = "application/octet-stream")
    {
        $ex = explode('/', ltrim($fn, '/'));
        $action = $ex[1];
        $params = $ex[2];

        ob_start();
        $temp_layout = $this->controller->layout;
        $this->controller->layout = $layout;
        $this_data = $this->controller->data;
        unset($this->controller->data);

        $this->controller->priloha = true;
        echo $this->controller->$action();
        $attach = ob_get_contents();
        ob_end_clean();

        $this->controller->layout = $temp_layout;
        $this->controller->data = $this_data;
        unset($this_data);
        $this->AddStringAttachment($attach, $filename, $encoding, $type);
    }

/*
* ftp function gets file from ftp server, and create attachment
* @param $filename - name of file in server to be attached ( intermediate path)
* @require - $this->ftp_user, $this->ftp_heslo, $this->ftp_host
*
* @return nothing
*/
    private function addAttachFromFtp($filename)
    {
        $filename = ltrim($filename, '/');
        $ex = explode('/', $filename);
        $filename = $ex[count($ex) - 1];
        unset($ex[count($ex) - 1]);
        $path = implode('/', $ex);
        $path = strtr($path, array('uploaded' => ''));

        $conn_id = ftp_connect($this->ftp_host) or die("Couldn't connect to $this->ftp_host");
        if (@ftp_login($conn_id, $this->ftp_user, $this->ftp_heslo)) {
            $temp_file = tmpfile();
            ftp_chdir($conn_id, $path);
            if (ftp_fget($conn_id, $temp_file, $filename, FTP_BINARY, 0)) {
                fseek($temp_file, 0);
                $fs = ftp_size($conn_id, $filename);
                $string = fread($temp_file, $fs);
                $this->AddStringAttachment($string, $filename);
            } else {
                echo 'nejde stahnout soubor';
            }
        } else {
            echo 'Nejde se zalogovat';
        }

        ftp_close($conn_id);
    }

    /*
     * function cover filled body template with <html> template, and add styles
     * @param $html - string to be covered
     * @return final content of mail
     */
    private function load_style($html, $out = '')
    {
        $out .= '<html>';
        $out .= '<head>';
        $out .= '<style>';
        $out .= 'body {font-family:Tahoma, Arial; font-size:12px; width:700px;}';
        $out .= 'h2 {font-size:16px}';
        $out .= 'h3 {font-size:14px}';
        $out .= 'table {border: 1px solid #CCC; width:700px}';
        $out .= 'table th {background:#AAAAAA; text-align:left;vertical-align:top;color:#FFFFFF;}';
        $out .= 'table td,table th {font-size:12px;}';
        $out .= '</style>';
        $out .= '</head>';
        $out .= '<body>';
        $out .= $html;
        $out .= '</body>';
        $out .= '</html>';
        return $out;
    }

    /*function load template from table MailTemplate, and fill template with data
     * @params $template_id - id of template
     * $this->constroller->data - from here function acquire data to fill template
     *
     * @return content of body.
     */
    private function loadTemplate($template_id = null)
    {
        if ($template_id != null) {
            $this->controller->loadModel('EmailTemplate');
            $template = $this->controller->EmailTemplate->read(null, $template_id);
            if ($template) {
                //                $this->load_APPData();
                $this->subject = $this->bbcode->get_text($template['EmailTemplate']['subject'], $this->replaceData);
                return $this->load_style($this->bbcode->get_text($template['EmailTemplate']['text'], $this->replaceData));
            } else
                return false;
        }
        return false;
    }

    /*fuction sets body, and subject of email. Body is created from template stored in database
     * @params $aOptions = array('subject'=>$sSubject,
     *                           'template_id' =>$iIdOfTemplate
     * @returns nothing
     */
    private function setTemplate($aOptions)
    {
        if (!empty($aOptions['data']))
            $this->controller->data = $aOptions['data'];
        $this->mail->IsHTML(true);
        if (key_exists('subject', $aOptions)) {
            $this->subject = $aOptions['subject'];
        }
        $this->html_body = $this->loadTemplate($aOptions['template_id']);
    }

    /**
     * pouziti Wapis Template
     */
    private function setWapisTemplate($aOptions)
    {
        $data = array();
        if (!empty($aOptions['data']))
            $data = $aOptions['data'];
        $this->mail->IsHTML(true);
        if (key_exists('subject', $aOptions)) {
            $this->subject = $aOptions['subject'];
        }

        $img_src = ROOT . "projects/master/templates/logo.jpg";
        $f = fopen($img_src, 'rb');
        $imgbinary = '';
        while (!feof($f))
            $imgbinary .= fread($f, filesize($img_src));
        fclose($f);

        $img_str = base64_encode($imgbinary);
        $data['logo'] = 'data:' . mime_content_type($img_src) . ';base64,' . $img_str;

        $template = ROOT . "projects/master/templates/wapis_email.template";
        try {
            if (file_exists($template)) {
                $content = file_get_contents($template);
            } else {
                log::djf("Template {$template} not exist");
            }

            foreach ($data as $key => $value) {
                $content = str_replace('{$' . $key . '}', $value, $content);
            }
        } catch (Exceptrion $e) {
            log::djf('Chyba během tvoření obsahu template: ' . $temp_name);
        }

        $this->html_body = $content;
    }


    /*fuction sets body, and subject of email. Body is created as return of view action in controller.
     *@params $aOptions = array('subject'=>$sSubject,
     *                          'action' =>$sNameOfActionToRenderBody
     * @return nothing
     */
    private function getContentFromAction($aOptions)
    {
        //        var_dump($aOptions['action']);exit;
        if ((!empty($aOptions['action']))) { //&& ( !empty($aOptions['controller']) )
            ob_start();
            $this->controller->layout = $this->layout;
            echo $this->controller->$aOptions['action']();
            $mail = ob_get_clean();
            $this->html_body = $mail;
            $this->subject = $aOptions['subject'];
        }
    }

    /* set body, and subject of email.
     * @params $aText - array with body and subject $aText = array('subject' => $sSubject,
     *                                                             'body' =>sBody
     *                                                              )
     * @returns nothing
     */
    private function setText($aText)
    {
        $this->html_body = $aText['body'];
        $this->subject = $aText['subject'];
    }

    private function valid_email($email)
    {
        if (preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/", $email) == false) {
            return false;
        }
        else
            return true;
    }

    /*
    * $aSettings = array (
    *                      'type'=>possibilities(
    *                          'action' =>array(
    *                                          'action' => $actionName
    *                                          'subject' => $sSubject
    *                                          'params' => $aParamsForAction
    *                                        )
    *                          'template' => array(
    *                                          'template_id' => $iTemplateId,
    *                                          'data' => $aData,
    *                                          'subject' => $sSubject
    *                                        )
    *                          'text' =>  array(
    *                                          'body' => $sBody,
    *                                          'subject' => $sSubject
    *                                        )
    *                      'attachments'=> array(3){
    *                              'ftp' => array(attachments from ftp array)
    *                              'action' => array(attachments generated by controller/action)
    *                              'text' => arrray(attachments generated by text)
    *                      }
    *                     )
    * $aRecipients = array(
    *              '0' =>array(
    *                  'name'=>'recipient name'
    *                  'email'=>'recipient email'
    *                  )
    *              ....
    *              )
    * $aFrom = array('email'=>$sEmail
    *                'name'=>$sName
    * )
    *
    * $aBcc = array(
    * 0 => array('email'=>$sEmail
    *                'name'=>$sName
    * ...
    *      )
    * )
    *
    */

    public function sendm($aSettings, $aRecipients, $aFrom = false, $cc = false, $bcc = false)
    {


        $this->mail->IsSMTP(); // set mailer to use SMTP
        $this->mail->SMTPAuth = true; // turn on SMTP authentication

        $this->mail->Host = $this->smtpHostNames;
        $this->mail->Username = $this->smtpUserName;
        $this->mail->Password = $this->smtpPassword;

        $aKey = array_keys($aSettings['type']);
        $sType = $aKey[0];

        //set recipients
        if (!empty($aRecipients)) {
            if (is_array($aRecipients)) {
                $this->to = $aRecipients;

            } elseif (is_string($aRecipients)) {
                if (self::valid_email($aRecipients)) {
                    $this->to[] = $aRecipients;
                }
            }
        }

        //set replay to
        if (is_array($aFrom)) {
            if (key_exists('email', $aFrom)) {
                $this->from = $aFrom['email'];
            }
            if (key_exists('name', $aFrom)) {
                $this->fromName = $aFrom['name'];
            }
        }

        switch ($sType) {
            case 'action':
                $this->getContentFromAction($aSettings['type'][$sType]);
                break;
            case 'template':
                if (isset($aSettings['type']['template']['data']))
                    $this->replaceData = $aSettings['type']['template']['data'];
                $this->setTemplate($aSettings['type'][$sType]);
                break;
            case 'text':
                $this->setText($aSettings['type'][$sType]);
                break;
            case 'wapistemplate':
                $this->setText($aSettings['type'][$sType]);
                if (isset($aSettings['type']['wapistemplate']['data']))
                    $this->replaceData = $aSettings['type']['wapistemplate']['data'];
                $this->setWapisTemplate($aSettings['type'][$sType]);
                break;
            case 'template_file':

                if(isset($aSettings['type']["subject"])) {                    
                    $this->subject = $aSettings['type']["subject"];
                }

                if(isset($aSettings['type']["email"])) {
                    $this->from = $aSettings['type']["email"];    
                }

                if(isset($aSettings['type']["name"])) {
                    $this->fromName = $aSettings['type']["name"];                    
                }
                
                if(isset($aSettings['type']["values"])) {
                    $this->templateData = $aSettings['type']["values"];
                }

                if(file_exists($aSettings['type'][$sType])) {    
                    ob_start();     
                    require_once($aSettings['type'][$sType]);
                    $this->html_body = ob_get_contents();

                    ob_end_clean();                         
                }

                break;
            default:
                break;
        }
        if (!empty($aSettings['attachments'])) {

              //  pr($aSettings['attachments']);
            if (isset($aSettings['attachments']['ftp'])) {
                $ftp = $aSettings['attachments']['ftp'];
            }

            if (isset($aSettings['attachments']['action'])) {
                $action = $aSettings['attachments']['action'];
            }

            if (isset($aSettings['attachments']['text'])) {
                $ftp = $aSettings['attachments']['text'];
            }

        }

        if (!empty($ftp) && is_array($ftp)) {
            //add attachments from ftp
            $i=0;
            foreach ($ftp as $att) {
              //  echo $i++.'-----------';
                $this->addAttachFromFtp($att['name']);
            }
        }
        if (!empty($action) && is_array($action)) {
            //add attachments generated by controller/action
            foreach ($action as $att) {
                $this->addAttachFromFunc($att['name'], $att['action'], $this->layout);
            }
        }
        if (!empty($text) && is_array($text)) {
            //add text attachments
            foreach ($text as $att) {
                $this->AddStringAttachment($string = $att['text'], $filename = $att['name']);
            }
        }


        $this->mail->CharSet = 'UTF-8';
        $this->mail->WordWrap = 50; // set word wrap to 50 characters

        $this->mail->IsHTML(true); // set email format to HTML
        $this->mail->Body = $this->html_body;
        $this->mail->Subject = $this->subject;

        foreach ($this->to as $to):
           /** if($_SERVER['REMOTE_ADDR'] == '90.176.43.89'){
                var_dump(self::valid_email($to['email']));
                pr($to['email']);
            }*/
            if (self::valid_email(strtolower($to['email']))) {
                $this->mail->AddAddress(strtolower($to['email']));
            }
        endforeach;

        if ($cc !== false)
            foreach ($cc as $to):
                if (self::valid_email($to['email']))
                    $this->mail->AddCC($to['email']);
            endforeach;

        if ($bcc !== false)
            foreach ($bcc as $to):
                if (self::valid_email($to['email']))
                    $this->mail->AddBCC($to['email']);
            endforeach;

        $this->mail->From = $this->from;
        $this->mail->FromName = $this->fromName;

        $result = $this->mail->Send();
        if ($result == false)
            $result = $this->mail->ErrorInfo;
        return $result;
    }

    public function clear_data()
    {
       $this->to = null;
       $this->text_body = null;
       $this->html_body = null;
       $this->toName = null;
       $this->subject = null;
       $this->mail->ClearAllRecipients();
       $this->mail->AddAddress = null;
    }

}

?>