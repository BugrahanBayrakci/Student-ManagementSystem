<?php
$serverName = "DESKTOP-HLBI80J\SQLEXPRESS"; // SQL Server adresi ve instance
$database = "OgrenciBilgiSistemi";          // Veritabanı adı
$UID = "sa";                                // SQL Server kullanıcı adı
$pass = "ogrencibilgisistemi123.";                         // Şifre

// Veritabanı ile etkileşimde bulunmak için PDO bağlantısı kuruyoruz
try {
    $pdo = new PDO("sqlsrv:Server=$serverName;Database=$database", $UID, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Bağlantı hatası: " . $e->getMessage());
}

// Ders listesini almak için SQL sorgusu
$sql = "SELECT DersID, DersAdi FROM Dersler"; 
$stmt = $pdo->prepare($sql);
$stmt->execute();

// Dersleri diziye alıyoruz
$dersler = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Öğretim üyelerini almak için SQL sorgusu
$sqlOgretimUyesi = "SELECT OgretimUyesiID, AdSoyad FROM OgretimUyeleri"; 
$stmtOgretimUyesi = $pdo->prepare($sqlOgretimUyesi);
$stmtOgretimUyesi->execute();
$ogretimUyesi = $stmtOgretimUyesi->fetchAll(PDO::FETCH_ASSOC);

// Kullanıcı ID kontrolü
if (isset($_GET['kullaniciId'])) {
    $KullaniciID = $_GET['kullaniciId'];
} else {
    echo "Veri bulunamadı!";
    exit;  // Kullanıcı ID yoksa işlem sonlandırılır
}

// Form gönderildiyse seçilen dersleri ve öğretim üyelerini alıyoruz
$selectedDersler = isset($_POST['dersler']) ? $_POST['dersler'] : [];
$selectedOgretimUyesi = isset($_POST['ogretimUyesi']) ? $_POST['ogretimUyesi'] : [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<h2>Seçilen Dersler ve Öğretim Üyeleri:</h2>";
    echo "<ul>";
    foreach ($selectedDersler as $dersID) {
        echo "<li>Ders ID: " . htmlspecialchars($dersID);
        // Ders ID'sine karşılık öğretim üyesi bilgilerini ekle
        if (isset($selectedOgretimUyesi[$dersID])) {
            echo
            $ogretimUyesiID = $selectedOgretimUyesi[$dersID];
            echo " - Öğretim Üyesi: " . htmlspecialchars($selectedOgretimUyesi[$dersID]);
            $sqlinsert = "INSERT INTO OgrenciDers (OgrenciID, DersID, OgretimUyesiID) VALUES ($KullaniciID, $dersID, $ogretimUyesiID);"; 
            $sqlinsert = $pdo->prepare($sqlinsert);
            $sqlinsert->execute();



        }
        echo "</li>";
    }
    echo "</ul>";
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ders Listesi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e0f7fa; /* Arka plan rengi mavi tonlarında */
            margin: 0;
            padding: 20px;
        }

        h1 {
            color: #0277bd; /* Başlık rengi */
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table th, table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #0288d1; /* Hücre kenarları */
        }

        table th {
            background-color: #0277bd; /* Başlık satırı mavi */
            color: white;
        }

        table tr:nth-child(even) {
            background-color: #b3e5fc; /* Çift satırlar daha açık mavi */
        }

        table tr:hover {
            background-color: #81d4fa; /* Hover efekti mavi */
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #ffffff; /* Container arka planı beyaz */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .checkbox-container {
            margin-top: 20px;
        }

        button {
            background-color: #0277bd; /* Buton rengi mavi */
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #01579b; /* Buton hover rengi */
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Ders Listesi</h1>
    <form method="POST">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Ders ID</th>
                    <th>Ders Adı</th>
                    <th>Öğretim Üyesi Seç</th>
                    <th>Seç</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($dersler as $index => $ders) {
                    echo "<tr>
                            <td>" . ($index + 1) . "</td>
                            <td>" . $ders['DersID'] . "</td>
                            <td>" . $ders['DersAdi'] . "</td>
                            <td>
                                <select name='ogretimUyesi[" . $ders['DersID'] . "]'>
                                    <option value=''>Seçiniz</option>";
                                    foreach ($ogretimUyesi as $ogretim) {
                                        echo "<option value='" . $ogretim['OgretimUyesiID'] . "'>" . $ogretim['AdSoyad'] . "</option>";
                                    }
                    echo    "</select>
                            </td>
                            <td><input type='checkbox' name='dersler[]' value='" . $ders['DersID'] . "'></td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
        
        <button type="submit">Seçilenleri Gönder</button>
    </form>

    <?php
    // Eğer dersler seçildiyse, PHP dizisinde hangi derslerin seçildiğini ve hangi öğretim üyelerinin atandığını göster
    if (!empty($selectedDersler)) {
        echo "<h2>Seçilen Dersler ve Öğretim Üyeleri:</h2>";
        echo "<ul>";
        foreach ($selectedDersler as $dersID) {
            echo "<li>Ders ID: " . htmlspecialchars($dersID);
            // Ders ID'sine karşılık öğretim üyesi bilgilerini ekle
            if (isset($selectedOgretimUyesi[$dersID])) {
                $ogretimUyesiID = $selectedOgretimUyesi[$dersID];
                echo " - Öğretim Üyesi: " . htmlspecialchars($selectedOgretimUyesi[$dersID]);
            }
            echo "</li>";
        }
        echo "</ul>";
    }
    ?>
</div>

</body>
</html>
