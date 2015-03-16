<?php
class SabNzbd{

public $apiAddress = "https://192.168.0.254:9090/sabnzbd/"; // IP, Domain, Port
public $apiKey = "58d04ec6bdbeed9cc70c32032a8e6629";
public $apiOperation;// Possible: qstatus, version, queue
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

    function ExecuteCurlOnSabnzbd()
    {
        $url = $this->GetSabNzbdApiUrl();
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
    
    function GetIPSDataTypeCode($dataType)
    {
     switch($dataType)
     {
         case "string";
         default:
             return 3;
             break;
         case "double": //float
             return 2;
             break;
         case "integer":
             return 1;
             break;
         case "boolean":
             return 0;
             break;
     }
    }

}

$testObject = new SabNzbd("version","json");

$version = $testObject->ExecuteCurlOnSabnzbd();

/*
var_dump($version);
var_dump(json_decode($version,true));



 $testObject->apiOperation = "warnings";
$warnings = $testObject->ExecuteCurlOnSabnzbd();

var_dump($warnings);
$decodedWarnings = json_decode($warnings,true);
var_dump($decodedWarnings); */

$testObject->apiOperation="queue";
$queue = $testObject->ExecuteCurlOnSabnzbd();
$decodedQueue = json_decode($queue);
var_dump($queue);
var_dump(json_decode($queue,true));


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

<?php 

foreach ($decodedQueue as $queue)
{
    
    foreach ($queue as $key => $value)
    {
        if(!is_array($value))
        {
            print "Schluessel: ".$key."     Wert: ".$value."      DATA TYPE=".gettype($value)."    IPS CODE= ".$testObject->GetIPSDataTypeCode(gettype($value))."<br>";
        }
        else 
        {
            print "VALUE OF ".$key." IS ARRAY <br>";
        }
    }
}
?>

