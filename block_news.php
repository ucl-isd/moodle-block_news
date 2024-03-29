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
        $this->title = get_string('pluginname', 'block_news');
    }

    /**
     * Gets the block settings.
     *
     * @return void
     */
    public function specialization() {
        if (!empty($this->config->title)) {
            $this->title = format_string($this->config->title, true, ['context' => $this->context]);
        }
    }

    /**
     * Gets the block contents.
     *
     * @return stdClass - the block content.
     */
    public function get_content(): stdClass {
        global $OUTPUT;

        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass();
        $this->content->footer = '';

        $template = new stdClass();
        $template->news = $this->fetch_news();
        $itemcount = count($template->news);

        // Hide block when no content.
        if (!$itemcount) {
            return $this->content;
        }

        if ($itemcount > 1) {
            $template->nav = true;
        }

        // Render from template.
        $this->content->text = $OUTPUT->render_from_template('block_news/content', $template);

        return $this->content;
    }

    /**
     *  Get the news.
     *
     * @return array news items.
     */
    public function fetch_news(): array {
        // Template data for mustache.
        $template = new stdClass();

        // Get news items.
        $contextid = $this->context->id;
        $component = "block_news";
        $filearea = "block_news";

        for ($i = 1; $i < 4; $i++) {
            $news = new stdClass();
            $news->title = get_config('block_news', 'title'.$i);
            $news->description = get_config('block_news', 'description'.$i);
            $news->link = get_config('block_news', 'link'.$i);
            $news->image = get_config('block_news', 'image'.$i);
            $news->date = get_config('block_news', 'date'.$i);

            // Get the Moodle URL for the image file.
            if (!empty($news->image)) {
                $itemid = $i;
                $filename = basename($news->image);
                $filepath = substr($news->image, 0, -strlen($filename));
                $news->image = moodle_url::make_pluginfile_url($contextid, $component, $filearea, $itemid, $filepath, $filename);
            }

            // Check news is populated.
            if ($news->title && $news->link) {
                // Format the date for display.
                if ($news->date) {
                    $news->displaydate = date_format(date_create($news->date), "jS M Y");
                }
                // Make a temp key value array to sort.
                // NOTE - index added to make keys unique.
                $template->tempnews[$news->date.'-'.$i] = $news;

            }
        }

        // Return if no news.
        if (!isset($template->tempnews)) {
            return [];
        }

        // Sort news items by date for output.
        krsort($template->tempnews);

        // Add sorted array to template.
        foreach ($template->tempnews as $news) {
            $template->news[] = $news;
        }

        // Set first element as active for carousel version.
        $template->news[0]->active = true;

        return  $template->news;
    }

    /**
     * Defines in which pages this block can be added.
     *
     * @return array of the pages where the block can be added.
     */
    public function applicable_formats(): array {
        return [
            'admin' => false,
            'site-index' => true,
            'course-view' => false,
            'mod' => false,
            'my' => true,
        ];
    }

    /**
     * Defines if the block can be added multiple times.
     *
     * @return bool.
     */
    public function instance_allow_multiple(): bool {
        return false;
    }

    /**
     * Defines if the has config.
     *
     * @return bool.
     */
    public function has_config(): bool {
        return true;
    }
}
