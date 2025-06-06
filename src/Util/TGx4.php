<?php

namespace GX4\Util;

use Exception;
use Normalizer;
use InvalidArgumentException;

class TGx4
{
    // Constantes para os dias da semana (agora o domingo é 0) teste 123
    const DOMINGO = 0;
    const SEGUNDA = 1;
    const TERCA   = 2;
    const QUARTA  = 3;
    const QUINTA  = 4;
    const SEXTA   = 5;
    const SABADO  = 6;

    /**
     * Exibe um ou mais valores com `var_dump` formatado em HTML.
     *
     * @param mixed ...$valores Um ou mais valores a serem exibidos.
     * @return void
     */
    public static function debug(...$valores): void
    {
        foreach ($valores as $valor) {
            echo '<pre>';
            var_dump($valor);
            echo '</pre>';
        }
    }

    /**
     * Valida documentos como CPF, CNPJ, RG, Título de Eleitor, NIS/PIS/PASEP, CNH e Passaporte.
     *
     * Este método realiza a validação de diferentes tipos de documentos, incluindo CPF, CNPJ, RG,
     * Título de Eleitor, NIS/PIS/PASEP, CNH e Passaporte. Dependendo do tipo de documento informado,
     * ele verifica a estrutura e os dígitos verificadores para garantir a validade do número.
     * Caso o número do documento seja inválido, uma exceção será lançada com a razão do erro.
     *
     * @param string $valor O número do documento a ser validado (pode ser CPF, CNPJ, RG, Título de Eleitor, NIS/PIS/PASEP, CNH ou Passaporte).
     * @param string $tipoDocumento O tipo do documento a ser validado (por exemplo, 'CPF', 'CNPJ', 'RG', etc.).
     * @return array Retorna um array com as chaves 'valido' (booleano indicando a validade) e 'tipo' (tipo do documento: 'CPF', 'CNPJ', 'RG', etc.).
     * @throws Exception Lança uma exceção caso o documento seja inválido, com uma mensagem explicando o motivo.
     */
    public static function validaDocumento(string $valor, $tipoDocumento): array
    {
        $numero = preg_replace('/[^a-zA-Z0-9]/', '', $valor);

        if ($tipoDocumento === 'CPF' || $tipoDocumento === 'CNPJ') {
            if (strlen($numero) === 11) {
                if (preg_match('/^(\d)\1{10}$/', $numero)) {
                    throw new Exception("CPF inválido: repetição de dígitos.");
                }

                for ($t = 9; $t < 11; $t++) {
                    $soma = 0;
                    for ($i = 0; $i < $t; $i++) {
                        $soma += $numero[$i] * (($t + 1) - $i);
                    }
                    $digitoEsperado = ($soma * 10) % 11;
                    $digitoEsperado = ($digitoEsperado === 10) ? 0 : $digitoEsperado;
                    if ($numero[$t] != $digitoEsperado) {
                        throw new Exception("CPF inválido: dígito verificador incorreto.");
                    }
                }
                return ['valido' => true, 'tipo' => 'CPF'];

            } elseif (strlen($numero) === 14) {
                if (preg_match('/^(\d)\1{13}$/', $numero)) {
                    throw new Exception("CNPJ inválido: repetição de dígitos.");
                }

                $peso1 = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
                $peso2 = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];

                $soma1 = 0;
                $soma2 = 0;

                for ($i = 0; $i < 12; $i++) {
                    $soma1 += $numero[$i] * $peso1[$i];
                }
                $digito1 = ($soma1 % 11) < 2 ? 0 : 11 - ($soma1 % 11);

                for ($i = 0; $i < 13; $i++) {
                    $soma2 += $numero[$i] * $peso2[$i];
                }
                $digito2 = ($soma2 % 11) < 2 ? 0 : 11 - ($soma2 % 11);

                if ($numero[12] != $digito1 || $numero[13] != $digito2) {
                    throw new Exception("CNPJ inválido: dígito verificador incorreto.");
                }

                return ['valido' => true, 'tipo' => 'CNPJ'];
            }
        }

        if ($tipoDocumento === 'RG') {
            $tamanho = strlen($numero);
            if ($tamanho < 7 || $tamanho > 9) {
                throw new Exception("RG inválido: deve conter entre 7 e 9 dígitos numéricos.");
            }
            if (!preg_match('/^\d+$/', $numero)) {
                throw new Exception("RG inválido: deve conter apenas números.");
            }
            return ['valido' => true, 'tipo' => 'RG'];
        }

        if ($tipoDocumento === 'TITULO_ELEITOR') {
            if (!preg_match('/^\d{12}$/', $numero)) {
                throw new Exception("Título de Eleitor inválido: deve conter exatamente 12 dígitos numéricos.");
            }
            return ['valido' => true, 'tipo' => 'Título de Eleitor'];
        }

        if ($tipoDocumento === 'NIS_PIS_PASEP') {
            if (!preg_match('/^\d+$/', $numero)) {
                throw new Exception("NIS/PIS/PASEP inválido: deve conter apenas números.");
            }
            if (strlen($numero) !== 11) {
                throw new Exception("NIS/PIS/PASEP inválido: deve conter exatamente 11 dígitos.");
            }
            return ['valido' => true, 'tipo' => 'NIS/PIS/PASEP'];
        }

        if ($tipoDocumento === 'CNH') {
            if (!preg_match('/^\d+$/', $numero)) {
                throw new Exception("CNH inválida: deve conter apenas números.");
            }
            if (strlen($numero) !== 11) {
                throw new Exception("CNH inválida: deve conter exatamente 11 dígitos.");
            }
            return ['valido' => true, 'tipo' => 'CNH'];
        }

        if ($tipoDocumento === 'PASSAPORTE') {
            if (strlen($numero) === 9 && preg_match('/^[A-Za-z]{3}\d{6}$/', $numero)) {
                return ['valido' => true, 'tipo' => 'Passaporte'];
            }
        }

        throw new Exception("Documento inválido: tipo ou formato incorreto.");
    }

    /**
     * Formata um CPF ou CNPJ automaticamente
     *
     * @param string $valor
     * @return string
     */
    public static function formataDocumento(string $valor): string
    {
        $numero = preg_replace('/\D/', '', $valor);

        if (strlen($numero) === 11) {
            return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $numero);
        }

        if (strlen($numero) === 14) {
            return preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $numero);
        }

        return $numero; // Retorna o número sem formatação se não for CPF ou CNPJ
    }

    /**
     * Retorna um array contendo o número e a descrição do dia da semana.
     *
     * @param int $semana Número do dia da semana (0 a 6), onde 0 é domingo.
     * @return array Array com as chaves 'numero' e 'descricao'.
     * @throws Exception Se o número do dia não estiver entre 0 e 6.
     */
    public static function semana(int $semana): array
    {
        $dias = [
            self::DOMINGO => ['numero' => self::DOMINGO, 'descricao' => 'Domingo'],
            self::SEGUNDA => ['numero' => self::SEGUNDA, 'descricao' => 'Segunda-feira'],
            self::TERCA   => ['numero' => self::TERCA, 'descricao' => 'Terça-feira'],
            self::QUARTA  => ['numero' => self::QUARTA, 'descricao' => 'Quarta-feira'],
            self::QUINTA  => ['numero' => self::QUINTA, 'descricao' => 'Quinta-feira'],
            self::SEXTA   => ['numero' => self::SEXTA, 'descricao' => 'Sexta-feira'],
            self::SABADO  => ['numero' => self::SABADO, 'descricao' => 'Sábado'],
        ];

        if (!array_key_exists($semana, $dias)) {
            throw new Exception("Dia da semana inválido! Deve ser um número entre 0 e 6.");
        }

        return $dias[$semana];
    }

    /**
     * Normaliza um texto removendo acentos e caracteres especiais.
     *
     * @param string $valor Texto a ser normalizado.
     * @param bool $maiusculas Define se o texto retornado deve ser em maiúsculas (true) ou minúsculas (false).
     * @return string Texto limpo, com ou sem caixa alta.
     */
    public static function normalizaTexto(string $valor, bool $maiusculas = true): string
    {
        if (!class_exists(Normalizer::class)) {
            throw new Exception('A extensão php-intl é necessária.');
        }

        // Remove acentuação
        $texto = Normalizer::normalize($valor, Normalizer::FORM_D);
        $texto = preg_replace('/\p{Mn}+/u', '', $texto); // marcas de acento

        // Remove tudo que não for letras A-Z, números 0-9 ou espaço
        $texto = preg_replace('/[^A-Za-z0-9\s]/u', '', $texto);

        // Normaliza múltiplos espaços
        // $texto = preg_replace('/\s+/', ' ', $texto);

        $texto = trim($texto);
        return $maiusculas ? mb_strtoupper($texto) : mb_strtolower($texto);
    }

    /**
     * Aplica uma máscara ao valor informado, utilizando o caractere "#" como posição a ser preenchida.
     *
     * @param string $mask Máscara desejada (use "#" como marcador de posição).
     * @param string $value Valor que será aplicado na máscara.
     * @return string Valor formatado com a máscara.
     */
    public static function applyMask(string $mask, string $value): string
    {
        if (empty($value) OR empty($mask)) {
            return $value;
        }

        $value = preg_replace('/\s+/', '', $value);
        $result = '';
        $index = 0;

        for ($i = 0; $i < strlen($mask); $i++) {
            if ($mask[$i] === '#' && isset($value[$index])) {
                $result .= $value[$index++];
            } else {
                $result .= $mask[$i];
            }
        }

        return $result;
    }

    /**
     * Gera uma senha aleatória com os caracteres definidos.
     *
     * @param int $length Tamanho desejado da senha.
     * @return string Senha gerada aleatoriamente.
     * @throws InvalidArgumentException Se o tamanho for menor que 1.
     */
    public static function generatePassword(int $length): string
    {
        if ($length < 1) {
            throw new InvalidArgumentException("O tamanho da senha deve ser maior que 0.");
        }

        $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ$!@&*$(){}';
        $characters = str_split($keyspace);
        shuffle($characters);
        return substr(implode('', $characters), 0, $length);
    }

    /**
     * Remove qualquer máscara de um valor, deixando apenas letras e números.
     *
     * @param string $value Valor com máscara.
     * @return string Valor limpo, sem símbolos ou espaços.
     */
    public static function removeMask(string $value): string
    {
        return preg_replace('/[^a-z\d]+/i', '', $value);
    }

    /**
     * Preenche uma string multibyte até o tamanho desejado, respeitando o alinhamento.
     *
     * @param string $str String a ser preenchida.
     * @param int $len Comprimento final desejado.
     * @param string $pad Caracter(es) de preenchimento.
     * @param int $align Tipo de alinhamento (STR_PAD_LEFT, STR_PAD_RIGHT, STR_PAD_BOTH).
     * @return string String formatada com preenchimento.
     */
    public static function mbStrPad(string $str, int $len, string $pad, int $align = STR_PAD_RIGHT): string
    {
        $strLen = mb_strlen($str);

        if ($strLen >= $len) {
            return $str;
        }

        $diff = $len - $strLen;
        $padding = mb_substr(str_repeat($pad, $diff), 0, $diff);

        switch ($align) {
            case STR_PAD_BOTH:
                $diffHalf = (int)($diff / 2 + 0.5);
                $leftPad = mb_substr(str_repeat($pad, $diffHalf), 0, $diffHalf);
                $rightPad = mb_substr(str_repeat($pad, $diff - $diffHalf), 0, $diff - $diffHalf);
                return $leftPad . $str . $rightPad;

            case STR_PAD_LEFT:
                return $padding . $str;

            case STR_PAD_RIGHT:
            default:
                return $str . $padding;
        }
    }

    /**
     * Grava um array associativo em um arquivo .ini.
     *
     * @param array $data Array associativo a ser salvo.
     * @param string $file Caminho completo do arquivo a ser salvo.
     * @param bool $hasSections Define se o array possui seções.
     * @return bool Retorna true em caso de sucesso, false caso contrário.
     */
    public static function saveIniFile(array $data, string $file, bool $hasSections = false): bool
    {
        $lines = [];

        if ($hasSections) {
            foreach ($data as $section => $values) {
                $lines[] = "[{$section}]";
                foreach ($values as $key => $value) {
                    $lines[] = self::formatIniValue($key, $value, false);
                }
            }
        } else {
            foreach ($data as $key => $value) {
                $lines[] = self::formatIniValue($key, $value, false);
            }
        }

        // Junta as linhas SEM adicionar quebra de linha ao final
        $content = implode("\n", $lines);

        // Força o uso de \n (evita CRLF em alguns sistemas)
        $content = str_replace("\r\n", "\n", $content);

        return file_put_contents($file, $content) !== false;
    }

    /**
     * Formata um valor para o conteúdo do arquivo INI.
     *
     * @param string $key Chave do parâmetro.
     * @param mixed $value Valor associado.
     * @return string Linha formatada.
     */
    private static function formatIniValue(string $key, $value, bool $appendNewline = true): string
    {
        // Verifica se o valor é do tipo array, object ou resource
        if (is_array($value) || is_object($value) || is_resource($value)) {
            throw new InvalidArgumentException("Valor do tipo " . gettype($value) . " não é permitido em arquivos .ini.");
        }

        // Converte booleano para '1' ou '0'
        if (is_bool($value)) {
            $value = $value ? '1' : '0';
        }

        // Formata o valor como chave = "valor"
        $formatted = sprintf('%s = "%s"', $key, $value);

        // Se necessário, adiciona a quebra de linha
        return $appendNewline ? $formatted . "\n" : $formatted;
    }




    /**
     * Verifica se um código EAN-13 é válido.
     *
     * @param string $ean Código EAN-13 a ser validado.
     * @return bool Retorna true se o código for válido, false caso contrário.
     */
    public function isAValidEAN13($ean)
    {
        echo $ean; // Provavelmente para depuração — pode ser removido em produção.
        $sumEvenIndexes = 0;
        $sumOddIndexes  = 0;

        $eanAsArray = array_map('intval', str_split($ean));

        if (!$this->has13Numbers($eanAsArray)) {
            return false;
        }

        for ($i = 0; $i < count($eanAsArray) - 1; $i++) {
            if ($i % 2 === 0) {
                $sumOddIndexes  += $eanAsArray[$i];
            } else {
                $sumEvenIndexes += $eanAsArray[$i];
            }
        }

        $rest = ($sumOddIndexes + (3 * $sumEvenIndexes)) % 10;

        if ($rest !== 0) {
            $rest = 10 - $rest;
        }

        return $rest === $eanAsArray[12];
    }

    /**
     * Verifica se o array contém exatamente 13 números (para EAN-13).
     *
     * @param array $ean Array com os dígitos do EAN.
     * @return bool Retorna true se tiver 13 números, false caso contrário.
     */
    private function has13Numbers(array $ean)
    {
        return count($ean) === 13;
    }

    /**
     * Valida um código de barras (GTIN-8, GTIN-12, GTIN-13, GTIN-14, GSIN, SSCC).
     *
     * @param string|int $barcode Código de barras a ser validado.
     * @return bool Retorna true se for válido conforme o dígito verificador, false caso contrário.
     */
    function isValidBarcode($barcode)
    {
        $barcode = (string) $barcode;

        // Aceita apenas dígitos
        if (!preg_match("/^[0-9]+$/", $barcode)) {
            return false;
        }

        // Verifica se o comprimento é um dos padrões aceitos
        $l = strlen($barcode);
        if (!in_array($l, [8, 12, 13, 14, 17, 18])) {
            return false;
        }

        $check = substr($barcode, -1);           // Último dígito (verificador)
        $barcode = substr($barcode, 0, -1);       // Remove o dígito verificador

        $sum_even = $sum_odd = 0;
        $even = true;

        while (strlen($barcode) > 0) {
            $digit = substr($barcode, -1);
            if ($even) {
                $sum_even += 3 * $digit;
            } else {
                $sum_odd += $digit;
            }
            $even = !$even;
            $barcode = substr($barcode, 0, -1);
        }

        $sum = $sum_even + $sum_odd;
        $sum_rounded_up = ceil($sum / 10) * 10;

        return ($check == ($sum_rounded_up - $sum));
    }
}
