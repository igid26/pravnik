<?php
function vytvorenie_faktury_pdf() {


    $my_plugin = WP_PLUGIN_DIR . '/profil-uzivatela/pdf-generator/examples/tcpdf_include.php';         
    require_once($my_plugin);

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['data'])) {
        $formData = $_POST['data'];
        
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

        // Vytvoření nového PDF dokumentu
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        

        // Základní nastavení dokumentu
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Vaše Firma');
        $pdf->SetTitle('Faktura');
        $pdf->SetSubject('Faktura');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(10, 10, 10);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->AddPage();
        $pdf->SetFont('dejavusans', '', 8);

        // Tvorba obsahu PDF
        $htmlContent = "
        <h1>Faktura</h1>
        <h2>Údaje o dodavateli</h2>
        <p><strong>Názov spoločnosti:</strong> " . htmlspecialchars($formData['nazov_spolecnosti_dpdavatel']) . "<br>
        <strong>Ulica:</strong> " . htmlspecialchars($formData['ulica_dodavatel']) . " " . htmlspecialchars($formData['cislo_dodavatel']) . "<br>
        <strong>PSČ:</strong> " . htmlspecialchars($formData['psc_dodavatel']) . "<br>
        <strong>Mesto:</strong> " . htmlspecialchars($formData['mesto_dodavatel']) . "<br>
        <strong>IČO:</strong> " . htmlspecialchars($formData['ico_dodavatel']) . "<br>
        <strong>DIČ:</strong> " . htmlspecialchars($formData['dic_dodavatel']) . "<br>
        <strong>IČ DPH:</strong> " . htmlspecialchars($formData['ic_dph_dodavatel']) . "<br>
        <strong>IBAN:</strong> " . htmlspecialchars($formData['iban_dodavatel']) . "<br>
        <strong>Variabilný symbol:</strong> " . htmlspecialchars($formData['vs_dodavatel']) . "<br>
        <strong>Email:</strong> " . htmlspecialchars($formData['email_dodavatel']) . "<br>
        <strong>Telefón:</strong> " . htmlspecialchars($formData['telefon_dodavatel']) . "<br>
        <strong>Web:</strong> " . htmlspecialchars($formData['web_dodavatel']) . "</p>";

        // Přidání položek faktury
        if (!empty($formData['tabulka'])) {
            foreach ($formData['tabulka'] as $radek) {
                $htmlContent .= "<p><strong>Názov položky:</strong> " . htmlspecialchars($radek['nazov_polozky']) . "<br>
                <strong>Popis:</strong> " . htmlspecialchars($radek['cas_polozky']) . "<br>
                <strong>Cena:</strong> " . htmlspecialchars($radek['suma_polozky']) . " EUR</p>";
            }
        }

        // Psaní HTML obsahu do PDF
        //$pdf->writeHTML($htmlContent, true, false, true, false, '');




include ('vzhlad/vzhlad1.php');
        $html = getHTMLPurchaseDataToPDF(
    $nazov_spolocnosti, 
    $ulica_dodavatel, 
    $cislo_dodavatel, 
    $psc_dodavatel, 
    $mesto_dodavatel, 
    $ico_dodavatel, 
    $dic_dodavatel, 
    $ic_dph_dodavatel, 
    $iban_dodavatel, 
    $vs_dodavatel, 
    $sp_dodavatel, 
    $email_dodavatel, 
    $telefon_dodavatel, 
    $web_dodavatel, 
    $nazov_klient_final, 
    $ulica_klient_final, 
    $cislo_klient_final, 
    $psc_klient_final, 
    $mesto_klient_final, 
    $ico_klient_final, 
    $dic_klient_final, 
    $ic_dph_klient_final, 
    $datum_vystavenia, 
    $datum_dodania, 
    $datum_splatnosti, 
    $noveRadky
);
        $pdf->writeHTML($html, true, false, true, false, '');
        ob_end_clean();
        
        


      $pdfPath = 'c:/xampp3/htdocs/prepravnika/wp-content/plugins/profil-uzivatela/profil-uzivatelov/vyuctovanie/pdf/faktury/faktura.pdf';

    // Uložení PDF na disk
    $pdf->Output($pdfPath, 'F');

    // Vrácení URL PDF souboru
    echo json_encode(array('url' => $pdfPath));
    exit;

        ob_end_flush(); // Ukončení output buffering a odeslání výstupu
    } else {
        echo "No data received";
        ob_end_clean(); // Ukončení a vyčištění bufferu, pokud nejsou přijata data
    }
}

add_action('wp_ajax_vytvorenie_faktury_pdf', 'vytvorenie_faktury_pdf');
add_action('wp_ajax_nopriv_vytvorenie_faktury_pdf', 'vytvorenie_faktury_pdf');
