<?php
/* Index example
 * author: Alexy DA CRUZ (GeomTech): dacruzalexy@gmail.com
*/

require_once("./SNClient/SNClient.php");
$SNClient = new ServiceNowClient("dev39869", "david.loo", "devatgl");

if($SNClient->Authenticated()){

  $data_array = array(
    'short_description' => '$short_description',
    'description' => '$description',
    'urgency' => "1",
    'impact' => "1",
    'state' => "1",
    'number' => "INC9000001",
  );

  $SNClient->POSTInTable("incident", $data_array);

  foreach($SNClient->GETFromTable("incident", "sysparm_query=number=INC9000001")->result as $incidents)
  {
    echo $incidents->number.'<br>';
  }
  echo '<br>';
}
?>
