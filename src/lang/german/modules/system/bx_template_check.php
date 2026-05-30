<?php
/* -----------------------------------------------------------------------------------------
   $Id: lang/german/modules/system/bx_template_check.php 1000 2026-05-30 13:00:00Z benax $
    _                           
   | |__   ___ _ __   __ ___  __
   | '_ \ / _ \ '_ \ / _ \ \/ /
   | |_) |  __/ | | | (_| |>  < 
   |_.__/ \___|_| |_|\__,_/_/\_\
   xxxxxxxxxxxxxxxxxxxxxxxxxxxxx

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
   -----------------------------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

define('MODULE_BX_TEMPLATE_CHECK_TEXT_TITLE', 'BX Template Check');

$module_description = '
<details class="bxac-card">
  <summary class="bxac-summary" style="list-style: none;">
    <span class="bxac-arrow">▸</span>
    <span class="bxac-title">' . xtc_image(DIR_WS_ICONS.'heading/bx_template_check.png', 'BX Template Check', '', '', 'style="max-height: 32px; vertical-align: middle; margin-right: 8px;"') . 'BX Template Check</span>
</summary>
  <div class="bxac-body">
    <h3 style="margin-top: 0;">Template-Überprüfung leicht gemacht!</h3>';

// Physical file deletion is only offered after uninstallation.
if(!defined('MODULE_BX_TEMPLATE_CHECK_STATUS') && basename($_SERVER['PHP_SELF']) !== 'start.php') {
   $module_description .= '<p><a class="button btnbox but_red" style="text-align: center; color: #FFF;" onclick="return confirmLink(\'Alle Moduldateien löschen?\', \'\' ,this);" href="' . xtc_href_link(FILENAME_MODULE_EXPORT, 'set=system&module=bx_template_check&action=custom&delete=true') . '">Alle Moduldateien löschen</a></p>';
}
$module_description .= '</div></details>';
  
define('MODULE_BX_TEMPLATE_CHECK_DESC', $module_description);
define('MODULE_BX_TEMPLATE_CHECK_STATUS_TITLE' , 'Status');
define('MODULE_BX_TEMPLATE_CHECK_STATUS_DESC' , 'Modul aktivieren?');
define('MODULE_BX_TEMPLATE_CHECK_CONFIG_ID_TITLE' , 'Konfigurations-ID');
define('MODULE_BX_TEMPLATE_CHECK_CONFIG_ID_DESC' , 'Automatisch ermittelt.');
