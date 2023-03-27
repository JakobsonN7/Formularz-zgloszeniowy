<?php
session_start();
require_once "connect.php";
extract($_REQUEST);


//sprawdzenie, czy połączenie się udało
if (!$db) {
  die("Połączenie nieudane: " . mysqli_connect_error());
}
    //pobranie danych z formularza
    $imie = $_POST['inname'];
    $nazwisko = $_POST['surname'];
    $email = $_POST['mail'];
    $wyksztalcenie = $_POST['education'];
    $lm = $_FILES['zalacznik1']['tmp_name'];
    $cv1 = $_FILES['zalacznik2']['tmp_name'];
    $cv2bool = false;
    if(isset($_FILES['zalacznik3']['tmp_name'])){
        $cv2 = $_FILES['zalacznik3']['tmp_name'];
        $cv2bool = true;
    }else{
        $cv2 = false;
    }
    
    //sprawdzenie, czy wszystkie pola zostały wypełnione
    if(empty($imie) || empty($nazwisko) || empty($email) || empty($wyksztalcenie) || (empty($cv1) XOR $cv2)) {
        echo "Proszę wypełnić wszystkie pola formularza.";
        var_dump($cv2bool);
    }
    else {
        //sprawdzenie, czy email jest poprawny
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Proszę podać poprawny adres e-mail.";
            var_dump($cv2bool);
        }
        else {
            //sprawdzenie, czy pliki zostały poprawnie przesłane
            $allowed = array('.jpg', '.pdf', '.doc');
            $ext_lm = pathinfo($lm, PATHINFO_EXTENSION);
            $ext_cv1 = pathinfo($cv1, PATHINFO_EXTENSION);
            $ext_cv2 = pathinfo($cv2, PATHINFO_EXTENSION);
            if(in_array($ext_lm, $allowed) || in_array($ext_cv1, $allowed) || in_array($ext_cv2, $allowed)) {
                echo "Niepoprawny format pliku. Dozwolone formaty to: JPG, PDF, DOC.";
            }
            else {
                //zapisanie danych do bazy danych
                $sql = "INSERT INTO appli (name, surname, mail, education)
                VALUES ('$imie', '$nazwisko', '$email', '$wyksztalcenie')";
                if(mysqli_query($db, $sql)) {                
                    //przypisanie id_appi od tego id->update
                    $appli_id = mysqli_insert_id($db);

                    // zapisujemy lm w bazie
                    $binaryDatalm = file_get_contents($lm);
                    $sqllm = "UPDATE appli SET lm = ? WHERE appli_id= ?";
                    $stmtlm = mysqli_prepare($db, $sqllm);
                    mysqli_stmt_bind_param($stmtlm, "sb", $binaryDatalm, $appli_id);
                    mysqli_stmt_execute($stmtlm);
                    mysqli_stmt_store_result($stmtlm);       

                    // zapisujemy cv1 w bazie
                    $binaryDatacv1 = file_get_contents($cv1);
                    $sqlcv1 = "INSERT INTO cv (id_appli, content) VALUES (?, ?)";
                    $stmtcv1 = mysqli_prepare($db, $sqlcv1);
                    mysqli_stmt_bind_param($stmtcv1, "sb", $appli_id, $binaryDatacv1);
                    mysqli_stmt_execute($stmtcv1);
                    mysqli_stmt_store_result($stmtcv1);

                    // zapisujemy cv2 w bazie jesli istnieje
                    if($cv2bool==true){
                        $binaryDatacv2 = file_get_contents($cv2);   
                        $sqlcv2 = "INSERT INTO cv (id_appli, content) VALUES (?, ?)";
                        $stmtcv2 = mysqli_prepare($db, $sqlcv2);
                        mysqli_stmt_bind_param($stmtcv2, "sb", $appli_id, $binaryDatacv2);
                        mysqli_stmt_execute($stmtcv2);
                        mysqli_stmt_store_result($stmtcv2);
                    }

                    // zapisujemy staz w bazie jesli istnieja
                    if(isset($_POST['zmienna'])){
                        $zmienna = $_POST['zmienna'];
                        for($i = 1; $i <= $zmienna; $i++){
                        $nazwaFirmy = $_POST['nazwa-firmy-'.$i];
                        $starter = $_POST['start-'.$i];
                        $ender = $_POST['end-'.$i];
                        var_dump($nazwaFirmy.$starter.$ender);
                        $zapytanie = mysqli_query($db, 
                        "INSERT INTO intern (appli_id, nazwa_firmy, starter, ender) VALUES ('$appli_id', '$nazwaFirmy', '$starter', '$ender')"
                        );
                        $update=$db->query($zapytanie) or die ('Błąd dodania artykułu. '.$db->error);
                    }

                    }
                    echo "Dziękujemy za skorzystanie z formularza.";
                }
                else {
                    echo "Błąd: " . $sql . "<br>" . mysqli_error($db);
                }
                
            } 
        }        
    }
    mysql_close($db);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
</body>
</html>