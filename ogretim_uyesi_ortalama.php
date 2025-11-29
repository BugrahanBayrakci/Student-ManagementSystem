<?php
$serverName = "DESKTOP-HLBI80J\SQLEXPRESS"; // SQL Server adresi ve instance
$connectionOptions = array(
    "Database" => "OgrenciBilgiSistemi", // Veritabanı adı
    "Uid" => "sa", // SQL Server kullanıcı adı
    "PWD" => "ogrencibilgisistemi123.", // SQL Server şifresi
    "CharacterSet" => "UTF-8" // Türkçe karakter desteği için
);

$conn = sqlsrv_connect($serverName, $connectionOptions);

if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}

$sql = "SELECT * FROM vv_OgrenciNotlariGoruntule";
$stmt = sqlsrv_query($conn, $sql);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Öğrenci Notları Görüntüleme</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(120deg, #2196F3, #00BCD4);
            color: #333;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            margin: 20px 0;
            font-size: 2rem;
            color: white;
        }

        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #2196F3;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
            cursor: pointer;
        }

        /* Responsive tasarım */
        @media (max-width: 768px) {
            table {
                width: 100%;
            }

            th, td {
                font-size: 0.9rem;
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <h1>Öğrenci Notları</h1>
    <table>
        <thead>
            <tr>
            <th>Öğrenci Numarası</th>

                <th>Öğrenci Adı</th>
                <th>Ders Adı</th>
                <th>Kredi</th>
                <th>Vize Notu</th>
                <th>Final Notu</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) { ?>
                <tr>
                <td><?php echo htmlspecialchars($row['OgrenciNO']); ?></td>
                    <td><?php echo htmlspecialchars($row['OgrenciAdSoyad']); ?></td>
                    <td><?php echo htmlspecialchars($row['DersinAdi']); ?></td>
                    <td><?php echo htmlspecialchars($row['Kredi']); ?></td>
                    <td><?php echo htmlspecialchars($row['VizeNotu']); ?></td>
                    <td><?php echo htmlspecialchars($row['FinalNotu']); ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>
