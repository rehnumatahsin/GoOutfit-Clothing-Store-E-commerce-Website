<div class="box"><!-- box Starts -->

<?php

$session_email = $_SESSION['customer_email'];

$select_customer = "select * from customers where customer_email='$session_email'";

$run_customer = mysqli_query($con,$select_customer);

$row_customer = mysqli_fetch_array($run_customer);

$customer_id = $row_customer['customer_id'];


?>

<h1 class="text-center">Payment with Stripe</h1>



<center>

  <form action="order.php?c_id=<?php echo $customer_id;?>" method="POST" target="_top" onsubmit= "return redirectMe();">
  <input type="hidden" name="cmd" value="_s-xclick">
  <input type="image" src="images/stripe.png" border="0" name="submit" alt="Make payment easily with Stripe!">
  <?php

  if(isset($_GET['c_id'])){
  
  $customer_id = $_GET['c_id'];
  
  }
  
  $ip_add = getRealUserIp();
  
  $status = "complete";
  
  $invoice_no = mt_rand();
  
  $select_cart = "select * from cart where ip_add='$ip_add'";
  
  $run_cart = mysqli_query($con,$select_cart);
  
  while($row_cart = mysqli_fetch_array($run_cart)){
  
  $pro_id = $row_cart['p_id'];
  
  $pro_size = $row_cart['size'];
  
  $pro_qty = $row_cart['qty'];
  
  
  $sub_total = $row_cart['p_price']*$pro_qty;
  
  
  $insert_customer_order = "insert into customer_orders (customer_id,due_amount,invoice_no,qty,size,order_date,order_status) values ('$customer_id','$sub_total','$invoice_no','$pro_qty','$pro_size',NOW(),'$status')";
  
  $run_customer_order = mysqli_query($con,$insert_customer_order);
  
  $insert_pending_order = "insert into pending_orders (customer_id,invoice_no,product_id,qty,size,order_status) values ('$customer_id','$invoice_no','$pro_id','$pro_qty','$pro_size','$status')";
  
  $run_pending_order = mysqli_query($con,$insert_pending_order);
  
  $delete_cart = "delete from cart where ip_add='$ip_add'";
  
  $run_delete = mysqli_query($con,$delete_cart);}
  ?>
  
  <script>
    function redirectMe() {
  window.location.replace("https://buy.stripe.com/test_14kcPi2bL5Z27xmdQR");
  return false;
}
  </script>
  </form>
<?php

$i = 0;

$ip_add = getRealUserIp();

$get_cart = "select * from cart where ip_add='$ip_add'";

$run_cart = mysqli_query($con,$get_cart);

while($row_cart = mysqli_fetch_array($run_cart)){

$pro_id = $row_cart['p_id'];

$pro_qty = $row_cart['qty'];

$pro_price = $row_cart['p_price'];

$get_products = "select * from products where product_id='$pro_id'";

$run_products = mysqli_query($con,$get_products);

$row_products = mysqli_fetch_array($run_products);

$product_title = $row_products['product_title'];

$i++;

?>

<input type="hidden" name="item_name_<?php echo $i; ?>" value="<?php echo $product_title; ?>" >

<input type="hidden" name="item_number_<?php echo $i; ?>" value="<?php echo $i; ?>" >

<input type="hidden" name="amount_<?php echo $i; ?>" value="<?php echo $pro_price; ?>" >

<input type="hidden" name="quantity_<?php echo $i; ?>" value="<?php echo $pro_qty; ?>" >


<?php } ?>



</form>

</center>

</div>
