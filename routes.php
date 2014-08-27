<?php

Router::any('/', 'HomeController:index');
Router::mount('/admin', 'Hook\Admin\Routes');
