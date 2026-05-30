<?php
/**
 * BX Template Check - English Admin Language Definitions
 *
 * File: lang/english/extra/admin/bx_template_check.php
 * Package: bx-codemaster/bx-template-check
 * Purpose: Provides English labels, messages and UI text constants for admin module.
 *
 * @author benax
 * @copyright 2009-2026 modified eCommerce Shopsoftware
 * @license GNU General Public License (GPL)
 * @link https://www.modified-shop.org
 */

defined('_VALID_XTC') or die('Direct Access to this location is not allowed.');

define('BX_TEMPLATE_CHECK_HEADING_TITLE', 'BX Template Check');
define('BX_TEMPLATE_CHECK_HEADING_SUBTITLE', 'Check template assignments in your shop');
define('BX_TEMPLATE_CHECK_OVERVIEW_FOR', 'Category overview with template check for:');

define('BX_TEMPLATE_CHECK_INFOBOX_TITLE', 'BX Template Check');
define('BX_TEMPLATE_CHECK_INFOBOX_PURPOSE_TITLE', 'Purpose');
define('BX_TEMPLATE_CHECK_INFOBOX_PURPOSE_TEXT', 'This module checks per category whether <strong>product_template</strong> and <strong>options_template</strong> point to existing template files.');
define('BX_TEMPLATE_CHECK_INFOBOX_HOWTO_TITLE', 'How to use it');
define('BX_TEMPLATE_CHECK_INFOBOX_STEP_1', 'Open a category in the tree view.');
define('BX_TEMPLATE_CHECK_INFOBOX_STEP_2', 'Review affected products in the error list.');
define('BX_TEMPLATE_CHECK_INFOBOX_STEP_3', 'Select the desired templates in the <strong>Bulk Assign</strong> section.');
define('BX_TEMPLATE_CHECK_INFOBOX_STEP_4', 'Optionally include subcategories.');
define('BX_TEMPLATE_CHECK_INFOBOX_STEP_5', 'Confirm with <strong>Assign</strong> and wait for the success message.');
define('BX_TEMPLATE_CHECK_INFOBOX_NOTE_TITLE', 'Note');
define('BX_TEMPLATE_CHECK_INFOBOX_NOTE_TEXT', 'After successful assignment, the page is reloaded and the statistics are updated.');

define('BX_TEMPLATE_CHECK_ERR_INVALID_CATEGORY', 'Invalid category ID.');
define('BX_TEMPLATE_CHECK_ERR_TEMPLATE_NOT_DETERMINED', 'Current template could not be determined.');
define('BX_TEMPLATE_CHECK_ERR_SELECT_TEMPLATE', 'Please select at least one template.');
define('BX_TEMPLATE_CHECK_ERR_INVALID_PRODUCT_TEMPLATE', 'Invalid product_template. File not found in active template.');
define('BX_TEMPLATE_CHECK_ERR_INVALID_OPTIONS_TEMPLATE', 'Invalid options_template. File not found in active template.');
define('BX_TEMPLATE_CHECK_ERR_NO_PRODUCTS_FOUND', 'No products found in the selected category.');

define('BX_TEMPLATE_CHECK_ASSIGN_SUCCESS_SUFFIX', 'product(s) updated.');

define('BX_TEMPLATE_CHECK_LABEL_FAULTY_OF_TOTAL', '%d of %d faulty');
define('BX_TEMPLATE_CHECK_LABEL_PRODUCTS_WITH_ERRORS', 'Products with faulty template assignments:');
define('BX_TEMPLATE_CHECK_LABEL_PRODUCT', 'Product');
define('BX_TEMPLATE_CHECK_LABEL_OK', 'OK?');
define('BX_TEMPLATE_CHECK_LABEL_YES', 'Yes');
define('BX_TEMPLATE_CHECK_LABEL_NO', 'No');
define('BX_TEMPLATE_CHECK_LABEL_BULK_ASSIGN', 'Bulk template assignment');
define('BX_TEMPLATE_CHECK_LABEL_SELECT', 'Please select');
define('BX_TEMPLATE_CHECK_LABEL_INCLUDE_SUBCATS', 'Include subcategories (%d)');
define('BX_TEMPLATE_CHECK_BUTTON_ASSIGN', 'Assign templates');

define('BX_TEMPLATE_CHECK_STATS_TITLE', 'Statistics:');
define('BX_TEMPLATE_CHECK_STATS_CHECKED', '%d products checked');
define('BX_TEMPLATE_CHECK_STATS_FAULTY', '%d faulty template assignments');
define('BX_TEMPLATE_CHECK_HINT_CLICK_CATEGORY', 'Click categories with faulty templates to see products and correction options.');
define('BX_TEMPLATE_CHECK_LABEL_CATEGORY_TREE', 'Category structure');
define('BX_TEMPLATE_CHECK_HINT_ALL_VALID', 'All checked products have valid template files.');

define('BX_TEMPLATE_CHECK_JS_SELECT_TEMPLATE', 'Please select at least one template.');
define('BX_TEMPLATE_CHECK_JS_PREVIEW_FAILED', 'Preview failed: ');
define('BX_TEMPLATE_CHECK_JS_PRODUCTS_IN_CATEGORY', 'products in category');
define('BX_TEMPLATE_CHECK_JS_PRODUCTS_IN_SUBCATS', 'products in subcategories');
define('BX_TEMPLATE_CHECK_JS_PRODUCTS_TOTAL', 'products total');
define('BX_TEMPLATE_CHECK_JS_PROCEED', 'Proceed?');
define('BX_TEMPLATE_CHECK_JS_ERROR_PREFIX', 'Error: ');
define('BX_TEMPLATE_CHECK_JS_PROCESSING_ERROR', 'Error during processing. Please try again.');

