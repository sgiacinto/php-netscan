<?php

// requires fping to be installed on the server  (apt install fping) 

header("Access-Control-Allow-Origin:*");
error_reporting(E_ERROR | E_PARSE | E_WARNING);

set_time_limit(120); // in case it takes longer

$start = $_GET['start'];
$end = $_GET['end'];

 function ip_range($start, $end) {
    $start = ip2long($start); // convert start and end IPs to long
    $end =   ip2long($end);
    return array_map('long2ip', range($start, $end) ); // get the range from-to and convert back to an IP
  }
 
function lookup($start, $end) {
            
            // crate an aray of single IPs from the range
            $ips = ip_range($start, $end);
    
            $str = implode(" ", $ips);
            $res = [];
            $final = [];
            $out = [];

            exec("fping -c1 -t100 $str", $res);
            // $res  has the result of every IP ping

            foreach ($res as $entry) {
                if (!strpos($entry, '100%')) { // filter out any with the 100% loss message
                    $final[] = trim(explode(":", $entry)[0]); // parse it and save the IP part
                }
            }

           foreach ($final as $ip) {
                $e = new StdClass;
				$x = gethostbyaddr($ip);  // get the hostname as per local DNS of the IP
                if($x == $ip) $x = "<span style='color:red;font-family:courier new'>no dns</span>";
                $e->ip = $ip;
                $e->name = $x;
                $out[] = $e;
			}

    return $out; // return the array of objects in the form {ip : <ip address>, name : <hostname>}
 }

header("Content-type:application/json");
echo json_encode(lookup($start,$end));
?>
