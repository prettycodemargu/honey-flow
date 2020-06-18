<?php

include __DIR__ . "/../../autoload.php";

$db = (new Db\Connection)->get();

if (in_array('up', $_SERVER['argv']))
{
    try {
        $db->query('
              CREATE TABLE IF NOT EXISTS storage (
              `id` INT(11) NOT NULL AUTO_INCREMENT,
              `dashboard_id` INT(11) NOT NULL,
              `storage_name` VARCHAR(255) NOT NULL,
              `amount` DECIMAL(15,2) NOT NULL,
              `currency_digital_code` INT(11) NOT NULL,
              `created` DATETIME NOT NULL DEFAULT now(),
              `is_deleted` TINYINT(1) NOT NULL default 0,
              PRIMARY KEY (`id`),
              INDEX `dashboard` (`dashboard_id` ASC));

              CREATE TABLE IF NOT EXISTS source (
              `id` INT(11) NOT NULL AUTO_INCREMENT,
              `dashboard_id` INT(11) NOT NULL,
              `source_name` VARCHAR(255) NOT NULL,
              `created` DATETIME NOT NULL DEFAULT now(),
              `is_deleted` TINYINT(1) NOT NULL default 0,
              PRIMARY KEY (`id`),
              INDEX `dashboard` (`dashboard_id` ASC));
              
              CREATE TABLE IF NOT EXISTS income (
              `id` INT(11) NOT NULL AUTO_INCREMENT,
              `dashboard_id` INT(11) NOT NULL,
              `source_id` INT(11) NOT NULL,
              `storage_id` INT(11) NOT NULL,
              `amount` DECIMAL(15,2) NOT NULL,
              `currency_digital_code` INT(11) NOT NULL,
              `created` DATETIME NOT NULL DEFAULT now(),
              `is_deleted` TINYINT(1) NOT NULL default 0,
              PRIMARY KEY (`id`),
              INDEX `dashboard` (`dashboard_id` ASC));    

              CREATE TABLE IF NOT EXISTS spending (
              `id` INT(11) NOT NULL AUTO_INCREMENT,
              `dashboard_id` INT(11) NOT NULL,
              `category_id` INT(11) NOT NULL,
              `storage_id` INT(11) NOT NULL,
              `amount` DECIMAL(15,2) NOT NULL,
              `currency_digital_code` INT(11) NOT NULL,
              `spending_name` VARCHAR(255) NOT NULL,
              `created` DATETIME NOT NULL DEFAULT now(),
              `is_deleted` TINYINT(1) NOT NULL default 0,
              PRIMARY KEY (`id`),
              INDEX `dashboard` (`dashboard_id`, `created` ASC),
              INDEX `dashboard_category` (`dashboard_id`,`category_id`));

              CREATE TABLE IF NOT EXISTS currency (
              `id` INT(11) NOT NULL AUTO_INCREMENT,
              `digital_code` INT(11) NOT NULL,
              `letter_code` VARCHAR(6) NOT NULL,
              PRIMARY KEY (`id`));
              
              CREATE TABLE IF NOT EXISTS category (
              `id` INT(11) NOT NULL AUTO_INCREMENT,
              `category_name` VARCHAR(255) NOT NULL,
              `description` TEXT NOT NULL,
              `created` DATETIME NOT NULL DEFAULT now(),
              `is_moderated` TINYINT(1) NOT NULL default 0,
              `is_deleted` TINYINT(1) NOT NULL default 0,
              PRIMARY KEY (`id`));     
              
              CREATE TABLE IF NOT EXISTS transfer (
              `id` INT(11) NOT NULL AUTO_INCREMENT,
              `dashboard_id` INT(11) NOT NULL,
              `from_storage_id` INT(11) NOT NULL,
              `to_storage_id` INT(11) NOT NULL,
              `amount` DECIMAL(15,2) NOT NULL,
              `currency_digital_code` INT(11) NOT NULL,
              `created` DATETIME NOT NULL DEFAULT now(),
              `is_deleted` TINYINT(1) NOT NULL default 0,
              PRIMARY KEY (`id`),
              INDEX `dashboard` (`dashboard_id` ASC));         
              
              CREATE TABLE IF NOT EXISTS dashboard (
              `id` INT(11) NOT NULL AUTO_INCREMENT,
              `dashboard_name` VARCHAR(255) NOT NULL,
              `user_id` INT(11) NOT NULL,
              `created` DATETIME NOT NULL DEFAULT now(),
              `is_deleted` TINYINT(1) NOT NULL default 0,
              PRIMARY KEY (`id`));
              
              CREATE TABLE IF NOT EXISTS user (
              `id` INT(11) NOT NULL AUTO_INCREMENT,
              `user_name` VARCHAR(255) NOT NULL,
              `email` VARCHAR(255) NOT NULL,
              `created` DATETIME NOT NULL DEFAULT now(),
              `is_deleted` TINYINT(1) NOT NULL default 0,
              PRIMARY KEY (`id`));
              
              CREATE TABLE IF NOT EXISTS link_user_dashboard (
              `id` INT(11) NOT NULL AUTO_INCREMENT,
              `dashboard_id` INT(11) NOT NULL,
              `user_id` INT(11) NOT NULL,
              `role` INT(11) NOT NULL DEFAULT 0,
              PRIMARY KEY (`id`));
              
              CREATE TABLE IF NOT EXISTS plan (
              `id` INT(11) NOT NULL AUTO_INCREMENT,
              `dashboard_id` INT(11) NOT NULL,
              `date_start` DATETIME NOT NULL,
              `date_end` DATETIME NOT NULL,
              `sum` DECIMAL(15,2) NOT NULL,
              `currency_digital_code` INT(11) NOT NULL,
              `created` DATETIME NOT NULL DEFAULT now(),
              `is_deleted` TINYINT(1) NOT NULL default 0,
              PRIMARY KEY (`id`),
              INDEX `dashboard` (`dashboard_id`,`date_start`,`date_end` ASC)); 
              
              CREATE TABLE IF NOT EXISTS tranche (
              `id` INT(11) NOT NULL AUTO_INCREMENT,
              `dashboard_id` INT(11) NOT NULL,
              `plan_id` INT(11) NOT NULL,
              `category_id` INT(11) NOT NULL,
              `amount` DECIMAL(15,2) NOT NULL,
              `currency_digital_code` INT(11) NOT NULL,       
              `created` DATETIME NOT NULL DEFAULT now(),
              `is_deleted` TINYINT(1) NOT NULL default 0,
              PRIMARY KEY (`id`),
              INDEX `dashboard` (`dashboard_id` ASC),
              INDEX `plan` (`plan_id`)); 
              
              CREATE TABLE IF NOT EXISTS link_category_dashboard (
              `id` INT(11) NOT NULL AUTO_INCREMENT,
              `dashboard_id` INT(11) NOT NULL,
              `category_id` INT(11) NOT NULL,
              PRIMARY KEY (`id`),
              INDEX `dashboard` (`dashboard_id`,`category_id` ASC));
        ');
    } catch (Exception $e) {
        print "Error!: " . $e->getMessage();
        die();
    }
}
elseif (in_array('down', $_SERVER['argv']))
{
    try {
        $db->query('
              DROP TABLE IF EXISTS storage;
              DROP TABLE IF EXISTS source;
              DROP TABLE IF EXISTS income;
              DROP TABLE IF EXISTS spending;
              DROP TABLE IF EXISTS transfer;
              DROP TABLE IF EXISTS currency;
              DROP TABLE IF EXISTS category;
              DROP TABLE IF EXISTS dashboard;
              DROP TABLE IF EXISTS user;
              DROP TABLE IF EXISTS plan;
              DROP TABLE IF EXISTS tranche;
              DROP TABLE IF EXISTS link_user_dashboard;
              DROP TABLE IF EXISTS link_category_dashboard;
              ');
    } catch (Exception $e) {
        print "Error!: " . $e->getMessage();
        die();
    }
}


