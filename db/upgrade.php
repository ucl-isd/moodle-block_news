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
 * This file keeps track of upgrades to the news block
 *
 * @package block_news
 * @copyright  2023 onwards University College London <m.opitz@ucl.ac.uk>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Upgrade code for the news block.
 *
 * @param int $oldversion
 */
function xmldb_block_news_upgrade($oldversion) {
    global $CFG;

    if ($oldversion < 2023111000) {
        // Use any news image URL and transform it into a Moodle file.
        for ($i = 1; $i < 4; $i++) {
            $image = get_config('block_news', 'image'.$i);
            if (isset($image) && filter_var($image, FILTER_VALIDATE_URL)) {
                $contextid = 1; // Forcing system context to match settings context.
                $component = 'block_news';
                $filearea = 'block_news';
                $itemid = $i;
                $filepath = '/';
                $filename = basename($image);
                $fileinfo = [
                    'contextid' => $contextid,
                    'component' => $component,
                    'filearea'  => $filearea,
                    'itemid'    => $itemid,
                    'filepath'  => $filepath,
                    'filename'  => $filename,
                ];

                $fs = get_file_storage();
                $file = $fs->get_file($contextid, $component, $filearea, $itemid, $filepath, $filename);
                if (!$file) {
                    // Only add the file if it does not already exist. Should not be the case, but check anyway.
                    $fs->create_file_from_url($fileinfo, $image);
                }
                set_config( 'image'.$i, $filepath.$filename, 'block_news');
            }
        }
        upgrade_block_savepoint(true, 2023111000, 'news');
    }
    return true;
}
