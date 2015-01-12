<?php

Router::any('/', 'HomeController:index');

//
// Default hook routes.
//
Router::mount('/', 'Hook\Application\Routes');
