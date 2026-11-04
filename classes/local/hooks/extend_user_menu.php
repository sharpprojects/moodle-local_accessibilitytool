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

namespace local_accessibilitytool\local\hooks;

/**
 * Class hook_callbacks
 *
 * @package    local_accessibilitytool
 * @copyright  2024 Southampton Solent University {@link https://www.solent.ac.uk}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class extend_user_menu {
    /**
     * Extend user menu with accessibility tool.
     *
     * @param \core_user\hook\extend_user_menu $hook
     * @return void
     */
    public static function callback(\core_user\hook\extend_user_menu $hook): void {
        $enabled = get_config('local_accessibilitytool', 'enable');
        if (!$enabled) {
            return;
        }
        $title = get_string('accessibilitytool', 'local_accessibilitytool');
        $url = new \core\url('/local/accessibilitytool/manage.php');

        // Moodle 5.3 introduced typed user menu items and deprecated add_navitem().
        if (method_exists($hook, 'add_menu_item')) {
            $hook->add_menu_item(new \core_user\output\user_action_menu\link(
                $url,
                $title,
                null,
                new \core\output\pix_icon('e/accessibility_checker', ''),
            ));
            return;
        }

        // Keep compatibility with Moodle versions that still use the legacy API.
        $item = new \stdClass();
        $item->itemtype = 'link';
        $item->title = $title;
        $item->url = $url;
        $item->pix = 'e/accessibility_checker';
        $hook->add_navitem($item);
    }
}
