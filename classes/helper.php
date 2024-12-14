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

namespace qbank_bulksearch;

/**
 * Class helper
 *
 * @package    qbank_bulksearch
 * @copyright  2024 2024 Marcus Green
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class helper {
    public static function bulk_search_questions($fromform) : array {
        global $DB;
        if ($questionids = explode(',', $fromform->selectedquestions)) {
            list($usql, $params) = $DB->get_in_or_equal($questionids);
            $sql = "SELECT q.id, q.questiontext, c.contextid
                      FROM {question} q
                      JOIN {question_versions} qv ON qv.questionid = q.id
                      JOIN {question_bank_entries} qbe ON qbe.id = qv.questionbankentryid
                      JOIN {question_categories} c ON c.id = qbe.questioncategoryid
                     WHERE q.id
                     {$usql} and q.questiontext like ?";
            $params[] =  "%$fromform->searchterm%";
            $questions = $DB->get_records_sql($sql, $params);
            xdebug_break();
            return $questions;

        }
    }

    /**
     *Searchy Process the question came from the form post.
     *
     * @param \stdClass $request raw questions came as a part of post.
     * @return array question ids got from the post are processed and structured in an array.
     */
    public static function process_question_ids(\stdClass $request): array {
        $questionids = [];
        $questionlist = '';
        $requestfields = get_object_vars($request);
        foreach (array_keys($requestfields) as $key) {
            // Parse input for question ids.
            if (preg_match('!^q([0-9]+)$!', $key, $matches)) {
                $key = $matches[1];
                $questionids[] = $key;
            }
        }
        if (!empty($questionids)) {
            $questionlist = implode(',', $questionids);
        }
        return [$questionids, $questionlist];
    }

}
