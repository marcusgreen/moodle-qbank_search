<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * TODO describe file search
 *
 * @package    qbank_bulksearch
 * @copyright  2024 2024 Marcus Green
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
use qbank_bulksearch\output\form\bulk_search_form;
use qbank_bulksearch\helper;
require('../../../config.php');
require_once($CFG->dirroot . '/question/bank/bulksearch/classes/output/form/bulk_search_form.php');
require_once($CFG->dirroot . '/question/bank/bulksearch/classes/helper.php');
//require_once($CFG->dirroot . '/question/editlib.php');

$returnurl = optional_param('returnurl', 0, PARAM_LOCALURL);
$cmid = optional_param('cmid', 0, PARAM_INT);
$courseid = optional_param('courseid', 0, PARAM_INT);
$cancel = optional_param('cancel', null, PARAM_ALPHA);
$searchselected = optional_param('bulksearch', false, PARAM_BOOL);
$filter = optional_param('filter', null, PARAM_SEQUENCE);
$category = optional_param('category', null, PARAM_SEQUENCE);

if ($returnurl) {
    $returnurl = new moodle_url($returnurl);
}
if ($cancel) {
    redirect($returnurl);
}
if ($cmid) {
    list($module, $cm) = get_module_from_cmid($cmid);

    require_login($cm->course, false, $cm);
    $thiscontext = context_system::instance();

} else if ($courseid) {
    require_login($courseid, false);
    $thiscontext = context_system::instance();
} else {
    throw new moodle_exception('missingcourseorcmid', 'question');
}

$url = new moodle_url('/question/bank/bulksearch/search.php', []);


$contexts = new core_question\local\bank\question_edit_contexts($thiscontext);
$url = new moodle_url('/question/bank/bulksearch/search.php');
$title = get_string('pluginname', 'qbank_bulksearch');

$PAGE->set_url($url);
$PAGE->set_context(context_system::instance());
 // Check if plugin is enabled or not.
 \core_question\local\bank\helper::require_plugin_enabled('qbank_bulksearch');
if($searchselected) {
    $request = data_submitted();
    [$questionids, $questionlist] = \qbank_bulksearch\helper::process_question_ids($request);
    $returnurl = optional_param('returnurl', 0, PARAM_LOCALURL);

    if ($returnurl) {
        $returnurl = new moodle_url($returnurl);
    }
    if ($cancel) {
     redirect($returnurl);
    }

    // No questions were selected.
    if (!$questionids) {
    redirect($returnurl);
    }

    // No questions were selected.
    if (!$questionids) {
        redirect($returnurl);
    }

    $bulksearchparams = [
        'selectedquestions' => $questionlist,
        'confirm' => md5($questionlist),
        'sesskey' => sesskey(),
        'returnurl' => $returnurl,
        'cmid' => $cmid,
        'courseid' => $courseid,
        'matchids' => '',
    ];
}
$form = new bulk_search_form();

if (isset($bulksearchparams)) {
    $form->set_data($bulksearchparams);
}

if ($fromform = $form->get_data()) {
    if (isset($fromform->submitbutton)) {
       $matches =  \qbank_bulksearch\helper::bulk_search_questions($fromform);
        if(!empty($matches)) {
            $request = data_submitted();
            $bulksearchparams['searchterm'] = $request->searchterm;
            $bulksearchparams['selectedquestions'] = $request->selectedquestions;
            $matchids = implode(',', array_keys($matches));
            $bulksearchparams['courseid'] = $courseid;
            $bulksearchparams['matchids'] = $matchids;
            $bulksearchparams['returnurl'] = optional_param('returnurl', 0, PARAM_LOCALURL);
            $form->set_data($bulksearchparams);
       } else {
        $msg = 'No matches found';
        \core\notification::add($msg, \core\notification::SUCCESS);

       }
    }
}
$PAGE->set_heading($SITE->fullname);
echo $OUTPUT->header();
$form->display();
echo $OUTPUT->footer();