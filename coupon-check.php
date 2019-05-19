// coupon valid shortcode
function check_coupon($atts) {
ob_start();

    ?> <div class="coupon-wrapper"><form class="form-inline" action="" method="post">
  
  <input type="text" id="couponcode" placeholder="Enter Coupon Code" name="couponcode" value="<?php echo isset($_REQUEST['couponcode']) ? $_REQUEST['couponcode'] : '';?>">

  <button type="submit">Check Coupon</button>
</form> 
<style>
.coupon-wrapper {
    max-width: 400px;
    margin: 15px auto;
}
/* Style the form - display items horizontally */
.form-inline { 
  text-align: center;
}

/* Add some margins for each label */
.form-inline label {
  margin: 5px 10px 5px 0;
}

/* Style the input fields */
.form-inline input {
  vertical-align: middle;
  padding: 10px;
  border: 1px solid #ddd;
  width:auto;
  margin: 5px 10px 5px 0;
  display:inline-block;
}

/* Style the submit button */
.form-inline button {
  padding:0px 20px;
    display:inline-block;
    height: 47px;
}
.invalid,.invalid strong {
    color: #ff0000!important;
}
.coupon-check-wrap {
    margin-top: 18px;
    background: #f9f3ab;
    padding: 12px 15px;
}

/* Add responsiveness - display the form controls vertically instead of horizontally on screens that are less than 800px wide */
@media (max-width: 800px) {
  .form-inline input {
    margin: 10px 0;
  }

  .form-inline {
    flex-direction: column;
    align-items: stretch;
  }
}
</style><?php
if(isset($_REQUEST['couponcode'])){
     echo '<div class="coupon-check-wrap">';
global $woocommerce;
$couponcode = $_REQUEST['couponcode'];
  global $woocommerce;
$coupon = new WC_Coupon($couponcode);
$id= $coupon->get_id();
if($id){
   
$coupon_post = get_post($id);
$coupon_data = array(
    'id' => $id,
    'code' => $coupon->get_code(),
    'type' => $coupon->get_discount_type(),
    'created_at' => $coupon_post->post_date_gmt,
    'updated_at' => $coupon_post->post_modified_gmt,
    'amount' => wc_format_decimal($coupon->get_amount(), 2),
    'individual_use' => ( 'yes' === $coupon->get_individual_use() ),
    'product_ids' => array_map('absint', (array) $coupon->get_product_ids()),
    'exclude_product_ids' => array_map('absint', (array) $coupon->get_excluded_product_ids()),
    'usage_limit' => (!empty($coupon->get_usage_limit()) ) ? $coupon->get_usage_limit() : null,
    'usage_count' => (int) $coupon->get_usage_count(),
    'expiry_date' => (!empty($coupon->get_date_expires()) ) ? date('Y-m-d', $coupon->get_date_expires()) : null,
    'enable_free_shipping' => $coupon->get_free_shipping(),
    'product_category_ids' => array_map('absint', (array) $coupon->get_product_categories()),
    'exclude_product_category_ids' => array_map('absint', (array) $coupon->get_excluded_product_categories()),
    'exclude_sale_items' => $coupon->get_exclude_sale_items(),
    'minimum_amount' => wc_format_decimal($coupon->get_minimum_amount(), 2),
    'maximum_amount' => wc_format_decimal($coupon->get_maximum_amount(), 2),
    'customer_emails' => $coupon->get_email_restrictions(),
    'description' => $coupon_post->post_excerpt,
);

$usage_left = $coupon_data['usage_limit'] - $coupon_data['usage_count'];

if ($usage_left > 0 || empty($coupon_data['usage_limit'])) {?>
    <p><strong>Valid Coupon</strong></p>
    <p><strong>Discount Amount : </strong><?php echo $coupon_data['amount'];?></p>
    <p><strong>Description : </strong><?php echo $coupon_data['description'];?></p>
<?php } 
else {
    echo ' <p class="invalid"><strong>This coupon has been redeemed.</strong></p>';
}
}else{
    echo ' <p class="invalid"><strong>This coupon has been redeemed.</strong></p>';
}
echo '</div></div>';
}

    return ob_get_clean();
}
add_shortcode('checkcoupon', 'check_coupon');