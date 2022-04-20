<?php

/**
 * -------------------------------------------------------------------------
 * AdvancedPlanning plugin for GLPI
 * -------------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of AdvancedPlanning.
 *
 * AdvancedPlanning is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * AdvancedPlanning is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with AdvancedPlanning. If not, see <http://www.gnu.org/licenses/>.
 * -------------------------------------------------------------------------
 * @copyright Copyright (C) 2019-2022 by AdvancedPlanning plugin team.
 * @license   GPLv3 https://www.gnu.org/licenses/gpl-3.0.html
 * @link      https://github.com/pluginsGLPI/advancedplanning
 * -------------------------------------------------------------------------
 */

define('PLUGIN_ADVANCEDPLANNING_VERSION', '1.1.0');
define('PLUGIN_ADVANCEDPLANNING_GLPIMIN', '10.0.0');
define('PLUGIN_ADVANCEDPLANNING_GLPIMAX', '10.0.99');

/**
 * Init hooks of the plugin.
 * REQUIRED
 *
 * @return void
 */
function plugin_init_advancedplanning() {
   global $PLUGIN_HOOKS, $CFG_GLPI;

   $PLUGIN_HOOKS['csrf_compliant']['advancedplanning'] = true;

   // manage list of authorized url where to load js/css
   $calendar_urls = [
      "front/planning.php",
      "front/reservation.php",
   ];
   foreach ($CFG_GLPI['reservation_types'] as $reservation_type) {
      $calendar_urls[] = $reservation_type::getFormUrl(false);
   }

   $found_url = false;
   foreach ($calendar_urls as $url) {
      if (strpos($_SERVER['REQUEST_URI'] ?? '', $url) !== false) {
         $found_url = true;
         break;
      }
   }

   if ($found_url) {
      $sc_lib = "lib/fullcalendar-scheduler-4.4.0/packages-premium";

      $PLUGIN_HOOKS['add_javascript']['advancedplanning'] = [
         "$sc_lib/resource-common/main.js",
         "$sc_lib/timeline/main.js",
         "$sc_lib/resource-timeline/main.js"
      ];

      $PLUGIN_HOOKS['add_css']['advancedplanning'] = [
         "$sc_lib/timeline/main.css",
         "$sc_lib/resource-timeline/main.css"
      ];

      $PLUGIN_HOOKS['planning_scheduler_key']['advancedplanning'] = function() {
         return "GPL-My-Project-Is-Open-Source";
      };
   }
}


/**
 * Get the name and the version of the plugin
 * REQUIRED
 *
 * @return array
 */
function plugin_version_advancedplanning() {
   return [
      'name'           => 'advancedplanning',
      'version'        => PLUGIN_ADVANCEDPLANNING_VERSION,
      'author'         => '<a href="http://www.teclib.com">Teclib\'</a>',
      'license'        => 'GPLV3',
      'homepage'       => '',
      'requirements'   => [
         'glpi' => [
            'min' => PLUGIN_ADVANCEDPLANNING_GLPIMIN,
            'max' => PLUGIN_ADVANCEDPLANNING_GLPIMAX,
         ]
      ]
   ];
}
