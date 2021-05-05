/* Libguides librarian directory by Stacey Knight-Davis*/

//enter styles below in the head of your document or as a separate css file

<style type="text/css">

div#linklist ul {
list-style-image: none;
list-style-type: none;
margin-left: 2em;}


.indent {padding-left: 2em}

	
	.directoryname {
	font-weight: bold;
	font-size: 15px;
}

.darkgrey {
	background-color: #dedede;
		height: 110px;
}

.lightgrey{
	background-color: #eeeeee;
	height: 110px;
}

	.shortinfo {
	margin-left: 5px;
	padding: 30px 20px;
	display: block;
	float: left;	
	margin-top: 5px;
	
}

 
 .inline li {
    float: left;
	margin-bottom: 10px;
}

 .inline a {
    display: block;
    width: 200px;
}

.inline  ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
} 
    .noborder {border-style: none;}
	
	.directorylisting{
		font-size: 13px;
		color: #2c2c2c;
		line-height: 17px;
}

.directorylisting img {
	float: left;
	display: block;
	margin-top: 5px;

}
</style>




 <div class="directorylisting">	 
        <table class="noborder" align="center">

<?php

//uncomment next line for full error reporting
//ini_set('error_reporting', E_ALL);

//enter values for your instutution in place of ALL CAPS below. Use values listed in the API section of LibGuides


$test_api_url2="https://lgapi-us.libapps.com/1.1/accounts?site_id=YOUR_SITE_ID&key=SECRET_KEY&expand=subjects";


$test_api_url="https://lgapi-us.libapps.com/1.1/accounts?site_id=YOUR_SITE_ID&key=SECRET_KEY&expand=profile";
	

//retrieve data from API for profiles
global $test_api_url;
		$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL => $test_api_url,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_RETURNTRANSFER => true
	));
	$response = curl_exec($curl);
	if(!$response){die("Connection Failure");}
   curl_close($curl);
   $array = json_decode($response, true);


   
 $profile_last_names = array_column($array, 'last_name');

array_multisort($profile_last_names, SORT_ASC, $array);

//retrieve data from API for subjects

   global $test_api_url2;
		$curl2 = curl_init();
	curl_setopt_array($curl2, array(
		CURLOPT_URL => $test_api_url2,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_RETURNTRANSFER => true
	));
	$response2 = curl_exec($curl2);
	if(!$response2){die("Connection Failure");}
   curl_close($curl2);
   

   
    $array2 = json_decode($response2, true);
 
  


 $last_names = array_column($array2, 'last_name');

array_multisort($last_names, SORT_ASC, $array2);






//counter set to 0

   $i=0;

//Itterate through profiles

 foreach($array2 as $key2 => $value2) {
	  # Increment our row counter
	 
  //Insert emails to exclue below in place of exclude_email@mylibrary.libanswers.com
  // only profiles with subjects assigned will be displayed
   
	 if (isset( $value2["subjects"] ) and $value2["email"]!=="exclude_email@mylibrary.libanswers.com"){
		 
		$i++; 
		
   
	echo "<tr>
	
	<td ><div style=\"width:450px;height:250px;\" class=\"";

  //sets background color. Adjust color in style sheet
     
	if ($i % 2 == 0 ){echo "darkgrey";}
	else {echo "lightgrey";}
	
   //margins for paragraph set inline. adjust them here  
     
	echo " shortinfo\"> <p style=\"margin-top:-10px;line-height:1.2;\">";
	 
		
	
	foreach($value2["subjects"] as $subkey => $subject){	
		echo "<a href=\"https://eiu.libguides.com/az.php?s=" . $subject['id'] . "\">" . $subject['name'] . "</a>";
		echo "<br />";
		
	}
		 
		 
	
	echo "</p></div></td>";
		 
		 
		 
		 //more inline styles. Adjust width if needed.
		 
		echo "<td><div style=\"width:500px;height:250px;float:left;\"";
	
	echo " class=\"";

    
	if ($i % 2 == 0 ){echo "darkgrey";}
	else {echo "lightgrey";}
	
     //I store photos outside of libguides so I can keep them sized consistantly.
     //Change https://mylibrary.org/photos to the location of your photos
     //All photos are named with the person's last name as the file name. _thmb is appended to the thumbnail size image.
     
	echo " shortinfo\"><img src=\"https://mylibrary.org/photos/". $value2["last_name"] .  "_thmb.jpg\" alt=\"" .
    
  $value2["first_name"] . " " . $value2["last_name"] . "\" ";
    
     //more inline styles. Adjust as needed.
     
     echo "style=\"margin-right:10px;margin-top:-5px;\">
	
	
	<div style=\"margin-left: 150px;\"><span class=\"directoryname\">";
		 
		 echo $value2["first_name"] . " " . $value2["last_name"] . "</span><p> ";

	 echo "<a href=\"mailto:" . $value2["email"] . "\">" . $value2["email"] ."</a><br />";
		 
		 $idnum=$value2["id"];

		echo  "<br/>";
		
		 //the API is pretty awful. This line matches information from the subjects call to the profile call.
     
		 $indexnum = array_search($idnum, array_column($array, 'id'));
		 
		  
		 
		
		 
			 
			
			 
		 if(strlen( $array[$indexnum]['title'])>0){
		 echo $array[$indexnum]['title'];
			 echo  "<br/>";}
		
			 
		
		
		   echo($array[$indexnum]['profile']['connect']['phone']);
		 
		  echo  "<br/>";
		 	if(strlen( $array[$indexnum]['profile']['connect']['address'])>0){
				echo($array[$indexnum]['profile']['connect']['address']);
				echo  "<br/>";}
		 if(strlen( $array[$indexnum]['profile']['connect']['website'])>0){
		 echo "<a href=\"" . $array[$indexnum]['profile']['connect']['website'] . "\">Selected Works</a><br/>";
		 }
		 
		  if(strlen( $array[$indexnum]['profile']['widget_lc'])>0){
		$appointment = $array[$indexnum]['profile']['widget_lc'];
						 
			 //The number needed to get an appointment is burried in javascript. This line looks for a 5 digit number
			  
			  preg_match('/[0-9]{5}/', substr($appointment,5), $matches, PREG_OFFSET_CAPTURE);
			  
			 $uid=$matches[0][0];
			  
			  echo "<br /><a href=\"https://eiu.libcal.com/appointment/" . $uid . "\">Make an Appointment</a>";
		 }
			 
		 echo "</p></div></td></tr>";
		 
		 
		
		 
		 
		
	 }
 }



echo "</table>";
    



?>
