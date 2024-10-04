<!DOCTYPE html>
<html>
<head>
    <title>選擇 Techniques</title>
    <style>
        /* 樣式設定 */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        form {
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 10px;
        }

        select {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            display: block;
            margin: 20px auto 0;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }

        .output {
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-top: 20px;
        }

        h2 {
            margin-top: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <form method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <label for="id">選擇 Techniques:</label>
            <select id="id" name="id" required>
                <option value="">請選擇</option>
                <?php
                // 讀取 JSON 檔案
                $jsonData1 = file_get_contents('attack_entreprise.json');
                $jsonData2 = file_get_contents('nist_attack.json');
                $jsonData3 = file_get_contents('ciscontrol_nist.json');
                $jsonData4 = file_get_contents('nist_catalog.json');
                $jsonData6 = file_get_contents('mitigation_entreprise.json');
                $jsonData5 = file_get_contents('attack_mitigaion.json');

                // 解析 JSON 字串為 PHP 陣列
                $data1 = json_decode($jsonData1, true);
                if ($data1 === null) {
                    echo "Error: Unable to parse attack_entreprise.json file.";
                    exit;
                } else {
                    // 生成下拉選單選項
                    foreach ($data1 as $item) {
                        echo "<option value='" . $item['ID'] . "'>" . $item['name'] . "</option>";
                    }
                }
                ?>
            </select>
            
            <button type="submit">提交</button>
        </form>

        <?php
        $data2 = json_decode($jsonData2, true);
        $data3 = json_decode($jsonData3, true);
        $data4 = json_decode($jsonData4, true);
        $data5 = json_decode($jsonData5, true);
        $data6 = json_decode($jsonData6, true);

        // 建立映射陣列
        $idMapping = [];
        foreach ($data2 as $item) {
            $idMapping[$item['ID']] = $item;
        }

        $idmiMapping = [];
        foreach ($data5 as $item) {
            $idmiMapping[$item['ID']] = $item;
        }

        $mitigationMapping = [];
        foreach ($data6 as $item) {
            $mitigationMapping[$item['Enterprise Mitigation ID']] = $item;
        }

        $controlnistIdMapping = [];
        foreach ($data4 as $item) {
            $controlnistIdMapping[$item['Control ID']] = $item;
        }

        $controlIdMapping = [];
        foreach ($data3 as $item) {
            $controlIdMapping[$item['Control ID']] = $item;
        }

        // 獲取用戶選擇的 ID
        $selectedID = isset($_GET['id']) ? $_GET['id'] : '';

        // 僅在用戶提交表單後顯示資料
        if ($selectedID !== '') {
            echo '<div class="output">';
            echo '<h2>選擇的 Techniques 資訊</h2>';
            foreach ($data1 as $item) {
                if ($item['ID'] === $selectedID) {
                    echo "● ID: " . $item['ID'] . "<br><br>";
                    echo "● Name: " . $item['name'] . "<br><br>";
                    echo "● Description: " . $item['description'] . "<br><br>";
                    echo "● Tactics: " . $item['tactics'] . "<br><br>";

                    // if (array_key_exists($selectedID, $idmiMapping)) {
                    //     $miatdata = $idmiMapping[$selectedID];
                    //     if (array_key_exists($miatdata['Enterprise Mitigation ID'], $mitigationMapping)) {
                    //         $mitigationData = $mitigationMapping[$miatdata['Enterprise Mitigation ID']];
                    //         echo "● Mitigation Data:<br><br>";
                    //         echo "● Enterprise Mitigation ID: " . $mitigationData['Enterprise Mitigation ID'] . "<br><br>";
                    //         echo "● Description: " . $mitigationData['description'] . "<br><br>";
                    //     }
                    // }

                    if (array_key_exists($selectedID, $idmiMapping)) {
                        $miatdata = $idmiMapping[$selectedID];
                        if (array_key_exists($miatdata['Enterprise Mitigation ID'], $mitigationMapping)) {
                            $mitigationData = $mitigationMapping[$miatdata['Enterprise Mitigation ID']];
                            //echo "● Mitigation Data:<br>";
                            echo "● Mitigation ID: " . $mitigationData['Enterprise Mitigation ID'] . "<br>";
                            echo "  > Description: " . $mitigationData['description'] . "<br><br>";
                        }
                    }

                    if (array_key_exists($selectedID, $idMapping)) {
                        $nistData = $idMapping[$selectedID];
                        //echo "● NIST Attack Data:<br>";
                        echo "● NIST Control ID: " . $nistData['Control ID'] . "<br>";
                        

                        if (array_key_exists($nistData['Control ID'], $controlnistIdMapping)) {
                            $nistControlData = $controlnistIdMapping[$nistData['Control ID']];
                            //echo "● NIST Control Data:<br><br>";
                            echo "   > Control Text: " . $nistControlData['Control Text'] . "<br><br>";
                        }

                        if (array_key_exists($nistData['Control ID'], $controlIdMapping)) {
                            $cisControlData = $controlIdMapping[$nistData['Control ID']];
                            //echo "● CIS Control Data:<br>";
                            echo "● CIS Safeguard: " . $cisControlData['CIS Safeguard'] . "<br>";
                            echo "   > Title: " . $cisControlData['Title'] . "<br>";
                            echo "   > Description: " . $cisControlData['Description'] . "<br><br>";
                        }
                    }

                    echo "\n";
                }
            }
            echo '</div>';
        }
        ?>
    </div>
</body>
</html>

