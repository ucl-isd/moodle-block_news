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
 * Block definition class for the block_news plugin.
 *
 * @package   block_news
 * @copyright 2023 Stuart Lamour
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class block_news extends block_base {

    /**
     * Initialises the block.
     *
     * @return void
     */
    public function init() {
        global $USER;
        $this->title = get_string('pluginname', 'block_news');
    }

    /**
     * Gets the block contents.
     *
     * @return string The block HTML.
     */
    public function get_content() {
        global $OUTPUT;

        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass();
        $this->content->footer = '';

        $template = new stdClass();
        $template->news = $this->fetch_news();
        
        $this->content->text = $OUTPUT->render_from_template('block_news/content', $template);

        return $this->content;
    }

     /**
     *  Get the news.
     * 
     * @return array news items.
     */
    public function fetch_news() : array {
        // Template data for mustache.
        $template = new stdClass();
        
        // Check 3 slots for news.
        for ($i = 1 ; $i < 4; $i++) {
            $news = new stdClass();
            $news->title = get_config('block_news', 'title'.$i);
            $news->description = get_config('block_news', 'description'.$i);
            $news->link = get_config('block_news', 'link'.$i);
            $news->image = get_config('block_news', 'image'.$i);

            // Check news is populated.
            if ($news->title) {
                $template->news[] = $news;
            }
        }
        if (!isset($template->news)) {
            return array();
        }

        return  $template->news;
        /*
        // Image.
            // SHAME - none standard course image.
            // Here for backwards compatability.
            $context = \context_course::instance($course->id);
            $fs = get_file_storage();
            $filerecord = $fs->get_area_files($context->id, 'block_news', 'block_news_image', '0', null, false);
            if ($filerecord) {
                $file = array_shift($filerecord);
                $url = moodle_url::make_pluginfile_url($file->get_contextid(),
                $file->get_component(), $file->get_filearea(), $file->get_itemid(), $file->get_filepath(), $file->get_filename());
                $image = $url->out();
                $data->courseimage = $image;
            }
        */
        /*
        foreach ($news as $n) {
            $template->headline = 'foo';
            $template->details = 'longer foo';
            $template->image = 'https://www.ucl.ac.uk/ucl-minds/sites/ucl_minds/files/lunchhourlectures-tile.png';
            $template->link = 'https://www.ucl.ac.uk/ucl-minds';
            $template->linktext = 'Read more about foo';
            $template->news[] = $news; 
        }
        */
        // $template->news[] = $template; 
        // return  $template->news; 
    }

    /**
     * Defines in which pages this block can be added.
     *
     * @return array of the pages where the block can be added.
     */
    public function applicable_formats() {
        return [
            'admin' => false,
            'site-index' => true,
            'course-view' => false,
            'mod' => false,
            'my' => true,
        ];
    }

    public function instance_allow_multiple() {
        return true;
    }

    function has_config() {
        return true;
    }
}