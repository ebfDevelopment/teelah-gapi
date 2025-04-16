<?php
    namespace Teelah\Gapi;

    use Teelah\Gapi\Boot\WordListExtractor;

    class PreList {

        protected array $configs;

        public function __construct($configs = [])
        {
            $this->configs = $configs;
        }

        public function preLista($data, $tipo){

            $tokenEncoded = base64_encode($this->configs["token"]);
    
            $postFields = [
                'token' => $tokenEncoded,
                'sessao' => 'pre-lista',
                'tipo_campanha' => $tipo, //(A = aberta / F = fechada)
                'palavras' => implode(',', $data)
            ];
    
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->configs["url"]);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch,  CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Authorization: ".$this->configs["auth"]." " . base64_encode($this->configs["secret"])
            ]);
    
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
    
            if ($httpCode == 200) {
                $list = explode(",", $response);

                return WordListExtractor::extract($data, $response);

            } else {
                return [
                    'erro' => true,
                    'mensagem' => "Erro ao conectar ao webservice. Código HTTP: $httpCode",
                    'detalhes' => $response
                ];
            }

        }

        public function preMinerar($data, $tipo, $tema){

            $tokenEncoded = base64_encode($this->configs["token"]);
    
            $postFields = [
                'token' => $tokenEncoded,
                'sessao' => 'pre-mineracao',
                'palavras' => $data,
                'tipo_campanha' => $tipo, //(A = aberta / F = fechada)
                'tema_central' => $tema
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->configs["url"]);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch,  CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Authorization: ".$this->configs["auth"]." " . base64_encode($this->configs["secret"])
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