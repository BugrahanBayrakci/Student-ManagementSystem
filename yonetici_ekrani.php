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

if (isset($_GET['kullaniciID'])) {
    $KullaniciID = $_GET['kullaniciID'];
} else {
    echo "Veri bulunamadı!";
    exit;
}

$OgrenciNo = '';
$ogrenciAdi = '';
$ogrenciSoyadi = '';
$dogumTarihi = '';
$bolum = '';
$sifre = '';
$eposta1 = '';
$telefon = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Form verilerini al
    $OgrenciNo = $_POST['ogrenci-no'];
    $ogrenciAdi = $_POST['ogrenci-adi'];
    $ogrenciSoyadi = $_POST['ogrenci-soyadi'];
    $dogumTarihi = $_POST['dogum-tarihi'];  // Tarih verisini al
    $bolum = $_POST['bolum'];
    $sifre = $_POST['sifre1'];
    $eposta1 = $_POST['eposta1'];
    $telefon = $_POST['telefon'];

    $ogrenciTamAdi = $ogrenciAdi . " " . $ogrenciSoyadi;

}

if ($OgrenciNo && $ogrenciAdi && $ogrenciSoyadi && $dogumTarihi && $bolum && $sifre && $eposta1 && $telefon) {
    $stmt2 = $pdo->prepare("EXEC sp_OgrenciEkle 
                            @KullaniciAdi = :KullaniciAdi, 
                            @AdSoyAd = :AdSoyAd, 
                            @Sifre = :Sifre, 
                            @statu = 'Ogrenci', 
                            @DogumTarihi = :DogumTarihi, 
                            @Bolum = :Bolum, 
                            @Telefon = :Telefon, 
                            @Eposta = :Eposta");

    // Parametreleri bağlama
    $stmt2->bindParam(':KullaniciAdi', $OgrenciNo, PDO::PARAM_STR);
    $stmt2->bindParam(':AdSoyAd', $ogrenciTamAdi, PDO::PARAM_STR);
    $stmt2->bindParam(':Sifre', $sifre, PDO::PARAM_STR);
    $stmt2->bindParam(':DogumTarihi', $dogumTarihi, PDO::PARAM_STR); // Tarih formatı uygun olmalı
    $stmt2->bindParam(':Bolum', $bolum, PDO::PARAM_STR);
    $stmt2->bindParam(':Telefon', $telefon, PDO::PARAM_STR);
    $stmt2->bindParam(':Eposta', $eposta1, PDO::PARAM_STR);

    // Sorguyu çalıştırma
    if ($stmt2->execute()) {
        echo "<script>alert('Öğrenci başarıyla eklendi!');</script>";
    } else {
        echo "<script>alert('Öğrenci eklenemedi!');</script>";
    }
} else {
    echo "<script>alert('Lütfen tüm alanları doldurduğunuzdan emin olun!');</script>";
}

?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Öğrenci Bilgi Sistemi - Yönetici Paneli</title>
    <style>
        /* Genel Stiller */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #d6e9ff, #f0f8ff);
            color: #333;
            line-height: 1.6;
            scroll-behavior: smooth;
        }

        /* Header */
        header {
            background: linear-gradient(135deg, #4a90e2, #007bff);
            color: white;
            padding: 40px;
            text-align: center;
            border-radius: 0 0 25px 25px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        header h1 {
            font-size: 3rem;
            font-weight: 800;
            letter-spacing: 1.5px;
        }

        /* Navigasyon */
        nav {
            display: flex;
            justify-content: center;
            background: #007bff;
            padding: 10px 0;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        nav a {
            color: white;
            text-decoration: none;
            padding: 12px 25px;
            margin: 0 10px;
            font-weight: bold;
            letter-spacing: 0.5px;
            border-radius: 20px;
            transition: all 0.3s ease-in-out;
        }
        nav a:hover {
            background: #0056b3;
            transform: scale(1.1);
        }

        /* Ana İçerik */
        main {
            padding: 30px;
            max-width: 1200px;
            margin: 30px auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            animation: fadeIn 1s ease-in-out;
        }

        /* Form Stilleri */
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        label {
            font-size: 1rem;
            font-weight: bold;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="date"],
        input[type="tel"],
        input[type="email"],
        input[type="password"] {
            padding: 12px;
            font-size: 1rem;
            border-radius: 8px;
            border: 1px solid #ddd;
            outline: none;
            transition: all 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="password"]:focus,
        input[type="date"]:focus {
            border-color: #007bff;
            box-shadow: 0 0 8px rgba(0, 123, 255, 0.5);
        }

        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 12px 20px;
            font-size: 1rem;
            font-weight: bold;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        /* Tablo Stilleri */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        table th, table td {
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        table th {
            background: #007bff;
            color: white;
            font-weight: bold;
        }
        table tr:nth-child(even) {
            background: #f9f9f9;
        }
        table tr:hover {
            background: #f1f1f1;
        }

        /* Butonlar */
        .btn {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 10px;
            cursor: pointer;
            font-weight: bold;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        /* Animasyon */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
    </style>
</head>
<body>
<header>
    <h1>Öğrenci Bilgi Sistemi Yönetici Paneli</h1>
</header>

<nav>
    <a href="#">Ana Sayfa</a>
    <a href="#">Öğrenci Listele</a>
    <a href="basvurular.php">Başvurular</a>
    <a href="#">Çıkış</a>
</nav>

<main>
    <form method="POST" action="">
        <label for="ogrenci-no">Öğrenci No</label>
        <input type="text" id="ogrenci-no" name="ogrenci-no" required>

        <label for="ogrenci-adi">Adı</label>
        <input type="text" id="ogrenci-adi" name="ogrenci-adi" required>

        <label for="ogrenci-soyadi">Soyadı</label>
        <input type="text" id="ogrenci-soyadi" name="ogrenci-soyadi" required>

        <label for="dogum-tarihi">Doğum Tarihi</label>
        <input type="date" id="dogum-tarihi" name="dogum-tarihi" required>

        <label for="bolum">Bölüm</label>
        <input type="text" id="bolum" name="bolum" required>

        <label for="sifre1">Şifre</label>
        <input type="password" id="sifre1" name="sifre1" required>

        <label for="eposta1">E-posta</label>
        <input type="email" id="eposta1" name="eposta1" required>

        <label for="telefon">Telefon</label>
        <input type="tel" id="telefon" name="telefon" required>

        <button type="submit">Öğrenci Ekle</button>
    </form>
</main>

</body>
</html>
