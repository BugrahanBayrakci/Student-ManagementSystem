<?php
// URL'den 'kullaniciID' parametresini alalım
if (isset($_GET['kullaniciID'])) {
    $KullaniciID = $_GET['kullaniciID'];  // GET ile gelen değeri alıyoruz
} else {
    echo "Kullanıcı ID bulunamadı!";
}



?>



<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Öğrenci Bilgi Sistemi - Ana Menü</title>
    <style>
        /* Genel Stiller */
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #00bcd4, #3a8d99);
            margin: 0;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
        }
        
        /* Başlık Alanı */
        .header {
            background-color: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 40px 20px;
            text-align: center;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
        }

        .header h1 {
            font-size: 48px;
            margin-bottom: 10px;
        }

        .header p {
            font-size: 22px;
            margin-top: 0;
        }

        /* Menü Konteyner */
        .menu-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            padding: 50px;
            max-width: 1200px;
            width: 100%;
            justify-items: center;
        }

        /* Menü Öğeleri */
        .menu-item {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            width: 250px;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease, background-color 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            overflow: hidden;
        }

        .menu-item:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            background-color:rgb(14, 35, 230);
        }

        .menu-item a {
            text-decoration: none;
            color: #333;
            font-size: 20px;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .menu-item:hover a {
            color: white;
        }

        .menu-item i {
            font-size: 50px;
            margin-bottom: 20px;
            color:rgb(8, 0, 82);
            transition: color 0.3s ease;
        }

        .menu-item:hover i {
            color: white;
        }

        /* Footer */
        .footer {
            text-align: center;
            background-color: #2c3e50;
            color: white;
            padding: 20px;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        /* Mobil uyumluluk */
        @media (max-width: 768px) {
            .header h1 {
                font-size: 36px;
            }

            .menu-item {
                padding: 30px;
            }

            .menu-item i {
                font-size: 40px;
            }

            .footer {
                font-size: 12px;
            }
        }
    </style>
</head>
<body>

    <!-- Başlık Bölümü -->
    <div class="header">
        <h1>Öğrenci Bilgi Sistemi</h1>
        <p>Hoşgeldiniz, Öğrencimiz!</p>
    </div>

    <!-- Menü Bölümü -->
    <div class="menu-container">
    <div class="menu-item">
        <i class="fa fa-user"></i>
        <!-- PHP ile dinamik olarak kullanıcı ID'si ekleniyor -->
        <a href="ogrenci_bilgileri.php?kullaniciId=<?php echo $KullaniciID; ?>">Öğrenci Bilgileri</a>
 
        </div>
        <div class="menu-item">
            <i class="fa fa-book"></i>
            <a href="dersler.php?kullaniciId=<?php echo $KullaniciID; ?>">Dersler</a>
        </div>
        <div class="menu-item">
            <i class="fa fa-graduation-cap"></i>
            <a href="AgnoHesapla.php?kullaniciId=<?php echo $KullaniciID; ?>">AGNO Hesapla</a>
            </div>
        <div class="menu-item">
            <i class="fa fa-calendar-check"></i>
            <a href="dersalma.php?kullaniciId=<?php echo $KullaniciID; ?>">Ders Alma</a>
        </div>
      
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; 2024 Buğrahan Bayrakci Öğrenci Bilgi sistemi</p>
    </div>

    <!-- FontAwesome CDN (Simge Ikonları) -->
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>

</body>


</html>
