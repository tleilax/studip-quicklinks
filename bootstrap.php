<?php
    if (!class_exists('CSRFProtection')) {
        class CSRFProtection {
            static function tokenTag() { return ''; }
        }
    }

    // Global includes
    require_once 'vendor/trails/trails.php';
    require_once 'app/controllers/studip_controller.php';
    require_once 'app/controllers/authenticated_controller.php';
    
    // Local includes
    require 'controllers/quicklinks_controller.php';
    require 'models/Quicklink.class.php';
