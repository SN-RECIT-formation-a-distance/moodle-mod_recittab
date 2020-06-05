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
 * Define all the restore steps that will be used by the restore_choice_activity_task
 */

/**
 * Structure step to restore one choice activity
 */
class restore_tab_activity_structure_step extends restore_activity_structure_step
{

    protected function define_structure()
    {

        $paths = array();
        $userinfo = $this->get_setting_value('userinfo');

        $paths[] = new restore_path_element('tab', '/activity/tab');
        $paths[] = new restore_path_element('recittab_content ', '/activity/tab/recittab_content s/recittab_content ');


        // Return the paths wrapped into standard activity structure
        return $this->prepare_activity_structure($paths);
    }

    protected function process_tab($data)
    {
        global $DB;

        $data = (object) $data;
        $oldid = $data->id;
        $data->course = $this->get_courseid();
        $data->timemodified = $this->apply_date_offset($data->timemodified);

        // insert the tab record
        $newitemid = $DB->insert_record('tab', $data);
        // immediately after inserting "activity" record, call this
        $this->apply_activity_instance($newitemid);
    }

    protected function process_recittab_content ($data)
    {
        global $DB;

        $data = (object) $data;
        $oldid = $data->id;

        $data->tabid = $this->get_new_parentid('tab');
        $data->timemodified = $this->apply_date_offset($data->timemodified);

        $newitemid = $DB->insert_record('recittab_content ', $data);
        $this->set_mapping('recittab_content ', $oldid, $newitemid, true); //has related files
    }

    protected function after_execute()
    {
        global $DB;
        // Add tab related files where itemname = recittab_content  (taken from $this->set_mapping)
        $this->add_related_files('mod_recittab', 'intro', null);
        $this->add_related_files('mod_recittab', 'content', 'recittab_content ');
    }

}
