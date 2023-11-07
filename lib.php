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
 * @package    block_news
 * @copyright  2023 onwards University College London <m.opitz@ucl.ac.uk>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Serve the files from the news file areas.
 *
 * @param stdClass $course the course object
 * @param stdClass $cm the course module object, not used here
 * @param context $context the context
 * @param string $filearea the name of the file area
 * @param array $args extra arguments (itemid, path)
 * @param bool $forcedownload whether or not force download
 * @param array $options additional options affecting the file serving
 * @return bool false if the file not found, just send the file otherwise and do not return anything
 */
function block_news_pluginfile(
    $course,
    $cm,
    $context,
    $filearea,
    $args,
    $forcedownload, $options = []) {

    // Check the contextlevel.
    if ($context->contextlevel != CONTEXT_BLOCK) {
        return false;
    }

    // Make sure the filearea is the correct one.
    if ($filearea !== 'block_news') {
        return false;
    }

    // The args is an array containing [itemid, path].
    // Fetch the itemid from the path.
    $itemid = array_shift($args);

    // Extract the filename / filepath from the $args array.
    $filename = array_pop($args); // The last item in the $args array.
    if (empty($args)) {
        // $args is empty => the path is '/'.
        $filepath = '/';
    } else {
        // $args contains the remaining elements of the filepath.
        $filepath = '/' . implode('/', $args) . '/';
    }

    // Retrieve the file from the Files API.
    $fs = get_file_storage();
    // Because the images are uploaded in settings they are stored with system context (id = 1).
    // To retrieve them from inside a block we have to force the system context here.
    $contextid = 1;
    $file = $fs->get_file($contextid, 'block_news', $filearea, $itemid, $filepath, $filename);
    if (!$file) {
        // The file does not exist.
        return false;
    }

    // We can now send the file back to the browser and cache it for a year if not changed.
    send_stored_file($file, 60 * 60 * 24 * 365, 0, $forcedownload, $options);
}
