<?php
require_once "./functions/tracking.php";
if (isset($_GET['number']) && !empty($_GET['number'])) {
    header("Content-Type: application/json; charset=UTF-8");
    
    $tracking_number = strtoupper(trim($_GET['number']));
    try {
        $tracking = new Tracking();
        $result = $tracking->getMailing($tracking_number);
        echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>เช็คพัสดุ</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        #result { margin-top: 20px; white-space: pre-wrap; background: #eee; padding: 10px; }
    </style>
</head>
<body>
    <h2>ตรวจสอบสถานะพัสดุ</h2>
    <input type="text" id="trackNumber" placeholder="กรอกเลขพัสดุ">
    <button onclick="search()">ค้นหา</button>

    <div id="result">result</div>

    <script>
        async function search() {
            const num = document.getElementById('trackNumber').value;
            const resDiv = document.getElementById('result');
            
            resDiv.innerText = "find...";
            
            const response = await fetch(`index.php?number=${num}`);
            const data = await response.json();
            
            resDiv.innerText = JSON.stringify(data, null, 2);
        }
    </script>
</body>
</html>