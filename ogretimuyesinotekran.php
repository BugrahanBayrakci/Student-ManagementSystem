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
$sql = "SELECT DersID, DersAdi,VizeOrtalama,FinalOrtalama FROM Dersler"; 
$stmt = $pdo->prepare($sql);
$stmt->execute();

// Dersleri diziye alıyoruz
$dersler = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Kullanıcı ID kontrolü
if (isset($_GET['kullaniciId'])) {
    $KullaniciID = $_GET['kullaniciId'];
} else {
    echo "Veri bulunamadı!";
    exit;  // Kullanıcı ID yoksa işlem sonlandırılır
}

$OgrenciNo = '';
$dersAdi = '';
$not = '';
$notTuru = '';

// POST isteği ile form verilerini işleme
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Form verilerini al
    $OgrenciNo = $_POST['OgrenciNo'];
    $DersId = $_POST['dersAdi'];
    $not = $_POST['not'];
    $notTuru = $_POST['notTuru'];

    // Basit doğrulama
    if (empty($OgrenciNo) || empty($DersId) || empty($not) || empty($notTuru)) {
        echo "Lütfen tüm alanları doldurun!";
    } else {
        // Verileri işleyin (örneğin veritabanına kaydet)

    }
}

// Stored procedure çağırma
if ($OgrenciNo && $DersId && $not && $notTuru) {
    $stmt2 = $pdo->prepare("EXEC sp_NotGirme 
            @DersId = :DersId, 
            @OgrenciNO = :OgrenciNO, 
            @Not = :Not, 
            @NotTuru = :NotTuru, 
            @OgretimUyesiId = :OgretimUyesiId");

    // Parametreleri bağlama
    $stmt2->bindParam(':DersId', $DersId, PDO::PARAM_INT);
    $stmt2->bindParam(':OgrenciNO', $OgrenciNo, PDO::PARAM_INT);
    $stmt2->bindParam(':Not', $not, PDO::PARAM_INT); 
    $stmt2->bindParam(':NotTuru', $notTuru, PDO::PARAM_STR); // Burada PDO::PARAM_STR kullanılmalı
    $stmt2->bindParam(':OgretimUyesiId', $KullaniciID, PDO::PARAM_INT);

    // Sorguyu çalıştırma
    if ($stmt2->execute()) {
        echo "Not başarıyla kaydedildi!";
    } else {
        echo "Not kaydedilemedi!";
    }
}
?>



<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ders Girme ve Listeleme</title>
    <!-- Bootstrap CSS Bağlantısı -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #007bff;
            font-family: 'Roboto', sans-serif;
            color: #ffffff;
        }

        .container {
            margin-top: 50px;
        }

        .card {
            border-radius: 16px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        .card-body {
            padding: 30px;
            background: #ffffff;
            border-radius: 16px;
            color: #333333;
        }

        h1, h2 {
            font-weight: bold;
        }

        label {
            font-weight: 600;
            color: #555;
        }

        .form-control {
            border-radius: 12px;
            border: 1px solid #ddd;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 8px rgba(0, 123, 255, 0.25);
        }

        .btn {
            border-radius: 12px;
            background: linear-gradient(90deg, #ffffff, #0056b3);
            color: #007bff;
            font-weight: bold;
            transition: background 0.3s ease-in-out, color 0.3s ease-in-out;
        }

        .btn:hover {
            background: linear-gradient(90deg, #0056b3, #ffffff);
            color: #ffffff;
        }

        .table {
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
        }

        .table thead {
            background: linear-gradient(90deg, #007bff, #0056b3);
            color: #ffffff;
        }

        .table tbody tr {
            transition: all 0.2s ease-in-out;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
            transform: scale(1.02);
        }
    </style>
</head>
<body>
<div class="container">
    <!-- Ders Girme Ekranı -->
    <div class="card mb-4">
        <div class="card-body">
            <h1 class="text-center mb-4">Ders Girme Ekranı</h1>
            <form action="ogretimuyesinotekran.php?kullaniciId=<?php echo $KullaniciID; ?>" method="POST">
                <div class="mb-3">
                    <label for="OgrenciNo" class="form-label">Ogrenci Numarası :</label>
                    <input type="text" class="form-control" id="OgrenciNo" name="OgrenciNo" placeholder="Ogrenci numarası giriniz" required>
                </div>
                <div class="mb-3">
                    <label for="dersAdi" class="form-label">Ders Adı:</label>
                    <select class="form-control" id="dersAdi" name="dersAdi" required onchange="dersiSec()">
                        <option value="">Bir ders seçin</option>
                        <?php
                        // Dersleri dinamik olarak listeleme
                        foreach ($dersler as $ders) {
                            echo "<option value='" . $ders['DersID'] . "'>" . $ders['DersAdi'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="not" class="form-label">Not:</label>
                    <input type="number" class="form-control" id="not" name="not" placeholder="0-100 arasında bir not giriniz" required min="0" max="100">
                </div>
                <div class="mb-3">
                    <label for="notTuru" class="form-label">Not Türü:</label>
                    <select class="form-control" id="notTuru" name="notTuru" required>
                        <option value="">Vize veya Final Seçin</option>
                        <option value="vize">Vize</option>
                        <option value="final">Final</option>
                    </select>
                </div>

                
                <button type="submit" class="btn btn-primary w-100">Notu Kaydet</button>
            </form>
        </div>
    </div>
</div>

        <!-- Ders Listesi -->
        <div class="card">
            <div class="card-body">
                <h2 class="text-center mb-4">Ders Listesi</h2>
                <table class="table table-striped table-hover text-center">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Ders Numarası</th>
                            <th>Ders Adı</th>
                            <th>Vize Ortalaması</th>
                            <th>Final Ortalaması</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Derslerin bilgilerini listeleme
                        foreach ($dersler as $index => $ders) {
                            echo "<tr>
                                    <td>" . ($index + 1) . "</td>
                                    <td>" . $ders['DersID'] . "</td>
                                    <td>" . $ders['DersAdi'] . "</td>
                                    <td>" . $ders['VizeOrtalama'] . "</td>
                                    <td>" . $ders['FinalOrtalama'] . "</td>
                                  </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bağlantısı -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
<script>
    // Seçilen dersin ID'sini tutacak değişken
    let secilenDers;

    // Ders seçildiğinde çağrılacak fonksiyon
    function dersiSec() {
        // Seçilen dersin ID'sini al
        secilenDers = document.getElementById("dersAdi").value;
        console.log("Seçilen Ders ID:", secilenDers); // Test amacıyla konsola yazdır
    }
</script>

</html>
