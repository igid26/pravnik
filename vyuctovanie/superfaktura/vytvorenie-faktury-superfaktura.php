<?php
function vytvorenie_faktury_superfaktura() {
    

//prístupové údaje k API
    $api_key = '.....';
    $company_id = '....';
    $email = '.....';

    // Endpoint pre vytvorenie novej faktúry
    $api_url = 'https://moja.superfaktura.sk/invoices/create/';
    
    
      $formData = !empty($_POST['data']) ? $_POST['data'] : '';

    // Kontrola, zda byla data předána
    if (!empty($formData)) {
        // Získání dat z formuláře s kontrolou prázdných hodnot
       $nazov_spolocnosti = !empty($formData['nazov_spolecnosti_dpdavatel']) ? $formData['nazov_spolecnosti_dpdavatel'] : '';
$ulica_dodavatel = !empty($formData['ulica_dodavatel']) ? $formData['ulica_dodavatel'] : '';
$cislo_dodavatel = !empty($formData['cislo_dodavatel']) ? $formData['cislo_dodavatel'] : '';
$psc_dodavatel = !empty($formData['psc_dodavatel']) ? $formData['psc_dodavatel'] : '';
$mesto_dodavatel = !empty($formData['mesto_dodavatel']) ? $formData['mesto_dodavatel'] : '';
$ico_dodavatel = !empty($formData['ico_dodavatel']) ? $formData['ico_dodavatel'] : '';
$dic_dodavatel = !empty($formData['dic_dodavatel']) ? $formData['dic_dodavatel'] : '';
$ic_dph_dodavatel = !empty($formData['ic_dph_dodavatel']) ? $formData['ic_dph_dodavatel'] : '';
$iban_dodavatel = !empty($formData['iban_dodavatel']) ? $formData['iban_dodavatel'] : '';
$vs_dodavatel = !empty($formData['vs_dodavatel']) ? $formData['vs_dodavatel'] : '';
$sp_dodavatel = !empty($formData['sp_dodavatel']) ? $formData['sp_dodavatel'] : '';
$email_dodavatel = !empty($formData['email_dodavatel']) ? $formData['email_dodavatel'] : '';
$telefon_dodavatel = !empty($formData['telefon_dodavatel']) ? $formData['telefon_dodavatel'] : '';
$web_dodavatel = !empty($formData['web_dodavatel']) ? $formData['web_dodavatel'] : '';

$nazov_klient_final = !empty($formData['nazov_klient_final']) ? $formData['nazov_klient_final'] : '';
$ulica_klient_final = !empty($formData['ulica_klient_final']) ? $formData['ulica_klient_final'] : '';
$cislo_klient_final = !empty($formData['cislo_klient_final']) ? $formData['cislo_klient_final'] : '';
$psc_klient_final = !empty($formData['psc_klient_final']) ? $formData['psc_klient_final'] : '';
$mesto_klient_final = !empty($formData['mesto_klient_final']) ? $formData['mesto_klient_final'] : '';
$ico_klient_final = !empty($formData['ico_klient_final']) ? $formData['ico_klient_final'] : '';
$dic_klient_final = !empty($formData['dic_klient_final']) ? $formData['dic_klient_final'] : '';
$ic_dph_klient_final = !empty($formData['ic_dph_klient_final']) ? $formData['ic_dph_klient_final'] : '';
$datum_vystavenia = !empty($formData['datum_vystavenia']) ? $formData['datum_vystavenia'] : '';
$datum_dodania = !empty($formData['datum_dodania']) ? $formData['datum_dodania'] : '';
$datum_splatnosti = !empty($formData['datum_splatnosti']) ? $formData['datum_splatnosti'] : '';

        
  
  
$nove_radky = !empty($formData['tabulka']) ? $formData['tabulka'] : array();


    // Dáta pre novú faktúru
    $data = array(
        "Invoice" => array(
            "name" => "Faktúra - $nazov_spolocnosti",
            "bank_accounts" => array(
                array(
                    "bank_name" => "New Bank",
                    "iban" => "$iban_dodavatel",
                    "swift" => ""
                )
            ),
            "issued_by" => "$nazov_spolocnosti",
            "issued_by_email" => "$email_dodavatel",
            "issued_by_phone" => "$telefon_dodavatel",
            "issued_by_web" => "$web_dodavatel",
            "discount" => 0,
            "header_comment" => "",
            "internal_comment" => "",
            "invoice_currency" => "EUR",
            "rounding" => "item_ext",
            "specific" => "$sp_dodavatel",
            "type" => "regular",
            "variable" => "$vs_dodavatel",
            "created" => "$datum_vystavenia"
        ),
        "Client" => array(
            "name" => "$nazov_klient_final",
            "ico" => "$ico_klient_final",
            "dic" => "$dic_klient_final",
            "ic_dph" => "$ic_dph_klient_final",
            "address" => "$ulica_klient_final $cislo_klient_final",
            "city" => "$mesto_klient_final",
            "zip" => "$psc_klient_final",
            "comment" => "",
            "update_addressbook" => 1,
            "iban" => "",
            "swift" => ""
        ),
        "InvoiceSetting" => array(
            "language" => "sk",
            "signature" => true,
            "payment_info" => true,
            "online_payment" => true,
            "bysquare" => true,
            "paypal" => true
        ),
        "InvoiceExtra" => array(
            "pickup_point_id" => 23
        ), 
        "MyData" => array(
            "address" => "$ulica_dodavatel $cislo_dodavatel",
            "business_register" => "-",
            "city" => "$mesto_dodavatel",
            "company_name" => "$nazov_spolocnosti",
            "country_id" => 191,
            "dic" => "$dic_dodavatel",
            "ic_dph" => "$ic_dph_dodavatel",
            "update_profile" => "0",
            "zip" => "$psc_dodavatel"
        )
    );
    
    foreach ($nove_radky as $radek) {
    
    if(!empty($radek['cas_polozky'])) {
    $cas = 'Odpracované hodiny:' . $radek['cas_polozky'];
    } else {
    $cas = '';
    }
    
    $novy_invoice_item = array(
        "description" => $cas,
        "name" => $radek['nazov_polozky'],
        "tax" => 0,
        "unit_price" => $radek['suma_polozky'],
        "AccountingDetail" => array(
            "place" => "Slovakia",
            "order" => "PLA",
            "operation" => "UXW",
            "type" => "item",
            "analytics_account" => "",
            "synthetic_account" => "000",
            "preconfidence" => "5ZV"
        )
    );

    // Přidat nový InvoiceItem do pole $data["InvoiceItem"]
    $data["InvoiceItem"][] = $novy_invoice_item;
}

    // Konvertuj dáta na formát JSON
    $json_data = json_encode($data);
    
    

    $ch = curl_init($api_url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POSTFIELDS, "data=$json_data");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/x-www-form-urlencoded;charset=UTF-8',
        'Authorization: SFAPI email=' . urlencode($email) . '&apikey=' . urlencode($api_key) . '&company_id=' . urlencode($company_id),
    ));

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Spracuj odpoveď
    if ($http_code == 200) {
        //echo "Faktúra bola úspešne vytvorená.\n";
        $invoice_data = json_decode($response, true);
        echo $response;
    } else {
        echo "Chyba pri vytváraní faktúry. HTTP kód: $http_code\n";
        echo "Odpoveď: $response\n";
    }

    // Dôležité: Zastav spracovanie požiadavky po výstupe z funkcie
    
}
wp_die();
}

// Pridaj AJAX hooky
add_action('wp_ajax_vytvorenie_faktury_superfaktura', 'vytvorenie_faktury_superfaktura');
add_action('wp_ajax_nopriv_vytvorenie_faktury_superfaktura', 'vytvorenie_faktury_superfaktura');
