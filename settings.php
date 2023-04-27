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
 * News block settings.
 *
 * @package    block_news
 * @copyright  2023 Stuart Lamour
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    if ($ADMIN->fulltree) {
        $default = '';

        // TODO - Make this easier to use.
        // e.g. not having to move data down, just add a new item ?

        // Add 3 slots for news.
        for ($i = 1 ; $i < 4; $i++) {
            
            $setting = new admin_setting_heading('h'.$i,
                get_string('newslink', 'block_news'). ' '.$i,
                ''
            );
            $settings->add($setting);

            $setting = new admin_setting_configtext('block_news/title'.$i,
                get_string('title', 'block_news'),
                '', 
                $default, 
                PARAM_RAW
            );
            $settings->add($setting);

            $setting = new admin_setting_configtext('block_news/image'.$i, 
                get_string('image', 'block_news'), 
                get_string('image_help', 'block_news'),
                $default,
                PARAM_RAW
            );
            $settings->add($setting);

            $setting = new admin_setting_configtext('block_news/description'.$i,
                get_string('description', 'block_news'),
                get_string('description_help', 'block_news'),
                $default, 
                PARAM_RAW
            );
            $settings->add($setting);

            $setting = new admin_setting_configtext('block_news/link'.$i,
                get_string('link', 'block_news'),
                '', 
                $default, 
                PARAM_RAW
            );
            $settings->add($setting);
        }

    }
}
