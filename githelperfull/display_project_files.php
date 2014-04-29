<?php
$project=$_GET['project'];
$directories = glob($project."/*",GLOB_ONLYDIR);
$user_name=$_GET['user_name'];
/*
foreach ($directories as $key => $value) {
	printf("folder::".basename($value));
	printf("\n");
}*/

printf("\n");
$files = array_filter(glob($project."/*"), 'is_file');
/*
foreach ($files as $key => $value) {
	printf("file::".basename($value));
	printf("\n");
}*/


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
$output=array();
$readme_list=array ();
if ($row = mysql_fetch_assoc($class_result)) {
	$url_list = explode(",", $row['clone_url']);
	$readme_list=explode(",", $row['readme']);

}

$output = array_slice(array_reverse($url_list), 0, 7);
$output_readme=array_slice(array_reverse($readme_list), 0, 7);
$select_repos = "SELECT starrepo from userinfo where username ='" . $user_name . "'";
//printf($select_repos);
//print_r($select_race);
$repos_result = mysql_query($select_repos, $dbhandle);
$num_results = mysql_num_rows($repos_result); 
$star_repo_list=array ();
	if ($num_results >0){ 
	if ($row = mysql_fetch_assoc($repos_result)) 
	{
	$star_repo_list = explode(",", $row['starrepo']);
	//print_r($star_repo_list);
	}
	}
$output_final=array();
$output_star_repo=array();
$output_other_repos=array();
if(count($star_repo_list)>0)
$output_star_repo=array_intersect($output, $star_repo_list);

//print_r($output_star_repo);
if(count($output_star_repo)>0)
{
	$output_final=array_merge($output_final,$output_star_repo);
	$output_other_repos=array_diff($output, $output_star_repo);
	if(count($output_other_repos)>0)
	{
		$output_final=array_merge($output_final,$output_other_repos);
	}	
}
else {
	$output_final=$output;
}
$final_readme=array ();
foreach($output_final as $value)
{
	foreach($output as $key=> $value1)
	{
		if($value==$value1)
		{
			$final_readme[$value]=$output_readme[$key];
		}
	}
}
//printf("final-------------");
//print_r($output_final);



?>





<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Git Helper</title>
<link href="stylesheets/common.css" rel="stylesheet" type="text/css" />
</head>
<body>
	<!-- Start Header -->
	<div id="header">
		<div class="container">
			<h1><a href="#" title="GITHELPER">GIT HELPER<span></span></a></h1>
			<hr />
			<!-- top navigation -->
			<ul id="navigation">
				<li class="active"><a href="#" title="Home">Home</a></li>
				<li><a href="#" title="About">About</a></li>
				<li><a href="#" title="Services">Create Repositories</a></li>
				<li><a href="#" title="Portofolio">Fork Repositories</a></li>
				<li><a href="#" title="Contact">Help</a></li>
			</ul>
			<hr />
			<!-- banner message and building background -->
			<div id="banner">
				Welcome <?php echo $user_name; ?>
			</div>
			<hr />
		</div>
	</div>
	<!-- Start Main Content -->
	<div id="main" class="container">
		<!-- left column (products and features) -->
		<div id="leftcolumn">
			<h3 class="leftbox">Sthitaprajna</h3>
			<img src="git.png" alt="Image:product" class="git_image" />
			<hr />
		</div>
		<!-- main content area -->
		<div id="center">
			<div id="content" class="article_wrapper">
				<h2><?php echo basename($project); ?></h2>
     <?php foreach ($directories as $key => $value) {?>
             <div class="article_wrapper">           
                            
 <a href="display_project_files.php?project=<?=$value?>" class="text-muted"><h3> <?= basename($value) ?></h3></a>
                            
                          </div>
                    
                        <?php } ?>
                        <?php foreach ($files as $key => $value) {?>
                 <div class="article_wrapper">  
                            <h3> <?= basename($value) ?></h3>
                          </div>
                    
                        
                        <?php } ?>
			</div>
		</div>
		<!-- product sales boxes -->
		<div id="rightcolumn">
			<div class="rightbox_wrapper">
				<div class="rightbox">
					<h3> Related repositories</h3>
					<div class="product_wrapper" style="word-wrap: break-word; width: 300px">
						 <?php foreach ($final_readme as $key => $value) {?>
                   
                           
 <a href="git_repo.php?git_url=<?=$key?>&user_name=<?=$user_name?>"  target ="blank" class="text-muted"><h3> <?= basename($key) ?></h3> </a>
                              <h4><?=$value?> </h4>
                         
                    
                        <?php } ?>
					</div>
				</div>
			</div>
			<hr />
		</div>
	</div>
	<!-- Start Bottom Information -->
	<div id="bottominfo">
		<div class="container">
			<!-- bottom left information -->
			<div class="bottomcolumn">
				
			</div>
			<!-- bottom center information -->
			<div class="bottomcolumn">
				
			</div>
			<!-- bottom right information -->
			<div class="bottomcolumn bottomright">
				
			</div>
			<hr />
		</div>
	</div>
	<!-- Start Footer -->
	<div id="footer">
		<div class="container">
			
		</div>
	</div>
	
<script>
function myFunc(Id)
{

//var y = document.getElementById(imageId);
alert ("You selected for "+ Id);
//window.location="race2.html";
//document.getElementById("demo").innerHTML=y;
}
</script>
</body>
</html>