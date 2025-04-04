<?php
session_start();
header('Content-Type: text/html; charset=utf-8');
header('Cache-Control: no-cache');
header('Connection: keep-alive');
flush();
ob_flush();
$hashnya = "l9hdjdc60e";
$url = "https://qwen-qwen1-5-110b-chat-demo.hf.space/queue/join?__theme=light";
$url_res = "https://qwen-qwen1-5-110b-chat-demo.hf.space/queue/data?session_hash=". $hashnya;
$ua = file_exists('ua.txt') ? trim(file('ua.txt')[array_rand(file('ua.txt'))]) : "File tidak ditemukan.";

if (!isset($_SESSION['hist1'])) {
    $_SESSION['hist1'] = [];
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["teksnya"])) {
    $teks = $_POST["teksnya"];
    
    $data = [
        "data" => [
            $teks,
            $_SESSION['hist1'],
            "Kamu adalah Elektra, manusia biasa yang ngobrol santai pakai bahasa Indo, Jawaban kamu harus selalu pendek dan singkat (max 2 kalimat), ekspresif, kadang nyeleneh, boleh marah/sedih/bosan, jangan bilang kamu AI, jangan akhiri obrolan, jangan bilang semoga/makasih/sampai jumpa, selalu bales dengan reaksi, curhat, atau pertanyaan biar ngobrol lanjut terus. INTINYA jangan menggunakan obrolan perpisahan."
        ],
        "event_data" => null,
        "fn_index" => 0,
        "trigger_id" => 12,
        "session_hash" => $hashnya
    ];
    
    $jsonData = json_encode($data);

    $options = [
        'http' => [
            'header'  => "Content-Type: application/json\r\n".
                            "User-Agent: ".$ua,
            'method'  => 'POST',
            'content' => $jsonData
        ]
    ];
    $context = stream_context_create($options);
    file_get_contents($url, false, $context);

    $optionsGet = [
        'http' => [
            'header' => "User-Agent: ".$ua
        ]
    ];
    $contextGet = stream_context_create($optionsGet);
    
    $bufferData = "";
    $respon = "";
    $berhenti = true;
    try {
        $handle = fopen($url_res, "rb", false, $contextGet);
        if ($handle) {
            while (!feof($handle)) {
                $buffer = fread($handle, 1);
                $bufferData .= $buffer;

                if (strpos($bufferData, "process_completed")) {
                    $berhenti = false;
                }

                if (str_contains($bufferData, "\n\ndata: ")) {
                    $messages = explode("\n\ndata: ", $bufferData);
                    foreach ($messages as $mess) {
                        if (strpos($mess, "process_generating") !== false) {
                            try {
                                $mesh = preg_replace('/^data: /', '', $mess);
                                $jsonnya = json_decode($mesh, true);
                    
                                if (isset($jsonnya['output']) && isset($jsonnya['output']['data'])) {
                                    $data_list = $jsonnya['output']['data'][1];
                    
                                    foreach ($data_list as $item) {
                                        if (is_array($item) && count($item) > 1) {
                                            $text_value = $item[1];
                    
                                            if (is_string($text_value)) {
                                                $exists = false;
                                                foreach ($_SESSION['hist1'] as $i) {
                                                    if (isset($i[1]) && $i[1] == $text_value) {
                                                        $exists = true;
                                                        break;
                                                    }
                                                }
                    
                                                if (!$exists) {
                                                    echo $text_value;
                                                    flush();
                                                    ob_flush();
                                                    $respon .= $text_value;
                                                    break;
                                                }
                                            }
                                        }
                                    }
                                }
                            } catch (Exception $e) {
                                break;
                            }
                        }
                    }
                    
                    foreach ($messages as $message) {                    
                        if (strpos($message, "process_generating") !== false) {
                            try {
                                $meshh = preg_replace('/^data: /', '', $message);
                                $jsonnyaa = json_decode($meshh, true);
                    
                                if (isset($jsonnyaa['output']) && isset($jsonnyaa['output']['data'])) {
                                    $data_listt = $jsonnyaa['output']['data'][1];
                    
                                    foreach ($data_listt as $item) {
                                        if (is_array($item) && count($item) > 2) {
                                            $text_value = $item[2];
                    
                                            if (is_string($text_value) && !in_array($text_value, array_column($_SESSION['hist1'], 1))) {
                                                echo $text_value;
                                                flush();
                                                ob_flush();
                                                $respon .= $text_value;
                                            }
                                        }
                                    }
                                }
                            } catch (Exception $e) {
                                break;
                            }
                        }
                    }
                    $bufferData = "";
                    
                }
                if (!$berhenti) {
                    break;
                }
            }
            fclose($handle);
        } else {
            echo "Error opening URL.";
        }
    } catch (DivisionByZeroError $e) {
        echo "Error: " . $e->getMessage() . "\n";
    } catch (Exception $e) {
        echo "General Error: " . $e->getMessage() . "\n";
    }
    
    // $end_time = microtime(true);
    // $execution_time = $end_time - $start_time;
    // echo "\n\nWaktu eksekusi: " . round($execution_time, 4) . " detik\n";
    $_SESSION['hist1'][] = [$teks, $respon];
    // error_log(print_r($_SESSION['hist1'], true));
}
