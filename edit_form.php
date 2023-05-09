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
 * News block edit form.
 *
 * @package   block_news
 * @author    2023 Stuart Lamour
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_news_edit_form extends block_edit_form {

    /**
     * Edit form.
     *
     */
    protected function specific_definition($mform) {

        // Fieldset.
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));

        // Block title.
        $mform->addElement('text', 'config_title', get_string('blocktitle', 'block_news'));
        $mform->setType('config_title', PARAM_TEXT);

        // Link to config.
        $href = new moodle_url('/admin/settings.php?section=blocksettingnews');
        $text = get_string('configurenews', 'block_news');
        $mform->addElement('html', '<p><a href="'.$href.'" >'.$text.'</a></p>');

        // TODO - Carousel or grid layout option?
    }

}

