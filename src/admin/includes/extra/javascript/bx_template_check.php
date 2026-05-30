<?php
 defined('_VALID_XTC') or die('Direct Access to this location is not allowed.');

 if (basename($_SERVER['PHP_SELF']) == 'bx_template_check.php') {
?>
<script>
  "use strict";

  $(document).ready(function() {
    $(".fixed_messageStack").slideDown("slow", function() {
      setTimeout(function() { $(".fixed_messageStack").slideUp("slow"); }, 2000); 
    });
  });
</script>
<?php
 }
?>