<?php class UnicomAPI {
    const URL = "https://unicom24.ru";
    private $username;
    private $password;

    public function __construct($username, $password) {
        $this->username = $username;
        $this->password = $password;
    }

    function getRegion($region_name) {
        $regions = $this->curlQuery(
                self::URL . "/api/partners/requests/v1/region/",
                "GET",
                ""
        );
        if ( ! $regions) {
            echo "Not found region " . $region_name . "\n";

            return null;
        } else {
            foreach ($regions as $k => $v) {
                if (isset ($v["title"]) && $region_name == $v["title"]) {
                    return array("id"   => $v["id"],
                                 "code" => $v["region_code"]);
                }
            }
            echo "Not found region " . $region_name . "\n";

            return null;
        }
    }

    function curlQuery($URL, $method, $postData) {
        $data_string = json_encode($postData);
        $ch          = curl_init($URL);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, $this->username . ":" . $this->password);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                        'Content-Type: application/json',
                        'Content-Length: ' . strlen($data_string))
        );
        $result = curl_exec($ch);
        if ( ! $result) {
            return curl_error($ch);
        } else {
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($http_code == 200 || $http_code == 201) {
                return json_decode($result, true);
            } elseif ($http_code == 401 || $http_code == 403) {
                echo "Login or password incorrect\n";
                return array();
            } elseif ($http_code == 400){
                return [json_decode($result, true), $http_code];
            }
        }
    }

    function getCityID($city_name, $region_code) {
        $cities = $this->curlQuery(
                self::URL . "/api/partners/requests/v1/locality/?term=" .
                urlencode($city_name) . "&region_code=" . $region_code,
                "GET",
                ""
        );
        if (isset($cities[0]["id"])) {
            return $cities[0]["id"];
        } else {
            echo "Not found city " . $city_name . "\n";

            return null;
        }
    }

    function createRequest($data, $city_id) {
        $post_data = array();
        foreach ($data as $k => $v) {
            $post_data[ $k ] = $data[ $k ];
        }
        //$post_data["region"]        = $region_id;
        $post_data["locality"]      = $city_id;
       // $post_data["fact_region"]   = $region_id;
        //$post_data["fact_locality"] = $city_id;
        $result                     = $this->curlQuery(
                self::URL . "/api/partners/requests/v1/create_send_all/",
                "POST",
                $post_data
        );

        return $result;
    }
}

$login    = "Kevbrin+34@gmail.ru"; // Логин от Unicom24
$password = "GoxIOib9"; // Пароль от Unicom24
//Анкета которую надо отправить
?>


