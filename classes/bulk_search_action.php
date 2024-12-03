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
 * Class search_action
 *
 * @package    qbank_bulksearch
 * @copyright  2024 2024 Marcus Green
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Class search_action
 *
 * @package    qbank_bulksearch
 * @copyright  2024 Marcus Green
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class bulk_search_action extends \core_question\local\bank\bulk_action_base {
    /**
     * Get the title for the bulk action.
     *
     * @return string The localized string for the bulk action title.
     */
    public function get_bulk_action_title(): string {
        return get_string('searchaction', 'qbank_bulksearch');
    }

    /**
     * Get the URL for the bulk action.
     *
     * @return \moodle_url The URL for the bulk action.
     */
    public function get_bulk_action_url(): \moodle_url {
        return new \moodle_url('/question/bank/bulksearch/search.php');
    }

    /**
     * Get the key for the bulk action.
     *
     * @return string The key for the bulk action.
     */
    public function get_key(): string {
        return 'search';
    }
    /**
     * Get the title for the bulk action.
     *
     * @return string The localized string for the bulk action title.
     */
    public function get_bulk_search_action_title(): string {
        return get_string('searchaction', 'qbank_search');
    }
    public function get_bulk_action_capabilities(): ?array {
        return [
            'moodle/question:moveall',
            'moodle/question:add',
        ];
    }

}
