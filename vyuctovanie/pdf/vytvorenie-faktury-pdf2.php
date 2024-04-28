<?php
require ABSPATH . 'vendor/autoload.php';
use SepaQr\SepaQr;
function vytvorenie_faktury_pdf_new() {
ob_start(); // Začátek output buffering

if ($_SERVER['REQUEST_METHOD'] == 'POST' ) {
        
        
$id_spolocnosti = $_POST['nazov-spolocnosti-dpdavatel'];
$nazov_spolocnosti = get_the_title($id_spolocnosti);
$ulica_dodavatel = $_POST['ulica-dodavatel'];
$cislo_dodavatel = $_POST['cislo-dodavatel'];
$psc_dodavatel = $_POST['psc-dodavatel'];
$mesto_dodavatel = $_POST['mesto-dodavatel'];
$ico_dodavatel = $_POST['ico-dodavatel'];
$dic_dodavatel = $_POST['dic-dodavatel'];
$ic_dph_dodavatel = $_POST['ic-dph-dodavatel'];
$iban_dodavatel = $_POST['iban-dodavatel'];
$vs_dodavatel = $_POST['vs-dodavatel'];
$sp_dodavatel = $_POST['sp-dodavatel'];
$email_dodavatel = $_POST['e-mail-dodavatel'];
$telefon_dodavatel = $_POST['telefon-dodavatel'];
$web_dodavatel = $_POST['web-dodavatel'];

$nazov_klient_final = !empty($_POST['nazov-klient-final']) ? $_POST['nazov-klient-final'] : '';
$ulica_klient_final = !empty($_POST['ulica-klient-final']) ? $_POST['ulica-klient-final'] : '';
$cislo_klient_final = !empty($_POST['cislo-klient-final']) ? $_POST['cislo-klient-final'] : '';
$psc_klient_final = !empty($_POST['psc-klient-final']) ? $_POST['psc-klient-final'] : '';
$mesto_klient_final = !empty($_POST['mesto-klient-final']) ? $_POST['mesto-klient-final'] : '';
$ico_klient_final = !empty($_POST['ico-klient-final']) ? $_POST['ico-klient-final'] : '';
$dic_klient_final = !empty($_POST['dic-klient-final']) ? $_POST['dic-klient-final'] : '';
$ic_dph_klient_final = !empty($_POST['ic-dph-klient-final']) ? $_POST['ic-dph-klient-final'] : '';
$datum_vystavenia = !empty($_POST['datum-vystavenia']) ? $_POST['datum-vystavenia'] : '';
$datum_dodania = !empty($_POST['datum-dodania']) ? $_POST['datum-dodania'] : '';
$datum_splatnosti = !empty($_POST['datum-splatnosti']) ? $_POST['datum-splatnosti'] : '';

$noveRadky = !empty($_POST['tabulka']) ? $_POST['tabulka'] : array();  
        
$hodiny_spolu = !empty($_POST['hodiny-spolu-final']) ? $_POST['hodiny-spolu-final'] : '';   
$suma_spolu = !empty($_POST['suma-spolu-final']) ? $_POST['suma-spolu-final'] : '';     

$nazovPolozky = !empty($_POST['tabulka']['nazov_polozky']) ? $_POST['tabulka']['nazov_polozky'] : array();
$casPolozky = !empty($_POST['tabulka']['cas_polozky']) ? $_POST['tabulka']['cas_polozky'] : array();
$sumaPolozky = !empty($_POST['tabulka']['suma_polozky']) ? $_POST['tabulka']['suma_polozky'] : array();

    $my_plugin = WP_PLUGIN_DIR . '/profil-uzivatela/pdf-generator/examples/tcpdf_include.php';         
    require_once($my_plugin);
    
    
    $path_zaklad = explode( 'wp-content', __DIR__ );
$path = $path_zaklad[0];      
require_once( $path . 'wp-load.php' );
    
$upload_dir = wp_upload_dir();
    // Vytvoření nového PDF dokumentu
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    
    

   
class MYPDF extends TCPDF {


public function Header() {
    $id_uzivatela = get_current_user_id();
$logo =  get_user_meta( $id_uzivatela, 'firma_logo' , true );
$podpis =  get_user_meta( $id_uzivatela, 'firma_podpis' , true );

$this->SetY(100);

}

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        $this->SetTextColor(198,198,198);
        $this->SetFont('freesans', 'I', 8);
        $this->Cell(80, 10, 'Strana  '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'L', 0, '', 0, false, 'T', 'M');
        $this->SetTextColor(103,103,103);
        $this->Cell(100, 10, "PDF vygeneroval systém Pre-pravnika.sk", 0, 0, 'R', 0, '', 0, false, 'T', 'M');
    }
}

$opensans_regular = TCPDF_FONTS::addTTFfont($path . '/wp-content/plugins/profil-uzivatela/pdf-generator/fonts/opensans/OpenSans-Regular.ttf', 'TrueTypeUnicode', '', 96);
$opensans_bold = TCPDF_FONTS::addTTFfont($path . '/wp-content/plugins/profil-uzivatela/pdf-generator/fonts/opensans/OpenSans-Bold.ttf', 'TrueTypeUnicode', '', 96);
$opensans_light = TCPDF_FONTS::addTTFfont($path . '/wp-content/plugins/profil-uzivatela/pdf-generator/fonts/opensans/OpenSans-Light.ttf', 'TrueTypeUnicode', '', 96);





 $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false); 
 $pdf->SetCreator(PDF_CREATOR); $pdf->SetAuthor('tmpAutor');  
 $pdf->SetTitle('tmpTitle'); $pdf->SetSubject('tmpSubject'); 
 $pdf->SetKeywords('tmp');   $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN)); 
 $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));                
 $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED); 
 $pdf->SetMargins(10, 40, 10);                                 
 $pdf->setHeaderMargin(10);  
 $pdf->SetFooterMargin(20);
 $pdf->setHeaderData($ln='', $lw=0, $ht='', $hs='<table style="border-collapse: border-collapse: collapse;border-bottom: 1px solid #c6c6c6;margin-bottom:20px;color:#c6c6c6;" cellspacing="5" cellpadding="0" border="0"><tr style="border-bottom:solid 1px #c6c6c6;"><td rowspan="3"><img style="opacity:0" src="" height="30px"></td><td style="text-align:right;">Hlavicka</td><td style="text-align:right;">dalej</td><td style="text-align:right;">tst</td></tr><tr><td></td></tr></table>');         
 $pdf->SetAutoPageBreak(True, PDF_MARGIN_BOTTOM); 
 //set image scale factor 
 $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); 
 //set some language-dependent strings 
 $pdf->AddPage(); 
 //$pdf->SetFont('arial', '', 9); 
 $pdf->SetFont($opensans_regular, '', 9, '', false); 

    // Tvorba obsahu PDF
   $vs_s = "SK6211000000002932395136";
   
   $variableSymbol = '123456'; // Vaši variabilní symbol
$specificSymbol = '7890';  // Vaši specifický symbol

$remittanceText = "VS: $variableSymbol, SS: $specificSymbol";


try {
    $sepaQr = new SepaQr();
    $sepaQr->setName($nazov_spolocnosti)
           ->setIban($vs_s)
           ->setAmount($suma_spolu) // Suma v Eurách
           ->setRemittanceText($remittanceText)
           ->setPurpose(999)
           ->setBic('SUBASKBX')
           ->setSize(400); // Veľkosť QR kódu


    $qrCodePath = WP_PLUGIN_DIR . '/profil-uzivatela/profil-uzivatelov/vyuctovanie/pdf/faktury/qr_code.png';
    if(!$sepaQr->writeFile($qrCodePath)) {
        throw new Exception("Nepodarilo sa uložiť QR kód na disk.");
    }
} catch (Exception $e) {
    echo 'Chyba pri generovaní QR kódu: ' . $e->getMessage();
}

$pdf->Image($qrCodePath, 165, 5, 35, 35, 'PNG');    

$logo = 'http://localhost/prepravnika/wp-content/themes/pravo/assets/images/blog/about-widget.jpg';


$pdf->Image($logo, 10, 8, 35, 35, 'JPG');      
    
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
    $noveRadky,
    $hodiny_spolu,  
    $suma_spolu,
    $nazovPolozky,
    $casPolozky,
    $sumaPolozky
);

        $pdf->writeHTML($html, true, false, true, false, '');


    
    
    

$faktury_dir = $upload_dir['basedir'] . '/faktury';
        if (!is_dir($faktury_dir)) {
            wp_mkdir_p($faktury_dir);
        }
        
        $id_spolocnosti_dir = $faktury_dir . '/' . $id_spolocnosti;
        if (!is_dir($id_spolocnosti_dir)) {
            wp_mkdir_p($id_spolocnosti_dir);
        }

        // Generate the PDF file path within the "faktury" subdirectory
        $pdfPath_new = $id_spolocnosti_dir . '/faktura.pdf';



$pdf->Output($pdfPath_new, 'F');


    // Uložení PDF na server
    $pdfPath = WP_PLUGIN_DIR . '/profil-uzivatela/profil-uzivatelov/vyuctovanie/pdf/faktury/faktura.pdf';
    ob_end_clean();
    $pdf->Output($pdfPath, 'F');

    // Vrácení URL PDF souboru
    echo '<a href="' . plugins_url('/profil-uzivatela/profil-uzivatelov/vyuctovanie/pdf/faktury/faktura.pdf') . '" target="_blank">Otvoriť faktúru</a>';

    ob_end_flush(); // Ukončení output buffering a odeslání výstupu
}
}
add_action('test_kodu', 'vytvorenie_faktury_pdf_new');
