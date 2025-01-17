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

namespace qbank_bulksearch\output\form;
defined('MOODLE_INTERNAL') || die();
global $CFG;
require_once($CFG->dirroot . '/lib/formslib.php');

/**
 * Class bulk_search_form
 *
 * @package    qbank_bulksearch
 * @copyright  2024 2024 Marcus Green
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class bulk_search_form extends \moodleform {

    /**
     * @var  $questionids string
     *
     * */
    public string $questionids;

     /**
     * @var  $editurl string
     *
     * */
    public string $editurl;

    /**
     *
     * Definition of the form to manage bulk search.
     *
     * @return void
     */
    protected function definition() {
        $mform = $this->_form;

        // Add hidden form fields.
        $mform->addElement('hidden', 'selectedquestions');
        $mform->setType('selectedquestions', PARAM_TEXT);
        $mform->addElement('hidden', 'returnurl');
        $mform->setType('returnurl', PARAM_URL);
        $mform->addElement('hidden', 'cmid');
        $mform->setType('cmid', PARAM_INT);
        $mform->addElement('hidden', 'courseid');
        $mform->setType('courseid', PARAM_INT);
        $mform->addElement('text', 'searchterm', get_string('searchterm', 'qbank_bulksearch'));
        $mform->addElement('hidden','matchids','');
        $mform->setType('matchids', PARAM_TEXT);
        $mform->addElement('static', 'matchedquestiontext');
        $mform->addElement('static', 'editurl');

        $mform->setType('matchedquestiontext', PARAM_TEXT);
        $this->add_action_buttons(true, get_string('search'));
    }
    /**
     * Sets the data for the form.
     *
     * @param \stdClass $data The data to set, containing the selected  search and questions.
     *
     * @return void
     */
    public function set_data($data) {
        $mform = $this->_form;
        $data = (object) $data;
        $mform->getElement('selectedquestions')->setValue($data->selectedquestions);
        $mform->getElement('returnurl')->setValue($data->returnurl);

        $mform->getElement('cmid')->setValue($data->cmid);
        $mform->getElement('courseid')->setValue($data->courseid);
        $mform->getElement('matchids')->setValue($data->matchids);
        $this->questionids = $data->matchids;
        if($data->searchterm) {
            $templateoutput = $this->get_matching_questions($data->matchids, $data->searchterm);
            $mform->getElement('matchedquestiontext')->setValue($templateoutput);
            $mform->getElement('editurl')->setValue($data->editurl);
        }
    }
    public function get_matching_questions(string $matchids, string $searchterm) {
        global $DB, $OUTPUT;
        if ($matchids == '') {
            return '';
        }
        $ids = explode("'", $matchids);
        $sql = 'SELECT id, name, questiontext FROM {question} WHERE id IN (' . implode(',', $ids) . ')';
        $matchingquestions = $DB->get_records_sql($sql);
        xdebug_break();
        foreach ($matchingquestions as $question) {
            $pattern = '/(' . preg_quote($searchterm, '/') . ')/i';
            $replacement = '<span class="bg-warning font-weight-bold">$1</span>';
            $question->questiontext = preg_replace($pattern, $replacement, $question->questiontext);
            $question->name = preg_replace($pattern, $replacement, $question->name);
        }
        $data = ['questions' => array_values($matchingquestions)];

        $templateoutput =  $OUTPUT->render_from_template('qbank_bulksearch/questions', $data);
        return $templateoutput;

    }
}
