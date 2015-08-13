<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    function get_image_filename($filename,$version) {
        $filename_version = "";
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $basename = basename($filename,'.'.$ext);
        
        $filename_version = $basename.'-'.$version.'.'.$ext;
        
        return $basename.'-'.$version.'.'.$ext;
    }
    
?>