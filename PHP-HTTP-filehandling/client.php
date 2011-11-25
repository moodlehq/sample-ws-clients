<?php
// This file is NOT a part of Moodle - http://moodle.org/
//
// This client for Moodle 2 is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//

/**
 * HTTP file upload/download client for Moodle 2.1/Moodle2.2 and higher
 * 
 * THIS DOES NOT CALL A WEB SERVICE FUNCTION BUT DEMONSTRATE HOW TO UPLOAD A FILE
 * IN USER PRIVATE FILE, AND TO DOWNLOAD A FILE.
 *
 * @author Jerome Mouneyrac
 */

/// GLOBAL SETTINGS - CHANGE THEM !
$token = '98e74de004450cf8be0668ba33507d5a';
$domainname = 'http://YOURMOODLESITE.com';

/* ========================================================================= */

/// UPLOAD PARAMETERS
//Note: check "Maximum uploaded file size" in your Moodle "Site Policies".
$imagepath = '/computerroot/.../sample-ws-clients/PHP-HTTP-filehandling/image_to_upload.jpg'; //CHANGE THIS !
$filepath = '/'; //put the file to the root of your private file area. //OPTIONAL

/// UPLOAD IMAGE - Moodle 2.1 and later
$params = array('file_box' => "@".$imagepath,'filepath' => $filepath, 'token' => $token);
$ch = curl_init();
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_VERBOSE, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
curl_setopt($ch, CURLOPT_URL, $domainname . '/webservice/upload.php');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $params); 
$response = curl_exec($ch);
print_r($response);

/* ========================================================================= */

/// DOWNLOAD PARAMETERS
//Note: The service associated to the user token must allow "file download" ! 
//      in the administration, edit the service to check the setting (click "advanced" button on the edit page).

//Normally you retrieve the file download url from calling the web service core_course_get_contents()
//However to be quick to demonstrate the download call, 
//you are going to retrieve the file download url manually:
//1- In Moodle, create a forum with an attachement
//2- look at the attachement link url, and copy everything after http://YOURMOODLE/pluginfile.php 
//   into the above variable
$relativepath = '/20/mod_forum/attachment/1/S8%20-%20Week%205%20-%20Thursday.pdf'; //CHANGE THIS !

//CHANGE THIS ! This is where you will store the file. 
//Don't forget to allow 'write permission' on the folder for your web server.
$path = '/computerroot/.../sample-ws-clients/PHP-HTTP-filehandling/S8 - Week 5 - Thursday.pdf'; 

/// DOWNLOAD IMAGE - Moodle 2.2 and later
$url  = $domainname . '/webservice/pluginfile.php' . $relativepath; //NOTE: normally you should get this download url from your previous call of core_course_get_contents() 
$tokenurl = $url . '?token=' . $token; //NOTE: in your client/app don't forget to attach the token to your download url
$fp = fopen($path, 'w');
$ch = curl_init($tokenurl);
curl_setopt($ch, CURLOPT_FILE, $fp);
$data = curl_exec($ch);
curl_close($ch);
fclose($fp);