<?php
//membuat zona waktu jadi WIB
date_default_timezone_set('Asia/Jakarta');

//mengambil path
$directory = new DirectoryIterator(dirname(__FILE__)); 
$directoryPath = $directory->getPath();
if(strtolower($directoryPath[0]) !== '/') { //untuk database lokal
    $directoryPath = str_replace("\\", "\\\\", $directoryPath);
    $directoryPath .= "\\\\";
    $phPassword = "";
    $phUsername = "root";
    $phHost = "localhost";
    $phDatabase = "phkematian";
} else { //untuk database online
    $directoryPath .= "/";
    $phPassword = "aITkeptflow3";
    $phUsername = "if0_34962067";
    $phHost = "sql209.infinityfree.com";
    $phDatabase = "if0_34962067_phkematian";
}
//connect dengan database sql server
$conn = mysqli_connect($phHost, $phUsername, $phPassword, $phDatabase);


// function untuk membuat script dengan cepat
function script($script) {
    echo "
<script>
    $script;
</script>
    ";
}

// function untuk redirect ke halaman lain
function jumpTo($destination) {
    script('window.location.href = "'.$destination.'";');
}

// function untuk menampilkan alert
function alert($alert) {
    echo "
<script>
    window.alert('$alert')
</script>
";}

// function mengambil data yang banyak
function query($query) {
    global $conn;
    $result = mysqli_query($conn, $query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

// function untuk menampilkan tanggal dalam Tahun-bulan-hari
function dateNow() {
    return date('Y-m-d');
}

function verifyPassword($password, $confirmPassword) {
    if(!password_verify($password, $confirmPassword)) {
        alert('Wrong Password');
        return false;
    }
    return true;
}


//function untuk login
function login($post) {
    global $conn;
    $username = $post['username'];
    $password = $post['password'];

    $query = "SELECT password, id FROM account WHERE username = '$username';";
    $results = mysqli_fetch_assoc(mysqli_query($conn, $query));
    if($results == NULL) {
        alert('Not Found');
        return false;
    }

    $truePassword = $results['password'];
    if(!verifyPassword($password, $truePassword)) {
        alert('Wrong Password');
        return false;
    }

    $loginId = $results['id'];

    session_start();

    if(strlen($loginId) == 7) {
        $_SESSION['type'] = 'outlet';
    } else {
        $_SESSION['type'] = 'hod';
    }
    $_SESSION['loginId'] = $loginId;
    return true;
}

function getOutletDistrict() {
    $query = "SELECT DISTINCT district FROM outlet";
    return query($query);
}

function getOutletArea($district) {
    $query = "SELECT DISTINCT area FROM outlet WHERE district = '$district'";
    return query($query);
}

function getOutlet($district = "", $area = "") {
    if($district == "" || $district == "all") {
        $query = "SELECT * FROM outlet";
    } else if($area == "all") {
        $query = "SELECT * FROM outlet WHERE district = '$district'";
    } else {
        $query = "SELECT * FROM outlet WHERE district = '$district' AND area = '$area'";
    }
    return query($query);
}

function getSurvey($district = "", $area = "") {
    if($district == "" || $district == "all") {
        $query = "SELECT * FROM survey";
    } else if($area == "all") {
        $query = "SELECT * FROM survey WHERE district = '$district'";
    } else {
        $query = "SELECT * FROM survey WHERE district = '$district' AND area = '$area'";
    }
    return query($query);
}



// function untuk lupa password
function forgetPassword($post) {
    session_start();
    global $conn;
    $temp = $post['username'];

    $query = "SELECT password, email FROM account WHERE username = '$temp' OR email = '$temp'";
    $result = mysqli_fetch_assoc(mysqli_query($conn, $query));
    if(!$result) {
        alert("Username/Email mu belum terdaftar. Daftar dulu ya :)");
        return false;
    }
    $email = $result['email'];
    $password = $result['password'];
    $code = strval(rand(100000,999999));
    $_SESSION['code'] = $code;
    $_SESSION['email'] = $email;
    $emailDomain = stristr($email, '@');
    $usernameLen = strlen($email) - strlen($emailDomain);
    $censoredEmail = "";
    for ($i = 0; $i < $usernameLen - 2; $i++) {
        $censoredEmail .= "*";
    }
    $_SESSION['privateEmail'] = $email[0].$email[1].$censoredEmail.stristr($email, '@');

    if(sendEmail($email, "Kode Lupa Password", "Kodenya: <b>$code</b>. Pakai yang gampang diingat aja kek tanggal lahir kalau emang pikun") == true) {
        return true;
    }
}

// function untuk mengambil data dari database
function fetch($request, $table = 'account', $id = false) {
    global $conn;
    if ($id === false) {
        session_start();
        $id = $_SESSION['loginId'];
        session_abort();
    }
    $query = "SELECT $request FROM $table WHERE id = '$id'";
    $result = mysqli_fetch_assoc(mysqli_query($conn, $query));
    $result = $result["$request"];
    return $result;
}

//function logout menghapus semua session
function logout() {
    session_unset();
}

//function nama hari dalam bahasa Indonesia
function dateMonth($date) {
    $month = $date[5].$date[6];
    $name = NULL;
    switch ($month) {
        case '01':
            $name = 'Januari';
            break;
        case '02':
            $name = 'Februari';
            break;
        case '03':
            $name = 'Maret';
            break;
        case '04':
            $name = 'April';
            break;
        case '05':
            $name = 'Mei';
            break;
        case '06':
            $name = 'Juni';
            break;
        case '07':
            $name = 'Juli';
            break;
        case '08':
            $name = 'Agustus';
            break;
        case '09':
            $name = 'September';
            break;
        case '10':
            $name = 'Oktober';
            break;
        case '11':
            $name = 'November';
            break;
        case '12':
            $name = 'December';
            break;
        }
    return $name;
}

//fungsi mengambil tahun dari suatu tanggal
function dateYear($date) {
    return $date[0].$date[1].$date[2].$date[3];
}

//fungsi menampilkan tanggal dalam format mm, $b yyyy
function showDate($date) {
    $dateDate = dateDate($date);
    $dateMonth = dateMonth($date);
    $dateYear = dateYear($date);
    if($dateDate[0] == '0') {
        $dateDate = $dateDate[1];
    }
    echo $dateDate.' '.$dateMonth.' '.$dateYear;
}

//funcgsi membuat titik setiap ribuan pada uang
function money($money) {
    return number_format($money, 0, ',', '.');
}

//fungsi membatasi digit di belakang koma
function percentage($percentage) {
    return number_format($percentage, 2, ',', '.');
}

//fungsi untuk menjumlahkan nilai dari suatu class
function listSpending($list, $db) {
    global $conn;
    $today = dateNow();
    $dateMonth = dateMonth($today);
    $result = [];
    foreach($list as $item) {
        $query = "SELECT * FROM $db WHERE class='spending' AND username='$item'";
        $values = query($query);
        $total = 0;
        foreach ($values as $value) {
            if(dateMonth($value['date']) == $dateMonth) {
                $total += (int) $value['value'];
            }
        }
        array_push($result, $total);
    }
    return $result;
}

//fungsi menghitung pendapatan total dengan parameter nama table user
function totalIncome($db) {
    global $conn;
    $today = dateNow();
    $query = "SELECT * FROM $db WHERE class='income'
        AND MONTH(date) = MONTH(CURRENT_DATE()) 
        AND YEAR(date) = YEAR(CURRENT_DATE())";
    $values = query($query);
    $total = 0;
    foreach ($values as $value) {
        $total += (int) $value['value'];
    }
    return $total;
}

//fungsi menghitung pengeluaran total dengan parameter nama table user
function totalSpending($db) {
    global $conn;
    $today = dateNow();
    $query = "SELECT * FROM $db WHERE class='spending'
        AND MONTH(date) = MONTH(CURRENT_DATE()) 
        AND YEAR(date) = YEAR(CURRENT_DATE())";
    $values = query($query);
    $total = 0;
    foreach ($values as $value) {
        $total += (int) $value['value'];
    }
    return $total;
}

//fungsi menghitung pendapatan tambahan dengan parameter nama table user
function additionalIncome($db) {
    global $conn;
    $today = dateNow();
    $query = "SELECT * FROM $db WHERE class='income' AND category='additional' AND username='additional'
        AND MONTH(date) = MONTH(CURRENT_DATE()) 
        AND YEAR(date) = YEAR(CURRENT_DATE())";
    $values = query($query);
    $total = 0;
    foreach ($values as $value) {
        $total += (int) $value['value'];
    }
    return $total;
}

//fungsi menghitung pendapatan rutin dengan parameter nama table user
function routineIncome($db) {
    global $conn;
    $today = dateNow();
    $query = "SELECT * FROM $db WHERE class='income' AND category='routine' AND username='routine'
        AND MONTH(date) = MONTH(CURRENT_DATE()) 
        AND YEAR(date) = YEAR(CURRENT_DATE())";
    $values = query($query);
    $total = 0;
    foreach ($values as $value) {
        $total += (int) $value['value'];
    }
    return $total;
}

//fungsi menghitung kebutuhan dengan parameter nama table user
function needsSpending($db) {
    global $conn;
    $today = dateNow();
    $query = "SELECT * FROM $db WHERE class='spending' AND category='needs'
        AND MONTH(date) = MONTH(CURRENT_DATE()) 
        AND YEAR(date) = YEAR(CURRENT_DATE())";
    $values = query($query);
    $total = 0;
    foreach ($values as $value) {
        $total += (int) $value['value'];
    }
    return $total;
}

//fungsi menghitung prioritas dengan parameter nama table user
function prioritySpending($db) {
    global $conn;
    $today = dateNow();
    $query = "SELECT * FROM $db WHERE class='spending' AND category='priority'
        AND MONTH(date) = MONTH(CURRENT_DATE()) 
        AND YEAR(date) = YEAR(CURRENT_DATE())";
    $values = query($query);
    $total = 0;
    foreach ($values as $value) {
        $total += (int) $value['value'];
    }
    return $total;
}

//fungsi menghitung keinginan dengan parameter nama table user
function wantsSpending($db) {
    global $conn;
    $today = dateNow();
    $query = "SELECT * FROM $db WHERE class='spending' AND category='wants'
        AND MONTH(date) = MONTH(CURRENT_DATE()) 
        AND YEAR(date) = YEAR(CURRENT_DATE())";
    $values = query($query);
    $total = 0;
    foreach ($values as $value) {
        $total += (int) $value['value'];
    }
    return $total;
}

//fungsi menghitung pengeluaran harian dengan parameter nama table user
function dailySpending($db) {
    global $conn;
    $query = "SELECT * FROM $db 
        WHERE MONTH(date) = MONTH(CURRENT_DATE()) 
        AND YEAR(date) = YEAR(CURRENT_DATE()) ORDER BY date";
    $totalDay = query($query);
    if($totalDay != NULL) {
        $firstDate = (int) dateDate(reset($totalDay)['date']);
        $lastDate = (int) dateDate(end($totalDay)['date']);
    } else {
        $firstDate = 0;
        $lastDate = 0;
    }
    $totalDay = $lastDate + 1 - $firstDate;
    $query = "SELECT * FROM $db 
        WHERE MONTH(date) = MONTH(CURRENT_DATE()) 
        AND YEAR(date) = YEAR(CURRENT_DATE()) 
        AND category = 'priority' ORDER BY date";
    $result = query($query);
    $priorityCost = 0;
    foreach ($result as $place) {
        $priorityCost += (int) $place['value'];
    }
    $result = (totalSpending($db) - $priorityCost) / $totalDay;
    return round($result);
}   

//function mengambil tiap kategori
function categoryList($identifier, $value) {
    global $conn;
    $query = "SELECT * FROM flow WHERE $identifier = '$value'";
    $result = query($query);
    return $result;
}

//function untuk mengambil detil
function listItem($identifier, $value, $info) {
    $tempList = array();
    $tempCategories = categoryList($identifier, $value);
    foreach ($tempCategories as $category) {
        if(!in_array($category[$info], $tempList)) {
            array_push($tempList, $category[$info]);
        }
    }
    return $tempList;
}

//function untuk mengubah nama
function changeName($post) {
    global $conn;
    $id = fetch('id');
    $name = $post['name'];
    $query = "UPDATE account SET name = '$name' WHERE id = $id";
    mysqli_query($conn, $query);
    if(mysqli_affected_rows($conn) <= 0) {
        alert('Error "k-04". Kami sangat menghargai jika kamu melaporkan bug ini');
        return false;
    }
    return true;
}

//function untuk mengubah nama panggilan
function changeNickname($post) {
    global $conn;
    $id = fetch('id');
    $nickname = $post['nickname'];
    $query = "UPDATE account SET nickname = '$nickname' WHERE id = $id";
    mysqli_query($conn, $query);
    if(mysqli_affected_rows($conn) <= 0) {
        alert('Error "k-05". Kami sangat menghargai jika kamu melaporkan bug ini');
        return false;
    }
    return true;
}

//function untuk mengubah nama panggilan
function changeUsername($post) {
    global $conn;
    $id = fetch('id');
    $oldUsername = fetch('username');
    $oldTable = $oldUsername.'_keep';
    $username = strtolower(stripslashes($post['username']));
    $query = "UPDATE account SET username = '$username' WHERE id = $id";
    mysqli_query($conn, $query);
    if(mysqli_affected_rows($conn) <= 0) {
        alert('Error "k-06". Kami sangat menghargai jika kamu melaporkan bug ini');
        return false;
    }

    $newTable = $username.'_keep';
    $query = "RENAME TABLE $oldTable TO $newTable";
    keepConn();
    if(!mysqli_query($conn, $query)){
        alert('Error "k-07". Kami sangat menghargai jika kamu melaporkan bug ini');
        keptConn();
        return false;
    }
    keptConn();
    $query = "UPDATE report SET username = '$username' WHERE username = '$oldUsername'";
    if(!mysqli_query($conn, $query)) {
        alert('Error "k-17". Kami sangat menghargai jika kamu melaporkan bug ini');
        return false;
    }
    return true;
}

//function untuk mengubah bio
function changeBio($post) {
    global $conn;
    $id = fetch('id');
    $bio = $post['bio'];
    $query = "UPDATE account SET bio = '$bio' WHERE id = $id";
    mysqli_query($conn, $query);
    if(mysqli_affected_rows($conn) <= 0) {
        alert('Error "k-08". Kami sangat menghargai jika kamu melaporkan bug ini');
        return false;
    }
    return true;
}

//function untuk mengganti password
function changePassword($newPassword) {
    global $conn;
    $newPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    $id = fetch('id');

    $query = "UPDATE account SET password = '$newPassword' WHERE id = $id";
    mysqli_query($conn, $query);
    if(mysqli_affected_rows($conn) <= 0) {
        alert('Error "k-09". Kami sangat menghargai jika kamu melaporkan bug ini');
        return false;
    }
    return true;
}

//function untuk mengganti email
function changeEmail($newEmail) {
    global $conn;
    $id = fetch('id');

    $query = "UPDATE account SET email = '$newEmail' WHERE id = $id";
    mysqli_query($conn, $query);
    if(mysqli_affected_rows($conn) <= 0) {
        alert('Error "k-10". Kami sangat menghargai jika kamu melaporkan bug ini');
        return false;
    }
    return true;
}

//function untuk memasukkan transaksi
function insertKeep($post) {
    global $conn;
    $date = $post['date'];
    if($post['input-isincome'] == 'true'){
        $class = "income";
    } else {
        $class = "spending";
    }
    $username = $post['input-class'];
    $query = "SELECT category, name FROM flow WHERE username = '$username'";
    $result = mysqli_fetch_assoc(mysqli_query($conn, $query));
    $category = $result['category'];
    $nominal = (int) $post['nominal'];
    if($nominal <= 0) {
        alert('Nominalnya gak valid');
        return false;
    }
    $name = $result['name'];
    $desc = $post['desc'];
    $table = fetch('username').'_keep';
    $query = "INSERT INTO $table VALUES(NULL, '$date', '$class', '$category', '$username', '$name', '$desc', $nominal)";
    keepConn();
    mysqli_query($conn, $query);
    if(mysqli_affected_rows($conn) <= 0) {
        alert('Error "k-11". Kami sangat menghargai jika kamu melaporkan bug ini');
        return false;
    }
    keptConn();
    return true;
}

//function untuk mengganti rencana cash flow
function updatePlan($needs, $wants, $saving) {
    global $conn;
    $id = fetch('id');
    
    $query = "UPDATE account SET needs = $needs WHERE id = $id";
    if(!mysqli_query($conn, $query)) {
        alert('Error "k-12". Kami sangat menghargai jika kamu melaporkan bug ini');
        return false;
    }
    
    $query = "UPDATE account SET wants = $wants WHERE id = $id";
    if(!mysqli_query($conn, $query)) {
        alert('Error "k-13". Kami sangat menghargai jika kamu melaporkan bug ini');
        return false;
    }
    
    $query = "UPDATE account SET saving = $saving WHERE id = $id";
    if(!mysqli_query($conn, $query)) {
        alert('Error "k-14". Kami sangat menghargai jika kamu melaporkan bug ini');
        return false;
    }
    
    $query = "UPDATE account SET new = 0 WHERE id = $id";
    if(!mysqli_query($conn, $query)) {
        alert('Error "k-15". Kami sangat menghargai jika kamu melaporkan bug ini');
        return false;
    }
    
    return true;
}

//fungsi menyapa di samping profil
function greeting() {
    $time = date('H');
    if((int) $time >= 6 AND (int) $time < 12) {
        $greeting = 'Pagi';
    } else if((int) $time >= 12 AND (int) $time < 15) {
        $greeting = 'Siang';
    } else if((int) $time >= 15 AND (int) $time < 18) {
        $greeting = 'Sore';
    } else {
        $greeting = 'Malam';
    }
    return $greeting.', '.fetch('nickname');
}

//funsgi mengirim laporan
function sendReport($type, $text) {
    global $conn;
    $username = fetch('username');
    $email = fetch('email');
    $query = "INSERT INTO report VALUES ('', '$type', '$text', '$username', '$email', NULL)";
    mysqli_query($conn, $query);
    if(mysqli_affected_rows($conn) < 1) {
        alert('Error. Mohon laporkan bug ini :v');
        return false;
    }
    return true;
}

//function mengambil report terjawab
function getAnsweredReport() {
    global $conn;
    $query = "SELECT * FROM report WHERE answer IS NOT NULL ORDER BY type DESC, username";
    return query($query);
}

//function untuk mentranslate jenis laporan
function reportType($type) {
    if($type == 'question') {
        return 'Pertanyaan';
    } else if($type == 'bug') {
        return 'Bug';
    }
    return 'Kritik/Saran';
}

//function untuk menentukan warna bantuan
function reportColor($type) {
    if($type == 'question') {
        return 'info';
    } else if($type == 'bug') {
        return 'danger';
    }
    return 'success';
}

//function mengubah status changed
function changedPlan() {
    global $conn;
    $id = fetch('id');
    $query = "UPDATE account SET changed = 1 WHERE id = $id";
    mysqli_query($conn, $query);
    if(mysqli_affected_rows($conn) < 1) {
        alert('Error "k-15". Kami sangat menghargai jika kamu melaporkan bug ini');
        return false;
    }
    return true;
}

?>