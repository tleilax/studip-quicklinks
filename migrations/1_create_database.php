<?php
class CreateDatabase extends DBMigration
{

    function description ()
    {
        return 'Creates neccessary tables for "Quick Links" plugin';
    }

    function up ()
    {
        DBManager::get()->exec("CREATE TABLE IF NOT EXISTS `quicklinks` (
            `user_id` CHAR(32) NOT NULL,
            `link_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
            `link` VARCHAR(255) NOT NULL,
            `title` VARCHAR(255) NOT NULL DEFAULT '',
            `position` SMALLINT(5) UNSIGNED NOT NULL DEFAULT 0,
            `click` INT(10) UNSIGNED NOT NULL DEFAULT 0,
            `timestamp` DATETIME NOT NULL,
            `modified` DATETIME NOT NULL,
            PRIMARY KEY (`user_id`, `link_id`)
        )");
    }

    function down ()
    {
        DBManager::get()->exec("DROP TABLE IF EXISTS `quicklinks`");
    }
}
