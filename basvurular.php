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

// AJAX ile gelen işlemi kontrol et
if (isset($_POST['action'])) {
    $action = $_POST['action'];
    $ogrenciId = $_POST['ogrenciId']; // Öğrenci ID'si
    $dersAdi = $_POST['dersAdi']; // Ders adı (Onay ya da Red için ders adı da gerekebilir)

    try {
        // Ders ID'sini almak için fonksiyonu kullanıyoruz
        $sql = "SELECT dbo.fn_dersinAdindanIdDondur(:dersAdi) AS DersID";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':dersAdi', $dersAdi, PDO::PARAM_STR);
        $stmt->execute();
        $dersResult = $stmt->fetch(PDO::FETCH_ASSOC);
        $dersId = $dersResult['DersID']; // Ders ID'sini alıyoruz
        
        // Onay işlemi
        if ($action == 'onay') {
            $sql = "UPDATE OgrenciDers SET durum = 'Onaylı' WHERE OgrenciID = :ogrenciId AND DersID = :DersID";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':DersID', $dersId);
            $stmt->bindParam(':ogrenciId', $ogrenciId);
            $stmt->execute();
            echo "Onay işlemi başarılı!";
        }
        // Red işlemi
        elseif ($action == 'red') {
            $sql = "UPDATE OgrenciDers SET durum = 'REDDİLDİ' WHERE OgrenciID = :ogrenciId AND DersID = :DersID";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':DersID', $dersId);
            $stmt->bindParam(':ogrenciId', $ogrenciId);
            $stmt->execute();
            echo "Red işlemi başarılı!";
        }
    } catch (Exception $e) {
        echo "Hata: " . $e->getMessage();
    }
    exit(); // İşlem tamamlandıktan sonra scripti sonlandırıyoruz
}

try {
    // Stored procedure'ü çalıştırma
    $sql = "EXEC sp_DurumDegistir";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    // Sonuçları alma
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Dönen tabloyu ekrana yazdırma
    if (count($results) > 0) {
        // Tablo başlıkları ve verilerinin stilini belirliyoruz
        echo '<style>
                table {
                    width: 80%;
                    margin: 20px auto;
                    border-collapse: collapse;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                }
                th, td {
                    padding: 10px;
                    text-align: center;
                    border: 1px solid #ddd;
                }
                th {
                    background-color:rgb(9, 5, 209);
                    color: white;
                    font-weight: bold;
                }
                tr:nth-child(even) {
                    background-color: #f2f2f2;
                }
                tr:hover {
                    background-color: #ddd;
                }
                .button {
                    padding: 6px 12px;
                    margin: 5px;
                    border: none;
                    border-radius: 5px;
                    cursor: pointer;
                    font-weight: bold;
                }
                .onay {
                    background-color:rgb(13, 45, 223);
                    color: white;
                }
                .red {
                    background-color: #f44336;
                    color: white;
                }
              </style>';

        // Tabloyu oluşturuyoruz
        echo "<table>";
        
        // Tablo başlıkları (sütun isimleri)
        echo "<tr>";
        foreach ($results[0] as $column => $value) {
            echo "<th>" . htmlspecialchars($column) . "</th>";
        }
        echo "<th>Onay</th><th>Red</th>"; // Butonlar için başlıklar
        echo "</tr>";

        // Tablo verileri
        foreach ($results as $row) {
            $ogrenciId = $row['OgrenciID']; // Her satır için benzersiz öğrenci ID'sini alıyoruz
            $dersAdi = $row['DersAdi']; // Ders Adı (Fonksiyon ile ilgili olacak)

            echo "<tr>";
            foreach ($row as $column => $value) {
                if ($column != 'id') { // id'yi tabloya yazdırmıyoruz
                    echo "<td>" . htmlspecialchars($value) . "</td>";
                }
            }

            // Onay ve Red butonları
            echo "<td><button class='button onay' data-id='$ogrenciId' data-ders='$dersAdi' onclick='updateStatus(\"onay\", $ogrenciId, \"$dersAdi\")'>Onay</button></td>";
            echo "<td><button class='button red' data-id='$ogrenciId' data-ders='$dersAdi' onclick='updateStatus(\"red\", $ogrenciId, \"$dersAdi\")'>Red</button></td>";

            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p style='text-align:center;'>No data returned.</p>";
    }
} catch (Exception $e) {
    echo "Hata: " . $e->getMessage();
}

// Bağlantıyı kapatma
$pdo = null;
?>

<script>
// JavaScript ile butonlara tıklandığında AJAX çağrısı yapıyoruz
function updateStatus(action, ogrenciId, dersAdi) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (xhr.status === 200) {
            alert(xhr.responseText); // Sunucudan gelen cevabı gösteriyoruz
            location.reload(); // Sayfayı yenileyerek değişiklikleri gösteriyoruz
        }
    };
    xhr.send('action=' + action + '&ogrenciId=' + ogrenciId + '&dersAdi=' + dersAdi); // AJAX isteği ile action, ogrenciId ve dersAdi parametrelerini gönderiyoruz
}
</script>
