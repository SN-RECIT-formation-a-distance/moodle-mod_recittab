<?php

/**
 * *************************************************************************
 * *                         OOHOO - Tab Display                          **
 * *************************************************************************
 * @package     mod                                                       **
 * @subpackage  tab                                                       **
 * @name        tab                                                       **
 * @copyright   oohoo.biz                                                 **
 * @link        http://oohoo.biz                                          **
 * @author      Patrick Thibaudeau                                        **
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later  **
 * *************************************************************************
 * ************************************************************************ */
$plugin->version = 2019062402;  // The current module version (Date: YYYYMMDDXX)
$plugin->requires = 2019052000;
$plugin->release = 'R12-V1.1.0'; 
$plugin->maturity = MATURITY_BETA; // MATURITY_ALPHA, MATURITY_BETA, MATURITY_RC or MATURITY_STABLE
$plugin->cron = 0;           // Period for cron to check this module (secs)
$plugin->component = 'mod_tab';
$plugin->dependencies = [                                                                                                           
    'theme_recit' => 2020103000];
        