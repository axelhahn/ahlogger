# example.php

```php
<?php
require '../classes/logger.class.php';
global $oLog;
$oLog = new logger();
$bIsDevelopEnvironment = true;


$oLog->add("info ... all GET params: <pre>" . print_r($_GET,1) . "</pre>");
$oLog->add("info ... all POST params: <pre>" . print_r($_POST,1) . "</pre>");

$oLog->add("start db request");
$sSql='select id, label, description from mytable;';
// ... make your query
$oLog->add("sql query finished: " . $sSql);

$oLog->add("description of action");
// ... do something

$oLog->add("description of another action");
// ... do something else

if ($bIsDevelopEnvironment){
	echo $oLog->render();
}
```
