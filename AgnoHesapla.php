<?php
$agno = null; // AGNO değişkeni başlangıçta null olarak ayarlanıyor

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // MSSQL bağlantı bilgileri
    $serverName = "DESKTOP-HLBI80J\SQLEXPRESS"; // SQL Server adresi ve instance
    $database = "OgrenciBilgiSistemi";          // Veritabanı adı
    $UID = "sa";                                // SQL Server kullanıcı adı
    $pass = "ogrencibilgisistemi123.";                         // Şifre

    // Kullanıcı ID kontrolü
    if (isset($_GET['kullaniciId'])) {
        $KullaniciID = $_GET['kullaniciId'];
    } else {
        echo "Veri bulunamadı!";
        exit;  // Kullanıcı ID yoksa işlem sonlandırılır
    }


    try {
        // MSSQL veritabanı bağlantısı
        $conn = new PDO("sqlsrv:server=$serverName;Database=$database", $UID, $pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Kullanıcı ID'yi SQL sorgusunda kullanmak için bağlama
        $sql = "SELECT dbo.fn_AGNO(:KullaniciID) AS AGNO";  // KullaniciID'yi fonksiyona parametre olarak gönder
        $stmt = $conn->prepare($sql);

        // Parametreyi bağla
        $stmt->bindParam(':KullaniciID', $KullaniciID, PDO::PARAM_INT);
        
        // Sorguyu çalıştır
        $stmt->execute();

        // Sonucu al
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            $agno = $result['AGNO']; // AGNO sonucu alınır
        } else {
            $agno = "Sonuç bulunamadı.";
        }
    } catch (PDOException $e) {
        // Hata durumunda mesajı ayarla
        $agno = "Hata: " . $e->getMessage();
    }

    // Bağlantıyı kapat
    $conn = null;
}
?>


<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Öğrenci AGNO Hesaplama</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
      font-family: Arial, sans-serif;
    }
    .container {
      margin-top: 50px;
    }
    .card {
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      border-radius: 10px;
    }
    .btn-custom {
      background-color: #007bff;
      color: white;
    }
    .btn-custom:hover {
      background-color: #0056b3;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header text-center">
            <h3>Öğrenci AGNO Hesaplama</h3>
          </div>
          <div class="card-body">
            <form method="POST">
              <div class="text-center">
                <button type="submit" class="btn btn-custom">AGNO Hesapla</button>
              </div>
            </form>

            <!-- AGNO Sonucu -->
            <div class="mt-4 text-center">
              <?php if (!is_null($agno)): ?>
                <h4>Hesaplanan AGNO: <span class="text-primary"><?= htmlspecialchars($agno) ?></span></h4>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS ve diğer gerekli dosyalar -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
