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

/**
 * Define all the backup steps that will be used by the backup_choice_activity_task
 */
class backup_tab_activity_structure_step extends backup_activity_structure_step
{

    protected function define_structure()
    {

        // To know if we are including userinfo
        $userinfo = $this->get_setting_value('userinfo');

        // Define each element separated
        $tab = new backup_nested_element('tab', array('id'), array('name', 'intro', 
                    'css', 'menucss', 'displaymenu', 'menuname', 'taborder', 'legacyfiles', 'legacyfileslast', 'timemodified', 'introformat'));

        $recittab_content s = new backup_nested_element('recittab_content s');

        $recittab_content  = new backup_nested_element('recittab_content ', array('id'), array('tabname',
                    'tabcontent', 'tabcontentorder', 'externalurl', 'contentformat', 'timemodified'));

        // Build the tree
        $tab->add_child($recittab_content s);
        $recittab_content s->add_child($recittab_content );
        // Define sources
        $tab->set_source_table('tab', array('id' => backup::VAR_ACTIVITYID));

        $recittab_content ->set_source_sql(
                'SELECT * FROM {recittab_content }
                        WHERE tabid = ?', array(backup::VAR_PARENTID));

        // Define id annotations
        //$recittab_content ->annotate_ids('tabid', 'tabid');
        // Define file annotations
        $tab->annotate_files('mod_recittab', 'intro', null);
        $recittab_content ->annotate_files('mod_recittab', 'content', 'id');

        // Return the root element (tab), wrapped into standard activity structure
        return $this->prepare_activity_structure($tab);
    }

}
