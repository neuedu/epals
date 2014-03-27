<?php

set_include_path(get_include_path() . PATH_SEPARATOR . "../classes");

require_once('UserPreference.php');

// set a preference
$p = new Epals\UserPreference(null, 'steve@epals.com');

$p->set('newsletter', true);

$response = $p->update();

echo "Add/update preference result: " . $response->status . "\n";

// load a preference
$response = $p->load('cool');
echo "Load preference : " . $response->result . "\n";

// load a list of all preferences
$response = $p->load();
foreach ($response->result as $k => $v)
{
    echo "Loaded preference from list $k : $v\n";
}

//clear preference
$response = $p->delete('cool');

echo "Clear preference result: " . $response->status . "\n";

