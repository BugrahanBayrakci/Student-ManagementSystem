<?php
$serverName = "DESKTOP-HLBI80J\SQLEXPRESS"; // SQL Server adresi ve instance
$database = "OgrenciBilgiSistemi";          // Veritabanı adı
$UID = "sa";                                // SQL Server kullanıcı adı
$pass = "ogrencibilgisistemi123.";                         // Şifre

//  veritabanı ile etkileşimde bulunmak için 
$pdo = new PDO("sqlsrv:Server=$serverName;Database=$database", $UID, $pass);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (isset($_GET['kullaniciId']) ) {
    $KullaniciID = $_GET['kullaniciId'];

} else {
    echo "Veri bulunamadı!";
}

$stmt = $pdo->prepare("EXEC sp_OgrenciDetay @KullaniciId = $KullaniciID");

$stmt->execute();

$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($result) {
    $OgrenciID = $result['OgrenciID'];// columların adlarından statü ve id bilgisini aldım
    $AdSoyad = $result['AdSoyad'];
    $bolum=$result['Bolum'];
    $DogumTarihi=$result['DogumTarihi'];
    $Telefon=$result['Telefon'];
    $eposta=$result['Eposta'];
    $OgrenciNO=$result['OgrenciNO'];

}



?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Öğrenci Kişisel Bilgileri</title>
    <style>
        /* GENEL AYARLAR */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg,rgb(33, 91, 216),rgb(28, 77, 211));
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
            color: #333333;
        }
        

        /* KART STİLİ */
        .card {
            background: linear-gradient(145deg, #ffffff, #f3f3f3);
            border-radius: 20px;
            box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.2), 0px -10px 30px rgba(255, 255, 255, 0.6);
            width: 400px;
            text-align: center;
            padding: 40px 30px;
            position: relative;
            transition: transform 0.4s ease, box-shadow 0.4s ease;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0px 15px 40px rgba(0, 0, 0, 0.3), 0px -15px 40px rgba(255, 255, 255, 0.7);
        }

        /* PROFİL FOTOĞRAFI */
        .profile-image {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid #2196f3 ;
            margin-top: -90px;
            background-color: white;
            transition: transform 0.3s ease;
        }

        .card:hover .profile-image {
            transform: scale(1.05);
        }

        /* METİN STİLİ */
        .card h1 {
            font-size: 28px;
            color: #333333;
            margin: 20px 0 10px;
            text-transform: capitalize;
            letter-spacing: 1px;
        }

        .card p {
            color: #666666;
            font-size: 16px;
            margin: 0;
            font-style: italic;
        }

        /* BİLGİ ALANI */
        .info {
            text-align: left;
            margin-top: 25px;
            padding: 0 20px;
        }

        .info-item {
            font-size: 16px;
            margin: 15px 0;
            display: flex;
            align-items: center;
            gap: 15px;
            transition: transform 0.3s ease;
        }

        .info-item:hover {
            transform: translateX(10px);
        }

        .info-item span {
            font-weight: bold;
            color:rgb(14, 34, 150);
        }

        .icon {
            width: 28px;
            height: 28px;
            filter: drop-shadow(1px 1px 3px rgb(113, 0, 219) 0.3)) hue-rotate(180deg);
        }

        /* E-posta ve Telefon Güncelleme Formu */
        .update-form {
            display: none;
            margin-top: 20px;
            text-align: left;
        }

        .update-form input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .update-button {
            padding: 10px 20px;
            background-color:rgb(76, 99, 175);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 100%;
            font-size: 16px;
        }

        .update-button:hover {
            background-color:rgb(56, 86, 142);
        }

        /* Düzenle Butonu */
        .info-item button {
            background-color:rgb(76, 91, 175);
            color: white;
            border: none;
            border-radius: 5px;
            padding: 8px 15px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .info-item button:hover {
            background-color:rgb(56, 69, 142);
        }

        /* SOSYAL MEDYA BAĞLANTILARI */
        .social-links {
            margin-top: 30px;
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        .social-links a {
            color: #ffffff;
            background-color:rgb(42, 65, 190);
            border-radius: 50%;
            width: 45px;
            height: 45px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 20px;
            text-decoration: none;
            transition: transform 0.3s ease, background-color 0.3s ease;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .social-links a:hover {
            background-color:rgb(2, 1, 54);
            transform: scale(1.1);
        }

        /* ALT BİLGİ */
        footer {
            position: absolute;
            bottom: 15px;
            font-size: 12px;
            color: #ffffff;
            text-align: center;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="card">
        <img src="https://img.icons8.com/ios-glyphs/100/0000ff/student-male.png" alt="Öğrenci Fotoğrafı" class="profile-image">
        <h1><?php echo $AdSoyad; ?></h1>
        <p><?php echo $bolum; ?></p>
        <p><?php echo $OgrenciNO; ?></p>
        <div class="info">
            <div class="info-item">
                <img src="https://img.icons8.com/ios-glyphs/30/2196f3/calendar.png" alt="Doğum Tarihi" class="icon">
                <span>Doğum Tarihi:</span> <?php echo $DogumTarihi; ?>
            </div>
            <div class="info-item">
                <img src="https://img.icons8.com/ios-glyphs/30/2196f3/marker.png" alt="Adres" class="icon">
                <span>Adres:</span> İstanbul, Türkiye
            </div>
            <div class="info-item" id="phone-display">
                <img src="https://img.icons8.com/ios-glyphs/30/2196f3/phone.png" alt="Telefon" class="icon">
                <span>Telefon:</span> <span id="phone-number"><?php echo $Telefon; ?></span>
                <button onclick="toggleForm('phone')">Düzenle</button>
            </div>
            <div class="info-item" id="email-display">
                <img src="https://img.icons8.com/ios-glyphs/30/2196f3/email.png" alt="E-posta" class="icon">
                <span>Eposta:</span> <span id="email-address"><?php echo $eposta; ?></span>
                <button onclick="toggleForm('email')">Düzenle</button>
            </div>
        </div>

        <!-- Güncelleme Formu -->
        <div class="update-form" id="phone-form">
            <input type="text" id="new-phone" placeholder="Yeni Telefon Numarası">
            <button class="update-button" onclick="updatePhone()">Güncelle</button>
        </div>
        <div class="update-form" id="email-form">
            <input type="email" id="new-email" placeholder="Yeni E-posta Adresi">
            <button class="update-button" onclick="updateEmail()">Güncelle</button>
        </div>

        <div class="social-links">
            <a href="#"><img src="https://img.icons8.com/ios-filled/20/ffffff/facebook-new.png" alt="Facebook"></a>
            <a href="#"><img src="https://img.icons8.com/ios-filled/20/ffffff/twitter.png" alt="Twitter"></a>
            <a href="#"><img src="https://img.icons8.com/ios-filled/20/ffffff/linkedin.png" alt="LinkedIn"></a>
        </div>
    </div>
    <footer>
        © 2024 Ali Yılmaz - Tüm Hakları Saklıdır.
    </footer>

    <script>
        // Formları göstermek ve gizlemek için fonksiyon
        function toggleForm(type) {
            if (type === 'phone') {
                document.getElementById('phone-form').style.display = 'block';
                document.getElementById('email-form').style.display = 'none';
            } else {
                document.getElementById('email-form').style.display = 'block';
                document.getElementById('phone-form').style.display = 'none';
            }
        }

        // Telefon güncelleme fonksiyonu
        function updatePhone() {
            const newPhone = document.getElementById('new-phone').value;
            if (newPhone) {
                document.getElementById('phone-number').textContent = newPhone;
                document.getElementById('phone-form').style.display = 'none';
            }
        }

        // E-posta güncelleme fonksiyonu
        function updateEmail() {
            const newEmail = document.getElementById('new-email').value;
            if (newEmail) {
                document.getElementById('email-address').textContent = newEmail;
                document.getElementById('email-form').style.display = 'none';
            }
        }
    </script>
</body>
</html>





