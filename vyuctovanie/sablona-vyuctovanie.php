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
    <th width="13%">Akcia</th>
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
$current_hodiny     = 0;

// Zobrazené řádky
$rows_to_display = array();

$rows_sucty = array();    // Pre súčty
$rows_nadradene = array(); // Pre nadradené riadky
$rows_nove = array(); 

if ($query_finalko->have_posts()) {
    while ($query_finalko->have_posts()) {
        $query_finalko->the_post();

        $id_vyuctovania = get_the_ID();
        $id_ulohy       = get_post_meta($id_vyuctovania, 'id_ulohy', true);
        $id_klienta     = get_post_meta($id_vyuctovania, 'id_klienta', true);

        if (!empty($id_klienta)) {
            $nazov_klienta = get_the_title($id_klienta);
        } else {
            $nazov_klienta = '';
        }

        $hodiny = get_post_meta($id_vyuctovania, 'cas_spolu', true);
        $suma   = get_post_meta($id_vyuctovania, 'suma_spolu', true);
        $ulica   = get_post_meta($id_klienta, 'ulica-klient', true);
        $cislo   = get_post_meta($id_klienta, 'cislo-klient', true);
        $psc   = get_post_meta($id_klienta, 'psc-klient', true);
        $mesto   = get_post_meta($id_klienta, 'mesto-klient', true);
        $ico   = get_post_meta($id_klienta, 'ico-klient', true);
        $dic   = get_post_meta($id_klienta, 'dic-klient', true);
        $ic_dph   = get_post_meta($id_klienta, 'ic-dph-klient', true);

        // Ak sa zmenilo id_klienta, pridaj nový riadok do poľa pre klienta
        if ($id_klienta !== $current_id_klienta) {
            if ($current_id_klienta !== null) {
                // Ak sme už mali klienta, pridáme jeho sčítanú sumu do poľa
                $rows_to_display[] = array(
                    'nazov_klienta' => get_the_title($current_id_klienta),
                    'id_ulohy'      => '',
                    'hodiny'        => $current_hodiny,
                    'suma'          => $current_suma,
                    'id_klienta'    => $current_id_klienta,
                    'id_vyuct'    => '',
                    'trieda'        => 'hlavicka',
                    'ico'        => $ico,
                    'dic'        => $dic,
                    'ic_dph'        => $ic_dph,
                    'ulica'        => $ulica,
                    'cislo'        => $cislo,
                    'psc'        => $psc,
                    'mesto'        => $mesto,
                );
            }

            $current_id_klienta = $id_klienta;
            $current_suma       = 0;
            $current_hodiny     = 0;
        }
        
        
        
        
        $polozky_tabulky = get_post_meta($id_vyuctovania, 'polozky_tabulky', true);
        if (!empty($polozky_tabulky)) {
            $polozky_tabulky = json_decode($polozky_tabulky, true);
            // Zobrazenie nových riadkov pre daný príspevok
            foreach ($polozky_tabulky['nazov_polozky'] as $key => $nazov_polozky) {
                $suma_polozky = $polozky_tabulky['suma_polozky'][$key];
                $cas_polozky  = $polozky_tabulky['cas_polozky'][$key];

                // Přidáme nový řádek do pole pro nové hodnoty
                $rows_to_display[] = array(
                    'nazov_klienta' => '',
                    'id_ulohy'      => $nazov_polozky,
                    'hodiny'        => $cas_polozky,
                    'suma'          => $suma_polozky,
                    'id_klienta'    => $id_klienta,
                    'id_vyuct'    => $id_vyuctovania,
                    'trieda'        => 'podradena',
                    'ico'        => ''
                );
            }
        }
        
        
        
        

        // Přidáme nový řádek do pole pro aktuálního klienta
        $rows_to_display[] = array(
            'nazov_klienta' => $nazov_klienta,
            'id_ulohy'      => get_the_title($id_ulohy),
            'hodiny'        => $hodiny,
            'suma'          => $suma,
            'id_klienta'    => $id_klienta,
            'id_vyuct'    => $id_vyuctovania,
            'trieda'        => 'hlavne',
            'ico'        => ''
        );

        // Přidáme hodnoty do sčítaných sum
        $current_suma += $suma;
        $current_hodiny += $hodiny;

        // Kontrola na prítomnosť meta fieldu pre nové riadky
        
    }

    // Po dokončení iterace přidáme poslední sčítanou sumu pro aktuálního klienta do pole
    if ($current_id_klienta !== null) {
        $rows_to_display[] = array(
            'nazov_klienta' => get_the_title($current_id_klienta),
            'id_ulohy'      => '',
            'hodiny'        => $current_hodiny,
            'suma'          => $current_suma,
            'id_klienta'    => $current_id_klienta,
            'id_vyuct'    => '',
            'trieda'        => 'hlavicka',
            'ico'        => $ico,
            'dic'        => $dic,
            'ic_dph'        => $ic_dph,
            'ulica'        => $ulica,
            'cislo'        => $cislo,
            'psc'        => $psc,
            'mesto'        => $mesto,
        );
    }

    wp_reset_postdata();

    // Vypíšeme řádky v pořadí
    foreach (array_reverse($rows_to_display) as $row) {
        
        
        
        if($row['trieda'] == 'hlavicka') {
        echo '<tr class="'.$row['trieda'].'" style="background:#faf4ec;" data-idklienta="'.$row['id_klienta'].'">'; ?>
        <td>
        <input type="checkbox" name="oznacit-vyuctovanie[<?php echo $row['id_klienta']; ?>]" value="<?php echo $row['id_klienta']; ?>">
        <input type="hidden" name="id-klient" value="<?php echo $row['id_klienta']; ?>">
        <input type="hidden" name="ulica-klient" value="<?php echo $row['ulica']; ?>">
        <input type="hidden" name="cislo-klient" value="<?php echo $row['cislo']; ?>">
        <input type="hidden" name="psc-klient" value="<?php echo $row['psc']; ?>">
        <input type="hidden" name="mesto-klient" value="<?php echo $row['mesto']; ?>">
        <input type="hidden" name="ico-klient" value="<?php echo $row['ico']; ?>">
        <input type="hidden" name="dic-klient" value="<?php echo $row['dic']; ?>">
        <input type="hidden" name="ic-dph-klient" value="<?php echo $row['ic_dph']; ?>">
        <input type="hidden" name="nazov-klient" value="<?php echo $row['nazov_klienta']; ?>">
        <input type="hidden" name="hodiny-spolu" value="<?php echo $row['hodiny']; ?>">
        <input type="hidden" name="suma-spolu" value="<?php echo $row['suma']; ?>">
        </td>
        <?php
        echo '<td><strong>' . $row['nazov_klienta'] . '</strong> <i class="fi flaticon-down-arrow ml-2 cursor-pointer" style="font-size:12px;"></i></td>';
        echo '<td><strong>' . $row['id_ulohy'] . '</strong></td>';
        echo '<td><strong>' . $row['hodiny'] . ' .hod</strong></td>';
        echo '<td><strong>' . number_format($row['suma'], 2) . ' €</strong></td>';
        echo '<td><button type="button"  onclick="oznacRiadky(' . $row['id_klienta'] . ')" data-idklienta="'.$row['id_klienta'].'" class="vyfakturovat-vsetko male-tlacidlo zlate">Vyfakturovať všetko</button></td>';

        } elseif($row['trieda'] == 'hlavne') {
        echo '<tr class="'.$row['trieda'].'" data-idklienta="'.$row['id_klienta'].'" data-idvyuct="'.$row['id_vyuct'].'">'; ?>
        
        <td>
        <input type="checkbox" name="oznacit-vyuctovanie[<?php echo $row['id_klienta']; ?>]" value="<?php echo $row['id_klienta']; ?>">
        <input type="hidden" name="nazov-klient" value="<?php echo $row['nazov_klienta']; ?>">
        <input type="hidden" name="nazov-ulohy" value="<?php echo $row['id_ulohy']; ?>">
        <input type="hidden" name="hodiny-spolu" value="<?php echo $row['hodiny']; ?>">
        <input type="hidden" name="suma-spolu" value="<?php echo $row['suma']; ?>">
        
        </td>
        <?php
        echo '<td>' . $row['nazov_klienta'] . ' <i class="fi flaticon-down-arrow ml-2 cursor-pointer" style="font-size:12px;"></i></td>';
        echo '<td>' . $row['id_ulohy'] . '</td>';
        echo '<td>' . $row['hodiny'] . ' .hod</td>';
        echo '<td>' . number_format($row['suma'], 2) . ' €</td>';
        echo '<td>';
        echo '<a title="Upraviť klienta" href="' . home_url() . '/pridat-klienta/?id=' . $row['id_klienta'] . '"><div class="male-tlacidlo slabo-modre float-left"><i class="fi flaticon-pencil"></i></div></a>';
        echo '<form method="post" class="d-inline-block float-left"><span class="p-relative zmazat-span d-inline-block float-left"><input type="submit" name="zmazat-vyuctovanie" class="slabo-cervene zmazat-ukon" value="Zmazanie" title="Zmazať Vyúčtovanie"></span></form>';
        echo '</td>';
        } elseif($row['trieda'] == 'podradena') {
        echo '<tr class="'.$row['trieda'].'" style="background:#eee;" data-idvyuct="'.$row['id_vyuct'].'" data-idklienta="'.$row['id_klienta'].'">'; ?>
        <td>
        <input type="checkbox" name="oznacit-vyuctovanie[<?php echo $row['id_klienta']; ?>]" value="<?php echo $row['id_klienta']; ?>">
        <input type="hidden" name="nazov-klient" value="<?php echo $row['nazov_klienta']; ?>">
        <input type="hidden" name="nazov-ulohy" value="<?php echo $row['id_ulohy']; ?>">
        <input type="hidden" name="hodiny-spolu" value="<?php echo $row['hodiny']; ?>">
        <input type="hidden" name="suma-spolu" value="<?php echo $row['suma']; ?>">
       </td>
        <?php
        echo '<td>' . $row['nazov_klienta'] . '</td>';
        echo '<td>' . $row['id_ulohy'] . '</td>';
        echo '<td>' . $row['hodiny'] . ' .hod</td>';
        echo '<td>' . number_format($row['suma'], 2) . ' €</td>';
        echo '<td>';
        echo '<a title="Upraviť klienta" href="' . home_url() . '/pridat-klienta/?id=' . $row['id_klienta'] . '"><div class="male-tlacidlo slabo-modre float-left"><i class="fi flaticon-pencil"></i></div></a>';
        echo '<form method="post" class="d-inline-block float-left"><span class="p-relative zmazat-span d-inline-block float-left"><input type="submit" name="zmazat-vyuctovanie" class="slabo-cervene zmazat-ukon" value="Zmazanie" title="Zmazať Vyúčtovanie"></span></form>';
        echo '</td>';
        }
        
        
        echo '</tr>';
    }


} else {
    echo 'Žiadne príspevky nenájdené.';
}
            ?>
            
        </tbody>       
</table>



</div>
</div>    

            
    
 </div>   
</section>



<div id="vyuctovanieModal" class="modal" >
    <div class="modal-content d-block float-left w-70" style="margin: 5% 15%;">
        <div class="modal-header w-100 d-block float-left slabo-zlate pt-2 pr-3 pb-2 pl-3">
            <h3 class="f-size-30">Fakturácia</h3>
            <span class="close">×</span>
        </div>

        <div class="modal-obsah w-100 d-block float-left p-3">
        
        <div class="w-100" id="zobrazenie-chyb">
        
        </div>
        
        

        <form method="post" id="exportovat-vyuctovanie" name="exportovat-vyuctovanie">
        
        
        <?php 
               
        
do_action('test_kodu'); ?>    
        
        <div class="w-100 d-inline-block float-left pl-1 mt-3 mb-2">
        <div class="w-25 d-inline-block float-left p-relative">
    <label>Typ exportu</label>
        <select id="vyber-exportu" class="pole-faktura d-inline-block float-left box-sizing mb-1">
            <option value="superfatura">SuperFaktura.sk</option>
            <option value="kros">Kros.sk</option>
            <option value="pdf">PDF</option>
        </select>

</div>
        
               <button type="button" id="btnVytvorFakturu" class="tlacidlo tien-pole float-right">Exportovať</button>
               
               <button type="submit" id="btnVytvorFakturuKros" class="tlacidlo tien-pole float-right">Exportovať Kros</button>
               
               <button type="submit" id="btnVytvorFakturuPDF" class="tlacidlo tien-pole float-right">Exportovať PDF</button>
             </div>
        
            <div class="polia-popup w-100 d-block float-left mb-3">

            <input class="w-100 dl-block float-left box-sizing pole-popup" type="hidden" name="id-klienta-final" id="id-klienta-final">
            

            
            
             
             
             
             
             <div class="dodavatel-box w-50 d-inline-block float-left pr-3">
             <h3 class="mb-2">Dodávateľ</h3>
             
             <div class="w-100 d-inline-block float-left biele tien-importu border-radius-1 p-3">
             
                <div class="lava-strana w-50 d-inline-block float-left pr-1">
                <select name="nazov-spolocnosti-dpdavatel" id="nazov-spolocnosti-dpdavatel" class="pole-faktura w-100 dl-block float-left box-sizing mb-1">
                <?php
              $prvy_vysledok = null;
              $id_spolocnosti = zobraz_spolocnosti_uzivatele();
              foreach($id_spolocnosti as $id_spolocnostis) { ?>
              <option value="<?php echo $id_spolocnostis; ?>"><?php echo get_the_title($id_spolocnostis) ; ?></option>
              <?php if ($prvy_vysledok === null) {
                    $prvy_vysledok = $id_spolocnostis;
                    break;
                    } ?>
              <?php } ?>
                </select>
                
                <?php $ulica = get_post_meta($prvy_vysledok, 'firma_ulica', true);
                $cislo_popis = get_post_meta($prvy_vysledok, 'firma_cislo', true); 
                $psc = get_post_meta($prvy_vysledok, 'firma_psc', true);
                $mesto = get_post_meta($prvy_vysledok, 'firma_mesto', true);
                $ico = get_post_meta($prvy_vysledok, 'firma_ico', true);
                $dic = get_post_meta($prvy_vysledok, 'firma_dic', true);
                $ic_dph = get_post_meta($prvy_vysledok, 'firma_ic_dph', true);
                $iban = get_post_meta($prvy_vysledok, 'firma_iban', true);
                
                $email = get_post_meta($prvy_vysledok, 'firma_email', true);
                $telefon = get_post_meta($prvy_vysledok, 'firma_telefon', true);
                $web = get_post_meta($prvy_vysledok, 'firma_webstranka', true);
                ?>
                
                
                <input class="pole-faktura w-50 dl-block float-left box-sizing mb-1" value="<?php echo $ulica; ?>" type="text" name="ulica-dodavatel" id="ulica-dodavatel" placeholder="Ulica ">
                <input class="pole-faktura w-50 dl-block float-left box-sizing mb-1" value="<?php echo $cislo_popis; ?>" type="text" name="cislo-dodavatel" id="cislo-dodavatel" placeholder="Číslo popisné">
                <input class="pole-faktura w-30 dl-block float-left box-sizing mb-1" value="<?php echo $psc; ?>" type="number" name="psc-dodavatel" id="psc-dodavatel" placeholder="PSČ">
                <input class="pole-faktura w-70 dl-block float-left box-sizing mb-1" value="<?php echo $mesto; ?>" type="text" name="mesto-dodavatel" id="mesto-dodavatel" placeholder="Mesto">
                </div>
                
                <div class="prava-strana prava-strana-dodavatel w-50 d-inline-block float-left pr-1 ">
                
                <div class="jeden-riadok w-100 d-block float-left ">
                <label>IČO:</label><input class="pole-faktura d-inline-block float-left box-sizing" value="<?php echo $ico; ?>" type="text" name="ico-dodavatel" id="ico-dodavatel" placeholder="IČO">
                </div>
                
                <div class="jeden-riadok w-100 d-block float-left">
                <label>DIČ:</label><input class="pole-faktura d-inline-block float-left box-sizing" value="<?php echo $dic; ?>" type="text" name="dic-dodavatel" id="dic-dodavatel" placeholder="DIČ">
                </div>
                
                <div class="jeden-riadok w-100 d-block float-left">
                <label>IČ DPH:</label><input class="pole-faktura d-inline-block float-left box-sizing" value="<?php echo $ic_dph; ?>" type="text" name="ic-dph-dodavatel" id="ic-dph-dodavatel" placeholder="IČ DPH">
                </div>
                
                </div>
                
                <div class="ciara w-100 d-block float-left"></div>
             
                <div class="spodok-dodavatel w-100 d-inline-block float-left mt-2">
                
                <div class="w-100 dlock float-left">
                <label for="iban-dodavatel">IBAN:</label>
                <input class="pole-faktura d-inline-block float-left box-sizing mb-1" value="<?php echo $iban; ?>" type="text" name="iban-dodavatel" id="iban-dodavatel" placeholder="IBAN">
                </div>
                
                <div class="w-100 dlock float-left">
                <label for="iban-dodavatel">VS:</label>
                <input class="pole-faktura d-inline-block float-left box-sizing mb-1" value="" type="text" name="vs-dodavatel" id="vs-dodavatel" placeholder="Variabilný symbol">
                </div>
                <div class="w-100 dlock float-left">
                <label for="iban-dodavatel">ŠP:</label>
                <input class="pole-faktura d-inline-block float-left box-sizing mb-1" value="" type="text" name="sp-dodavatel" id="sp-dodavatel" placeholder="Špecifický symbol">
                </div>
                
                </div>
             
             </div>
             
             </div>
             
             
             <div class="odberatel-box w-50 d-inline-block float-right  p-3">
             <h3>Odberateľ</h3>
             <div class="w-100 d-inline-block float-left ">
             
             
             
             <div class="lava-strana w-50 d-inline-block float-left pr-1">
             <div class="container-zvacsenie">
            <input class="dynamicInput pole-faktura d-block float-left box-sizing " type="text" name="nazov-klient-final" id="nazov-klient-final" placeholder="Názov klienta / spoločnosti">
             </div>
             
             <div class="jeden-riadok w-100 d-block float-left">
             <div class="container-zvacsenie d-inline-block float-left ">
            <input class="dynamicInput pole-faktura d-inline-block float-left box-sizing " style="width:70px;" type="text" name="ulica-klient-final" id="ulica-klient-final" placeholder="Ulica">
            </div>
            
            <div class="container-zvacsenie d-inline-block float-left ">
            <input class="dynamicInput pole-faktura d-inline-block float-left box-sizing " style="width:50px;" type="text" name="cislo-klient-final" id="cislo-klient-final" placeholder="Číslo popisné">
            </div>
            </div>
            
            <div class="jeden-riadok w-100 d-block float-left">
            <div class="container-zvacsenie d-inline-block float-left ">
            <input class="dynamicInput pole-faktura d-inline-block float-left box-sizing" style="width:40px;" type="text" name="psc-klient-final" id="psc-klient-final" placeholder="PSČ">
            </div>
            
            <div class="container-zvacsenie d-inline-block float-left">
            <input class="dynamicInput pole-faktura d-inline-block float-left box-sizing " style="width:90px;" type="text" name="mesto-klient-final" id="mesto-klient-final" placeholder="Mesto">
            </div>
            </div>
    
    
            </div>
            
            
            <div class="prava-strana prava-strana-dodavatel w-50 d-inline-block float-right pr-1">
            
            
            <div class="jeden-riadok w-100 d-block float-left">
            <label>IČO:</label><input class="pole-faktura d-inline-block float-left box-sizing" type="text" name="ico-klient-final" id="ico-klient-final" placeholder="Zadajte IČO">
            </div>
 
            <div class="jeden-riadok w-100 d-block float-left">
            <label>DIČ:</label><input class="pole-faktura d-inline-block float-left box-sizing" type="text" name="dic-klient-final" id="dic-klient-final" placeholder="Zadajte DIČ">
            </div>
            
            <div class="jeden-riadok w-100 d-block float-left">
            <label>DIČ:</label><input class="pole-faktura d-inline-block float-left box-sizing" type="text" name="ic-dph-klient-final" id="ic-dph-klient-final" placeholder="Zadajte IČ DPH">
            </div>
    
            </div>
            
            
            <div class="spodok-odberatel w-100 d-inline-block float-left mt-2">
            
            <?php $dnes = date('Y-m-d'); 
                  $strnast_dni = strtotime("+14 day");
                  $splatnost = date('Y-m-d', $strnast_dni); 
                  ?>
            
            <div class="w-100 dlock float-left">
            <label for="datum-vystavenia">Dátum vystavenia:</label>
            <input class="pole-faktura d-inline-block float-left box-sizing" type="date" id="datum-vystavenia" name="datum-vystavenia" placeholder="Dátum vystavenia" value="<?php echo $dnes; ?>">
            </div>
            
            <div class="w-100 dlock float-left">
            <label for="datum-dodania">Dátum dodania:</label>
            <input class="pole-faktura d-inline-block float-left box-sizing" type="date" id="datum-dodania" name="datum-dodania" placeholder="Dátum dodania" value="<?php echo $dnes; ?>">
            </div>
            
            <div class="w-100 dlock float-left">
            <label for="datum-splatnosti">Splatnosť:</label>
            <input class="pole-faktura d-inline-block float-left box-sizing" type="date" id="datum-splatnosti" name="datum-splatnosti" placeholder="Dátum splatnosti" value="<?php echo $splatnost; ?>">
            </div>
            
            </div>
            
            

            </div>
             </div>
             
             
             
             
             
             
             </div>

            
            <table class="moja-tabulka tabulka w-100 mt-2">
            <thead>
            <tr>
            <td>Názov úlohy / úkonu</td>
            <td width="18%">Odpracované hodiny</td>
            <td width="18%">Suma</td>
            </tr>
            </thead>
            <tbody>
            <tr><td>Test</td></tr>
            </tbody>
            <tfoot>
            <tr>
            <td><strong>Spolu</strong></td>
            <td class="spolu-hodiny-spodok-tabulka p-relative text-center"><input class="spolu-hodiny-spodok-tabulka polia-v-tabulke w-100 d-block float-left" type="number" name="hodiny-spolu-final" id="hodiny-spolu-final"><span class="symbol-tabulka">hod.</span></td>
            <td class="spolu-suma-spodok-tabulka p-relative text-center"><input class="spolu-suma-spodok-tabulka polia-v-tabulke w-100 d-block float-left" type="number" name="suma-spolu-final" id="suma-spolu-final"><span class="symbol-tabulka">€</span></td>
            </tr>
            </tfoot>
            </table>
            
            <div class="kontktne-informacie-faktura prava-strana-dodavatel w-90 d-block float-left mt-3 p-relative" style="top:80px;">
                <div class="w-30 d-inline-block floa-left">
                <div class="jeden-riadok w-100 d-block float-left">
            <label>E-mail:</label><input class="pole-faktura d-inline-block float-left box-sizing" value="<?php echo $email;?>" type="text" name="e-mail-dodavatel" id="e-mail-dodavatel" placeholder="Zadajte e-mail">
            </div>
                </div>
                
                <div class="w-30 d-inline-block floa-left">
                <div class="jeden-riadok w-100 d-block float-left">
            <label>Telefón:</label><input class="pole-faktura d-inline-block float-left box-sizing" value="<?php echo $telefon;?>" type="text" name="telefon-dodavatel" id="telefon-dodavatel" placeholder="Zadajte telefón">
            </div>
                </div>
                
                <div class="w-30 d-inline-block floa-left">
                <div class="jeden-riadok w-100 d-block float-left">
            <label>Web:</label><input class="pole-faktura d-inline-block float-left box-sizing" value="<?php echo $web;?>" type="text" name="web-dodavatel" id="web-dodavatel" placeholder="Zadajte wwww">
            </div>
                </div>
                
            </div>
            
           </form> 
        </div>

        <div class="modal-pata w-100 d-block float-left slabo-zlate pt-2 pr-3 pb-2 pl-3">
            <input class="tlacidlo hranate float-right" name="ulozit-spolocnost" type="submit" value="Uložiť">
        </div>
    </div>
</div>



        <?php } else { ?>
            <div class="alert alert-primary text-center" role="alert">Táto sekcia je určená len pre registrovaných užívateľov. Prosím prihláste sa alebo zaregistrujte.
            </div>
            <?php echo do_shortcode('[pms-login]'); ?>
        <?php } ?>
    </div>
    


<script>



    $(document).ready(function () {
        // Skryjeme všetky riadky s triedou 'hlavne'
        $('.hlavne').hide();
        $('.podradena').hide();

        // Po kliknutí na riadok s triedou 'hlavicka'
        $('.hlavicka').click(function () {
            // Nájdeme všetky riadky s rovnakým id_klienta
            var id_klienta = $(this).data('idklienta');
            var hlavneRiadky = $('.hlavne[data-idklienta="' + id_klienta + '"]');

            // Skryjeme všetky ostatné otvorené riadky s triedou 'hlavne'
            $('.hlavne').not(hlavneRiadky).hide();
            $('.podradena').hide();

            // Toggle zobrazenie alebo skrytie aktuálneho riadku s triedou 'hlavne'
            hlavneRiadky.toggle();
        });
        
        $('.hlavne').click(function () {
            // Skryjeme všetky riadky s triedou 'hlavne'
            $('.podradena').hide();
            // Zobrazíme iba riadky s rovnakým id_klienta ako v kliknutom riadku
            var id_vyuct = $(this).data('idvyuct');
            $('.podradena[data-idvyuct="' + id_vyuct + '"]').toggle();
        });
        
        
        
        
    });
    
    
     function oznacRiadky(idKlienta) {
            // Označ všetky checkboxy s rovnakým id_klienta
            var checkboxes = document.querySelectorAll('input[type="checkbox"][name^="oznacit-vyuctovanie"]');
            
            
            checkboxes.forEach(function (checkbox) {
            checkbox.checked = false;
            
                if (checkbox.name.indexOf('[' + idKlienta + ']') !== -1) {
                    checkbox.checked = true;
                }
            });
        }
    
    

    
$(document).ready(function () {
    // Přidání události na kliknutí na tlačítko "Vyfakturovat všechno"
    $('.male-tlacidlo.zlate').on('click', function () {
        // Získání hodnot checkboxů označených klientů
        $('.modal-obsah table.moja-tabulka tbody').empty(); 
  
        var idKlientaZaklad = $(this).data('idklienta');
        var hlavicka = $('.hlavicka[data-idklienta="' + idKlientaZaklad + '"]');
        var hlavne = $('.hlavne[data-idklienta="' + idKlientaZaklad + '"]');
        
        var idKlienta = hlavicka.find('[name="id-klient"]').val(); 
        var nazovKlienta = hlavicka.find('[name="nazov-klient"]').val(); 
        var hodinySpolu = hlavicka.find('[name="hodiny-spolu"]').val();
        var sumaSpolu = hlavicka.find('[name="suma-spolu"]').val();
        var icoKlienta = hlavicka.find('[name="ico-klient"]').val(); 
        var dicKlienta = hlavicka.find('[name="dic-klient"]').val(); 
        var icDphKlienta = hlavicka.find('[name="ic-dph-klient"]').val(); 
        var ulicaKlienta = hlavicka.find('[name="ulica-klient"]').val(); 
        var cisloKlienta = hlavicka.find('[name="cislo-klient"]').val(); 
        var pscKlienta = hlavicka.find('[name="psc-klient"]').val();
        var mestoKlienta = hlavicka.find('[name="mesto-klient"]').val();  
        
        $('#id-klienta-final').val(idKlienta);
        $('#hodiny-spolu-final').val(hodinySpolu);
        $('#suma-spolu-final').val(sumaSpolu);
        
        $('#nazov-klient-final').val(nazovKlienta);
        $('#ico-klient-final').val(icoKlienta);
        $('#dic-klient-final').val(dicKlienta);
        $('#ic-dph-klient-final').val(icDphKlienta);
        $('#ulica-klient-final').val(ulicaKlienta);
        $('#cislo-klient-final').val(cisloKlienta);
        $('#psc-klient-final').val(pscKlienta);
        $('#mesto-klient-final').val(mestoKlienta);
        
        //$('.spolu-hodiny-spodok-tabulka').html('<strong>' + hodinySpolu + ' hod. </strong>');
        //$('.spolu-suma-spodok-tabulka').html('<strong>' + parseFloat(sumaSpolu).toFixed(2) +' € </strong>');

        var selectedCheckboxes = $('input[type="checkbox"][name^="oznacit-vyuctovanie"]:checked');
        var modalContent = '';
        
        var podradenaRows = $('.podradena[data-idklienta="' + idKlientaZaklad + '"]');

        podradenaRows.each(function () {
            var podradenaRow = $(this);
            var idHlavne = podradenaRow.data('idvyuct'); // Získání ID hlavního řádku
            var hlavneRow = $('.hlavne[data-idklienta="' + idKlientaZaklad + '"][data-idvyuct="' + idHlavne + '"]');
            
            var nazovHlavne = hlavneRow.find('[name="nazov-ulohy"]').val();
            var nazovPodradene = podradenaRow.find('[name="nazov-ulohy"]').val();
            var hodinySpolu = podradenaRow.find('[name="hodiny-spolu"]').val();
            var sumaSpolu = podradenaRow.find('[name="suma-spolu"]').val();

            modalContent += '<tr><td><textarea type="text" class="polia-v-tabulke w-100 d-block float-left" name="tabulka[nazov_polozky][]">' + nazovHlavne + ' - ' + nazovPodradene + '</textarea></td>' + 
            '<td class="p-relative text-center"><input type="text" name="tabulka[cas_polozky][]" class="polia-cas-polozky polia-v-tabulke w-100 d-block float-left" value="' + hodinySpolu + '"> <span class="symbol-tabulka">hod.</span></td>' +
            '<td class="p-relative text-center"><input type="text" name="tabulka[suma_polozky][]" class="polia-suma-polozky polia-v-tabulke d-block float-left" value="' + parseFloat(sumaSpolu).toFixed(2) + '"> <span class="symbol-tabulka">€</span></td></tr>';
        });
        
        $('.modal-obsah table.moja-tabulka tbody').append(modalContent); 
        $('#vyuctovanieModal').show();
        
        
       
       $(document).on('input', '.polia-cas-polozky', function () {
       var sumHodiny = 0;
        // Prejdite cez všetky inputy s názvom "cas-polozky" a pridajte hodnoty
        $('input[name="cas-polozky"]').each(function () {
            var hodiny = parseFloat($(this).val()) || 0; // získa hodnotu alebo nastaví 0, ak je hodnota neplatná
            sumHodiny += hodiny;
        });
        
        

        // Nastavenie výslednej sumy do príslušného inputu
        $('.spolu-hodiny-spodok-tabulka').val(sumHodiny);
        
        
    }); 
    
    $(document).on('input', '.polia-suma-polozky', function () {
    var sumSuma = 0;
    $('input[name="suma-polozky"]').each(function () {

            var suma = parseFloat($(this).val()) || 0; // získa hodnotu alebo nastaví 0, ak je hodnota neplatná
            sumSuma += suma;
        });
        
        $('.spolu-suma-spodok-tabulka').val(sumSuma);
    
    });
    
    
     $('.dynamicInput').each(function () {
     var currentWidth = parseInt($(this).css('width'), 10);
      $(this).css('width', (currentWidth + 5) + 'px');
    setInputWidth($(this));
});   
        
    });

    // Přidání události na kliknutí mimo modální okno pro jeho zavření
    $(document).on('click', function (e) {
        if ($(e.target).hasClass('modal')) {
            // Zavřít modální okno
            $('#vyuctovanieModal').hide();
            $('.modal-obsah table.moja-tabulka tbody').empty(); 
        }
    });

    // Zabránit zavření modálního okna, pokud klikneme na jeho obsah
    $('.modal-content').on('click', function (e) {
        e.stopPropagation();
    });
  




 function setInputWidth(input) {
    var container = input.closest('.container-zvacsenie');
    var textWidth = input[0].scrollWidth + 5;
    input.css('width', textWidth + 'px');
    container.css('width', textWidth + 'px');
  }
  
  function setInputWidthDelete(input) {
    var container = input.closest('.container-zvacsenie');
    var textWidth = input[0].scrollWidth;
    input.css('width', textWidth + 'px');
    container.css('width', textWidth + 'px');
  }
  


  $('.dynamicInput').on('input', function (e) {
    var currentValue = $(this).val();
    var previousValue = $(this).data('previousValue') || '';

    if (currentValue.length > previousValue.length) {
      var currentWidth = parseInt($(this).css('width'), 10);
      $(this).css('width', (currentWidth) + 'px');
      setInputWidth($(this));
    }

    // Uložit aktuální hodnotu do data atributu pro další kontrolu
    $(this).data('previousValue', currentValue);
  });
  
  
  // Udalosť pri stisknutí klávesy DELETE
  $('.dynamicInput').on('keydown', function (e) {
    if (e.keyCode === 8) { // 8 je kód DELETE klávesy
      setInputWidthDelete($(this));
    }
  });



  
  
});
    
</script>




<?php get_footer(); ?>
