<?php 

require_once 'dummycart.inc';
place_order();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN";
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title>Test Shopping Cart</title>
</head>
<body>
  <h1>Order Placed</h1>

  <p>Order <?php echo $_GET['orderno'] ?> for $<?php echo $_GET['quantity'] * 5?> has been placed.

  <?php if($new_order) { ?>
    (This is a new order.)
  <?php } else { ?>
    (This order has already been sent to the affiliate system, so it will not be sent again.)
  <?php } ?>

  <p>You can now <a href="ship.php?orderno=<?php echo $_GET['orderno'] ?>">ship the order</a> or <a href="cancel.php?orderno=<?php echo $_GET['orderno'] ?>">cancel the order</a>.</p>

  </p>
</body>
</html>
