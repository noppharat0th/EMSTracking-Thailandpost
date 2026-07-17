<?php
class Tracking {
    private $url = "https://trackweb.thailandpost.co.th/post/api/web/getMailing";
    private $headers;

    public function __construct(){
        $this->headers = [
            "accept: application/json, text/plain, */*",
            "accept-language: en-US,en;q=0.9,th;q=0.8",
            "content-type: application/json",
            "sec-ch-ua: \"Not:A-Brand\";v=\"99\", \"Google Chrome\";v=\"145\", \"Chromium\";v=\"145\"",
            "sec-ch-ua-mobile: ?0",
            "sec-ch-ua-platform: \"Windows\"",
            "sec-fetch-dest: empty",
            "sec-fetch-mode: cors",
            "sec-fetch-site: same-site",
            "x-requested-with: XMLHttpRequest",
            "Referer: https://track.thailandpost.co.th/"
        ];
    }

    private function btoa($data){
        return base64_encode($data);
    }

    private function enc($raw_payload) {
        $chunk_size = 24;
        $b64_str = $this->btoa($raw_payload);
        
        $chunks = str_split($b64_str, $chunk_size);
        
        $indices = range(0, count($chunks) - 1);
        shuffle($indices);
        
        $shuffled_str = "";
        $key_parts = [];
        
        foreach ($indices as $i) {
            $shuffled_str .= $chunks[$i];
            $key_parts[] = $i . $chunk_size;
        }
        
        $key_str = implode(":", $key_parts);
        $enc_key = $this->btoa($key_str);
        
        return $shuffled_str . "," . $enc_key;
    }

    public function getMailing($tracking_number) {
        $inner_data = [
            "trackingNo" => $tracking_number,
            "flagOS" => "1",
            "flagBill" => "",
            "checkBot" => "1",
            "check" => "AA",
            "tnt" => "",
            "turnstileToken" => null
        ];
        
        $payload = [
            "data" => json_encode($inner_data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            "d" => null
        ];
        
        $raw_json = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        
        $header_array = (array) $this->headers; 
        
        $body = $this->enc($raw_json);
        
        $ch = curl_init($this->url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header_array);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_2_0);
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if(curl_errno($ch)){
            $error_msg = curl_error($ch);
            curl_close($ch);
            throw new Exception("cURL Error: " . $error_msg);
        }
        
        curl_close($ch);
        
        if ($http_code >= 400) {
            throw new Exception("HTTP Error " . $http_code . ": " . $response);
        }
        
        return json_decode($response, true);
    }

}

?>