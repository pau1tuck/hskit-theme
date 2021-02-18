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
 * @package    theme_hskit
 * @copyright  2021 Paul Tuck @pau1tuck
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($ADMIN->fulltree) {
    $settings = new theme_boost_admin_settingspage_tabs('themesettinghskit', get_string('configtitle', 'theme_hskit'));
    $page = new admin_settingpage('theme_hskit_general', get_string('generalsettings', 'theme_hskit'));

    // Preset.
    $name = 'theme_hskit/preset';
    $title = get_string('preset', 'theme_hskit');
    $description = get_string('preset_desc', 'theme_hskit');
    $default = 'default.scss';

    $context = context_system::instance();
    $fs = get_file_storage();
    $files = $fs->get_area_files($context->id, 'theme_hskit', 'preset', 0, 'itemid, filepath, filename', false);

    $choices = [];
    foreach ($files as $file) {
        $choices[$file->get_filename()] = $file->get_filename();
    }
    // These are the built in presets.
    $choices['default.scss'] = 'default.scss';
    $choices['plain.scss'] = 'plain.scss';

    $setting = new admin_setting_configthemepreset($name, $title, $description, $default, $choices, 'hskit');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Preset files setting.
    $name = 'theme_hskit/presetfiles';
    $title = get_string('presetfiles', 'theme_hskit');
    $description = get_string('presetfiles_desc', 'theme_hskit');

    $setting = new admin_setting_configstoredfile(
        $name,
        $title,
        $description,
        'preset',
        0,
        array('maxfiles' => 20, 'accepted_types' => array('.scss'))
    );
    $page->add($setting);

    // Variable $body-color.
    // We use an empty default value because the default colour should come from the preset.
    $name = 'theme_hskit/brandcolor';
    $title = get_string('brandcolor', 'theme_hskit');
    $description = get_string('brandcolor_desc', 'theme_hskit');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, '');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Must add the page after definiting all the settings!
    $settings->add($page);

    // Advanced settings.
    $page = new admin_settingpage('theme_hskit_advanced', get_string('advancedsettings', 'theme_hskit'));

    // Raw SCSS to include before the content.
    $setting = new admin_setting_scsscode(
        'theme_hskit/scsspre',
        get_string('rawscsspre', 'theme_hskit'),
        get_string('rawscsspre_desc', 'theme_hskit'),
        '',
        PARAM_RAW
    );
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Raw SCSS to include after the content.
    $setting = new admin_setting_scsscode(
        'theme_hskit/scss',
        get_string('rawscss', 'theme_hskit'),
        get_string('rawscss_desc', 'theme_hskit'),
        '',
        PARAM_RAW
    );
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $settings->add($page);
}
