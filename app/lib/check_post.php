<?php
class CheckPost{
    public static function injection($user_input) {
        $user_input = preg_replace('/(SELECT)/i','_SELECT_', $user_input);//nämä pitää tietokannan ehjänä
        $user_input = preg_replace('/(DELETE)/i','_DELETE_', $user_input);
        $user_input = preg_replace('/(INSERT)/i','_INSERT_', $user_input);
        $user_input = preg_replace('/(UPDATE)/i','_UPDATE_', $user_input);
        $user_input = preg_replace('/(TRUNCATE)/i','_TRUNCATE_', $user_input);
        $user_input = str_replace('<','&lt;',$user_input);//nämä pitää html:n ehjänä
        $user_input = str_replace('>','&gt;',$user_input);//toisaalta saman asian voisi toteuttaa tulostettaessa
        return $user_input;
    }
    
    public static function injections() {
        $posts = $_POST;
        foreach($posts as $key => $post){
        	$_POST[$key] = self::injection($post);
        }
    }
    
    public static function required($required) {
        self::injections();
        if(count($required)<1) return true;
        foreach($required as $key => $require){
            if(!isset($_POST[$require])) return false;
        }
        return true;
    }
    
    public static function required_redirect($required, $address = null) {
        if(!self::required($required)) Redirect::back($address);
    }
    
    public static function required_exit($required) {
        if(!self::required($required)) exit();
    }
}
