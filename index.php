<?php
/* Index example
 * author: Alexy DA CRUZ (GeomTech): dacruzalexy@gmail.com
*/

require_once("./SNClient/SNClient.php");
$SNClient = new ServiceNowClient("dev39869", "david.loo", "devatgl");

if($SNClient->Authenticated()){
  foreach($SNClient->GETFromTable("incident")->result as $incidents)
	{
    echo $incidents->number;
  }
}
?>
