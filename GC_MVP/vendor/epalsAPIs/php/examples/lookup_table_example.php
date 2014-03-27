<?php

set_include_path(get_include_path() . PATH_SEPARATOR . "../classes");

require_once('SessionBroker.php');
require_once('Session.php');
require_once('CountryLookup.php');
require_once('SchoolTypeLookup.php');
require_once('GradeLookup.php');
require_once('AgeRangeLookup.php');
require_once('RoleLookup.php');

$session_broker = new Epals\SessionBroker();
//$session = $session_broker->login('abcdefg@epals.com', 'abcdefg');
$session = $session_broker->login(NULL, NULL);

// passing the session doesn't do anything currently, could be very important someday though 
$lookup_table = new Epals\CountryLookup($session);

$all_countries = $lookup_table->getAllCountries();

echo "getAllCountries:\n";
foreach ($all_countries as $country) {
    echo "$country->countryCode : $country->countryName<br />";
}

echo '<hr />';
echo "getCountryName: " . $lookup_table->getCountryName('ca') . "<br />";

echo "getCountryCode: " . $lookup_table->getCountryCode('Canada') . "<br />";

$provinces = $lookup_table->getCountryProvinces('ca');

echo "getCountryProvinces:<br />";
foreach ($provinces as $p) {
    echo $p->provinceCode . " : " . $p->provinceName . "<br />";
}

echo '<hr />';

$lookup_table = new Epals\SchoolTypeLookup($session);
$school_types = $lookup_table->getAllSchoolTypes();
echo "School Types:<br />";
foreach ($school_types as $s) {
    echo $s->schoolTypeId . " : " . $s->schoolTypeName . "<br />";
}

echo "getSchoolTypeId: " . $lookup_table->getSchoolTypeId('Public') . "<br />";
echo "getSchoolTypeName: " . $lookup_table->getSchoolTypeName('public') . "<br />";

echo '<hr />';

$lookup_table = new Epals\AgeRangeLookup($session);
$age_ranges = $lookup_table->getAllAgeRanges();
echo "Age Ranges:<br />";
foreach ($age_ranges as $s) {
    echo $s->ageId . " : " . $s->ageRange . "<br />";
}

echo "getAgeRangeId: " . $lookup_table->getAgeRangeId('8-10') . "<br />";
echo "getAgeRange: " . $lookup_table->getAgeRangeName(1) . "<br />";

echo '<hr />';

$lookup_table = new Epals\GradeLookup($session);
$grades = $lookup_table->getAllGrades();
echo "Grades:<br />";
foreach ($grades as $s) {
    echo $s->gradeId . " : " . $s->gradeName . "<br />";
}

echo "getGradeId: " . $lookup_table->getGradeId('First') . "<br />";
echo "getGradeName: " . $lookup_table->getGradeName('first') . "<br />";

echo '<hr />';

$lookup_table = new Epals\RoleLookup($session);
$roles = $lookup_table->getAllRoles();
echo "Roles:<br />";
foreach ($roles as $s) {
    echo $s->roleId . " : " . $s->roleName . "<br />";
}

echo "getRoleId: " . $lookup_table->getRoleId('Teacher') . "<br />";
echo "getRoleName: " . $lookup_table->getRoleName('teacher') . "<br />";


