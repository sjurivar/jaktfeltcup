<?php

namespace Jaktfeltcup\Services;

use Jaktfeltcup\Core\Database;

class EmailServiceMailjet
{
    private $config;
    private $db;
    private $mailjet_config;
    
    public function __construct($config, $db = null, $mailjet_config = null)
    {
        $this->config = $config;
        $this->db = $db;
        $this->mailjet_config = $mailjet_config;
    }
    
    /**
     * Send email using Mailjet API
     */
    public function sendEmail($to, $subject, $message, $from = null, $fromName = null)
    {
        try {
            // Set default from if not provided
            if (!$from) {
                $from = $this->mailjet_config['from_email'] ?? 'noreply@jaktfeltcup.no';
            }
            if (!$fromName) {
                $fromName = $this->mailjet_config['from_name'] ?? 'Jaktfeltcup';
            }
            
            // Prepare Mailjet API data
            $data = [
                'Messages' => [
                    [
                        'From' => [
                            'Email' => $from,
                            'Name' => $fromName
                        ],
                        'To' => [
                            [
                                'Email' => $to,
                                'Name' => ''
                            ]
                        ],
                        'Subject' => $subject,
                        'TextPart' => strip_tags($message),
                        'HTMLPart' => $message
                    ]
                ]
            ];
            
            // Send via Mailjet API
            $result = $this->sendViaMailjetAPI($data);
            
            if ($result['success']) {
                return [
                    'success' => true,
                    'message' => 'E-post sendt via Mailjet',
                    'mailjet_id' => $result['mailjet_id'] ?? null
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Feil ved sending via Mailjet: ' . $result['error']
                ];
            }
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Feil ved e-post sending: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Send email via Mailjet API
     */
    private function sendViaMailjetAPI($data)
    {
        $api_key = $this->mailjet_config['api_key'] ?? '';
        $secret_key = $this->mailjet_config['secret_key'] ?? '';
        
        if (empty($api_key) || empty($secret_key)) {
            return [
                'success' => false,
                'error' => 'Mailjet API nøkler ikke konfigurert'
            ];
        }
        
        // Mailjet API endpoint
        $url = 'https://api.mailjet.com/v3.1/send';
        
        // Prepare headers
        $headers = [
            'Content-Type: application/json',
            'Authorization: Basic ' . base64_encode($api_key . ':' . $secret_key)
        ];
        
        // Send cURL request
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($ch);
        curl_close($ch);
        
        if ($curl_error) {
            return [
                'success' => false,
                'error' => 'cURL feil: ' . $curl_error
            ];
        }
        
        if ($http_code !== 200) {
            return [
                'success' => false,
                'error' => 'HTTP feil: ' . $http_code . ' - ' . $response
            ];
        }
        
        $response_data = json_decode($response, true);
        
        if (isset($response_data['Messages']) && count($response_data['Messages']) > 0) {
            $message = $response_data['Messages'][0];
            if (isset($message['Status']) && $message['Status'] === 'success') {
                return [
                    'success' => true,
                    'mailjet_id' => $message['To'][0]['MessageID'] ?? null
                ];
            } else {
                return [
                    'success' => false,
                    'error' => 'Mailjet feil: ' . ($message['Errors'][0]['ErrorMessage'] ?? 'Ukjent feil')
                ];
            }
        }
        
        return [
            'success' => false,
            'error' => 'Uventet respons fra Mailjet: ' . $response
        ];
    }
    
    /**
     * Test Mailjet connection
     */
    public function testConnection()
    {
        try {
            $api_key = $this->mailjet_config['api_key'] ?? '';
            $secret_key = $this->mailjet_config['secret_key'] ?? '';
            
            if (empty($api_key) || empty($secret_key)) {
                return [
                    'success' => false,
                    'message' => 'Mailjet API nøkler ikke konfigurert'
                ];
            }
            
            // Test API connection
            $url = 'https://api.mailjet.com/v3/REST/user';
            
            $headers = [
                'Authorization: Basic ' . base64_encode($api_key . ':' . $secret_key)
            ];
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            
            $response = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($http_code === 200) {
                return [
                    'success' => true,
                    'message' => 'Mailjet API tilkobling OK'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Mailjet API feil: HTTP ' . $http_code
                ];
            }
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Feil ved Mailjet test: ' . $e->getMessage()
            ];
        }
    }
}
