<?php
//include 'my.php';
$url=$_GET['git_url'];
//process($url)
$starrepo="";
$user_name=$_GET['user_name'];
	$dbhandle = mysql_connect("localhost", "root", "enter password here") or die("Unable to connect to MySQL");
	$selected = mysql_select_db("githelper", $dbhandle) or die("Could not select examples");
	$select_repos="SELECT starrepo from userinfo where username ='" . $user_name . "'";	
	$user_result = mysql_query($select_repos, $dbhandle);
	$num_results = mysql_num_rows($user_result); 
	//printf($num_results);
	if ($num_results > 0){
		if ($row = mysql_fetch_assoc($user_result)) {
			
		$starrepo=$row['starrepo'];
		//printf($starrepo);
		}
		$url_list= explode(",", $starrepo);
		if(!in_array($url, $url_list))
		 $starrepo=$starrepo.",".$url;
		else {
			$starrepo=$url;
		}
	//printf ("User is not registered"); 
		//exit;
	}
	else {
		$starrepo=$url;
			
	}
	
	$update_repos="UPDATE  userinfo SET starrepo='" .$starrepo ."' where username ='" . $user_name . "'";

	mysql_query($update_repos, $dbhandle);
	
	header("Location: $url");
	exit;
    //printf();
?>