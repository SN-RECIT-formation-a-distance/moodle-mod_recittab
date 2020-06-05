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
defined('MOODLE_INTERNAL') || die; 

/**
 * This function is run when the plugin have to be updated
 * @global stdClass $CFG
 * @global moodle_database $DB
 * @param int $oldversion The older version of the plugin installed on the moodle
 * @return boolean True if the update passed successfully
 */
function xmldb_recittab_upgrade($oldversion = 0)
{

    global $CFG, $THEME, $DB;

    $dbman = $DB->get_manager();

       

   
    if ($oldversion < 201906242)
    {
        //+ Moodle 3.8 Update
        // tab savepoint reached
        upgrade_mod_savepoint(true, 2019062401, 'recittab');
    }
}