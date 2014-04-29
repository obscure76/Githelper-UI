<?php

$files = array_filter(glob($_GET['project'] . "/*"), 'is_file');
//print_r($files);
//printf("********file names*********"+"\n");
$dbhandle = mysql_connect("localhost", "root", "enter password here") or die("Unable to connect to MySQL");
$selected = mysql_select_db("githelper", $dbhandle) or die("Could not select examples");
foreach ($files as $index => $file) {
	if (stripos(basename($file), "readme") !== false) {
		$file_path = $file;
	}
}
//$mycon=$contents;
//printf($mycon[1]);
//printf($contents);
echo "\n";
#printf($file_path);
$mystring = exec("python myscript.py $file_path", $retval);
//printf("1111".$mystring);
$select_class = "SELECT * from datatable where class_name ='" . $mystring . "'";
//printf($select_class);
//print_r($select_race);
$class_result = mysql_query($select_class, $dbhandle);
if (!$class_result) {
	echo "Could not successfully run query ($select_race) from DB: " . mysql_error();
	exit ;
}

$url_list = array();

if ($row = mysql_fetch_assoc($class_result)) {
	$url_list = explode(",", $row['clone_url']);

}

$output = array_slice(array_reverse($url_list), 0, 20);
print_r($output);

//$tmp=exec("python myscript.py $contents");
//echo $tmp;
?>
