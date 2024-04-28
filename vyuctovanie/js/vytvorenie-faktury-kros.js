var baseurl = window.location.origin;

if (baseurl == 'http://localhost') {
    zakladurl = 'http://localhost/prepravnika';
} else {
    zakladurl = window.location.origin;
}

var ajaxurl = zakladurl + '/wp-admin/admin-ajax.php';




jQuery(document).ready(function($) {


$("#exportovat-vyuctovanie").validate({
         rules: {
            'ico-dodavatel': {
                required: true,
                digits: true,
                minlength: 8,
                maxlength: 8
            },
            'dic-dodavatel': {
                required: true,
                digits: true,
                minlength: 10,
                maxlength: 10
            },
            'ulica-dodavatel': {
                required: true
            },
            'cislo-dodavatel': {
                required: true
            },
            'psc-dodavatel': {
                required: true
            },
            'mesto-dodavatel': {
                required: true
            },
            'iban-dodavatel': {
                required: true,
                minlength: 24,
                maxlength: 24
            },
            'ico-klient-final': {
                required: true,
                digits: true,
                minlength: 8,
                maxlength: 8
            },
            'dic-klient-final': {
                required: true,
                digits: true,
                minlength: 10,
                maxlength: 10
            },
            'ulica-klient-final': {
                required: true
            },
            'cislo-klient-final': {
                required: true
            },
            'psc-klient-final': {
                required: true
            },
            'mesto-klient-final': {
                required: true
            }
        },
        messages: {
            'ico-dodavatel': {
                required: "Toto pole je povinné.",
                digits: "Zadajte, prosím, len čísla.",
                minlength: "Toto pole musí obsahovať presne 8 čísiel.",
                maxlength: "Toto pole musí obsahovať presne 8 čísiel."
            },
            'dic-dodavatel': {
                required: "Toto pole je povinné.",
                digits: "Zadajte, prosím, len čísla.",
                minlength: "Toto pole musí obsahovať presne 10 čísiel.",
                maxlength: "Toto pole musí obsahovať presne 10 čísiel."
            },
            'ulica-dodavatel': {
                required: "Toto pole je povinné."
            },
            'cislo-dodavatel': {
                required: "Toto pole je povinné."
            },
            'psc-dodavatel': {
                required: "Toto pole je povinné."
            },
            'mesto-dodavatel': {
                required: "Toto pole je povinné."
            },
            'iban-dodavatel': {
                required: "Toto pole je povinné.",
                minlength: "Toto pole musí obsahovať presne 24 znakov.",
                maxlength: "Toto pole musí obsahovať presne 24 znakov."
            },
            'ico-klient-final': {
                required: "Toto pole je povinné.",
                digits: "Zadajte, prosím, len čísla.",
                minlength: "Toto pole musí obsahovať presne 8 čísiel.",
                maxlength: "Toto pole musí obsahovať presne 8 čísiel."
            },
            'dic-klient-final': {
                required: "Toto pole je povinné.",
                digits: "Zadajte, prosím, len čísla.",
                minlength: "Toto pole musí obsahovať presne 10 čísiel.",
                maxlength: "Toto pole musí obsahovať presne 10 čísiel."
            },
            'ulica-klient-final': {
                required: "Toto pole je povinné."
            },
            'cislo-klient-final': {
                required: "Toto pole je povinné."
            },
            'psc-klient-final': {
                required: "Toto pole je povinné."
            },
            'mesto-klient-final': {
                required: "Toto pole je povinné."
            }
        }
    });



    $('#btnVytvorFakturuKros').on('click', function(e) {
       e.preventDefault(); 
       if($("#exportovat-vyuctovanie").valid()) { 
        
        var formData = {
            nazov_spolecnosti_dpdavatel: $('#nazov-spolocnosti-dpdavatel').text(),
            ulica_dodavatel: $('#ulica-dodavatel').val(),
            cislo_dodavatel: $('#cislo-dodavatel').val(),
            psc_dodavatel: $('#psc-dodavatel').val(),
            mesto_dodavatel: $('#mesto-dodavatel').val(),
            ico_dodavatel: $('#ico-dodavatel').val(),
            dic_dodavatel: $('#dic-dodavatel').val(),
            ic_dph_dodavatel: $('#ic-dph-dodavatel').val(),
            iban_dodavatel: $('#iban-dodavatel').val(),
            vs_dodavatel: $('#vs-dodavatel').val(),
            sp_dodavatel: $('#sp-dodavatel').val(),
            email_dodavatel: $('#e-mail-dodavatel').val(),
            telefon_dodavatel: $('#telefon-dodavatel').val(),
            web_dodavatel: $('#web-dodavatel').val(),
            nazov_klient_final: $('#nazov-klient-final').val(),
            ulica_klient_final: $('#ulica-klient-final').val(),
            cislo_klient_final: $('#cislo-klient-final').val(),
            psc_klient_final: $('#psc-klient-final').val(),
            mesto_klient_final: $('#mesto-klient-final').val(),
            ico_klient_final: $('#ico-klient-final').val(),
            dic_klient_final: $('#dic-klient-final').val(),
            ic_dph_klient_final: $('#ic-dph-klient-final').val(),
            datum_vystavenia: $('#datum-vystavenia').val(),
            datum_dodania: $('#datum-dodania').val(),
            datum_splatnosti: $('#datum-splatnosti').val(),
            // Další pole a data podle potřeby
        };
        
        var noveRadkyData = [];
        
        $('.moja-tabulka tbody tr').each(function(index) {
            var novyRadekData = {
                nazov_polozky: $(this).find('[name="nazov-polozky"]').val(),
                cas_polozky: $(this).find('[name="cas-polozky"]').val(),
                suma_polozky: $(this).find('[name="suma-polozky"]').val()
            };
            noveRadkyData.push(novyRadekData);
        });
        
         formData['tabulka'] = noveRadkyData;
        


        var postData = {
            action: 'vytvorenie_faktury_kros',
            data: formData // Konvertujeme data na JSON
        };

        $.ajax({
    url: ajaxurl,
    type: 'POST',
    data: postData,
    success: function(response) {
    
    console.log(response);
        if (!response.success) {
              $(".error-message, .error-details").remove();
            // Zpracování chyby
            var message = response.data.message;
            var details = response.data.details;

            var errorHtml = "<div class='error-message'>" + message + "</div>";
            if (details && details.errors && details.errors.length > 0) {
                errorHtml += "<div class='error-details alert alert-danger'>";
                details.errors.forEach(function(err) {
                    errorHtml += "<p>Error: " + err.errorMessage + "</p>";
                });
                errorHtml += "</div>";
            }

            $('#zobrazenie-chyb').html(errorHtml);
        } else {
                $(".error-message, .error-details").remove();
                okHtml = "<div class='error-details alert alert-success'>";
                
                okHtml += "<p>Faktúra bola vygenerovaná.</p>";
               
                okHtml += "</div>";
                $('#zobrazenie-chyb').html(okHtml);
        }
    },
    error: function(xhr, status, error) {
        // Zpracování jiných chyb AJAX
        console.log("Chyba AJAX: ", error);
        $('#zobrazenie-chyb').html("<div class='error-message'>Nastala neznámá chyba AJAX.</div>");
    }
});

}

    });
});
