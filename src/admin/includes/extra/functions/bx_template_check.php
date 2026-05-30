<?php
/* ----------------------------------------------------------------------------------------------
   $Id: admin/includes/extra/functions/bx_template_check.php 1000 2026-05-30 13:00:00Z benax $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
   ----------------------------------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------------------------------*/

   if (!function_exists('bx_template_check_send_json_response')) {
   function bx_template_check_send_json_response(array $payload)
   {
      while (ob_get_level() > 0) {
         ob_end_clean();
      }
      header('Content-Type: application/json; charset=UTF-8');
      echo json_encode($payload);
      exit;
   }
   }

   if (!function_exists('bx_template_check_get_all_subcategory_ids')) {
   function bx_template_check_get_all_subcategory_ids($parent_id)
   {
      $result = array();
      $query = xtc_db_query("SELECT categories_id FROM " . TABLE_CATEGORIES . " WHERE parent_id = '" . (int)$parent_id . "'");
      while ($row = xtc_db_fetch_array($query)) {
         $cat_id = (int)$row['categories_id'];
         $result[] = $cat_id;
         $result = array_merge($result, bx_template_check_get_all_subcategory_ids($cat_id));
      }
      return $result;
   }
   }
