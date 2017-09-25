# Service-Now-PHPClient

Great to have all your Service-Now incidents, changes, tasks etc... on a beautiful dashboard for example !

## How to use Service-Now-PHPClient ?

To use Service-Now-PHPClient, you must have to declare this in your files.

```php
require_once("./SNClient/SNClient.php");
```

You need to use credentials to connect your website to Service-Now.
(You have to create a generic account if possible or use a normal account. Here we used an account from a developper instance from Service-Now.)

```php
$SNClient = new ServiceNowClient($instance, $username, $password);
```

Here, we verify that we can login on Service-Now and we display all number of first 10 incidents ! (see parameters on
  Service-Now docs.)

```php
if($SNClient->Authenticated()){
  foreach($SNClient->GETFromTable("incident", "sysparm_limit=10")->result as $incidents)
	{
    echo $incidents->number;
  }
}
```

You can create record in a table ! All parameters available ! :)
```php
  $data_array = array(
    'short_description' => $short_description,
    'description' => $description,
    'urgency' => $urgency,
    'impact' => $impact,
    'state' => $state,
    'assignment_group' => $assignment_group,
    'caller_id' => $caller_id,
  );

  $SNClient->POSTInTable("incident", $data_array);
```

**So, it's easy ? :)**

## You found a bug ?

Open an issue and I will correct this :)

If you resolve the issue by modify code, do a pull request and add your name in comment.

## TODO
- [x] GET TableAPI
- [ ] PUT TableAPI
- [ ] PATCH TableAPI
- [ ] DELETE TableAPI
