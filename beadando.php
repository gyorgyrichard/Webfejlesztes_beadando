<?php

$conn =  new mysqli("localhost","root","","adatok");
if ($conn->connect_errno) {
    die("Conenction failed: " . $conn->connect_error);
}
if (isset($_POST['password']) != "") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $file = fopen("password.txt", 'r');
    $fsize = filesize("password.txt");
    $line = fread($file, $fsize);
    $dobhato = array();
    $b = "";
    for ($i = 0; $i < strlen($line); $i++) {
        if (str_pad(dechex(ord($line[$i])), 2, '0', STR_PAD_LEFT) == '0a') {
            array_push($dobhato, $b);
            $b = (string)NULL;
        } else {
            $b .= str_pad(dechex(ord($line[$i])), 2, '0', STR_PAD_LEFT);
        }
    }
    $counter = 0;
    $decode_char = "";
    $decoded_lines = array();
    foreach ($dobhato as $x) {
        $counter = 0;
        for ($i = 0; $i < strlen($x); $i += 2) {
            $hexek = $x[$i] . $x[$i + 1];
            $decode_char = $decode_char . decode($hexek, $counter);
            $counter++;
            
            if ($counter == 5) {
                $counter = 0;
            }
        }
        array_push($decoded_lines, $decode_char);
        $decode_char = (string)null;

        
		
		
    }
	

    $jo_email = False;
    $jo_pw = False;
    $email = array();
    $pw = array();
    foreach ($decoded_lines as $x) {

        split_strings($x);
        /*$filename = 'adat'.".txt";
        if (!file_exists($filename)) {
            $fh = fopen($filename, 'w') or die("Nemlehet létrehozni a fájlt");
        }
        $ret = file_put_contents($filename, $x, FILE_APPEND | LOCK_EX);
        if($ret === false) {
            die('A fájl irása közben hiba lépett fel');
        }
        else {
            echo "Sikeres fájlba írás";
        }*/
    
    
        
		
    }

    for ($i = 0; $i < sizeof($email); $i++)
        if ($_POST['username'] == $email[$i]) {
            $jo_email = True;
            if ($_POST['password'] == $pw[$i]) {
                $jo_pw = True;
            }
        }
    if ($jo_email and $jo_pw) {
        create_form();
        $sql = "select Titkos from tabla where '$username'=Username";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
        }
        switch ($row['Titkos']) {
            case "piros":
                echo "<script>document.getElementById('clr').style.backgroundColor='red';</script>";
                break;
            case "zold":
                echo "<script>document.getElementById('clr').style.backgroundColor='green';</script>";
                break;
            case "sarga":
                echo "<script>document.getElementById('clr').style.backgroundColor='yellow';</script>";
                break;
            case "kek":
                echo "<script>document.getElementById('clr').style.backgroundColor='blue';</script>";
                break;
            case "fekete":
                echo "<script>document.getElementById('clr').style.backgroundColor='black';
                              document.getElementById('t1').style.color='white';
                              document.getElementById('t2').style.color='white';
                              document.getElementById('t3').style.color='white';</script>";
                break;
            case "feher":
                echo "<script>document.getElementById('clr').style.backgroundColor='white';</script>";
                break;
        }

    } elseif ($jo_email) {
        header("Refresh: 3; url=http://police.hu");
        create_form();
        echo "<script>document.getElementById('error_msg').innerText='Hibás jelszó!'</script>";
        exit;
    } elseif (!$jo_email) {
        create_form();
        echo "<script>document.getElementById('error_msg').innerText='Nincs ilyen felhasználó!'</script>";
    }
}
else
{
    create_form();
}



function split_strings($string)
{
    $pieces = explode("*", $string);
    global $email, $pw;
    
    array_push($email, $pieces[0]);
    array_push($pw, $pieces[1]);
   
}

function decode($var, $hanyadik)
{
    if ($hanyadik == 0) {
        $kiir = hexdec($var) - 5;
        return chr($kiir);
    } else if ($hanyadik == 1) {
        $kiir = hexdec($var) + 14;
        return chr($kiir);
    } else if ($hanyadik == 2) {
        $kiir = hexdec($var) - 31;
        return chr($kiir);
    } else if ($hanyadik == 3) {
        $kiir = hexdec($var) + 9;
        return chr($kiir);
    } else {
        $kiir = hexdec($var) - 3;
        return chr($kiir);
    }
}
function create_form(){

echo"<!DOCTYPE html>
<html>
<head>
    <link rel='stylesheet' href='style.css'>
</head>
<style>
body {
    min-height: 100%;
  background-image: url('bg.jpg');
  background-size:cover;
  background-repeat: no-repeat;
  background-attachment: fixed;
}
</style>
<body>
<form action='beadando.php' method='POST'>
<div class='colorbox'></div>

    <div class='box'>
        <div>
            <h1 class='login'>Add meg a bejelentkezési adataidat!</h1>
            <div>
                <h3 class='username'>Felhasználónév</h3>
                <input type='email' name='username' id='imputfield1' >
            </div>
            <div>
                <h3 class='password'>Jelszó</h3>
                <input type='password' name='password' id='imputfield2' >
            </div>
            <div class='button'>
            <input type='submit' id='login_button' value='Bejelentkezés'></input>
            </dev>
        </div>
        <span class='msg' id = 'error_msg'></span>
        <div class ='datas' id='clr'>
                <p id ='t1'>Név: György Richárd </p>
                <p id ='t2'>Neptun kód: MQK493</p>
                <p id ='t3'>Hanyast érdemelnél: 5  </p>
            </div>

    </div>
</form>
</body>
</html>
";
}
?>