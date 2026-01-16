<?php
class FcmApi
{
    private $_serviceAccountKeyFile = '';
    private $_authData = [];
    private $_project_id = '';
    private $_message = [];
    private $_error = '';

    public function __construct($data = [])
    {
        $this->_serviceAccountKeyFile = ((!empty($data['path'])) ? $data['path'] : '');
        $this->_project_id = ((!empty($data['project_id'])) ? $data['project_id'] : '');
    }

    public function init($data = [])
    {
        $this->_serviceAccountKeyFile = ((!empty($data['path'])) ? $data['path'] : '');
        $this->_project_id = ((!empty($data['project_id'])) ? $data['project_id'] : '');

        return $this;
    }


    public function setMessage($data = [])
    {
        $this->_message =
            [
                'title' => ((!empty($data['title'])) ? $data['title'] : ''),
                'body' => ((!empty($data['body'])) ? $data['body'] : '')

            ];
        $this->_payload =
            [
                'image' => ((!empty($data['data1'])) ? $data['image'] : ''),
                'data1' => ((!empty($data['data1'])) ? $data['data1'] : ''),
                'data2' => ((!empty($data['data2'])) ? $data['data2'] : ''),
                'data3' => ((!empty($data['data3'])) ? $data['data3'] : '')
            ];

        return $this;
    }

    public function send($token = '', $debug = false)
    {
        if (!empty($this->oAuth2($debug)) && !empty($token)) {
            // FCM API endpoint for sending messages
            $apiUrl = 'https://fcm.googleapis.com/v1/projects/' . $this->_project_id . '/messages:send';

            $message = [
                'message' =>
                    [
                        'token' => $token,
                        'notification' => $this->_message,
                        'data' => [
                            'data1' => $this->_payload['data1'],
                            'data2' => $this->_payload['data2'],
                            'data3' => $this->_payload['data3'],
                        ],
                        'android' => [
                            'notification' => [
                                'icon' => $this->_payload['image'],
                            ]
                        ],
                        'webpush' => [
                            'notification' => [
                                'icon' => $this->_payload['image'],
                            ]
                        ]
                    ]
            ];

            // Convert the message array to JSON
            $jsonMessage = json_encode($message);

            // Set cURL options
            $ch = curl_init($apiUrl);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $this->_authData['access_token']
            ]);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonMessage);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            // Execute cURL session
            $response_data = curl_exec($ch);

            // Show respons if need
            if (!empty($debug)) {
                echo '<pre>';
                print_r($response_data);
                echo '</pre>';
            }

            // Check for cURL errors
            if (curl_errno($ch)) {
                $this->_error = 'cURL error: ' . curl_error($ch);
                return false;
            } else {
                // Close cURL session
                curl_close($ch);
                // Handle FCM API response
                $response_dataData = json_decode($response_data, true);
                if (!empty($response_dataData['name'])) {
                    //echo 'Message sent successfully. Message ID: ' . $response_dataData['name'];
                    return $response_dataData;
                } else {
                    $this->_error = 'Failed to send message.';
                    return false;
                }
            }
        }

        return false;
    }

    public function getError()
    {
        return $this->_error;
    }

    private function oAuth2($debug = false)
    {
        // Load service account credentials from JSON file
        $credentials = json_decode(file_get_contents($this->_serviceAccountKeyFile), true);

        // OAuth 2.0 token endpoint
        $tokenEndpoint = 'https://oauth2.googleapis.com/token';

        // Set POST fields for token request
        $fields = [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $this->generateJWT($credentials, $tokenEndpoint),
        ];

        // Initialize cURL session
        $ch = curl_init();

        // Set cURL options for token request
        curl_setopt($ch, CURLOPT_URL, $tokenEndpoint);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute cURL session
        $response_data = curl_exec($ch);

        // Show respons if need
        if (!empty($debug)) {
            echo '<pre>';
            print_r($response_data);
            echo '</pre>';
        }

        // Check for errors
        if (curl_errno($ch)) {
            $this->_error = 'Error occurred: ' . curl_error($ch);
        } else {
            // Close cURL session
            curl_close($ch);
            // Handle token response
            $this->_authData = json_decode($response_data, true);
            if (!empty($this->_authData['access_token'])) {
                //echo 'Access token obtained successfully: ' . $this->_authData['access_token'];
                return true;
            } else {
                $this->_error = 'Failed to obtain access token. Error: ' . $response_data;
            }
        }

        return false;
    }

    private function generateJWT($credentials, $tokenEndpoint)
    {
        $header = base64_encode(json_encode(['alg' => 'RS256', 'typ' => 'JWT']));
        $now = time();
        $payload = base64_encode(json_encode([
            'iss' => $credentials['client_email'],
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
            'aud' => $tokenEndpoint,
            'iat' => $now,
            'exp' => $now + 3600, // Token expires in 1 hour
        ]));
        $signature = $this->signData("$header.$payload", $credentials['private_key']);
        return "$header.$payload.$signature";
    }

    // Function to sign data using RSA private key
    private function signData($data, $privateKey)
    {
        $key = openssl_pkey_get_private($privateKey);
        openssl_sign($data, $signature, $key, OPENSSL_ALGO_SHA256);
        openssl_free_key($key);
        return base64_encode($signature);
    }
}