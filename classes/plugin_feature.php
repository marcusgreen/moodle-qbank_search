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
 * Plugin entrypoint for columns.
 *
 * @package    qbank_search
 * @copyright  2024 Marcus Green
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class plugin_feature extends \core_question\local\bank\plugin_features_base {

    /**
     * Get the bulk actions for this plugin.
     * from the bluk_tag_action.php file
     *
     * @return array An array of bulk actions.
     */
    public function get_bulk_actions(): array {
        return [
            new bulk_search_action(),
        ];
    }
}

