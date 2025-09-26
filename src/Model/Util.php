<?php
    namespace Model;
    use \PDO;
    class Util  {
        private  $host = "agilesaptech.com";
        private  $user = "agiletwn_ess";
        private  $pass = "agiletwn_ess";
        private  $dbnm = "agiletwn_ess";
        private  $options;
        private  $conn;
        public function __construct() {
        }
        public function connect() {
            try {
                $this->options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Enable exceptions
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ, // Set fetch mode to associative array
                    PDO::ATTR_EMULATE_PREPARES => false // Disable emulated prepared statements
                ];
                $this->conn = new PDO("mysql:host={$this->host};dbname={$this->dbnm};charset=utf8;", $this->user,$this->pass,$this->options);
            } catch (\PDOException $e) {
                die("Database connection failed: " . $e->getMessage());
            }
            return $this->conn;
        }
        public function execQuery($query, $param = [],$rows = 0 ) {
            $conn = $this->connect();
            $stmt = $conn->prepare($query);
            $rset = $stmt->execute($param);
            if($stmt->rowCount() > 0 ) {
                if ( $rows == 1 ) {
                    return $stmt->fetch();
                } else {
                    return $stmt->fetchAll();
                } 
            } 
            $conn = null;
        }
        public function writeLog($message) {
            $logFile = 'error_log';
            $logMesg = date('Y-m-d H:i:s') . " - " . $message . PHP_EOL;
            file_put_contents($logFile, $logMesg, FILE_APPEND);
        }

        function getFY($date) {
            // Convert the date to a DateTime object
            $oDate = new DateTime($date);
        
            // Get the year of the given date
            $year = $oDate->format('Y');
            
            // If the month is before April (i.e. January, February, or March), the fiscal year will be last year
            if ($oDate->format('m') < 4) {
                $FY = ($year - 1);
            } else {
                $FY = $year;
            }
        
            return $FY;
        }
        function getStates($country="IN") {
            $api_url = "https://api.countrystatecity.in/v1/countries/{$country}/states/";
            $api_key = "NHhvOEcyWk50N2Vna3VFTE00bFp3MjFKR0ZEOUhkZlg4RTk1MlJlaA==";

            // $api_url = $api_url ."?X-CSCAPI-KEY=".$api_key;

            $ch = curl_init();

            // Set the URL of the API endpoint
            curl_setopt($ch, CURLOPT_URL, $api_url);

            // Set the request method to GET
            curl_setopt($ch, CURLOPT_HTTPGET, true);

            // Set custom headers (e.g., API key)
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "X-CSCAPI-KEY: {$api_key}",
                "Content-Type: application/json"
            ]);

            // Set cURL options
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            // Execute the cURL session and get the response
            $resp = curl_exec($ch);

            // Check for errors
            if(curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
            } else {
                return  json_decode($resp);
            }

            // Close the cURL session
            curl_close($ch);
        }
        function getCities($state="MH") {
            $api_url = "https://api.countrystatecity.in/v1/countries/IN/states/{$state}/cities/";
            $api_key = 'NHhvOEcyWk50N2Vna3VFTE00bFp3MjFKR0ZEOUhkZlg4RTk1MlJlaA==';

            $ch = curl_init();

            // Set the URL of the API endpoint
            curl_setopt($ch, CURLOPT_URL, $api_url);

            // Set the request method to GET
            curl_setopt($ch, CURLOPT_HTTPGET, true);

            // Set custom headers (e.g., API key)
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "X-CSCAPI-KEY: {$api_key}",
                "Content-Type: application/json"
            ]);

            // Set cURL options
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            // Execute the cURL session and get the response
            $resp = curl_exec($ch);

            // Check for errors
            if(curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
            } else {
                return  json_decode($resp);
            }

            // Close the cURL session
            curl_close($ch);
        }
        function getDistance($param=[]) {
            $api_url = 'https://maps.googleapis.com/maps/api/distancematrix/json?';

            $api_key = 'AIzaSyD9xDPAVYW7yMVPAYb5uV9-nfhjPkiPs28'; // Replace with your API key
    
            // Construct the API URL
            $url = $api_url . 'origins=' . urlencode($param[0]) . '&destinations=' . urlencode($param[1]) . '&key=' . $api_key;
    
            // Initialize cURL session
            $ch = curl_init();
    
            // Set cURL options
            curl_setopt($ch, CURLOPT_URL, $url); // Set the API URL
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return response as string
    
            // Execute the cURL session and get the response
            $response = curl_exec($ch);
    
            // Check for errors
            if(curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
            } else {
                // Decode the JSON response
                $data = json_decode($response, true);
    
                // Get the distance from the response
                if (isset($data['rows'][0]['elements'][0]['distance'])) {
                    echo "Distance: " . $data['rows'][0]['elements'][0]['distance']['text'];
                } else {
                    echo "No distance data available.";
                }
            }
    
            // Close the cURL session
            curl_close($ch);
        }        
        

        public function writeSql($query, $param=[]) {          
            foreach ($param as $key => $value) {
                // If the parameter is an integer, replace it directly
                if (is_int($value)) {
                    $query = preg_replace('/:' . $key . '\b/', $value, $query);
                } else {
                    // Otherwise, wrap the parameter in quotes
                    $query = preg_replace('/:' . $key . '\b/', "'" . $value . "'", $query);
                }
            }
            
            return $query;
        }
        public function __destruct() {
            // $this->close(); 
        }
    }
