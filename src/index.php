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
require('../../config.php');

$id = required_param('id', PARAM_INT); // course id

$course = $DB->get_record('course', array('id' => $id), '*', MUST_EXIST);

require_course_login($course, true);
$PAGE->set_pagelayout('incourse');

$strpage = get_string('modulename', 'recittab');
$strpages = get_string('modulenameplural', 'recittab');
$strsectionname = get_string('sectionname', 'format_' . $course->format);
$strname = get_string('name');
$strintro = get_string('moduleintro');
$strlastmodified = get_string('lastmodified');

$modinfo = get_fast_modinfo($course);

$PAGE->set_url('/mod/reicttab/index.php', array('id' => $course->id));
$PAGE->set_title($course->shortname . ': ' . $strpages);
$PAGE->set_heading($course->fullname);
$PAGE->navbar->add($strpages);
echo $OUTPUT->header();

//log the view information
$event = \mod_recittab\event\course_module_instance_list_viewed::create(array(
    'objectid' => $PAGE->cm->instance,
    'context' => $PAGE->context,
));
$event->add_record_snapshot('course', $PAGE->course);
$event->add_record_snapshot($PAGE->cm->modname, $recittab);
$event->trigger();



if (!$tabs = get_all_instances_in_course('recittab', $course))
{
    notice(get_string('thereareno', 'moodle', $strpages), "$CFG->wwwroot/course/view.php?id=$course->id");
    exit;
}

$usesections = course_format_uses_sections($course->format);

if ($usesections)
{
    $sections = $modinfo->get_section_info_all($course->id);
}

$table = new html_table();
$table->attributes['class'] = 'generaltable mod_index';

if ($usesections)
{
    $table->head = array($strsectionname, $strname, $strintro);
    $table->align = array('center', 'left', 'left');
}
else
{
    $table->head = array($strlastmodified, $strname, $strintro);
    $table->align = array('left', 'left', 'left');
}


$currentsection = '';
foreach ($recittabs as $recittab)
{
    $cm = $modinfo->cms[$recittab->coursemodule];
    if ($usesections)
    {
        $printsection = '';
        if ($recittab->section !== $currentsection)
        {
            if ($recittab->section)
            {
                $printsection = get_section_name($course, $sections[$recittab->section]);
            }
            if ($currentsection !== '')
            {
                $table->data[] = 'hr';
            }
            $currentsection = $reciittab->section;
        }
    }
    else
    {
        $printsection = '<span class="smallinfo">' . userdate($recittab->timemodified) . "</span>";
    }

    $class = $recittab->visible ? '' : 'class="dimmed"'; // hidden modules are dimmed

    $table->data[] = array(
        $printsection,
        "<a $class href=\"view.php?id=$cm->id\">" . format_string($recittab->name) . "</a>",
        format_module_intro('recittab', $recittab, $cm->id));
}

echo html_writer::table($table);

echo $OUTPUT->footer();
