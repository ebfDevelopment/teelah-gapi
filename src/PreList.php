<?php
    namespace Teelah\Gapi;

    class PreList {

        public function teste(){

            $segredo = defined('CONF_TESTE_CONFIG') ? CONF_TESTE_CONFIG : null;

            if (!$segredo) {
                throw new \Exception("Chave CONF_TESTE_CONFIG não definida");
            }
    
            // Usar $segredo normalmente...
            return "Usando o segredo: " . $segredo;

        }

        public function preLista($data, $tipo){

            $url = defined('CONF_API_CLIENT_KEYCONF_API_PREPOSICOES_URL') ? CONF_API_CLIENT_KEYCONF_API_PREPOSICOES_URL : null;
            $token = defined('CONF_API_PREPOSICOES_TOKEN') ? CONF_API_PREPOSICOES_TOKEN : null; 
            $auth  = defined('CONF_API_PREPOSICOES_AUTHORIZATION') ? CONF_API_PREPOSICOES_AUTHORIZATION : null; 
            $secret  = defined('CONF_API_CLIENT_KEY') ? CONF_API_CLIENT_KEY : null; 

    
            $tokenEncoded = base64_encode($token);
    
            $postFields = [
                'token' => $tokenEncoded,
                'sessao' => 'pre-lista',
                'tipo_campanha' => $tipo, //(A = aberta / F = fechada)
                'palavras' => implode(',', $data)
            ];
    
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch,  CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Authorization: ".$auth." " . base64_encode($secret)
            ]);
    
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
    
            if ($httpCode == 200) {
                return $response;
            } else {
                return [
                    'erro' => true,
                    'mensagem' => "Erro ao conectar ao webservice. Código HTTP: $httpCode",
                    'detalhes' => $response
                ];
            }

        }

        public function preMinerar($data, $tipo, $tema){

            $url = defined('CONF_API_CLIENT_KEYCONF_API_PREPOSICOES_URL') ? CONF_API_CLIENT_KEYCONF_API_PREPOSICOES_URL : null;
            $token = defined('CONF_API_PREPOSICOES_TOKEN') ? CONF_API_PREPOSICOES_TOKEN : null; 
            $auth  = defined('CONF_API_PREPOSICOES_AUTHORIZATION') ? CONF_API_PREPOSICOES_AUTHORIZATION : null; 
            $secret  = defined('CONF_API_CLIENT_KEY') ? CONF_API_CLIENT_KEY : null; 

    
            $tokenEncoded = base64_encode($token);
    
            $postFields = [
                'token' => $tokenEncoded,
                'sessao' => 'pre-mineracao',
                'palavras' => $data,
                'tipo_campanha' => $tipo, //(A = campanha aberta / F = campanha fechada)
                'tema_central' => $tema
            ];
    
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch,  CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Authorization: ".$auth." " . base64_encode($secret)
            ]);
    
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
    
            if ($httpCode == 200) {
                return $response;
            } else {
                return [
                    'erro' => true,
                    'mensagem' => "Erro ao conectar ao webservice. Código HTTP: $httpCode",
                    'detalhes' => $response
                ];
            }

        }


    }