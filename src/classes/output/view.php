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

namespace mod_tab\output;

/**
 * 
 * @global \stdClass $USER
 * @param \renderer_base $output
 * @return array
 */
class view implements \renderable, \templatable {

    private $tab;
    private $courseId;
    private $courseContext;
    private $cm;

    /**
     * 
     * @global type $CFG
     * @global \stdClass $USER
     * @global \moodle_database $DB
     * @param array $tab
     * @param int $courseId
     * @param array $cm
     */
    public function __construct($tab, $courseId, $cm) {
        global $CFG, $USER, $DB;

        $this->tab = $tab;
        $this->courseId = $courseId;
        $this->courseContext = \context_course::instance($courseId);
        $this->cm = $cm;
    }

    /**
     * 
     * @global \stdClass $USER
     * @global \moodle_database $DB
     * @param \renderer_base $output
     * @return array
     */
    public function export_for_template(\renderer_base $output) {
        global $CFG, $USER, $DB, $COURSE;

        $tab = $this->tab;
        $cm = $this->cm;
        $intro = '';
        if (trim(strip_tags($tab->intro))) {
            $intro = format_module_intro('tab', $tab, $cm->id);
        }
        $classrecit  = $tab->classrecit;
        switch ($classrecit) {
            case 0:
                $classrecitstyle = "recit_tab_0 tabrecit_tab_0" ;
                break;
            case 1:
                $classrecitstyle = "recit_tab_1 tabrecit_tab_1" ;
                break;
            case 2:
                $classrecitstyle = "recit_tab_2 tabrecit_tab_2" ;
                break;
            case 3:
                $classrecitstyle = "recit_tab_3 tabrecit_tab_3" ;
                break;
            case 4:
                $classrecitstyle = "recit_tab_4 tabrecit_tab_4" ;
                break;
            case 5:
                $classrecitstyle = "recit_tab_5 tabrecit_tab_5" ;
                break;
            case 6:
                $classrecitstyle = "recit_tab_6 tabrecit_tab_6" ;
                break;
            case 7:
                $classrecitstyle = "recit_tab_7 tabrecit_tab_7" ;
                break;
            case 8:
                $classrecitstyle = "recit_tab_8 tabrecit_tab_8" ;
                break;
            case 9:
                $classrecitstyle = "recit_tab_9 tabrecit_tab_9" ;
                break;
            case 10:
                $classrecitstyle = "recit_tab_10 tabrecit_tab_10" ;
                break;
            case 11:
                $classrecitstyle = "recit_tab_11 tabrecit_tab_11" ;
                break;
            case 12:
                $classrecitstyle = "recit_tab_12 tabrecit_tab_12" ;
                break; 
            default: $classrecitstyle = "recit_tab_0 tabrecit_tab_0" ;
                break;
    }
        $data = [
            'wwwroot' => $CFG->wwwroot,
            'intro' => $intro,
            'classrecit'=> $classrecitstyle,
            'showMenu' => $tab->displaymenu,
            'menu' => $this->getTabMenuContent(),
            'tabs' => $this->getTabContent()

        ];

        return $data;
    }
    
   private function getTabMenuContent() {
        global $PAGE;
        //Détermine les activités du cours
        
        $course = $PAGE->course->id;
        $modinfo = get_fast_modinfo($course);
        $activities = [];
        $i=0;
            foreach ($modinfo->get_cms() as $cminfo) {
                $activities[$i]['modname']= $cminfo->modname;
                $activities[$i]['id']= $cminfo->id;
                $activities[$i]['in']= $cminfo->instance;
                $activities[$i]['name']= $cminfo->get_formatted_name();
                $i++;
                }
        //Activités à afficher dans le bloc        
        $tab = $this->tab;
        $listactivity  = $tab->listactivity;
            if (!isset ($listactivity)){
            return;
            }
        $tactivities =[];
        $tactivities = explode(",", $listactivity);
        //Compare la liste d,activité àux activités du cours
        $items = [];
            $j=0;
                foreach($tactivities as $tact) {
                    foreach($activities as $act){
                        if  (($tact==$act['name'])) {
                        $items[$j]['modname'] = $act['modname'];
                            $items[$j]['id'] = $act['id'];
                            $items[$j]['name'] = $act['name'];
                        } 
                    }
                $j++;
                }
            $menu = [
                'name' => $this->tab->menuname,
                'items' => $items
            ];
    return $menu;
    }
   
    private function getTabContent() {
        global $CFG, $DB;
        
        $context = \context_module::instance($this->cm->id);
        $editoroptions = array('subdirs' => 1, 'maxbytes' => $CFG->maxbytes, 'maxfiles' => -1, 'changeformat' => 1, 'context' => $context, 'noclean' => 1, 'trusttext' => true);
        $options = $DB->get_records('tab_content', array('tabid' => $this->tab->id), 'tabcontentorder');
        $contents = [];
        $i = 0;
        foreach ($options as $option) {

            //New conditions now exist. Must verify if embedding a pdf or url
            //Content must change accordingly
            //$pdffile[$key] = $options[$key]->pdffile;


            $externalurl = $option->externalurl;
            //Eventually give option for height within the form. Pass this by others, because it could be confusing.
            $iframeheight = '800px';

            if (!empty($externalurl)) {
                //todo check url
                if (!preg_match('{https?:\/\/}', $externalurl)) {
                    $externalurl = 'http://' . $externalurl;
                }
            } else {
                if (empty($option->format)) {
                    $option->format = 1;
                }
                $content = file_rewrite_pluginfile_urls($option->tabcontent, 'pluginfile.php', $context->id, 'mod_tab', 'content', $option->id);
                $content = format_text($content, $option->contentformat, $editoroptions, $context);
                //PDF
                $content2 = str_ireplace(array(' ', "\n", "\r", "\t", '&nbsp;'), array(), strip_tags($content, '<a>'));

                if (stripos($content2, '<a') === 0 && stripos($content2, '</a>') >= strlen($content2) - 4) {
                    $start = strpos($content2, '"') + 1;
                    $l = strpos($content2, '"', $start + 1) - $start;

                    $href = substr($content2, $start, $l);
                    if (stripos($href, '.pdf') !== false) {
                        $externalurl = $href;
                    }
                }
            }
            //Enter into proper div
            //Check for pdf
            if (!empty($externalurl) && preg_match('/\bpdf\b/i', $externalurl)) {
                $contents[$i]['content'] = tab_embed_general(process_urls($externalurl), '', get_string('embed_fail_msg', 'tab') . "<a href='$externalurl' target='_blank' >" . get_string('embed_fail_link_text', 'tab') . '</a>', 'application/pdf');
            } elseif (!empty($externalurl)) {
                $contents[$i]['content'] = tab_embed_general(process_urls($externalurl), '', get_string('embed_fail_msg', 'tab') . "<a href='$externalurl' target='_blank' >" . get_string('embed_fail_link_text', 'tab') . '</a>', 'text/html');
            } else {
                $contents[$i]['content'] = $content;
            }
            $contents[$i]['name'] = $option->tabname;
            $contents[$i]['id'] = $option->id;
            if ($i == 0) {
               $contents[$i]['active'] = true; 
            } else {
                $contents[$i]['active'] = false;
            }
            $i++;
        }
        
        return $contents;
    }

}
