<?php
// This file is NOT a part of Moodle - http://moodle.org/
//
// This client for Moodle 2 is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//

/**
 * JAVASCRIPT client for Moodle 2.2 or later
 *
 * @authorr Jerome Mouneyrac
 */
?>

<html>
<head>
<script type="text/javascript" src="http://code.jquery.com/jquery-1.6.4.js"></script>
<script type="text/javascript">
$(document).ready(function() {

    var domainname = 'http://yourmoodle';
    var token = 'acabec9d208978d986986g987657ffg9';
    var functionname = 'core_user_create_users';

    var serverurl = domainname + '/webservice/rest/server.php' ;
    //add params into data
    var userstocreate = [{  username: 'testusername1',
                            password: 'testpassword1',
                            firstname: 'testfirstname1',
                            lastname: 'testlastname1',
                            email: 'testemail1@moodle.com',
                            auth: 'manual',
                            idnumber: 'testidnumber1',
                            lang: 'en',
                            theme: 'standard',
                            timezone: '-12.5',
                            mailformat: 0,
                            description: 'Hello World!',
                            city: 'testcity1',
                            country: 'au',
                            preferences: [
                                {type: 'preference1', value: 'preferencevalue1'},
                                {type: 'preference2', value: 'preferencevalue2'}
                            ]
                         },
                         {  username: 'testusername2',
                            password : 'testpassword2',
                            firstname : 'testfirstname2',
                            lastname : 'testlastname2',
                            email : 'testemail2@moodle.com',
                            timezone : 'Pacific/Port_Moresby'
                         }
                     ];

    var data = {
                wstoken: token,
                wsfunction: functionname,
                moodlewsrestformat: 'json',
                users: userstocreate
                }
    var response = $.ajax(
                            {   type: 'POST',
                                data: data,
                                url: serverurl
                            }
                         );
    console.info(response);
});
</script>
</head>
<body>
    Check your Javascript console for the "responseText" value.
</body>
</html>


