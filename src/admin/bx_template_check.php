<?php
/**
 * BX Template Check - Admin Overview and Bulk Assignment
 *
 * File: admin/bx_template_check.php
 * Package: bx-codemaster/bx-template-check
 * Purpose: Validate product template assignments per category and apply bulk fixes.
 *
 * @author benax
 * @copyright 2009-2026 modified eCommerce Shopsoftware
 * @license GNU General Public License (GPL)
 * @link https://www.modified-shop.org
 */

require ('includes/application_top.php');

$current_template = defined('CURRENT_TEMPLATE') ? CURRENT_TEMPLATE : '';

if (isset($_POST['fieldID'])) {
  $fieldID = xtc_db_prepare_input($_POST['fieldID']);

  if (strpos($fieldID, 'PREVIEW_TEMPLATES_') === 0) {
    $category_id = isset($_POST['categoryId']) ? (int)$_POST['categoryId'] : 0;
    $include_subcategories = isset($_POST['includeSubcategories']) && $_POST['includeSubcategories'] === '1';

    if ($category_id <= 0) {
      bx_template_check_send_json_response(array('error' => BX_TEMPLATE_CHECK_ERR_INVALID_CATEGORY));
    }

    $category_ids = array($category_id);
    $subcategory_ids = array();
    if ($include_subcategories) {
      $subcategory_ids = bx_template_check_get_all_subcategory_ids($category_id);
      $category_ids = array_merge($category_ids, $subcategory_ids);
    }

    $current_count = 0;
    $current_query = xtc_db_query("SELECT COUNT(DISTINCT products_id) AS cnt FROM " . TABLE_PRODUCTS_TO_CATEGORIES . " WHERE categories_id = '" . (int)$category_id . "'");
    if ($current_row = xtc_db_fetch_array($current_query)) {
      $current_count = (int)$current_row['cnt'];
    }

    $subcategory_count = 0;
    if (!empty($subcategory_ids)) {
      $ids = implode(',', array_map('intval', $subcategory_ids));
      $sub_query = xtc_db_query("SELECT COUNT(DISTINCT products_id) AS cnt FROM " . TABLE_PRODUCTS_TO_CATEGORIES . " WHERE categories_id IN (" . $ids . ")");
      if ($sub_row = xtc_db_fetch_array($sub_query)) {
        $subcategory_count = (int)$sub_row['cnt'];
      }
    }

    bx_template_check_send_json_response(array(
      'success' => true,
      'current_count' => $current_count,
      'subcategory_count' => $subcategory_count,
      'total_count' => $current_count + $subcategory_count,
      'category_count' => count($subcategory_ids),
    ));
  }

  if (strpos($fieldID, 'ASSIGN_TEMPLATES_') === 0) {
    $category_id = isset($_POST['categoryId']) ? (int)$_POST['categoryId'] : 0;
    $product_template = isset($_POST['productTemplate']) ? trim(xtc_db_prepare_input($_POST['productTemplate'])) : '';
    $options_template = isset($_POST['optionsTemplate']) ? trim(xtc_db_prepare_input($_POST['optionsTemplate'])) : '';
    $include_subcategories = isset($_POST['includeSubcategories']) && $_POST['includeSubcategories'] === '1';
    $template_name_pattern = '/^[A-Za-z0-9._-]+$/';

    $valid_product_templates_cache = array();
    $valid_options_templates_cache = array();

    if ($current_template === '') {
      bx_template_check_send_json_response(array('error' => BX_TEMPLATE_CHECK_ERR_TEMPLATE_NOT_DETERMINED));
    }

    $product_info_dir = DIR_FS_CATALOG . 'templates/' . $current_template . '/module/product_info/';
    if (is_dir($product_info_dir)) {
      foreach (scandir($product_info_dir) as $file) {
        if ($file !== '.' && $file !== '..' && substr($file, -5) === '.html') {
          $valid_product_templates_cache[$file] = true;
        }
      }
    }

    $product_options_dir = DIR_FS_CATALOG . 'templates/' . $current_template . '/module/product_options/';
    if (is_dir($product_options_dir)) {
      foreach (scandir($product_options_dir) as $file) {
        if ($file !== '.' && $file !== '..' && substr($file, -5) === '.html') {
          $valid_options_templates_cache[$file] = true;
        }
      }
    }

    if ($category_id <= 0) {
      bx_template_check_send_json_response(array('error' => BX_TEMPLATE_CHECK_ERR_INVALID_CATEGORY));
    }

    if ($product_template === '' && $options_template === '') {
      bx_template_check_send_json_response(array('error' => BX_TEMPLATE_CHECK_ERR_SELECT_TEMPLATE));
    }

    if ($product_template !== '') {
      if (!preg_match($template_name_pattern, $product_template) || !isset($valid_product_templates_cache[$product_template])) {
        bx_template_check_send_json_response(array('error' => BX_TEMPLATE_CHECK_ERR_INVALID_PRODUCT_TEMPLATE));
      }
    }

    if ($options_template !== '') {
      if (!preg_match($template_name_pattern, $options_template) || !isset($valid_options_templates_cache[$options_template])) {
        bx_template_check_send_json_response(array('error' => BX_TEMPLATE_CHECK_ERR_INVALID_OPTIONS_TEMPLATE));
      }
    }

    $category_ids = array($category_id);
    if ($include_subcategories) {
      $category_ids = array_merge($category_ids, bx_template_check_get_all_subcategory_ids($category_id));
    }

    $ids = implode(',', array_map('intval', $category_ids));
    $products_query = xtc_db_query("SELECT DISTINCT products_id FROM " . TABLE_PRODUCTS_TO_CATEGORIES . " WHERE categories_id IN (" . $ids . ")");

    if (xtc_db_num_rows($products_query) < 1) {
      bx_template_check_send_json_response(array('error' => BX_TEMPLATE_CHECK_ERR_NO_PRODUCTS_FOUND));
    }

    $update_parts = array();
    if ($product_template !== '') {
      $update_parts[] = "product_template = '" . xtc_db_input($product_template) . "'";
    }
    if ($options_template !== '') {
      $update_parts[] = "options_template = '" . xtc_db_input($options_template) . "'";
    }

    $updated_count = 0;
    $update_sql = implode(', ', $update_parts);
    while ($row = xtc_db_fetch_array($products_query)) {
      $product_id = (int)$row['products_id'];
      xtc_db_query("UPDATE " . TABLE_PRODUCTS . " SET " . $update_sql . " WHERE products_id = '" . $product_id . "'");
      $updated_count++;
    }

    bx_template_check_send_json_response(array(
      'success' => true,
      'updated_count' => $updated_count,
      'category_count' => count($category_ids),
      'message' => $updated_count . ' ' . BX_TEMPLATE_CHECK_ASSIGN_SUCCESS_SUFFIX,
    ));
  }
}



require_once (DIR_WS_INCLUDES.'head.php');

$messageStack->output();
?>
</head>
<!-- header //-->
<?php require(DIR_WS_INCLUDES.'header.php'); ?>

<!-- header_eof //-->
<!-- body //-->
<table class="tableBody">
  <tr>
    <?php //left_navigation
    if (USE_ADMIN_TOP_MENU == 'false') {
      echo '<td class="columnLeft2">'.PHP_EOL;
      echo '<!-- left_navigation //-->'.PHP_EOL;
      require_once(DIR_WS_INCLUDES.'column_left.php');
      echo '<!-- left_navigation eof //-->'.PHP_EOL;
      echo '</td>'.PHP_EOL;
    }
    ?>
    <!-- body_text //-->
    <td class="boxCenter">
      <div class="pageHeadingImage">
        <?php echo xtc_image(DIR_WS_ICONS.'heading/bx_template_check.png', 'BX Template Check', '', '', 'style="max-height: 40px;"'); ?>
      </div>
      <div class="pageHeading flt-l">
        <?php echo BX_TEMPLATE_CHECK_HEADING_TITLE; ?>
        <div class="main pdg2">
          <?php echo BX_TEMPLATE_CHECK_HEADING_SUBTITLE; ?>
        </div>
      </div>
      <div class="clear"></div>

      <table class="tableCenter" style="margin-top: 5px;">
        <tr>
          <td class="boxCenterLeft">
            <div id="headboard">
              <div class="main"><strong><?php echo BX_TEMPLATE_CHECK_OVERVIEW_FOR; ?></strong></div>
              <div class="main" style="font-size: 1.2rem; font-weight: 900;"><?php echo $current_template; ?></div>
            </div>
            
            <div class="clear div_box">
<?php
              $available_product_templates   = array();
              $available_options_templates   = array();
              $valid_product_templates_cache = array();
              $valid_options_templates_cache = array();

              $product_info_dir = DIR_FS_CATALOG . 'templates/' . $current_template . '/module/product_info/';
              if (is_dir($product_info_dir)) {
                foreach (scandir($product_info_dir) as $file) {
                  if ($file !== '.' && $file !== '..' && substr($file, -5) === '.html') {
                    $available_product_templates[] = $file;
                    $valid_product_templates_cache[$file] = true;
                  }
                }
                sort($available_product_templates);
              }

              $product_options_dir = DIR_FS_CATALOG . 'templates/' . $current_template . '/module/product_options/';
              if (is_dir($product_options_dir)) {
                foreach (scandir($product_options_dir) as $file) {
                  if ($file !== '.' && $file !== '..' && substr($file, -5) === '.html') {
                    $available_options_templates[] = $file;
                    $valid_options_templates_cache[$file] = true;
                  }
                }
                sort($available_options_templates);
              }

              $products_by_category = array();
              $all_products_query = xtc_db_query("SELECT ptc.categories_id,
                                                         p.products_id,
                                                         pd.products_name,
                                                         p.product_template,
                                                         p.options_template
                                                    FROM " . TABLE_PRODUCTS_TO_CATEGORIES . " ptc
                                                    LEFT JOIN " . TABLE_PRODUCTS . " p ON ptc.products_id = p.products_id
                                                    LEFT JOIN " . TABLE_PRODUCTS_DESCRIPTION . " pd ON p.products_id = pd.products_id
                                                          AND pd.language_id = '" . (int)$_SESSION['languages_id'] . "'
                                                   WHERE p.products_id IS NOT NULL
                                                   ORDER BY ptc.categories_id, pd.products_name");

              $total_checked = 0;
              while ($product = xtc_db_fetch_array($all_products_query)) {
                $cat_id = (int)$product['categories_id'];
                $product_tpl = trim($product['product_template']);
                $options_tpl = trim($product['options_template']);

                $product_tpl_ok = ($product_tpl !== '' && isset($valid_product_templates_cache[$product_tpl]));
                $options_tpl_ok = ($options_tpl !== '' && isset($valid_options_templates_cache[$options_tpl]));

                if (!isset($products_by_category[$cat_id])) {
                  $products_by_category[$cat_id] = array('total' => 0, 'problematic' => array());
                }

                $products_by_category[$cat_id]['total']++;
                $total_checked++;

                if (!$product_tpl_ok || !$options_tpl_ok) {
                  $products_by_category[$cat_id]['problematic'][] = array(
                    'products_id'         => (int)$product['products_id'],
                    'products_name'       => !empty($product['products_name']) ? $product['products_name'] : ('ID ' . $product['products_id']),
                    'product_template'    => $product_tpl,
                    'product_template_ok' => $product_tpl_ok,
                    'options_template'    => $options_tpl,
                    'options_template_ok' => $options_tpl_ok,
                  );
                }
              }

              $categories_query = xtc_db_query("SELECT c.categories_id, c.parent_id, cd.categories_name, c.sort_order
                                                  FROM " . TABLE_CATEGORIES . " c
                                                  LEFT JOIN " . TABLE_CATEGORIES_DESCRIPTION . " cd
                                                         ON c.categories_id = cd.categories_id
                                                        AND cd.language_id = '" . (int)$_SESSION['languages_id'] . "'
                                              ORDER BY c.sort_order, cd.categories_name");

              $all_categories  = array();
              $categories_tree = array();

              while ($cat = xtc_db_fetch_array($categories_query)) {
                $cat_id = (int)$cat['categories_id'];
                $parent_id = (int)$cat['parent_id'];
                $all_categories[$cat_id] = array(
                  'categories_id' => $cat_id,
                  'parent_id' => $parent_id,
                  'categories_name' => $cat['categories_name'],
                );
                if (!isset($categories_tree[$parent_id])) {
                  $categories_tree[$parent_id] = array();
                }
                $categories_tree[$parent_id][] = $cat_id;
              }

              $render_category_tree = function ($parent_id = 0, $level = 0) use (&$render_category_tree, $available_product_templates, $available_options_templates, $products_by_category, $all_categories, $categories_tree) {
                $html = '';
                if (!isset($categories_tree[$parent_id])) {
                  return $html;
                }

                foreach ($categories_tree[$parent_id] as $cat_id) {
                  $category = $all_categories[$cat_id];
                  $indent = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $level) . '&#9492;&#9472;&#9472; ';
                  $problematic_products = isset($products_by_category[$cat_id]) ? $products_by_category[$cat_id]['problematic'] : array();
                  $total_product_count = isset($products_by_category[$cat_id]) ? $products_by_category[$cat_id]['total'] : 0;
                  $subcategories_html = $render_category_tree($cat_id, $level + 1);
                  $has_problematic_subcategories = ($subcategories_html !== '');

                  if (count($problematic_products) > 0 || $has_problematic_subcategories) {
                    $has_subcategories = isset($categories_tree[$cat_id]) && count($categories_tree[$cat_id]) > 0;
                    $subcat_count = $has_subcategories ? count($categories_tree[$cat_id]) : 0;
                    $problematic_count = count($problematic_products);
                      $faulty_label = sprintf(BX_TEMPLATE_CHECK_LABEL_FAULTY_OF_TOTAL, $problematic_count, $total_product_count);

                    $html .= '<div style="padding: 8px; border-bottom: 1px solid #eee; cursor: pointer;" onclick="toggleCategory(' . $cat_id . ')">' . PHP_EOL
                      . '  ' . $indent . '<img id="cat_' . $cat_id . '_icon" src="images/icons/folder_closed.png" alt="" style="width: 24px; height: 24px; vertical-align: middle; margin-right: 6px;">'
                      . '<strong>' . htmlspecialchars($category['categories_name']) . '</strong> <span style="color: #cc0000; font-size: 11px; font-weight: bold;">(' . $faulty_label . ')</span>' . PHP_EOL
                          . '</div>' . PHP_EOL
                          . '<div id="cat_' . $cat_id . '_list" style="display: none; padding: 10px 0 10px 20px; background-color: #f9f9f9;">' . PHP_EOL;

                    if (count($problematic_products) > 0) {
                        $html .= '<p style="font-size: 11px; color: #cc0000; font-weight: bold; margin: 5px 0;">' . BX_TEMPLATE_CHECK_LABEL_PRODUCTS_WITH_ERRORS . '</p>' . PHP_EOL
                            . '<table style="width: 100%; font-size: 11px; margin: 5px 0; border-collapse: collapse;">' . PHP_EOL
                            . '  <thead><tr style="background-color: #f0f0f0; font-weight: bold;">'
                          . '    <th style="padding: 6px; text-align: left; border: 1px solid #ddd;">' . BX_TEMPLATE_CHECK_LABEL_PRODUCT . '</th>'
                            . '    <th style="padding: 6px; text-align: left; border: 1px solid #ddd;">product_template</th>'
                          . '    <th style="padding: 6px; text-align: center; border: 1px solid #ddd; width: 60px;">' . BX_TEMPLATE_CHECK_LABEL_OK . '</th>'
                            . '    <th style="padding: 6px; text-align: left; border: 1px solid #ddd;">options_template</th>'
                          . '    <th style="padding: 6px; text-align: center; border: 1px solid #ddd; width: 60px;">' . BX_TEMPLATE_CHECK_LABEL_OK . '</th>'
                            . '  </tr></thead><tbody>' . PHP_EOL;

                      foreach ($problematic_products as $product) {
                      $product_tpl_ok = $product['product_template_ok'] ? '<span style="color: green; font-weight: bold;">' . BX_TEMPLATE_CHECK_LABEL_YES . '</span>' : '<span style="color: red; font-weight: bold;">' . BX_TEMPLATE_CHECK_LABEL_NO . '</span>';
                      $options_tpl_ok = $product['options_template_ok'] ? '<span style="color: green; font-weight: bold;">' . BX_TEMPLATE_CHECK_LABEL_YES . '</span>' : '<span style="color: red; font-weight: bold;">' . BX_TEMPLATE_CHECK_LABEL_NO . '</span>';
                        $product_tpl_style = $product['product_template_ok'] ? '' : ' background-color: #ffe6e6;';
                        $options_tpl_style = $product['options_template_ok'] ? '' : ' background-color: #ffe6e6;';

                        $html .= '<tr style="border-bottom: 1px solid #ddd;">'
                              . '<td style="padding: 4px; border: 1px solid #ddd;"><a href="' . xtc_href_link('bx_template_check.php', 'pID=' . (int)$product['products_id']) . '">' . htmlspecialchars($product['products_name']) . '</a></td>'
                              . '<td style="padding: 4px; border: 1px solid #ddd;' . $product_tpl_style . '"><small>' . htmlspecialchars($product['product_template']) . '</small></td>'
                              . '<td style="padding: 4px; border: 1px solid #ddd; text-align: center;' . $product_tpl_style . '">' . $product_tpl_ok . '</td>'
                              . '<td style="padding: 4px; border: 1px solid #ddd;' . $options_tpl_style . '"><small>' . htmlspecialchars($product['options_template']) . '</small></td>'
                              . '<td style="padding: 4px; border: 1px solid #ddd; text-align: center;' . $options_tpl_style . '">' . $options_tpl_ok . '</td>'
                              . '</tr>' . PHP_EOL;
                      }

                      $html .= '</tbody></table>' . PHP_EOL;
                    }

                    $html .= '<div style="padding: 10px; margin: 8px 0; background-color: #e8f4f8; border-radius: 3px;">'
                        . '<div style="font-size: 11px; font-weight: bold; margin-bottom: 8px;">' . BX_TEMPLATE_CHECK_LABEL_BULK_ASSIGN . '</div>'
                          . '<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-bottom: 8px;">'
                        . '<div><label style="font-size: 10px; color: #333;">product_template:</label><select id="bulk_product_tpl_' . $cat_id . '" style="width: 100%; padding: 4px; font-size: 11px;"><option value="">' . BX_TEMPLATE_CHECK_LABEL_SELECT . '</option>';
                    foreach ($available_product_templates as $tpl) {
                      $html .= '<option value="' . htmlspecialchars($tpl) . '">' . htmlspecialchars($tpl) . '</option>';
                    }
                    $html .= '</select></div>'
                        . '<div><label style="font-size: 10px; color: #333;">options_template:</label><select id="bulk_options_tpl_' . $cat_id . '" style="width: 100%; padding: 4px; font-size: 11px;"><option value="">' . BX_TEMPLATE_CHECK_LABEL_SELECT . '</option>';
                    foreach ($available_options_templates as $tpl) {
                      $html .= '<option value="' . htmlspecialchars($tpl) . '">' . htmlspecialchars($tpl) . '</option>';
                    }
                    $html .= '</select></div></div>';

                    if ($has_subcategories) {
                        $include_subcats_label = sprintf(BX_TEMPLATE_CHECK_LABEL_INCLUDE_SUBCATS, $subcat_count);
                      $html .= '<div style="margin-bottom: 8px; padding: 8px; background-color: #f0f0f0; border-radius: 2px;">'
                            . '<label style="font-size: 11px; cursor: pointer; display: flex; align-items: center;">'
                          . '<input type="checkbox" id="include_subcats_' . $cat_id . '" style="margin-right: 8px; cursor: pointer;" checked="checked">' . $include_subcats_label
                            . '</label></div>';
                    }

                    $html .= '<div style="text-align: center;">'
                        . '<button type="button" onclick="assignTemplatesToCategory(' . $cat_id . ')" class="button" style="padding: 6px 20px; font-size: 11px;">' . BX_TEMPLATE_CHECK_BUTTON_ASSIGN . '</button>'
                          . '</div></div>';

                    $html .= '</div>' . PHP_EOL;
                    $html .= $subcategories_html;
                  }
                }

                return $html;
              };

              $total_problematic = 0;
              foreach ($products_by_category as $cat_data) {
                $total_problematic += count($cat_data['problematic']);
              }

              echo '<div class="main pdg2">';
              echo '<div style="padding: 10px; margin: 10px 0; background-color: #e8f5e9; border-left: 4px solid #4caf50; border-radius: 3px;">';
              echo '<strong>' . BX_TEMPLATE_CHECK_STATS_TITLE . '</strong> ' . sprintf(BX_TEMPLATE_CHECK_STATS_CHECKED, (int)$total_checked) . ' | <span style="color: #d32f2f; font-weight: bold;">' . sprintf(BX_TEMPLATE_CHECK_STATS_FAULTY, (int)$total_problematic) . '</span>';
              echo '</div>';

              if ($total_problematic > 0) {
                echo '<p style="font-size: 12px; color: #666; margin: 10px 0;">' . BX_TEMPLATE_CHECK_HINT_CLICK_CATEGORY . '</p>';
                echo '<div style="margin-top: 15px; border: 1px solid #ddd; padding: 15px; border-radius: 4px; background-color: #f9f9f9;">';
                echo '<div style="margin-bottom: 15px;">';
                echo '<label style="display: block; font-weight: bold; margin-bottom: 8px;">' . BX_TEMPLATE_CHECK_LABEL_CATEGORY_TREE . '</label>';
                echo '<div style="border: 1px solid #ccc; background-color: white; padding: 10px; border-radius: 3px; max-height: 600px; overflow-y: auto;">';
                echo $render_category_tree(0, 0);
                echo '</div></div></div>';
              } else {
                echo '<div style="padding: 10px; margin: 10px 0; background-color: #f1f8e9; border: 1px solid #c5e1a5; border-radius: 3px;">' . BX_TEMPLATE_CHECK_HINT_ALL_VALID . '</div>';
              }

              echo '</div>';
?>
              <script>
                function toggleCategory(catId) {
                  var list = document.getElementById('cat_' + catId + '_list');
                  var icon = document.getElementById('cat_' + catId + '_icon');
                  if (list) {
                    list.style.display = list.style.display === 'none' ? 'block' : 'none';
                    if (icon) {
                      icon.src = list.style.display === 'none' ? 'images/icons/folder_closed.png' : 'images/icons/folder_open.png';
                    }
                  }
                }

                function assignTemplatesToCategory(catId) {
                  var productTplEl = document.getElementById('bulk_product_tpl_' + catId);
                  var optionsTplEl = document.getElementById('bulk_options_tpl_' + catId);
                  var includeSubcatsEl = document.getElementById('include_subcats_' + catId);
                  var productTpl = productTplEl ? productTplEl.value : '';
                  var optionsTpl = optionsTplEl ? optionsTplEl.value : '';
                  var includeSubcats = includeSubcatsEl ? includeSubcatsEl.checked : false;

                  if (!productTpl && !optionsTpl) {
                    alert('<?php echo BX_TEMPLATE_CHECK_JS_SELECT_TEMPLATE; ?>');
                    return;
                  }

                  var endpoint = '<?php echo xtc_href_link('bx_template_check.php'); ?>';
                  var withCacheBuster = function(url) {
                    return url + (url.indexOf('?') === -1 ? '?' : '&') + 'v=' + Date.now();
                  };
                  var previewData = new FormData();
                  previewData.append('fieldID', 'PREVIEW_TEMPLATES_' + catId);
                  previewData.append('categoryId', catId);
                  previewData.append('includeSubcategories', includeSubcats ? '1' : '0');
                  previewData.append('productTemplate', productTpl);
                  previewData.append('optionsTemplate', optionsTpl);
<?php if (defined('CSRF_TOKEN_SYSTEM') && CSRF_TOKEN_SYSTEM == 'true') { ?>
                  previewData.append('<?php echo $_SESSION['CSRFName']; ?>', '<?php echo $_SESSION['CSRFToken']; ?>');
<?php } ?>

                  fetch(withCacheBuster(endpoint), { method: 'POST', body: previewData })
                    .then(function (response) { return response.text(); })
                    .then(function (raw) {
                      var preview = JSON.parse(raw);
                      if (preview.error) {
                        alert('<?php echo BX_TEMPLATE_CHECK_JS_PREVIEW_FAILED; ?>' + preview.error);
                        return;
                      }

                      var msg = preview.current_count + ' <?php echo BX_TEMPLATE_CHECK_JS_PRODUCTS_IN_CATEGORY; ?>';
                      if (preview.subcategory_count > 0) {
                        msg += '\n+ ' + preview.subcategory_count + ' <?php echo BX_TEMPLATE_CHECK_JS_PRODUCTS_IN_SUBCATS; ?>';
                        msg += '\n= ' + preview.total_count + ' <?php echo BX_TEMPLATE_CHECK_JS_PRODUCTS_TOTAL; ?>';
                      }
                      msg += '\n<?php echo BX_TEMPLATE_CHECK_JS_PROCEED; ?>';

                      if (!confirm(msg)) {
                        return;
                      }

                      var formData = new FormData();
                      formData.append('fieldID', 'ASSIGN_TEMPLATES_' + catId);
                      formData.append('categoryId', catId);
                      formData.append('productTemplate', productTpl);
                      formData.append('optionsTemplate', optionsTpl);
                      formData.append('includeSubcategories', includeSubcats ? '1' : '0');
<?php if (defined('CSRF_TOKEN_SYSTEM') && CSRF_TOKEN_SYSTEM == 'true') { ?>
                      formData.append('<?php echo $_SESSION['CSRFName']; ?>', '<?php echo $_SESSION['CSRFToken']; ?>');
<?php } ?>

                      return fetch(withCacheBuster(endpoint), { method: 'POST', body: formData });
                    })
                    .then(function (response) {
                      if (!response) {
                        return null;
                      }
                      return response.json();
                    })
                    .then(function (data) {
                      if (!data) {
                        return;
                      }
                      if (data.error) {
                        alert('<?php echo BX_TEMPLATE_CHECK_JS_ERROR_PREFIX; ?>' + data.error);
                        return;
                      }
                      alert(data.message);
                      window.setTimeout(function () { window.location.reload(); }, 400);
                    })
                    .catch(function () {
                      alert('<?php echo BX_TEMPLATE_CHECK_JS_PROCESSING_ERROR; ?>');
                    });
                }
              </script>
            </div>

          </td>
          <td class="boxRight">
<?php
  $heading  = array();
  $contents = array();

  $heading[]  = array('text' => '<strong>' . BX_TEMPLATE_CHECK_INFOBOX_TITLE . '</strong>');
  $contents[] = array('text' =>
    '<strong>' . BX_TEMPLATE_CHECK_INFOBOX_PURPOSE_TITLE . '</strong><br>'
    . BX_TEMPLATE_CHECK_INFOBOX_PURPOSE_TEXT . '<br><br>'
    . '<strong>' . BX_TEMPLATE_CHECK_INFOBOX_HOWTO_TITLE . '</strong><br>'
    . '1. ' . BX_TEMPLATE_CHECK_INFOBOX_STEP_1 . '<br>'
    . '2. ' . BX_TEMPLATE_CHECK_INFOBOX_STEP_2 . '<br>'
    . '3. ' . BX_TEMPLATE_CHECK_INFOBOX_STEP_3 . '<br>'
    . '4. ' . BX_TEMPLATE_CHECK_INFOBOX_STEP_4 . '<br>'
    . '5. ' . BX_TEMPLATE_CHECK_INFOBOX_STEP_5 . '<br><br>'
    . '<strong>' . BX_TEMPLATE_CHECK_INFOBOX_NOTE_TITLE . '</strong><br>'
    . BX_TEMPLATE_CHECK_INFOBOX_NOTE_TEXT
  );

  if ( (xtc_not_null($heading)) && (xtc_not_null($contents)) ) {
    $box = new box;
    echo $box->infoBox($heading, $contents);
  }
?>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<!-- body_eof //-->
<!-- footer //-->
<?php require(DIR_WS_INCLUDES.'footer.php'); ?>
<!-- footer_eof //-->

</body>
</html>
<?php require(DIR_WS_INCLUDES.'application_bottom.php'); ?>