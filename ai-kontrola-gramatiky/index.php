<?php 
/*
Plugin Name: Kontrola gramatiky AI
Plugin URI: https://example.com/kontrola-gramatiky-ai
Description: Tento plugin využíva umelú inteligenciu na kontrolu gramatiky vo WordPress.
Version: 1.0
Author: Igor Majan
Author URI: https://example.com/about-igor-majan
License: GPL2
*/


//Testovanie ChatGTP na kontrolu gramatiky - Chat gramtiku skontroluje, ale často mení celé slová, aj v prípade zadania  len na opravi čiarok a hrubiek. 

?>


<form action="" method="post" enctype="multipart/form-data" class="w-100 d-block float-right mb-5">
    Zadaj text na kontrolu gramatiky:<br>
    <textarea class="w-100 d-block float-left" name="textToCheck" rows="5" cols="40" placeholder="Sem zadajte text, ktorý si želáte skontrolovať."></textarea><br><br>
    <input type="submit" value="Odoslať" class="tlacidlo-nove float-right mt-2">
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["textToCheck"])) {
    $api_key = 'sem-vlozte';  // Sem vlozte api klúč z OpenAI
    $endpoint = 'https://api.openai.com/v1/chat/completions';


    $text_to_check = removeLineBreaks($_POST["textToCheck"]);

    $data = array(
        'model' => 'gpt-3.5-turbo',
        'messages' => array(
            array('role' => 'system', 'content' => 'V texte skontroluj gramatické chyby ako napríklad meké a tvrdé i/y, diakritiku, čiarky a bodky. Ostatné chyby neopravuj. '),
            array('role' => 'user', 'content' => $text_to_check)
        ),
        'temperature' => 0.2,
        'presence_penalty' => 0.8,
    );

    $ch = curl_init($endpoint);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Authorization: Bearer ' . $api_key
    ));

    $response = curl_exec($ch);

    if ($response === FALSE) {
        die('Nepodarilo sa pripojiť k OpenAI API');
    }

    curl_close($ch);

    $result = json_decode($response, true);
    print_r($result);
    $generated_text = $result['choices'][0]['message']['content'];

    echo "<h2>Výsledok:</h2>";

    
    
     $text1 = $text_to_check;
    $text2 = $generated_text;



    // Zvýraznenie rozdielov
    $highlightedText1 = highlightDifferences($text1, $text2);
    $highlightedText2 = highlightDifferences($text2, $text1);

?>


<div class="w-100 d-block float-left">

<div class="povodny-text w-50 d-inline-block float-left">
<h3>Pôvodný text</h3>
<?php echo "<p>$highlightedText1</p>"; ?>
</div>

<div class="opraveny-text w-50 d-inline-block float-left">
<h3>Upravený text</h3>
<?php echo "<p>$highlightedText2</p>"; ?>
</div>

</div>


<?php


}


function removeLineBreaks($inputText) {
    return str_replace(array("\r\n", "\r", "\n"), ' ', $inputText);
}


function highlightDifferences($text1, $text2) {
    // Rozdelenie textov na slová
    $words1 = explode(' ', $text1);
    $words2 = explode(' ', $text2);

    // Získanie zoznamu opravených slov s čiarkou
    $correctedWordsWithComma = array_filter($words1, function($word) use ($words2) {
        return !in_array($word, $words2) && strpos($word, ',') !== false;
    });

    // Získanie zoznamu opravených slov bez čiarky
    $correctedWordsWithoutComma = array_filter($words1, function($word) use ($words2) {
        return !in_array($word, $words2) && strpos($word, ',') === false;
    });

    // Zvýraznenie opravených slov s čiarkou
    $highlightedText = '';
    foreach ($words1 as $word) {
        if (in_array($word, $correctedWordsWithComma)) {
            $highlightedText .= "<span style='background-color: green;'>$word</span> ";
        } elseif (in_array($word, $correctedWordsWithoutComma)) {
            $highlightedText .= "<span style='background-color: purple;'>$word</span> ";
        } else {
            $highlightedText .= "$word ";
        }
    }

    return rtrim($highlightedText);
}

?>
