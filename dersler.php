<?php
$serverName = "DESKTOP-HLBI80J\SQLEXPRESS"; // SQL Server adresi ve instance
$database = "OgrenciBilgiSistemi";          // Veritabanı adı
$UID = "sa";                                // SQL Server kullanıcı adı
$pass = "ogrencibilgisistemi123.";                         // Şifre
if (isset($_GET['kullaniciId']) ) {
    $KullaniciID = $_GET['kullaniciId'];

} else {
    echo "Veri bulunamadı!";
}
//  veritabanı ile etkileşimde bulunmak için 
$pdo = new PDO("sqlsrv:Server=$serverName;Database=$database", $UID, $pass);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


$stmt = $pdo->prepare("EXEC sp_Dersler @OgrenciID = $KullaniciID;");
$stmt2 = $pdo->prepare("EXEC sp_OgrenciDetay @KullaniciId = $KullaniciID");

$stmt->execute();
$stmt2->execute();


// Tüm sonuçları almak için fetchAll() kullanıyoruz
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
$results2 = $stmt2->fetch(PDO::FETCH_ASSOC);
if ($results2) {
    $AdSoyad = $results2['AdSoyad'];
    $bolum = $results2['Bolum'];
    $DogumTarihi = $results2['DogumTarihi'];
    $OgrenciNo = $results2['OgrenciNO'];

    $Telefon = $results2['Telefon'];
    $eposta = $results2['Eposta'];
}


$sayac =0;
if ($results) {
    // Sonuçları döngü ile listeleme
    foreach ($results as $row) {
        $dersBilgileri[] = array(
            'DersiVerenOgretimUyesi' => $row['DersiVerenOgretimUyesi'],
            'DersinAdi' => $row['DersinAdi'],
            'kredi' => $row['Kredi'],
            'VizeNıotu' => $row['VizeNotu'],
            'FinalNotu' => $row['FinalNotu'],

        );
    }
} else {
    echo "Veri bulunamadı.";
}



?>



<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Öğrenci Notları</title>
    <style>
        /* Genel stil */
        body {
            font-family: 'Roboto', sans-serif;
            background: #2980b9;
            color: #fff;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-image: linear-gradient(to bottom right, #2980b9, #6dd5fa);
        }

        h1 {
            text-align: center;
            color: #fff;
            margin-bottom: 50px;
            font-size: 3.5em;
            letter-spacing: 2px;
            text-transform: uppercase;
            font-weight: bold;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            background-color: #fff;
            border-radius: 20px;
            box-shadow: 0 20px 30px rgba(0, 0, 0, 0.1);
            padding: 40px;
            color: #333;
        }

        .student-info {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 30px;
            border-bottom: 3px solid #3498db;
        }

        .student-info h2 {
            font-size: 2.5em;
            color: #2980b9;
            margin: 10px 0;
            font-weight: bold;
        }

        .student-info p {
            font-size: 1.1em;
            margin: 5px 0;
            color: #555;
        }

        .courses h3 {
            font-size: 1.7em;
            color: #3498db;
            margin-bottom: 30px;
            text-align: center;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
            border-radius: 12px;
            overflow: hidden;
        }

        th, td {
            padding: 18px;
            text-align: left;
            border: 1px solid #ddd;
            font-size: 1.1em;
        }

        th {
            background-color: #3498db;
            color: white;
            font-weight: bold;
            text-transform: uppercase;
        }

        tr:nth-child(even) {
            background-color: #f7f7f7;
        }

        .btn {
            display: inline-block;
            padding: 16px 30px;
            background-color: #3498db;
            color: white;
            border-radius: 25px;
            font-size: 1.2em;
            text-decoration: none;
            margin-top: 40px;
            text-align: center;
        }
    </style>
</head>
<body>

    <h1>Notlarım     </h1>

    <div class="container">

        <!-- Öğrenci Bilgisi -->
        <div class="student-info">
            <h2><?php echo isset($AdSoyad) ? htmlspecialchars($AdSoyad) : "Öğrenci Adı"; ?></h2>
            <p><strong>Ogrenci Numarası:</strong> <?php echo isset($OgrenciNo) ? htmlspecialchars($OgrenciNo) : 'Ogrenci Numarası'; ?> </p>
            <p><strong>DogumTarihi:</strong> <?php echo isset($DogumTarihi) ? htmlspecialchars($DogumTarihi) : 'DogumTarihi'; ?> </p>
            <p><strong>Bölümü:</strong> <?php echo isset($bolum) ? htmlspecialchars($bolum) : 'Bölüm'; ?> </p>

        </div>

        <!-- Ders Bilgileri -->
        <div class="courses">
            <h3>Aldığı Dersler ve Notlar</h3>
            <table>
                <thead>
                    <tr>
                        <th>Dersi Veren Öğretim Üyesi</th>
                        <th>Ders Adı</th>
                        <th>Kredi</th>
                        <th>Vize</th>
                        <th>Final</th>
                        <th>Harf Notu</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($dersBilgileri)) {
                        foreach ($dersBilgileri as $ders) { ?>
                            <tr>
                                <td><?php echo isset($ders['DersiVerenOgretimUyesi']) ? htmlspecialchars($ders['DersiVerenOgretimUyesi']) : "Bilinmiyor"; ?></td>
                                <td><?php echo isset($ders['DersinAdi']) ? htmlspecialchars($ders['DersinAdi']) : "Bilinmiyor"; ?></td>
                                <td><?php echo isset($ders['kredi']) ? htmlspecialchars($ders['kredi']) : "Bilinmiyor"; ?></td>
                                <td><?php echo isset($ders['VizeNıotu']) ? htmlspecialchars($ders['VizeNıotu']) : "Bilinmiyor"; ?></td>
                                <td><?php echo isset($ders['FinalNotu']) ? htmlspecialchars($ders['FinalNotu']) : "Not Girilmemis"; ?></td>

                            </tr>
                        <?php }
                    } else { ?>
                        <tr>
                            <td colspan="4">Henüz ders bilgisi eklenmemiş.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <!-- Daha Fazla Ders Listele Butonu -->
        <div style="text-align: center;">
            <a href="javascript:void(0);" class="btn" onclick="addMoreCourses()">Daha Fazla Ders Listele</a>
        </div>

    </div>

    <script>
        function addMoreCourses() {
            alert("Şu anda başka ders yok.");
        }
    </script>

</body>
</html>
