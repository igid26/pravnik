<?php

function vytvorenie_faktury_kros() {
    // Kontrola, či boli prijaté dáta
    $formData = !empty($_POST['data']) ? $_POST['data'] : '';

    if (!empty($formData)) {
        // Spracovanie prijatých dát
        $nazov_spolocnosti = $formData['nazov_spolecnosti_dpdavatel'];
        $ulica_dodavatel = $formData['ulica_dodavatel'];
        $cislo_dodavatel = $formData['cislo_dodavatel'];
        $psc_dodavatel = $formData['psc_dodavatel'];
        $mesto_dodavatel = $formData['mesto_dodavatel'];
        $ico_dodavatel = $formData['ico_dodavatel'];
        $dic_dodavatel = $formData['dic_dodavatel'];
        $ic_dph_dodavatel = $formData['ic_dph_dodavatel'];
        $iban_dodavatel = $formData['iban_dodavatel'];
        $vs_dodavatel = $formData['vs_dodavatel'];
        $sp_dodavatel = $formData['sp_dodavatel'];
        $email_dodavatel = $formData['email_dodavatel'];
        $telefon_dodavatel = $formData['telefon_dodavatel'];
        $web_dodavatel = $formData['web_dodavatel'];
        
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
        
        $noveRadky = !empty($formData['tabulka']) ? $formData['tabulka'] : array();

        $max_length = 8; // Maximálna povolená dĺžka pre registrationId
$partner_registrationId = strlen($ico_dodavatel) <= $max_length ? $ico_dodavatel : substr($ico_dodavatel, 0, $max_length);



        $items = array();
foreach ($noveRadky as $radek) {
    $items[] = array(
        "name" => $radek['nazov_polozky'],
        "description" => isset($radek['cas_polozky']) ? "Odpracované hodiny: " . $radek['cas_polozky'] : '', // Príklad ako pridať popis
        "amount" => 1, // Predpokladám, že množstvo je 1, upravte podľa potreby
        "measureUnit" => "ks", // Jednotka merania, upravte podľa potreby
        "vatRate" => 0, // DPH, upravte podľa potreby
        "discountPercent" => 0, // Percento zľavy, upravte podľa potreby
        "totalPriceInclVat" => floatval($radek['suma_polozky']), // Konverzia na číslo
        // Doplniť ďalšie potrebné polia pre položku
    );
}

        // Tu doplňte štruktúru pre KROS API
        $data = [
    "data" => [
        "externalId" => "", // Toto by malo byť dynamicky generované alebo získané
        "partner" => [
            "address" => [
                "businessName" => "$nazov_klient_final",
                "contactName" => "",
                "street" => "$ulica_klient_final $cislo_klient_final",
                "postCode" => $psc_klient_final,
                "city" => $mesto_klient_final,
                "country" => ""
            ],
            "registrationId" => $partner_registrationId,
            "taxId" => $dic_dodavatel,
            "vatId" => $ic_dph_dodavatel,
            "phoneNumber" => $telefon_dodavatel,
            "email" => $email_dodavatel,
        ],
        "myCompany" => [
            "address" => [
                "businessName" => $nazov_spolocnosti,
                "contactName" => "",
                "street" => $ulica_dodavatel . ' ' . $cislo_dodavatel,
                "postCode" => $psc_dodavatel,
                "city" => $mesto_dodavatel,
                "country" => ""
            ],
            "registrationId" => $partner_registrationId,
            "taxId" => $dic_dodavatel,
            "vatId" => $ic_dph_dodavatel,
            "phoneNumber" => $telefon_dodavatel,
            "email" => $email_dodavatel,
            "web" => $web_dodavatel
        ],
        "items" => $items,
        // Doplnenie ďalších potrebných častí podľa špecifikácie
        "internalNote" => "",
        "printedNote" => "",
        "vatPayerType" => 1,
        "useParagraph7or7a" => false,
        "culture" => "sk-SK",
        "openingText" => "",
        "closingText" => "",
        "registrationCourtText" => "Firma zapísaná v registri ...",
        "dueDate" => $datum_splatnosti,
        "currency" => "EUR",
        "exchangeRate" => 1,
        "discountPercent" => 0,
        "discountTotalPriceInclVat" => 0,
        "issueDate" => $datum_vystavenia,
        "orderNumber" => "",
        "paymentType" => "Bankový prevod",
        "variableSymbol" => $vs_dodavatel,
        "bankAccount" => [
            "iban" => $iban_dodavatel,
            "accountNumber" => "",
            "isForeign" => false,
            "swift" => ""
        ],
        "deliveryDate" => $datum_dodania,
        "advancePaymentDeduction" => 0,
        "numberingSequence" => "OF",
        "documentNumber" => "ON",
        "invoiceType" => 0,
        "creditedInvoiceNumber" => "",
        "mandatoryText" => "",
        "mandatoryTextType" => 0,
        "ossTaxState" => 0,
    ]
];



        $json_data = json_encode($data);
        

        // API endpoint a autorizačné údaje
        $api_url = 'https://api-economy.kros.sk/api/invoices';
        $api_key = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJJZCI6Ijc3Y2JlYmYxLWEzNDgtNDllMS1iMDQzLTZjZmE4MzJjMGYwYyIsIlRlbmFudElkIjoiMTExODAzIiwiU2NvcGUiOiJLcm9zLkVzdyIsIkNyZWRlbnRpYWxzIjoie1wiVXNlcklkXCI6OTYxODh9IiwibmJmIjoxNzAzMDUzNjMxLCJleHAiOjQxMDI0NDQ4MDAsImlzcyI6Imtyb3Muc2siLCJhdWQiOiIzcmRwYXJ0eWFwaSJ9.3GkY4BoJVokbIqBvfl5mqAvUrL0WPwyewA-Uy0e0tfA'; // Nastavte váš API kľúč

        // Inicializácia cURL a odoslanie požiadavky
        $ch = curl_init($api_url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $api_key
        ));

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

       if ($http_code == 202 OR $http_code == 202) {
    $response_data = json_decode($response); // Dekódovanie JSON odpovede
    wp_send_json_success($response_data); // Posiela úspešnú odpoveď
} else {
    // Vytvorenie štruktúry chybovej správy
    $error_message = "Chyba pri vytváraní faktúry. HTTP kód: $http_code";
    $error_details = json_decode($response, true); // Dekódovanie JSON odpovede

    // Posielanie chybovej odpovede
    wp_send_json_error(array(
        'message' => $error_message,
        'details' => $error_details
    ));
}
    }
    wp_die();
}

add_action('wp_ajax_vytvorenie_faktury_kros', 'vytvorenie_faktury_kros');
add_action('wp_ajax_nopriv_vytvorenie_faktury_kros', 'vytvorenie_faktury_kros');
