<?php 
require_once 'dummycart.inc';
cancel_order();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN";
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title>Test Shopping Cart</title>
</head>
<body>
  <h1>Order Cancelled</h1>

  <p>Order <?php echo $_GET['orderno'] ?> has been marked as cancelled.</p>
</body>
</html>
