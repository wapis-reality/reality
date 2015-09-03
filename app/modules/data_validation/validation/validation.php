<?php
if(!empty($_GET["columns"])){
    $file_name = "trss.csv";
    $f = fopen("csv/".$file_name, 'w');
    $columns = explode(",",$_GET["columns"]);
    foreach($columns as $val) {
        $data[] = $val;
    }
    fputcsv($f, $data, ",");
    fseek($f, 0);
    fclose($f);

    //create xd file
    $xsd_data = '<xs:schema xmlns:xs="http://www.w3.org/2001/XMLSchema">
        <xs:element name="trss">
            <xs:complexType>
                <xs:sequence>
                    <xs:element ref="trs"/>
                </xs:sequence>
            </xs:complexType>
        </xs:element>

        <xs:element name="trs">
            <xs:complexType>
                <xs:sequence>';
    foreach($data as $d) {
        $xsd_data .=   '<xs:element ref="'.$d.'"/>';
    }
    $xsd_data .= '</xs:sequence>
            </xs:complexType>
        </xs:element>';
    foreach($data as $d) {
        if($d == "Staff_No.")
            $xsd_data .= '<xs:element name="'.$d.'" type="xs:integer"/>';
        else
            $xsd_data .= '<xs:element name="'.$d.'" type="xs:float"/>';
    }
    $xsd_data .= '</xs:schema>';

    $xf = fopen("xsd/trss.xsd", 'w');
    fwrite($xf,$xsd_data);
    fclose($xf);
    if($f && $xf) {$result["file"] = $file_name; die(json_encode($result));}
    else die("error");
}

$parent_names = array("e"=>"employees","d"=>"dependents","f"=>"benefits","t"=>"trss");
$child_names = array("e"=>"employee","d"=>"dependent","f"=>"benefit","t"=>"trs");

//headers of files
$e = array("Staff Number","Title","First Name","Surname","System Status","Preferred Name","Middle Name","Address 1","Address 2","Address 3","Town","County","Postcode","Gender","Company Start Date","Days Per Week","Hours Per Week","Entitlement","Date of Birth","Leavers Date","NI Number","Salary","Pension Salary","Tax Code","Tax Band","Job Title","Department","Contract Type","Employment Status","Work Email","Manager","Home Phone","Mobile Phone","Flex Employee","Flex Fund Value","Pension Scheme","Pension EE %","Pension ER %","Pension EE Monthly Amount","Pension ER Monthly Amount","Core Entitlement Holiday","Pro-Rated Entitlement Holiday","Holiday Carry Over","Passed Probation","Special Benefit Exceptions Identifier");

$d = array("Staff No.","First Name","Surname","Dependant Surname","Dependant First Name","Dependant Date of Birth","Relationship","Dependant Gender","Dependant Student");

$f = array("Staff Number","First Name","Surname","Provider","Benefit Name","Option Selected","Coverage Selected","Employee Cost (annual)","Employer Cost (annual)","Start Date","End Date","Start of Cover","Dependant 1 Date of Birth","Dependant 1 First Name","Dependant 1 Gender","Dependant 1 Relationship","Dependant 1 Surname","Dependant 1 Student","Dependant 2 Date of Birth","Dependant 2 First Name","Dependant 2 Gender","Dependant 2 Relationship","Dependant 2 Surname","Dependant 2 Student","Dependant 3 Date of Birth","Dependant 3 First Name","Dependant 3 Gender","Dependant 3 Relationship","Dependant 3 Surname","Dependant 3 Student","Dependant 4 Date of Birth","Dependant 4 First Name","Dependant 4 Gender","Dependant 4 Relationship","Dependant 4 Surname","Dependant 4 Student","Dependant 5 Date of Birth","Dependant 5 First Name","Dependant 5 Gender","Dependant 5 Relationship","Dependant 5 Surname","Dependant 5 Student","Dependant 6 Date of Birth","Dependant 6 First Name","Dependant 6 Gender","Dependant 6 Relationship","Dependant 6 Surname","Dependant 6 Student","Dependant 7 Date of Birth","Dependant 7 First Name","Dependant 7 Gender","Dependant 7 Relationship","Dependant 7 Surname","Dependant 7 Student","Dependant 8 Date of Birth","Dependant 8 First Name","Dependant 8 Gender","Dependant 8 Relationship","Dependant 8 Surname","Dependant 8 Student","Dependant 9 Date of Birth","Dependant 9 First Name","Dependant 9 Gender","Dependant 9 Relationship","Dependant 9 Surname","Dependant 9 Student","Dependant 10 Date of Birth","Dependant 10 First Name","Dependant 10 Gender","Dependant 10 Relationship","Dependant 10 Surname","Dependant 10 Student");

$message = array();
$data["answer"] = "2";
$file = "csv/".$_FILES['upload']['name'];
$section = $_GET['file'];

//validation if file is not csv
if(substr($file,strrpos($file,".")+1) <> "csv") {
    $message[] = "Please Upload CSV file format";
    $data["message"] = $message;
    die(json_encode($data));
}
//validation for file size

move_uploaded_file($_FILES['upload']['tmp_name'],$file);

//validation for matching headers
$inputFile  = fopen($file, 'rt');
$headers = fgetcsv($inputFile);
$headers = array_filter($headers);
if($section <> "t"){
$comp = array_diff($headers,$$section);
if(count($comp) > 0) {
    $message[] = "File Template is not correct, Please download the Template first";
    $data["message"] = $message;
    die(json_encode($data));
}
}

if(count(file($file,FILE_SKIP_EMPTY_LINES)) == 1){
    $message[] = "File contains no data";
    $data["message"] = $message;
    die(json_encode($data));
}

$outputFilename   = 'xml/'.$parent_names[$section].'.xml';
$doc  = new DomDocument();
$doc->formatOutput   = true;
$root = $doc->createElement($parent_names[$section]);
$root = $doc->appendChild($root);

while (($row = fgetcsv($inputFile)) !== FALSE)
{
    $container = $doc->createElement($child_names[$section]);
    foreach ($headers as $i => $header)
    {
        $head = str_replace(array(" ","%","(",")"),"",$header);
        $child = $doc->createElement(trim(str_replace(" ","",$head)));
        $child = $container->appendChild($child);
        $no_value = trim($row[$i]);
       /* if(preg_match("/^[0-9]{2}/[0-9]{2}/[0-9]{4}$/", $no_value))
        {
            echo "yes";
            //$no_value = str_replace("/","-",$no_value);
        }*/
        $value = $doc->createTextNode($no_value);
        $value = @trim($child->appendChild($value));
    }
    $root->appendChild($container);
}
$strxml = $doc->saveXML();
$handle = fopen($outputFilename, "w");
fwrite($handle, $strxml);
fclose($handle);

libxml_use_internal_errors(true);
$doc2 = new DOMDocument();
$doc2->load($outputFilename);
/*if($section == "t")
$doc2->schemaValidate('http://rewardr-core:8888/modules/data_validation/xsd/trss.xsd');
else*/
$doc2->schemaValidate('xsd/'.$parent_names[$section].'.xsd');

$xpath = new DOMXpath($doc2);
$count = $xpath->query("//".$child_names[$section]."[1]/*")->length +2;

$errors = libxml_get_errors();
if(empty($errors)) {
    $message[] = "The CSV has no validation error";
    $data["answer"] = "1";
}else {
    foreach ($errors as $err) {
        $row_num = ceil(($err->line - 2) / $count);
        $message[] = "Row: " . $row_num . ' - ' . $err->message;
    }
    $data["answer"] = "0";
}
$data["message"] = $message;
echo json_encode($data);
?>