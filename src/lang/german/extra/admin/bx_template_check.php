<?php
/**
 * BX Template Check - German Admin Language Definitions
 *
 * File: lang/german/extra/admin/bx_template_check.php
 * Package: bx-codemaster/bx-template-check
 * Purpose: Provides German labels, messages and UI text constants for admin module.
 *
 * @author benax
 * @copyright 2009-2026 modified eCommerce Shopsoftware
 * @license GNU General Public License (GPL)
 * @link https://www.modified-shop.org
 */

defined('_VALID_XTC') or die('Direct Access to this location is not allowed.');

define('BX_TEMPLATE_CHECK_HEADING_TITLE', 'BX Template Check');
define('BX_TEMPLATE_CHECK_HEADING_SUBTITLE', 'Überprüfen Sie die Zuweisung der Templates in Ihrem Shop');
define('BX_TEMPLATE_CHECK_OVERVIEW_FOR', 'Kategorieübersicht mit Template-Prüfung für:');

define('BX_TEMPLATE_CHECK_INFOBOX_TITLE', 'BX Template Check');
define('BX_TEMPLATE_CHECK_INFOBOX_PURPOSE_TITLE', 'Zweck');
define('BX_TEMPLATE_CHECK_INFOBOX_PURPOSE_TEXT', 'Dieses Modul prüft pro Kategorie, ob <strong>product_template</strong> und <strong>options_template</strong> auf existierende Template-Dateien zeigen.');
define('BX_TEMPLATE_CHECK_INFOBOX_HOWTO_TITLE', 'So arbeiten Sie damit');
define('BX_TEMPLATE_CHECK_INFOBOX_STEP_1', 'Kategorie in der Baumansicht aufklappen.');
define('BX_TEMPLATE_CHECK_INFOBOX_STEP_2', 'Betroffene Produkte in der Fehlerliste prüfen.');
define('BX_TEMPLATE_CHECK_INFOBOX_STEP_3', 'Gewünschte Templates im Block <strong>Bulk Assign</strong> auswählen.');
define('BX_TEMPLATE_CHECK_INFOBOX_STEP_4', 'Optional Unterkategorien einbeziehen.');
define('BX_TEMPLATE_CHECK_INFOBOX_STEP_5', 'Mit <strong>Zuweisen</strong> bestätigen und Erfolgsmeldung abwarten.');
define('BX_TEMPLATE_CHECK_INFOBOX_NOTE_TITLE', 'Hinweis');
define('BX_TEMPLATE_CHECK_INFOBOX_NOTE_TEXT', 'Nach erfolgreicher Zuweisung wird die Seite neu geladen und die Statistik aktualisiert.');

define('BX_TEMPLATE_CHECK_ERR_INVALID_CATEGORY', 'Ungültige Kategorie-ID.');
define('BX_TEMPLATE_CHECK_ERR_TEMPLATE_NOT_DETERMINED', 'Aktuelles Template konnte nicht ermittelt werden.');
define('BX_TEMPLATE_CHECK_ERR_SELECT_TEMPLATE', 'Bitte mindestens ein Template auswählen.');
define('BX_TEMPLATE_CHECK_ERR_INVALID_PRODUCT_TEMPLATE', 'Ungültiges product_template. Datei nicht im aktiven Template gefunden.');
define('BX_TEMPLATE_CHECK_ERR_INVALID_OPTIONS_TEMPLATE', 'Ungültiges options_template. Datei nicht im aktiven Template gefunden.');
define('BX_TEMPLATE_CHECK_ERR_NO_PRODUCTS_FOUND', 'Keine Produkte in der ausgewählten Kategorie gefunden.');

define('BX_TEMPLATE_CHECK_ASSIGN_SUCCESS_SUFFIX', 'Produkt(e) aktualisiert.');

define('BX_TEMPLATE_CHECK_LABEL_FAULTY_OF_TOTAL', '%d von %d fehlerhaft');
define('BX_TEMPLATE_CHECK_LABEL_PRODUCTS_WITH_ERRORS', 'Produkte mit fehlerhaften Template-Zuweisungen:');
define('BX_TEMPLATE_CHECK_LABEL_PRODUCT', 'Produkt');
define('BX_TEMPLATE_CHECK_LABEL_OK', 'OK?');
define('BX_TEMPLATE_CHECK_LABEL_YES', 'Ja');
define('BX_TEMPLATE_CHECK_LABEL_NO', 'Nein');
define('BX_TEMPLATE_CHECK_LABEL_BULK_ASSIGN', 'Template-Massenzuweisung');
define('BX_TEMPLATE_CHECK_LABEL_SELECT', 'Bitte wählen');
define('BX_TEMPLATE_CHECK_LABEL_INCLUDE_SUBCATS', 'Unterkategorien einbeziehen (%d)');
define('BX_TEMPLATE_CHECK_BUTTON_ASSIGN', 'Templates zuweisen');

define('BX_TEMPLATE_CHECK_STATS_TITLE', 'Statistik:');
define('BX_TEMPLATE_CHECK_STATS_CHECKED', '%d Produkte geprüft');
define('BX_TEMPLATE_CHECK_STATS_FAULTY', '%d fehlerhafte Template-Zuweisungen');
define('BX_TEMPLATE_CHECK_HINT_CLICK_CATEGORY', 'Kategorien mit fehlerhaften Templates anklicken, um Produkte und Korrekturoptionen zu sehen.');
define('BX_TEMPLATE_CHECK_LABEL_CATEGORY_TREE', 'Kategoriestruktur');
define('BX_TEMPLATE_CHECK_HINT_ALL_VALID', 'Alle geprüften Produkte haben gültige Template-Dateien.');

define('BX_TEMPLATE_CHECK_JS_SELECT_TEMPLATE', 'Bitte mindestens ein Template auswählen.');
define('BX_TEMPLATE_CHECK_JS_PREVIEW_FAILED', 'Vorschau fehlgeschlagen: ');
define('BX_TEMPLATE_CHECK_JS_PRODUCTS_IN_CATEGORY', 'Produkte in der Kategorie');
define('BX_TEMPLATE_CHECK_JS_PRODUCTS_IN_SUBCATS', 'Produkte in Unterkategorien');
define('BX_TEMPLATE_CHECK_JS_PRODUCTS_TOTAL', 'Produkte gesamt');
define('BX_TEMPLATE_CHECK_JS_PROCEED', 'Fortfahren?');
define('BX_TEMPLATE_CHECK_JS_ERROR_PREFIX', 'Fehler: ');
define('BX_TEMPLATE_CHECK_JS_PROCESSING_ERROR', 'Fehler bei der Verarbeitung. Bitte erneut versuchen.');
