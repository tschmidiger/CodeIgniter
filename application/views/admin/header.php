<!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin | Welcome</title>
    <link rel="stylesheet" href="/assets/foundation/css/foundation.css" />
    <link rel="stylesheet" href="/assets/css/custom.css" />
    <script src="/assets/foundation/js/vendor/modernizr.js"></script>
    <script src="/assets/foundation/js/vendor/jquery.js"></script>
    <?
    
    if(isset($crud->css_files)) { 
        foreach($crud->css_files as $file):
            ?><link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" /><?
        endforeach; 
    }
    if(isset($crud->js_files)) {
        foreach($crud->js_files as $file): 
            ?><script src="<?php echo $file; ?>"></script><?
        endforeach;
    }
    
    ?>
  </head>
    <body>
        <nav class="row top-bar" data-topbar role="navigation">
            <ul class="title-area">
                <li class="name">
                    <h1><a href="#">galeriebernhard.com</a></h1>
                </li>
                <!-- Remove the class "menu-icon" to get rid of menu icon. Take out "Menu" to just have icon alone -->
                <li class="toggle-topbar menu-icon">
                    <a href="#"><span>Menu</span></a>
                </li>
            </ul>

            <section class="top-bar-section">
                <!-- Left Nav Section -->
                <ul class="left">
                    <li<? if($method == 'index') { echo ' class="active"'; } ?>>
                        <a href="/admin/">Exhibitions</a>
                    </li>
                    <li<? if($method == 'artists') { echo ' class="active"'; } ?>>
                        <a href="/admin/artists">Artists</a>
                    </li>
                    <li<? if($method == 'images') { echo ' class="active"'; } ?>>
                        <a href="/admin/images">Images</a>
                    </li>
                </ul>
            </section>
        </nav>
