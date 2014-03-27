<?php
namespace Epals {

require_once('ApiEntityObject.php');


class Profile extends ApiEntityObject {

    function setUserId($user_id)
    {
        $this->data['user_id'] = $user_id;
    }
    
    function getUserId($user_id)
    {
        return $this->data['user_id'];
    }

    function setDescription($description) {
        $this->data['description'] = $description;
    }

    function getDescription() {
        return $this->data['description'];
    }

    function getSchoolName() {
        return $this->data['school_name'];
    }

    function setSchoolName($school_name) {
        $this->data['school_name'] = $school_name;
    }

    function getTeacherName() {
        return $this->data['teacher_name'];
    }

    function setTeacherName($profile_name) {
        $this->data['teacher_name'] = $profile_name;
    }

    function setEmail($external_email) {
        $this->data['email'] = $external_email;
    }

    function getEmail() {
        return $this->data['email'];
    }

    function setCountry($country_code) {
        $this->data['country'] = $country_code;
    }

    function getCountry() {
        return $this->data['country'];
    }

    function setStreet1($street1) {
        $this->data['street1'] = $street1;
    }

    function getStreet1() {
        return $this->data['street1'];
    }

    function setStreet2($street2) {
        $this->data['street2'] = $street2;
    }

    function getStreet2() {
        return $this->data['street2'];
    }

    function setCity($city) {
        $this->data['city'] = $city;
    }

    function getCity() {
        return $this->data['city'];
    }

    function setState($state) {
        $this->data['state'] = $state;
    }

    function getState() {
        return $this->data['state'];
    }

    function setZip($zip) {
        $this->data['zip'] = $zip;
    }

    function getZip() {
        return $this->data['zip'];
    }

    function setPhone($phone) {
        $this->data['phone'] = $phone;
    }

    function getPhone() {
        return $this->data['phone'];
    }

    function setSkypeName($skype_name) {
        $this->data['skype_name'] = $skype_name;
    }

    function getSkypeName() {
        return $this->data['skype_name'];
    }

    function setSkypeVisibility($skype_visibility) {
        $this->data['skype_visibility'] = $skype_visibility;
    }

    function getSkypeVisibility() {
        return $this->data['skype_visibility'];
    }

    function setAgeRange($age_range) {
        $this->data['age_range'] = $age_range;
    }

    function getAgeRange() {
        return $this->data['age_range'];
    }

    function setNumStudents($num_students) {
        $this->data['num_students'] = $num_students;
    }

    function getNumStudents() {
        return $this->data['num_students'];
    }

    function setLanguages($languages) {
        $this->data['languages'] = $languages;
    }

    function getLanguages() {
        return $this->data['languages'];
    }

    function setSubjects($subjects) {
        $this->data['subjects'] = $subjects;
    }

    function getSubjects() {
        return $this->data['subjects'];
    }

    function setCollaboration($collaboration) {
        $this->data['collaboration'] = $collaboration;
    }

    function getCollaboration() {
        return $this->data['collaboration'];
    }

    function setVisibility($profile_visibility) {
        $this->data['visibility'] = $profile_visibility;
    }

    function getVisibility() {
        return $this->data['visibility'];
    }

    function isDraft() {
        if ($this->data['status'] == self::$PROFILE_STATUS_DRAFT) {
            return true;
        } else {
            return false;
        }
    }

    function isApproved() {
        return ($this->data['approved'] == true);
    }

}
}
