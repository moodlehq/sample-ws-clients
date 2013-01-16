#!/usr/bin/perl
#===============================================================================
#         FILE:  sample-rest-client.pl
#        USAGE:  ./sample-rest-client.pl
#  DESCRIPTION:  Sample Perl rest client to access moodle 2 web services
#                In this sample, we create two users
#       AUTHOR:  Emmanuel Otton (EO), otton@mines-albi.fr
#                based on a post by Richard Gillette
#                ( http://moodle.org/mod/forum/discuss.php?d=182471 )
#      VERSION:  1.0
#      CREATED:  02/04/2012 16:30
#===============================================================================
use strict;
use warnings;
use LWP::UserAgent; # web client
use JSON;           # imports encode_json, decode_json, to_json and from_json.
use Data::Dumper;   # to print the result variable

# --- These need to be changed for your site:
my $url_ws = "http://www.yourmoodle.com/~jerome/Moodle_HEAD/webservice/rest/server.php";
my $token  = "f95fe8ce5f6a4f01dc24ccdf333bba22";

# --- Function name and parameters
my $functionname = "core_user_create_users";
my $restformat = "json"; # Moodle rest server can also return xml
my $params = {
    'wstoken'                      => $token,
    'wsfunction'                   => $functionname,
    'moodlewsrestformat'           => $restformat,
    'users[0][username]'           => 'testusername1',
    'users[0][password]'           => 'Testpassword1!',
    'users[0][firstname]'          =>  'testfirstname1',
    'users[0][lastname]'           => 'testlastname1',
    'users[0][email]'              => 'testemail1@moodle.com',
    'users[0][timezone]'           =>  '-12.5',
    'users[0][auth]'               =>  'manual',
    'users[0][idnumber]'           =>  'testidnumber1',
    'users[0][lang]'               =>  'en',
    'users[0][theme]'              =>  'standard',
    'users[0][mailformat]'         =>  '0',
    'users[0][description]'        =>  'Hello World',
    'users[0][city]'               =>  'testcity1',
    'users[0][country]'            =>  'au',
    'users[0][preferences][0][type]'  =>  'preference1',
    'users[0][preferences][0][value]' =>  'preferencevalue1',
    'users[0][preferences][1][type]'  =>  'preference2',
    'users[0][preferences][1][value]' =>  'preferencevalue1',
    'users[1][username]'           => 'testusername2',
    'users[1][password]'           => 'Testpassword2!',
    'users[1][firstname]'          =>  'testfirstname2',
    'users[1][lastname]'           => 'testlastname2',
    'users[1][email]'              => 'testemail2@moodle.com',
    'users[1][timezone]'           =>  'Pacific/Port_Moresby',
};

my $ua = LWP::UserAgent->new;        # -- let's create our user agent
#$ua->ssl_opts(verify_hostname => 0); # be tolerant to self-signed certificates

my $result = $ua->post( $url_ws, $params );;      # --- ..and send the get request

if ( not $result->is_success ) {
    print $result->status_line, "\n";   # --- it might not work...
}

my $jsondecoder = JSON->new->allow_nonref;  # --- decode the JSON result,
my $userids = $jsondecoder->decode( $result->content );

print Dumper($userids);

__END__
