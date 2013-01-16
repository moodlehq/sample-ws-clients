<?php
// This file is NOT a part of Moodle - http://moodle.org/
//
// This client for Moodle 2 is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//

/**
 * SOAP client for Moodle 2
 *
 * @authorr Jerome Mouneyrac
 */

/// SETUP - NEED TO BE CHANGED
$token = 'acabec9d20933913f14301785324f579';
$domainname = 'http://www.yourmoodle.com';
$functionname = 'core_user_create_users';

//////// moodle_user_create_users ////////

/// PARAMETERS - NEED TO BE CHANGED IF YOU CALL A DIFFERENT FUNCTION
$user1 = new stdClass();
$user1->username = 'testusername1';
$user1->password = 'Testpassword1!';
$user1->firstname = 'testfirstname1';
$user1->lastname = 'testlastname1';
$user1->email = 'testemail1@moodle.com';
$user1->auth = 'manual';
$user1->idnumber = 'testidnumber1';
$user1->lang = 'en';
$user1->theme = 'standard';
$user1->timezone = '-12.5';
$user1->mailformat = 0;
$user1->description = 'Hello World!';
$user1->city = 'testcity1';
$user1->country = 'au';
$preferencename1 = 'preference1';
$preferencename2 = 'preference2';
$user1->preferences = array(
    array('type' => $preferencename1, 'value' => 'preferencevalue1'),
    array('type' => $preferencename2, 'value' => 'preferencevalue2'));
$user2 = new stdClass();
$user2->username = 'testusername2';
$user2->password = 'Testpassword2!';
$user2->firstname = 'testfirstname2';
$user2->lastname = 'testlastname2';
$user2->email = 'testemail2@moodle.com';
$user2->timezone = 'Pacific/Port_Moresby';
$params = array($user1, $user2);

/// SOAP CALL
$serverurl = $domainname . '/webservice/soap/server.php'. '?wsdl=1&wstoken=' . $token;

//Check that the wsdl is available (no authentication error)
//Note: the wsdl generation script could return a xml error document instead of a WSDL document.
//      SoapClient() would not recognize this xml error document as a WSDL document and it will throw an invalid WSDL exception.
//      So we need to catch these WSDL generation errors first.
//      TODO: try this check only once. Then cache the WSDL. You don't want to do this extra call all the time.
$xml = simplexml_load_file($serverurl);
$faulcode = $xml->xpath('/SOAP-ENV:Envelope/SOAP-ENV:Body/SOAP-ENV:Fault/faultcode');
if (!empty($faulcode[0])) {
    $faultcode = (array) $faulcode[0];
    print_r($faultcode[0]);

    $faultstring = $xml->xpath('/SOAP-ENV:Envelope/SOAP-ENV:Body/SOAP-ENV:Fault/faultstring');
    if (!empty($faultstring[0])) {
        $faultstring = (array) $faultstring[0];
        print_r('<BR/>');
        print_r($faultstring[0]);
    }
    die();
}

////Do the main soap call
$client = new SoapClient($serverurl);
try {
$resp = $client->__soapCall($functionname, array($params));
} catch (Exception $e) {
    print_r($e);
}
if (isset($resp)) {
    print_r($resp);
}
