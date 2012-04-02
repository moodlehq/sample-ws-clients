#!/usr/bin/perl 
#===============================================================================
#         FILE:  sample-rest-client.pl
#        USAGE:  ./sample-rest-client.pl  
#  DESCRIPTION:  Sample Perl rest client to access moodle 2 web services
#                In this sample, we create two courses
#       AUTHOR:  Emmanuel Otton (EO), otton@mines-albi.fr
#                based on a post by Richard Gillette
#                ( http://moodle.org/mod/forum/discuss.php?d=182471 )
#      VERSION:  1.0
#      CREATED:  02/04/2012 16:30
#     REVISION:  ---
#===============================================================================
use strict;
use warnings;
use LWP::UserAgent;

# --- These need to be changed for your site:
my $url_ws = "https://mymoodle.mydomain.fr/webservice/rest/server.php";
my $token  = "12345678901234567890123456789012";

# -- let's create our user agent
my $ua = LWP::UserAgent->new;
$ua->ssl_opts(verify_hostname => 0);    # be tolerant to self-signed certificates

# --- prepare our parameters, with enough data to create two courses.
# we cannot send the parameters
# in the form of a serialized structure.
# We have to send separately each parameter,
# and name it like a PHP-structure element,
# as the WS API doc says:
#   REST (POST parameters)
#   courses[0][fullname]= string
#   courses[0][shortname]= string
#   courses[0][categoryid]= int
# .../...
my $params = {
    'wstoken'                 => $token,
    'wsfunction'              => 'core_course_create_courses',
    'moodlewsrestformat'      => 'json',
    'courses[0][fullname]'    => 'My first API-created course',
    'courses[0][shortname]'   => 'BINGO_01',
    'courses[0][categoryid]'  =>  4,
    'courses[0][idnumber]'    => 'mdlws101',
    'courses[0][format]'      => 'topics',
    'courses[0][numsections]' =>  3,
    'courses[1][fullname]'    => 'And this one is the second',
    'courses[1][shortname]'   => 'BINGO_02',
    'courses[1][categoryid]'  =>  4,
    'courses[1][idnumber]'    => 'sgbd101',
    'courses[1][format]'      => 'topics',
    'courses[1][numsections]' =>  3,
};

# --- Now just send our post request...
my $res = $ua->post( $url_ws, $params );

# --- and test success or failure:
if ($res->is_success) {
    print $res->content, "\n";
} else {
    print $res->status_line, "\n";
}
