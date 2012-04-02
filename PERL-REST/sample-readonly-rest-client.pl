#!/usr/bin/perl 
#===============================================================================
#         FILE:  sample-readonly-rest-client.pl
#        USAGE:  ./sample-readonly-rest-client.pl  
#  DESCRIPTION:  Sample Perl rest client to access moodle 2 web services
#                In this sample, we read the list of courses
#       AUTHOR:  Emmanuel Otton (EO), otton@mines-albi.fr
#      VERSION:  1.0
#      CREATED:  02/04/2012 16:30
#     REVISION:  ---
#===============================================================================
use strict;
use warnings;
use LWP::UserAgent; # web client
use JSON;           # imports encode_json, decode_json, to_json and from_json.

# --- These need to be changed for your site:
my $url_ws = "https://mymoodle.mydomain.fr/webservice/rest/server.php";
my $token  = "12345678901234567890123456789012";

my $ua = LWP::UserAgent->new;        # -- let's create our user agent
$ua->ssl_opts(verify_hostname => 0); # be tolerant to self-signed certificates

# --- then build our GET url..
my $url_get = $url_ws . "?wsfunction=core_course_get_courses&wstoken=$token&moodlewsrestformat=json";

my $result = $ua->get( $url_get );      # --- ..and send the get request

if ( not $result->is_success) {
    print $result->status_line, "\n";   # --- it might not work...
}

my $jsondecoder = JSON->new->allow_nonref;  # --- decode the JSON result,
my $perl_result = $jsondecoder->decode( $result->content );

# --- and, for example, print the names of our courses:
for my $course ( @{$perl_result} ) {
     my $fullname = ${$course}{'fullname'};
     print qq($fullname\n);
}

__END__
