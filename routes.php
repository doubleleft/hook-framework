<?php

Router::any('/', 'HomeController:index');
Router::mount('/admin', 'Hook\CMS\Controllers\AdminController');
