<?php

function pr($arr=array()){
    echo '<pre>';
    print_r($arr);
    echo '</pre>';
}

//Make sure it doesn't time out
        set_time_limit (0);

	$con = mysql_connect("localhost","root","root");
	if (!$con){die('Could not connect: ' . mysql_error());}
	mysql_select_db("rewardr", $con);

        mysql_query('UPDATE '.$table.' SET level =0');


	function rebuild_tree($parent_id, $left, $level) {
            $table = 'menu_items';
	   // the right value of this node is the left value + 1
	   $right = $left+1;

	   // get all children of this node
	   $result = mysql_query('SELECT id FROM '.$table.' '.
	                          'WHERE parent="'.$parent_id.'" ORDER BY lft ASC;');



	   while ($row = mysql_fetch_array($result)) {
//	       pr($row);

                $right = rebuild_tree($row['id'], $right, $level + 1);
	   }

            /*
            if ($parent_id == 0){
                $level = 0;
            } else {
                $q = 'select level FROM tmp_tree where id = '.$parent_id;
                $l = mysql_query($q);

                $row = mysql_fetch_row($l);
                $level = (int)$row['0'] + 1;
            }
            */
            $query = 'UPDATE '.$table.' SET lft='.$left.', rght='.$right.', level ='.$level.' WHERE id="'.$parent_id.'";';
            echo $query.'<br />';
	   // we've got the left value, and now that we've processed
	   // the children of this node we also know the right value
	   mysql_query($query);

	   // return the right value of this node + 1
	   return $right+1;
	}

rebuild_tree('0',0,0);

mysql_close($con);
echo end;
?>