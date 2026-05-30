<?php 
  defined('_VALID_XTC') or die('Direct Access to this location is not allowed.');

  if (basename($_SERVER['PHP_SELF']) == 'bx_template_check.php') {
?>
<style>
  #headboard {
    display: flex; 
    flex-direction: row; 
    justify-content: flex-start;
    width: 100%;
    align-items: center; 
    background: #AF417E; 
    color: #ffffff; 
    border-radius: 4px; 
    margin-bottom: 10px; 
    padding: 4px 0 2px 0;
    line-height: 30px;
  }

  #headboard .main {
    margin: 5px 10px;
  }
  
  #headboard .SumoSelect {
    color: #000;
  }

  .div_box {
    padding: 10px;
    background-color: #f6f6f6;
    border: 1px solid #ddd;
    border-radius: 6px;
    max-width: 100%;
  }

.fixed_messageStack {
  position: fixed; 
  top: 88px; 
  left: 50%;
  transform: translateX(-50%);
  z-index: 1000;
  width: 80%;
  padding: 10px 0;
  text-align: center;    
  display: none;
}

.error_message,
.warning_message,
.info_message,
.success_message {
  margin-bottom: 2px;
  display: inline-block;
  width: 100%;
}

</style>
<?php  
  }
