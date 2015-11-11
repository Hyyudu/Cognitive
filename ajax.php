<?php
include_once('cMainModel.php');

cDB::init();

if (isset($_GET['get_users_data'])) {
    $data = array('root' => array_values(cMainModel::getUsersData()));
}
elseif (isset($_GET['get_educations_data'])) {
    $data = array('root' => cMainModel::getEducationData());
}
elseif (isset($_GET['get_cities_data'])) {
    $data = array('root' => cMainModel::getCitiesData());
}
elseif (isset($_GET['change_user_education']))  {
    cMainModel::setUserEducation($_REQUEST['user_id'], $_REQUEST['education_id']);
    $data = array('success' => true);
}

echo(json_encode($data));