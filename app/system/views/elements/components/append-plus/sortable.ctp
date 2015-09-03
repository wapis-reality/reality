<?php
    $link = str_replace("-","_",str_replace(".","",$detail["link"]));
    $link = explode("/",$link);
    $link = "[".implode("][", $link)."]";

    $text = $detail["text"];
    $value = $detail["value"];
    $class = str_replace(" ","_",str_replace(".","",$detail["class"]));
    $remove = $detail["remove"];
?>

<li class="append-plus-sortable">
    <span class="sortable-name"><?= $text ?></span>
    <?php
        if($remove) {
            ?>
                <span class="remove-appended"><i class='fa fa-remove'></i></span>
            <?php
        }
    ?>
    <span class="clear"></span>
    <input type="hidden" name="data<?= $link; ?>[value][]" class="<?= $class; ?>_value" value="<?= $value ?>"/>
    <input type="hidden" name="data<?= $link; ?>[text][]" class="<?= $class; ?>_text" value="<?= $text ?>"/>
</li>

