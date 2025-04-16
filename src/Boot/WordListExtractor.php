<?php

namespace Teelah\Gapi\Boot;

class WordListExtractor
{
    public static function extract(string $texto, array $lista): array
    {
        $novaLista = [];

        foreach ($lista as $item) {
            // Separa palavras grudadas à palavra-chave
            $item = preg_replace('/(\p{L}+)' . preg_quote($texto, '/') . '/iu', '$1 ' . $texto, $item);
            $item = preg_replace('/' . preg_quote($texto, '/') . '(\p{L}+)/iu', $texto . ' $1', $item);

            // Divide por separadores comuns e espaços especiais
            $fragmentos = preg_split('/[.!?,;\-\n]/u', $item);

            foreach ($fragmentos as $frase) {
                $frase = trim(preg_replace('/\s+/', ' ', $frase));
                if (empty($frase)) continue;

                // Quebra sentenças duplicadas
                $frase = preg_replace('/(' . preg_quote($texto, '/') . ')(\s+[^' . preg_quote($texto, '/') . ']+)?\s*(' . preg_quote($texto, '/') . ')/iu', '$1$2###SPLIT###$3', $frase);
                $subFrases = preg_split('/###SPLIT###/', $frase);

                foreach ($subFrases as $sub) {
                    $sub = trim(preg_replace('/\s+/', ' ', $sub));
                    if (empty($sub)) continue;

                    // Remove duplicata da palavra-chave no final se também estiver no início
                    if (
                        preg_match('/^' . preg_quote($texto, '/') . '\b/i', $sub) &&
                        preg_match('/\b' . preg_quote($texto, '/') . '$/i', $sub)
                    ) {
                        $sub = preg_replace('/\b' . preg_quote($texto, '/') . '$/iu', '', $sub);
                        $sub = trim($sub);
                    }

                    // Sentenças com até 4 palavras
                    $numPalavras = str_word_count($sub, 0, 'áàãâéêíóôõúçÁÀÃÂÉÊÍÓÔÕÚÇ');
                    if ($numPalavras > 0 && $numPalavras <= 4) {
                        $novaLista[] = $sub;
                    }
                }
            }
        }

        return array_values(array_unique($novaLista));
    }
}
