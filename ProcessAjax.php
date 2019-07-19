<?php

require 'Includes/dbcon.php';
require 'Includes/RepoDB.php';
require 'Includes/Common.php';

use SQLData\Common as Common;

$ajaxValue = json_decode(file_get_contents("php://input"));
$person = array();

if(!empty($ajaxValue))
{
    $_POST = stdToArray($ajaxValue);
    if(isset($_POST['person']) && !empty($_POST['person']))
    {
        $person = $_POST['person'];
    }
}


if(isset($_POST['function2call']) && !empty($_POST['function2call'])) {

    $function2call = $_POST['function2call'];
    switch($function2call) {
        case 'get_personnel' :
            $personnel = Common::get_data('get_personnel');

              echo json_encode($personnel);
            break;
        case 'get_locations' :
             $locations = Common::get_data('get_locations');

            echo json_encode($locations);
            break;
        case 'update_person' :
            $person = (object)$person;
            $success = Common::add_data('update_person', $person);

            echo json_encode($success);
            break;
    }
}
    function stdToArray($obj){
        $reaged = (array)$obj;
        foreach($reaged as $key => &$field){
            if(is_object($field))$field = stdToArray($field);
        }
        return $reaged;
    }

?>