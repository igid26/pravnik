<?php get_header(); ?>
<section class="service-single-section section-padding maly-padding">
    <div class="container">
    
   <?php if (is_user_logged_in()) { ?>
            <?php do_action('uzivatelske-menu'); ?> 
    
    
    <div class="w-100 d-block float-left">

<div class="box-formular w-100 d-inline-block float-right mb-2">
<a class="tlacidlo zlate float-right d-inline-block mt-3 farba-biela ml-1" href="<?php echo home_url(); ?>/pridat-klienta/"><i class="moje-ikony fi flaticon-add mr-1"></i> Nastavenie fakturácie</a>   
<a class="tlacidlo modre float-right d-inline-block mt-3 farba-biela" href="<?php echo home_url(); ?>/pridat-klienta/"><i class="moje-ikony fi flaticon-import mr-1"></i> Pridať vyúčtovanie</a>    
</div>


    
    
<div class="tabulka-kontainer w-100 d-block p-relative">
<div class="tableFixHead w-100 d-block tien-importu border-radius-1 ">
<table class="tabulka-klienti tabulka border-radius-1 tien-importu border-radius-1">
<thead class="border-radius-1" >
<tr>
    <th width="5%"></th>
    <th width="15%">Názov klienta</th>
    <th width="25%">Názov úlohy</th>
    <th width="10%">Odpracované hodiny</th>
    <th width="10%">Suma vyúčtovania</th>
    <th width="10%">Akcia</th>
  </tr>
</thead>  
  
<tbody class=" sortable connectedSortable ui-sortable">    
        

            <?php
            $current_user = wp_get_current_user();

            $args_splocnosti_autor = array(
                'post_type' => 'spolocnosti',
                'author__in' => array($current_user->ID),
                'posts_per_page' => '-1',
            );

            $args_splocnosti_zamestnanci = array(
                'post_type' => 'spolocnosti',
                'posts_per_page' => '-1',
                'meta_query' => array(
                    array(
                        'key' => 'zamestnanci',
                        'value' => $current_user->ID,
                        'compare' => 'LIKE',
                    ),
                ),
            );

            $array_tvorba = array();

            $query_autor = new WP_Query($args_splocnosti_autor);
            $query_zamestnanci = new WP_Query($args_splocnosti_zamestnanci);

            $data_autor = $query_autor->posts;
            $data_zamestnanci = $query_zamestnanci->posts;

            $allData = array_merge($data_autor, $data_zamestnanci);
            $postIDs = array_unique(wp_list_pluck($allData, 'ID'));

            $args_splocnosti = array(
                'post_type' => 'spolocnosti',
                'posts_per_page' => '-1',
                'post__in' => $postIDs,
            );

            $projekty_query = new WP_Query($args_splocnosti);

            if ($projekty_query->have_posts()) {
                while ($projekty_query->have_posts()) {
                    $projekty_query->the_post();
                    global $post;

                    $id_clanku = $post->ID;
                    $id_spolocnosti = $post->ID;
                    ?>


                            <?php

                            $args_projekty_podla_autora = array(
                                'post_type' => 'projekty',
                                'posts_per_page' => '-1',
                                'author' => $current_user->ID,
                                'meta_query' => array(
                                    array(
                                        'key' => 'id_spolocnosti',
                                        'value' => $id_spolocnosti,
                                    ),
                                ),
                            );

                            $args_projekty_podla_spolocnosti = array(
                                'post_type' => 'projekty',
                                'posts_per_page' => '-1',
                                'meta_query' => array(
                                    'relation' => 'AND',
                                    array(
                                        'key' => 'id_spolocnosti',
                                        'value' => $id_spolocnosti,
                                    ),
                                    array(
                                        'key' => 'clenovia_id',
                                        'value' => '(^|,)' . $current_user->ID . '(,|$)',
                                        'compare' => 'REGEXP',
                                    ),
                                ),
                            );

                            $test_1 = new WP_Query($args_projekty_podla_autora);
                            $test_1_fin = wp_list_pluck($test_1->posts, 'ID');

                            $test_2 = new WP_Query($args_projekty_podla_spolocnosti);
                            $test_2_fin = wp_list_pluck($test_2->posts, 'ID');

                            $merged_query_args = array_merge($test_1_fin, $test_2_fin);
                            $query_args = array_unique(array_map('intval', $merged_query_args));
                            $array_tvorba[$id_spolocnosti] = $query_args;
                            ?>


                    <?php
                }
                wp_reset_postdata();
            } else {
                echo 'Zatiaľ neboli pridané žiadne firemné projekty.';
            }


            $args_final = array(
    'post_type'      => 'vyuctovanie',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'meta_query'     => array(
        array(
            'key'     => 'id_spolocnosti',
            'value'   => $array_tvorba,
            'compare' => '=',
        ),
    ),
    'meta_key'       => 'id_klienta',  // Pridaný meta_key pre zoradenie
    'orderby'        => 'meta_value_num',
);

$query_finalko = new WP_Query($args_final);

$current_id_klienta = null;
$current_suma       = 0; // Inicializujte sčítanú sumu pre každého klienta
$current_hodiny       = 0;

if ($query_finalko->have_posts()) {
    while ($query_finalko->have_posts()) {
        $query_finalko->the_post();

        $id_vyuctovania = get_the_ID();

        $id_ulohy = get_post_meta($id_vyuctovania, 'id_ulohy', true);

        $id_klienta = get_post_meta($id_vyuctovania, 'id_klienta', true);
        if (!empty($id_klienta)) {
            $nazov_klienta = get_the_title($id_klienta);
        } else {
            $nazov_klienta = '';
        }

        $hodiny = get_post_meta($id_vyuctovania, 'cas_spolu', true);
        $suma   = get_post_meta($id_vyuctovania, 'suma_spolu', true);

        // Ak sa zmenilo id_klienta, pridaj nový riadok s hlavičkou
        if ($id_klienta !== $current_id_klienta) {
            if ($current_id_klienta !== null) {
                // Ak sme už mali klienta, vypíšeme jeho sčítanú sumu v hlavičke
                echo '<td><strong>Spolu</strong></td><td><strong>' . get_the_title($current_id_klienta) . '</strong></td><td></td><td><strong>'.$current_hodiny .' .hod</strong></td><td><strong>' . number_format($current_suma, 2) . ' €</strong></td></tr>';
            }
            echo '<tr class="nova-hlavicka" style="background:#faf4ec;">';
            echo '<td><input type="checkbox" name="oznacit-vyuctovanie"></td>';
            echo '<td>' . $nazov_klienta . '</td>';
            echo '<td>' . get_the_title($id_ulohy) . '</td>';
            echo '<td>' . $hodiny . ' .hod</td>';
            echo '<td>' . number_format($suma, 2) . ' €</td>';
            echo '<td>' . $id_klienta . '</td>';
            echo '</tr>'; // Uzavrieť riadok s hlavičkou
            $current_id_klienta = $id_klienta;     // Aktualizuj hodnotu aktuálneho id_klienta
            $current_suma       = 0;              // Zresetuj sčítanú sumu pre nového klienta
            $current_hodiny       = 0; 
        } else {
            echo '<tr class="nova-hlavicka">'; // Začať nový riadok pre ostatné hodnoty
            echo '<td><input type="checkbox" name="oznacit-vyuctovanie"></td>';
            echo '<td>' . $nazov_klienta . '</td>';
            echo '<td>' . get_the_title($id_ulohy) . '</td>';
            echo '<td>' . $hodiny . ' .hod</td>';
            echo '<td>' . number_format($suma, 2) . ' €</td>';
            echo '<td>' . $id_klienta . '</td>';
            echo '</tr>'; // Uzavrieť riadok pre ostatné hodnoty
            $current_id_klienta = $id_klienta;     // Aktualizuj hodnotu aktuálneho id_klienta
        }

        // Ak nie je $suma prázdna, pridaj ju k sčítanej sume
        if (!empty($suma)) {
            $current_suma += $suma;
        }
        if (!empty($hodiny)) {
            $current_hodiny += $hodiny;
        }
    }
    // Po dokončení iterácie vypíšeme poslednú sčítanú sumu v hlavičke
    if ($current_id_klienta !== null) {
        echo '<td><strong>Spolu</strong></td><td><strong>' . $nazov_klienta . '</strong></td><td></td><td><strong>'.$current_hodiny .' .hod</strong></td><td><strong>' . number_format($current_suma, 2). ' €</strong></td></tr>';
    }
    wp_reset_postdata();
} else {
    echo 'Žiadne príspevky nenájdené.';
}
            ?>
            
        </tbody>       
</table>



</div>
</div>    
            

        <?php } else { ?>
            <div class="alert alert-primary text-center" role="alert">Táto sekcia je určená len pre registrovaných užívateľov. Prosím prihláste sa alebo zaregistrujte.
            </div>
            <?php echo do_shortcode('[pms-login]'); ?>
        <?php } ?>
    </div>
    
    
 </div>   
</section>
<?php get_footer(); ?>
