<?php
/* Index example
 * author: Alexy DA CRUZ (GeomTech): dacruzalexy@gmail.com
 * Available on Github: https://github.com/geomtech/Service-Now-PHPClient
*/

require_once("./SNClient/SNClient.php");
$SNClient = new ServiceNowClient("dev39869", "david.loo", "devatgl");

if($SNClient->Authenticated()){

  $data_array = array(
    'short_description' => 'modified',
  );

  $SNClient->ModifyRecord("incident", "9c573169c611228700193229fff72400", $data_array);

  foreach($SNClient->RetrieveRecord("incident", "9c573169c611228700193229fff72400") as $incidents)
  {
    echo $incidents->short_description.'<br>';
  }
  echo '<br>';
}
?>
