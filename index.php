<?php
class SabNzbd{

public $apiAddress = "https://192.168.0.254:9090/sabnzbd/"; // IP, Domain, Port
public $apiKey = "58d04ec6bdbeed9cc70c32032a8e6629";
public $apiOperation;// Possible: qstatus, version
public $apiOutput; //json or xml

public function __construct($apiOperation, $apiOutput)
{
    $this->apiOperation = $apiOperation;
    $this->apiOutput = $apiOutput;
}

function GetSabNzbdApiUrl()
{
     return  $this->apiAddress."api?apikey=".$this->apiKey."&mode=".$this->apiOperation."&output=".$this->apiOutput;
}

function ExecuteCurlOnSabnzbd($url)
{
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //disable SSL verification on peer
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); //disable SSL verification on host
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  //returns response; prints response if set to false
    //curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/'.$this->apiOutput)); //required for sending data to api
    curl_setopt($ch, CURLOPT_URL, $url);
    
    $result = curl_exec($ch);
    if (FALSE === $result)
        throw new Exception(curl_error($ch), curl_errno($ch));
    
    curl_close($ch);
    return $result;
}

}

$testObject = new SabNzbd("version","json");
$sabNzbdBaseUrl = $testObject->GetSabNzbdApiUrl();

$result = $testObject->ExecuteCurlOnSabnzbd($sabNzbdBaseUrl);

//var_dump($result);
//var_dump(json_decode($result,true));

$sabWarnings = new SabNzbd("warnings", "json");
$warnings = $sabWarnings->ExecuteCurlOnSabnzbd($sabWarnings->GetSabNzbdApiUrl());

var_dump($warnings);

$decodedWarnings = json_decode($warnings,true);
//var_dump($decodedWarnings);




?>
<table border="1" style="width:100%">
<tr>
<th>Key</th>
<th>Warning</th>
</tr>
<?php 
/* foreach ($decodedWarnings as $warning)
{
    foreach ($warning as $key => $value)
    {
        print "<tr><td>".$key."</td><td>".$value."</td></tr>";
    }
} */

?>
</table>