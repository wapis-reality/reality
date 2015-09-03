<?php

    $link = str_replace("-","_",str_replace(".","",$detail["link"]));
    $view = (isset($detail["view"])) ? $detail["view"] : "default";

    //The link is working just only one slash. example: controller/something
    if(strpos($link,"/")) {
        $link = explode("/",$link);
        $link = "[".$link[0]."][".$link[1]."]";
    } else {
        $link = "[".$link."]";
    }

    $text = $detail["text"];
    $value = $detail["value"];
    $array_value = false;

    $class = str_replace(".","",$detail["class"]);
    $remove = $detail["remove"];
    $empty = (isset($detail["empty"])) ? $detail["empty"] : true;

    $elements = explode("|",$detail["elements"]);
    $select_options = explode("|",$detail["select_value"]);
    $selected = (isset($detail["selected"])) ? $detail["selected"] :"";

    $input_value = (isset($detail["input_value"])) ? $detail["input_value"]: "";
?>
<?php
    if($view == "table") {
        ?>
            <tr>
        <?php
            foreach($elements as $key => $element) {
                if(is_array($text) && is_array($value)) { $array_value = true; }

                switch($element) {

                    /**
                     * Simple text with hidden
                     */
                    case "value":
                        ?>
                        <td>
                           <?= (!$array_value) ? $text : $text["value"]; ?>
                            <input type="hidden" name="data<?= $link ; ?>[value][]" class="<?= $class; ?>_value" value="<?= (!$array_value) ? $value : $value["value"]; ?>"/>
                            <input type="hidden" name="data<?= $link ; ?>[text][]" class="<?= $class; ?>_text" value="<?= (!$array_value) ? $text : $text["value"]; ?>"/>
                        </td>
                        <?php
                        break;

                    /**
                     * HTML select
                     */
                    case "select":
                        ?>
                            <td>
                                  <select name="data<?= $link ; ?>[select][]" onchange="funnyBlur()">
                                      <?php  for($i = 0; $i < count($select_options); $i++) { ?>
                                          <?php if($selected == $select_options[$i]) { ?>
                                              <option SELECTED><?= $select_options[$i]; ?></option>
                                          <?php } else { ?>
                                              <option><?= $select_options[$i]; ?></option>
                                          <?php } ?>
                                      <?php } ?>
                                  </select>
                            </td>
                        <?php
                        break;

                    /**
                     * Input text
                     */
                    case "text":
                        ?>
                            <td>
                                <input type="input" onblur="funnyBlur()" name="data<?= $link ; ?>[text_input][]" class="form-control <?= $class; ?>_text1" value="<?= ($empty === true) ? "" : $input_value ; ?>"/>
                            </td>
                        <?php
                        break;

                    /**
                     * date
                     */
                    case "date":
                        ?>
                        <td>
                            <?= date("Y-m-d"); ?>
                            <input type="hidden" name="data<?= $link ; ?>[date][]" class="<?= $class; ?>_date" value="<?= date("Y-m-d"); ?>"/>
                        </td>
                        <?php
                        break;

                    /**
                     * datetime
                     */
                    case "datetime":
                        $detail["datetime"] = (isset($detail["datetime"])) ? $detail["datetime"] : date("Y-m-d H:i:s");
                        ?>
                            <td>
                                <?= $detail["datetime"] ?>
                                <input type="hidden" name="data<?= $link ; ?>[datetime][]" class="<?= $class; ?>_date" value="<?= $detail["datetime"] ?>"/>
                            </td>
                        <?php
                        break;

                    /**
                     * user text
                     */
                    case "user":
                        ?>
                        <td>
                               <?php
                               $user = $_SESSION["user"];
                               $detail["user"] = ((isset($detail["user"])) && ($detail["user"] != "")) ? $detail["user"] : $user["User"]["first_name"]." ".$user["User"]["last_name"];
                               $detail["user_id"] = ((isset($detail["user_id"])) && ($detail["user_id"] != "")) ? $detail["user_id"] : $user["User"]["id"];
                               ?>
                                <?=  $detail["user"]; ?>
                                <input type="hidden" name="data<?= $link ; ?>[user_id][]" class="<?= $class; ?>_date" value="<?= $detail["user_id"] ?>"/>
                        </td>
                        <?php
                        break;

                    /**
                     * user text
                     */
                    default:
                        ?>
                            <td>
                                <?= $element; ?>
                            </td>
                        <?php
                        break;
                }
            }

        if($remove) {
            ?>
            <td>
                <span class="remove-appended"><i class='fa fa-remove'></i></span>
            </td>
        <?php
        }

        ?>
            </tr>
        <?php

    } else {
?>
<div class="spec-container">
    <?php
        foreach($elements as $key => $element) {
            if(is_array($text) && is_array($value)) { $array_value = true; }

            switch($element) {

                /**
                 * Simple text with hidden
                 */
                case "value":
                        ?>
                            <span class="spec-name">
                               <?= (!$array_value) ? $text : $text["value"]; ?>
                                <input type="hidden" name="data<?= $link ; ?>[value][]" class="<?= $class; ?>_value" value="<?= (!$array_value) ? $value : $value["value"]; ?>"/>
                                <input type="hidden" name="data<?= $link ; ?>[text][]" class="<?= $class; ?>_text" value="<?= (!$array_value) ? $text : $text["value"]; ?>"/>
                            </span>
                        <?php
                    break;

                /**
                 * HTML select
                 */
                case "select":
                        ?>
                             <span class="spec-select">
                                  <select name="data<?= $link ; ?>[select][]" onchange="funnyBlur()">
                                      <?php  for($i = 0; $i < count($select_options); $i++) { ?>
                                          <?php if($selected == $select_options[$i]) { ?>
                                              <option SELECTED><?= $select_options[$i]; ?></option>
                                          <?php } else { ?>
                                              <option><?= $select_options[$i]; ?></option>
                                          <?php } ?>
                                      <?php } ?>
                                  </select>
                             </span>
                        <?php
                    break;

                /**
                 * Input text
                 */
                case "text":
                        ?>
                            <span class="spec-input">
                                <input type="input" onblur="funnyBlur()" name="data<?= $link ; ?>[text_input][]" class="form-control <?= $class; ?>_text1" value="<?= ($empty === true) ? "" : $input_value ; ?>"/>
                            </span>
                        <?php
                    break;

                /**
                 * date
                 */
                case "date":
                    ?>
                    <span class="spec-date">
                        <?= date("Y-m-d"); ?>
                        <input type="hidden" name="data<?= $link ; ?>[date][]" class="<?= $class; ?>_date" value="<?= date("Y-m-d"); ?>"/>
                    </span>
                    <?php
                    break;

                /**
                 * datetime
                 */
                case "datetime":
                    $detail["datetime"] = (isset($detail["datetime"])) ? $detail["datetime"] : date("Y-m-d H:i:s");
                    ?>
                    <span class="spec-datetime">
                         <?= $detail["datetime"] ?>
                        <input type="hidden" name="data<?= $link ; ?>[datetime][]" class="<?= $class; ?>_date" value="<?= $detail["datetime"] ?>"/>
                    </span>
                    <?php
                    break;

                /**
                 * user text
                 */
                case "user":
                    ?>
                    <span class="spec-user">
                       <?php
                            $user = $_SESSION["user"];
                            $detail["user"] = ((isset($detail["user"])) && ($detail["user"] != "")) ? $detail["user"] : $user["User"]["first_name"]." ".$user["User"]["last_name"];
                            $detail["user_id"] = ((isset($detail["user_id"])) && ($detail["user_id"] != "")) ? $detail["user_id"] : $user["User"]["id"];
                       ?>
                        <?=  $detail["user"]; ?>
                        <input type="hidden" name="data<?= $link ; ?>[user_id][]" class="<?= $class; ?>_date" value="<?= $detail["user_id"] ?>"/>
                    </span>
                    <?php
                    break;
            }
        }

    if($remove) {
    ?>
        <span class="remove-appended"><i class='fa fa-remove'></i></span>
    <?php
    }
    ?>
</div>

<?php
    }
?>