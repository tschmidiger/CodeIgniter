<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['file-upload-path'] = "assets/uploads/files";

$config['image-versions'] = array(
                                //version name, width, height
                                array(
                                    "version" => "xlarge"
                                    ,"width" => 1600
                                    ,"height" => 800
                                    ,"sharpen" => false
                                    )
                                ,array(
                                    "version" => "large"
                                    ,"width" => 1200
                                    ,"height" => 700
                                    ,"sharpen" => false
                                    )
                                ,array(
                                    "version" => "medium"
                                    ,"width" => 800
                                    ,"height" => 600
                                    ,"sharpen" => false
                                    )
                                ,array(
                                    "version" => "small"
                                    ,"width" => 600
                                    ,"height" => 0
                                    ,"sharpen" => true
                                    ) 
                                ,array(
                                    "version" => "thumb"
                                    ,"width" => 100
                                    ,"height" => 100
                                    ,"sharpen" => true
                                    ) 
                                ,array(
                                    "version" => "retina"
                                    ,"width" => 1600
                                    ,"height" => 800
                                    ,"sharpen" => false
                                    )
                            );

?>