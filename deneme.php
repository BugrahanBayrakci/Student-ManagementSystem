<?php
$serverName = "DESKTOP-HLBI80J\SQLEXPRESS"; // SQL Server adresi ve instance
$connectionOptions = array(
    "Database" => "OgrenciBilgiSistemi", // Veritabanı adı
    "Uid" => "sa", // SQL Server kullanıcı adı
    "PWD" => "ogrencibilgisistemi123." // SQL Server şifresi
);

// Bağlantıyı oluştur
$conn = sqlsrv_connect( $serverName, $connectionOptions );

// Bağlantı kontrolü
if( !$conn ) {
    die( print_r(sqlsrv_errors(), true));
}

// View'dan veriyi çekme
$sql = "SELECT * FROM vv_OgrenciNotlariGoruntule;";
$query = sqlsrv_query( $conn, $sql );

// Verileri HTML Tablosuna yazdırma
echo "<table border='1'>
        <tr>
            <th>Öğrenci Adı</th>
            <th>Ders Adı</th>
            <th>OgretimUyesiAdiSoyadi</th>
            <th>Not 2</th>
            <th>Kredi</th>
        </tr>";

while( $row = sqlsrv_fetch_array( $query, SQLSRV_FETCH_ASSOC )) {
    echo "<tr>
            <td>".$row['OgrenciAdSoyad']."</td>
            <td>".$row['DersinAdi']."</td>
            <td>".$row['OgretimUyesiAdiSoyadi']."</td>
            <td>".$row['Kredi']."</td>
            <td>".$row['Notun']."</td>
          </tr>";
}

echo "</table>";

// Bağlantıyı kapat
sqlsrv_close( $conn );
?>

