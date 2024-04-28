<?php
function getHTMLPurchaseDataToPDF($nazov_spolocnosti, $ulica_dodavatel, $cislo_dodavatel, $psc_dodavatel, $mesto_dodavatel, $ico_dodavatel, $dic_dodavatel, $ic_dph_dodavatel, $iban_dodavatel, $vs_dodavatel, $sp_dodavatel, $email_dodavatel, $telefon_dodavatel, $web_dodavatel, $nazov_klient_final, $ulica_klient_final, $cislo_klient_final, $psc_klient_final, $mesto_klient_final, $ico_klient_final, $dic_klient_final, $ic_dph_klient_final, $datum_vystavenia, $datum_dodania, $datum_splatnosti, $noveRadky, $hodiny_spolu, $suma_spolu, $nazovPolozky, $casPolozky, $sumaPolozky) {
ob_start();
?>
<html>
<head>Receipt of Purchase -
</head>
<body>

<style>  
.tabulka-objednavka, .tabulka-objednavka tbody {
width:100%;
border-collapse: collapse;
}  
.tabulka-objednavka tr{
width:100%;
}

.tabulka-dodavatel, .tabulka-odberatel {
border:1px solid #000;
padding:15px;
width:50%;
}
.tabulka-podradena tr, .tabulka-podradena {
width:100%;

}
.tabulka-podradena tr.posledny-padding{
padding-bottom:20px;
height:30px;
line-height:15px;
}

.tabulka-podradena td{
width:50%;
}

.kontakt-male {
font-size:10px;
}

.posledne-male {
padding-top:20px;
  padding-bottom:20px;
  padding-right:20px;   
}
.obrazok-tabulky {
width:100%;
border:solid 1px #000;
}
.nacenovanie-tabulky {
width:100%;
border:solid 1px #000;
vertical-align:top;
padding:15px;
}

.tabulka-obrazok tr, .tabulka-obrazok td{
text-align:center;
}

.tabulka-nacenovanie {
width:100%;
}

.riadok-parametre td {
width:100%;
}

.tabulka-nacenovanie span{
text-align:right !important;
float:right;
display:none;
}

.tabulka-nacenovanie td:last-child{
text-align:right !important;
}

.riadok-oddelovac {
line-height:10px;
}

.final-tabulka-farebna {
border:1px solid #000;
width:100%;
background-color:#29395b;
color:#ffffff;
}



</style>     

<table class="hlavicka-tabulky w-100" style="width:100%;text-align:right;">
<tr>
<td width="50%" style="text-align:left;font-size:14px;height:25px;"><strong>Faktúra: FA4563254</strong></td>
<td width="50%" style="text-align:right;font-size:14px;height:25px;"><strong>Faktúra: FA4563254</strong></td>
</tr>

</table>

<table class=" hlavicka-tabulky w-100" style="border: 1px solid #000;width:100%">
        
        <tr> <!--Hlavicka Zaciatok-->
        
        
        <td class="tabulka-dodavatel">
         <table class="tabulka-podradena">
         
         <tr class="posledny-padding" style="line-height:10px;">
          <td></td>
          <td></td>  
          </tr>
         
          <tr>
          <td><strong class="w-100">Dodávateľ</strong></td>
          </tr>
          
          <tr class="posledny-padding" style="line-height:10px;">
          <td></td>
          <td></td>  
          </tr>
          
          <tr>
          <td><?php echo $nazov_spolocnosti; ?></td>
          <td>IČO: <?php echo $ico_dodavatel; ?></td>
          </tr>
          <tr>
          <td><?php echo $ulica_dodavatel; ?> <?php echo $cislo_dodavatel; ?></td>
          <td>DIČ: <?php echo $dic_dodavatel; ?></td>
          </tr>
          <tr>
          <td><?php echo $psc_dodavatel; ?> <?php echo $mesto_dodavatel; ?></td>
          <td>IČ DPH: <?php echo $ic_dph_dodavatel; ?></td>
          </tr>

          
          <tr>
          <td>_</td>
          <td>_</td> 
          </tr>
          
          
          <tr class="posledny-padding" style="margin-top:10px;">
          <td width="15%">tel::</td>
          <td width="85%"><?php echo $telefon_dodavatel; ?></td>  
          </tr>
          
          <tr class="posledny-padding" style="margin-top:10px;">
          <td width="15%">e-mail:</td>
          <td width="85%"><?php echo $email_dodavatel; ?></td>  
          </tr>
          
          <tr class="posledny-padding" style="margin-top:10px;">
          <td width="15%">web:</td>
          <td width="85%"><?php echo $web_dodavatel; ?></td>  
          </tr>
          
          
          <tr class="posledny-padding">
          <td></td>
          <td></td>  
          </tr>
          
          <tr class="posledny-padding" style="margin-top:10px;">
          <td width="15%">IBAN:</td>
          <td width="85%"><?php echo $iban_dodavatel; ?></td>  
          </tr>
          <tr class="posledny-padding" >
          <td width="15%">VS:</td>
          <td width="85%"><?php echo $vs_dodavatel; ?></td>  
          </tr>
          <tr class="posledny-padding">
          <td width="15%">KŠ:</td>
          <td width="80%"><?php echo $sp_dodavatel; ?></td>  
          </tr>
          
          <tr class="posledny-padding" style="line-height:30px;">
          <td></td>
          <td></td>  
          </tr>

          
          
          
         </table>
        </td> <!--tabulka-dodavatel-->
        
        
        <td class="tabulka-odberatel">
        <table class="tabulka-podradena">
        
         <tr class="posledny-padding" style="line-height:10px;">
          <td></td>
          <td></td>  
          </tr>
        
          <tr>
          <td><strong class="w-100">Odberateľ</strong></td>
          </tr>


         <tr class="posledny-padding" style="line-height:10px;">
          <td></td>
          <td></td>  
          </tr>
          
          
           <tr>
          <td><?php echo $nazov_klient_final; ?></td>
          <td>IČO: <?php echo $ico_klient_final; ?></td>
          </tr>
          <tr>
          <td><?php echo $ulica_klient_final; ?> <?php echo $cislo_klient_final; ?></td>
          <td>DIČ: <?php echo $dic_klient_final; ?></td>
          </tr>
          <tr>
          <td><?php echo $psc_klient_final; ?> <?php echo $mesto_klient_final; ?></td>
          <td>IČ DPH: <?php echo $ic_dph_klient_final; ?></td>
          </tr>
          
          
          <tr>
          <td>_</td>
          <td>_</td> 
          </tr>
          
          
          
          
          
          <tr class="posledny-padding" style="margin-top:30px;">
          <td>Dátum vystavenia</td>
          <td><?php echo $datum_vystavenia; ?></td>  
          </tr>
          <tr class="posledny-padding" >
          <td>Dátum dodania</td>
          <td><?php echo $datum_dodania; ?></td>  
          </tr>
          <tr class="posledny-padding">
          <td>Dátum splatnosti</td>
          <td><?php echo $datum_splatnosti; ?></td>  
          </tr>
          
          <tr class="posledny-padding" style="line-height:30px;">
          <td></td>
          <td></td>  
          </tr>

          
         </table>
        </td> <!--tabulka-dodavatel-->
    
        </tr> <!--Hlavicka koniec-->
        
        
        
        <tr> <!--Obsah Zaciatok-->
        
     
         
        <td class="nacenovanie-tabulky">  <!--nacenovanie-tabulky-zaciatok-->
        
           <table class="tabulka-nacenovanie">
            
          <tr style="line-height:40px;text-align:center;">
          <td  width="60%" style="border-bottom:solid 1px #000;line-height:20px;text-align:left;"><i>Názov položky</i></td> 
          <td width="19%" style="border-bottom:solid 1px #000;">Hodiny</td>
          <td width="19%" style="border-bottom:solid 1px #000;"><i>Suma</i></td>
          </tr>
          
          
        <?php 
        foreach ($nazovPolozky as $index => $nazov) {
    $nazovPolozky_postupne = $nazov;
    $casPolozky_postupne = $casPolozky[$index];
    $sumaPolozky_postupne = $sumaPolozky[$index];



        ?>
        
        <tr style="line-height:40px;text-align:center;">
          <td  width="60%" style="border-bottom:solid 1px #000;line-height:20px;text-align:left;"><i><?php echo $nazovPolozky_postupne; ?></i></td> 
          <td width="19%" style="border-bottom:solid 1px #000;"><?php echo $casPolozky_postupne; ?> hod.</td>
          <td width="19%" style="border-bottom:solid 1px #000;"><i><?php echo $sumaPolozky_postupne; ?> €</i></td>
          </tr>      
       <?php  
       } 
       ?>

          
           <tr class="riadok-oddelovac">
          <td style="text-align:left;"></td>
          <td style="text-align:left;"></td> 
          <td style="text-align:right;"></td> 
          </tr>
          

          
          
          <tr class="riadok-oddelovac">
          <td style="text-align:left;"></td>
          <td style="text-align:left;"></td>
          <td style="text-align:right;"></td>
          </tr>
          
          </table>
          
          <table class="tabulka-nacenovanie">
          
          <tr>
          <td width="50%"></td>
          <td width="48%">
          
          
          <table class="final-tabulka-farebna" cellpadding="15">
          
          
          <tr class="riadok-parametre-4" style="line-height:0px;">
          <td width="5%"></td>
          <td width="50%" style="padding: 0px; border-bottom:solid 1px #fff;">Spolu bez DPH</td>
          <td width="40%" style="text-align:left; border-bottom:solid 1px #fff; padding: 0px"><?php echo $suma_spolu; ?> €</td>
          <td width="5%"></td>
          </tr>
          
          <tr class="riadok-parametre-4" style="line-height:0px;">
          <td width="5%"></td>
          <td width="50%" style="text-align:left; border-bottom:solid 1px #fff; padding: 0px">DPH</td>
          <td width="40%" style="text-align:left; border-bottom:solid 1px #fff; padding: 0px">20 €</td>
          <td width="5%"></td>
          </tr>
          

          
          <tr class="riadok-parametre-4" style="line-height:0px;">
          <td width="5%"></td>
          <td width="50%" style="text-align:left; padding: 0px"><strong>Spolu s DPH</strong></td>
          <td width="40%" style="text-align:left; padding: 0px"><strong>50 €</strong></td>
          <td width="5%"></td>
          </tr>
          
          </table>
          
          
          </td>
          </tr>
          

          <tr class="riadok-oddelovac">
          <td style="text-align:left;"></td>
          <td style="text-align:left;"></td>
          <td style="text-align:right;"></td>
          </tr>
          
         
          
         </table>
        
        
        </td> <!--nacenovanie-tabulky-koniec-->
         
         
        
        
        </tr> <!--Obsah koniec-->
        
        
        <tr> <!--pata zaciatok-->
         <td> 
        <table class="tabulka-pata">
          <tr style="line-height:30px;">
          <td style="text-align:left;">'.$preklad_vystavil.$prezyvka .'</td>
          <td style="text-align:right;"></td>
          </tr>

          
        </table>
         </td> 
        </tr> <!--pata zaciatok-->
        
        
</table>


<table class="hlavicka-tabulky w-100" style="width:100%;text-align:right;">
<tr>
<td width="50%" style="text-align:left;font-size:14px;height:25px;"></td>
<td width="50%" style="text-align:left;font-size:14px;height:25px;"><strong>Vystavil Janko Hraško</strong></td>
</tr>
<tr>
<td width="50%" style="text-align:left;font-size:14px;height:25px;"></td>
<td width="50%" style="text-align:left;font-size:14px;height:25px;"><img src="http://localhost/prepravnika/wp-content/themes/pravo/assets/images/blog/about-widget.jpg"></td>
</tr>

</table>


</body>
</html>

<?php
return ob_get_clean();
}
?>
