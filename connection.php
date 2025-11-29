<?php
$serverName = "DESKTOP-HLBI80J\SQLEXPRESS"; // SQL Server adresi ve instance
$database = "OgrenciBilgiSistemi";          // Veritabanı adı
$UID = "sa";                                // SQL Server kullanıcı adı
$pass = "ogrencibilgisistemi123.";                         // Şifre

//  veritabanı ile etkileşimde bulunmak için 
$pdo = new PDO("sqlsrv:Server=$serverName;Database=$database", $UID, $pass);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Giriş işlemi yapılacaksa
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $KullaniciAdi = $_POST['kullaniciAdi'];//kullaniciAdi idsinden veriyi aldım
    $sifre = $_POST['sifre'];
    $statu = null;
    $KullaniciID = null;

    // Kullanıcı doğrulama için SP çağırma
    $stmt = $pdo->prepare("
        DECLARE @statu NVARCHAR(50), @KullaniciID INT;
        EXEC sp_Giris @KullaniciAdi = :KullaniciAdi, @Sifre = :Sifre, @statu = @statu OUTPUT, @KullaniciID = @KullaniciID OUTPUT;
        SELECT @statu AS Statu, @KullaniciID AS KullaniciID
    ");
    $stmt->bindParam(':KullaniciAdi', $KullaniciAdi, PDO::PARAM_STR);// $KullaniciAdi: Bu, PHP'deki değişkenin adıdır. Bu değişkenin değeri sorgu çalıştırıldığında, :KullaniciAdi parametresine atanacaktır.
    $stmt->bindParam(':Sifre', $sifre, PDO::PARAM_STR);

    // Sorguyu çalıştır
    $stmt->execute();

    // Sonuçları al
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $statu = $result['statu'];// columların adlarından statü ve id bilgisini aldım
        $KullaniciID = $result['kullaniciID'];

        // Kullanıcıyı bulduysak, giriş yapıldıysa
        if ($statu) {
            echo "Giriş başarılı!";
            // Kullanıcı rolüne göre yönlendirme
            if ($statu == 'Ogrenci') {
                header("Location: ogrencigirisekran.php?kullaniciID=$KullaniciID");
            } elseif ($statu == 'Yonetici') {
                header("Location: yonetici_ekrani.php?kullaniciID=$KullaniciID");
            } elseif ($statu == 'OgretimUyesi') {
                header("Location: ogretim_uyesi_ekrani.php?kullaniciID=$KullaniciID");
            } else {
                echo "<script>alert('Geçersiz kullanıcı rolü!');</script>";
            }
        } else {
            echo "<div class='error-message'>Kullanıcı adı veya şifre hatalı!</div>";
        }
    } else {
        echo "<div class='error-message'>Kullanıcı adı veya şifre hatalı!</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giriş Yap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f2f5;
            font-family: Arial, sans-serif;
        }
        .login-container {
            width: 100%;
            max-width: 400px;
            margin: 100px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .login-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .login-header h2 {
            color: #007bff;
        }
        .error-message {
            color: red;
            font-size: 14px;
            margin-bottom: 15px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h2>Giriş Yap</h2>
        </div>
       
        <form method="POST" action="">
            <div class="mb-3">
                <label for="kullaniciAdi" class="form-label">Kullanıcı Adı</label>
                <input type="text" name="kullaniciAdi" id="kullaniciAdi" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="sifre" class="form-label">Şifre</label>
                <input type="password" name="sifre" id="sifre" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Giriş Yap</button>
        </form>
    </div>
</body>
</html>
