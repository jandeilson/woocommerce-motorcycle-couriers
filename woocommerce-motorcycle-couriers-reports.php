<?php

function motorcycle_couriers_reports() {
     
    ?>
    <div class="wrap">
        <h2>Reports</h2>
        
        <form id="pages-filter" action="" method="post">
            <select id="MotorcycleCourier" name="motorcycle_courier_name">
                <option value="">Select Motorcycle Courier you want reports</option>
                <?php

                $motorcycle_couriers = query_posts( array( 'post_type' => 'motorcycle_courier' ) );  
                $motorcycle_courier_name = $_POST['motorcycle_courier_name']; 
                $date = $_POST['date'];

                foreach ($motorcycle_couriers as $motorcycle_courier) {
                    $selected = '';

                    if ($motorcycle_courier->post_title == $motorcycle_courier_name) $selected= 'selected="selected"';
                    echo '<option value="'.esc_attr($motorcycle_courier->post_title).'" '.$selected.'>'.$motorcycle_courier->post_title.'</option>';
                }

                ?>
           </select>
       
           <input id="date" name="date" class="datepicker" type="text">
           <input value="Search" name="submitSearch" class="button-primary" type="submit">
           <button class="button">Print</button>
       </form>
    <?php

    if(isset($_POST['submitSearch'])) {
        
        $date_format = explode('-', $date);
        
        $filters = array(
            'post_status' => 'any',
            'post_type' => 'shop_order',
            'meta_key' => 'motorcycle_courier',
            'meta_value' => $motorcycle_courier_name,
            'posts_per_page' => 100,
            'paged' => 1,
            'orderby' => 'date',
            'order' => 'ASC',
            'date_query' => array(
                array(
                    'year'  => $date_format[0],
                    'month' => $date_format[1],
                    'day'   => $date_format[2]
                ),
            )
        );

        $query = new WP_Query($filters);
  ?><table class="widefat fixed" cellspacing="0" id="printTable">
        <thead>
            <tr>
                <th id="columnname" class="manage-column column-columnname" scope="col">Costumer</th>
                <th id="columnname" class="manage-column column-columnname" scope="col">Motorcycle Courier</th>
                <th id="columnname" class="manage-column column-columnname" scope="col">Shipping method</th> 
                <th id="columnname" class="manage-column column-columnname" scope="col">Order date</th>
                <th id="columnname" class="manage-column column-columnname" scope="col">Price</th>
            </tr>
        </thead>
        
        <tbody><?php

        if ( $query->have_posts() ) {
            $total_price_shipping = 0;

            while ($query->have_posts()) {

                $query->the_post();

                $order_id = get_the_ID();
                $order = new WC_Order($order_id);

                $first_name = $order->get_billing_first_name();
                $last_name = $order->get_billing_last_name();
                $location = $order->get_shipping_method();
                $currency = $order->get_currency();
                $price_shipping = $order->get_shipping_total();
                $motorcycle_courier = get_post_meta( $order->get_order_number(), 'motorcycle_courier', true );

                $total_price_shipping += $price_shipping;

                $date = $order->get_date_created();

            if (!empty($motorcycle_courier)) {
            
         ?><tr class="alternate">
                <td class="column-columnname"><?php echo esc_html($first_name); ?> <?php echo esc_html($last_name); ?></td>
                <td class="column-columnname"><?php echo esc_html($motorcycle_courier); ?></td>
                <td class="column-columnname"><?php echo esc_html($location); ?></td>
                <td class="column-columnname"><?php echo date("d/m/Y", strtotime($date)); ?></td>
                <td class="column-columnname">$ <?php echo esc_html($price_shipping); ?></td>
            </tr><?php }
            }

            wp_reset_postdata();
        } ?><tfoot>
            <tr class="alternate">
                <th id="columnname" class="manage-column column-columnname" scope="col"></th>
                <th id="columnname" class="manage-column column-columnname" scope="col"></th>
                <th id="columnname" class="manage-column column-columnname" scope="col"></th>
                <th id="columnname" class="manage-column column-columnname" scope="col"></th>
                <?php
                if ($total_price_shipping) {
                    echo '<th id="columnname" class="manage-column column-columnname" scope="col">Total: $ '.$total_price_shipping.'</th>';
                } else {
                    echo '<th id="columnname" class="manage-column column-columnname" scope="col"></th>';
                }
                ?>
            </tr>
            </tfoot>
        </tbody>
    </table>
<?php
    }

    // scripts
    wp_enqueue_script( 'jquery-ui-datepicker' );
    wp_register_style( 'jquery-ui', 'http://code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css' );
    wp_enqueue_style( 'jquery-ui' );
    wp_enqueue_script( 'scripts', plugins_url( 'assets/js/scripts.js', __FILE__ ), array('jquery') );
    
?>
    </div>
<?php
}