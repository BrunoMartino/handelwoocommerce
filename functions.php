<?php
function handel_add_woocommerce_support() {
  add_theme_support('woocommerce');
}
add_action('after_setup_theme', 'handel_add_woocommerce_support');

function handel_css() {
  wp_register_style('handel-style', get_template_directory_uri() . '/style.css', [], '1.0.0', false);
  wp_enqueue_style('handel-style');
}
add_action('wp_enqueue_scripts', 'handel_css');

function handel_custom_img() {
  add_image_size('slide', 1000, 800, ['center', 'top']);
  update_option('medium_crop', 1);
}
add_action('after_setup_theme', 'handel_custom_img');

function handel_loop_shop_per_page(){
  return 6;
}

add_filter('loop_shop_per_page', 'handel_loop_shop_per_page');

add_filter('body_class', 'remove_some_body_class');

function remove_some_body_class($classes) {
$woo_class = array_search('woocommerce' , $classes);
$woopage_class = array_search('woocommerce-page' , $classes);
$search = in_array('archive' , $classes) || in_array('product-template-default' , $classes);
if($woo_class && $woopage_class && $search) {
  unset($classes[$woo_class]);
  unset($classes[$woopage_class]);
}
return $classes;
}


function format_products($products, $img_size = 'medium') {
  $products_final = [];
  foreach($products as $product){
    $products_final[] = [
      'name' => $product->get_name(),
      'preco' =>$product->get_price_html(),
      'link' => $product->get_permalink(),
      'img' => wp_get_attachment_image_src($product->get_image_id(), $img_size)[0],
    ];
  }
  return $products_final;
}

function handel_product_list($products) { ?>
<ul class="products-list">
<?php foreach($products as $product) { ?>
  <li class="product-item">
    <a href="<?= $product['link']; ?>">
    <div class="product-info">
      <img src="<?= $product['img'] ?>" alt="<?= $product['name'] ?>">
      <h2><?= $product['name'] ?> - <span><?= $product['preco'] ?></span></h2>
    </div>
    <div class="product-overlay">
      <span class='btn-link'>Ver Mais</span>
    </div>
  </a>
</li>
  <?php } ?>
</ul>
<?php } ?>
<?php

add_filter('woocommerce_enable_order_notes_field', '__return_false');

include(get_template_directory() . '/inc/user-custom-menu.php');
include(get_template_directory() . '/inc/checkout-customizado.php');

//function handel_change_email_header() {
//echo '<h2 style="text-align: center;">Mensagem Header</h2>';

//}
//add_action('woocommerce_email_header', 'handel_change_email_header');

function handel_change_email_footer_text($text){
  echo 'Handel
  
  <ul style="padding:0; margin:0; list-style:none;">
  <li><a href="/">Facebook</a></li>
  <li><a href="/">Instagram</a></li>
  <li><a href="/">Youtube</a></li>
  </ul>';
}
add_filter('woocommerce_email_footer_text', 'handel_change_email_footer_text');

function handel_add_email_meta($order) {
  $presente = get_post_meta($order->get_id(), '_billing_presente', true);
  $mensagem = get_post_meta($order->get_id(), 'mensagem_personalizada', true);

  echo '<h2 style="margin:-20px 0 10px 0">Detalhes</h2>
  <p style="font-size: 16px; border: 1px solid #e5e5e5; padding:10px;"><strong>Mensagem: </strong>' . $mensagem . '</p>
  <p style="font-size: 16px; border: 1px solid #e5e5e5; padding:10px;"><strong>Presente: </strong>' . $presente . '</p>
  ';
}



add_action('woocommerce_email_order_meta','handel_add_email_meta')
?>