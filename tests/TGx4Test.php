<?php

use GX4\Util\TGx4;
use PHPUnit\Framework\TestCase;

class TGx4Test extends TestCase
{
    public function testValidaDocumentoComCpfValido()
    {
        $cpf = '123.456.789-09'; // CPF de exemplo válido (ajuste conforme necessário)
        $resultado = TGx4::validaDocumento($cpf, 'CPF');

        $this->assertTrue($resultado['valido']);
        $this->assertEquals('CPF', $resultado['tipo']);
    }

    public function testValidaDocumentoComCpfInvalido()
    {
        $cpf = '123.456.789-00'; // CPF inválido
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('CPF inválido: dígito verificador incorreto.');

        TGx4::validaDocumento($cpf, 'CPF');
    }

    public function testValidaDocumentoComCnpjValido()
    {
        $cnpj = '12.345.678/0001-95'; // CNPJ de exemplo válido (ajuste conforme necessário)
        $resultado = TGx4::validaDocumento($cnpj, 'CNPJ');

        $this->assertTrue($resultado['valido']);
        $this->assertEquals('CNPJ', $resultado['tipo']);
    }

    public function testValidaDocumentoComCnpjInvalido()
    {
        $cnpj = '12.345.678/0001-00'; // CNPJ inválido
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('CNPJ inválido: dígito verificador incorreto.');

        TGx4::validaDocumento($cnpj, 'CNPJ');
    }

    public function testValidaDocumentoComRgValido()
    {
        $rg = '12345678'; // RG válido de exemplo
        $resultado = TGx4::validaDocumento($rg, 'RG');

        $this->assertTrue($resultado['valido']);
        $this->assertEquals('RG', $resultado['tipo']);
    }

    public function testValidaDocumentoComRgInvalido()
    {
        $rg = '123456SP'; // RG inválido
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('RG inválido: deve conter apenas números.');

        TGx4::validaDocumento($rg, 'RG');
    }

    public function testValidaDocumentoComTituloEleitorValido()
    {
        $titulo = '104130630469';  // Título de Eleitor válido
        $resultado = TGx4::validaDocumento($titulo, 'TITULO_ELEITOR');

        $this->assertTrue($resultado['valido']);
        $this->assertEquals('Título de Eleitor', $resultado['tipo']);
    }

    public function testValidaDocumentoComTituloEleitorInvalido()
    {
        $titulo = '1234567ad89012'; // Título de Eleitor inválido
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Título de Eleitor inválido: deve conter exatamente 12 dígitos numéricos.');

        TGx4::validaDocumento($titulo, 'TITULO_ELEITOR');
    }

    public function testValidaDocumentoComNisPisPasepValido()
    {
        $nisPisPasep = '12345678901'; // NIS/PIS/PASEP válido
        $resultado = TGx4::validaDocumento($nisPisPasep, 'NIS_PIS_PASEP');


        $this->assertTrue($resultado['valido']);
        $this->assertEquals('NIS/PIS/PASEP', $resultado['tipo']);
    }

    public function testValidaDocumentoComNisPisPasepComLetrasInvalido()
    {
        $nisPisPasep = 'a123R45Z#678900'; // NIS/PIS/PASEP inválido
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('NIS/PIS/PASEP inválido: deve conter apenas números.');

        TGx4::validaDocumento($nisPisPasep, 'NIS_PIS_PASEP');
    }

    public function testValidaDocumentoComNisPisPasepNumerosAMaisInvalido()
    {
        $nisPisPasep = '12345678900432343'; // NIS/PIS/PASEP inválido
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('NIS/PIS/PASEP inválido: deve conter exatamente 11 dígitos.');

        TGx4::validaDocumento($nisPisPasep, 'NIS_PIS_PASEP');
    }

    public function testValidaDocumentoComCnhValido()
    {
        $cnh = '04970296204'; // CNH válida
        $resultado = TGx4::validaDocumento($cnh, 'CNH');

        $this->assertTrue($resultado['valido']);
        $this->assertEquals('CNH', $resultado['tipo']);
    }

    public function testValidaDocumentoComCnhApenasNumerosInvalido()
    {
        $cnh = '1$234W5678a900'; // CNH inválida
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('CNH inválida: deve conter apenas números.');

        TGx4::validaDocumento($cnh, 'CNH');
    }

    public function testValidaDocumentoComCnhApenas11DigitosInvalido()
    {
        $cnh = '12345678900456'; // CNH inválida
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('CNH inválida: deve conter exatamente 11 dígitos.');

        TGx4::validaDocumento($cnh, 'CNH');
    }

    public function testValidaDocumentoComPassaporteValido()
    {
        $passaporte = 'ABC123456'; // Passaporte válido
        $resultado = TGx4::validaDocumento($passaporte, 'PASSAPORTE');

        $this->assertTrue($resultado['valido']);
        $this->assertEquals('Passaporte', $resultado['tipo']);
    }

    public function testValidaDocumentoComPassaporteInvalido()
    {
        $passaporte = '123456789'; // Passaporte inválido
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Documento inválido: tipo ou formato incorreto.');

        TGx4::validaDocumento($passaporte, 'PASSAPORTE');
    }

    public function testValidaDocumentoComTipoInvalido()
    {
        $documento = '12345678901'; // Documento com tipo inválido
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Documento inválido: tipo ou formato incorreto.');

        TGx4::validaDocumento($documento, 'TIPO_INVALIDO');
    }

    public function testFormataDocumentoComCpfValido()
    {
        $cpf = '12345678909';
        $resultado = TGx4::formataDocumento($cpf);

        $this->assertEquals('123.456.789-09', $resultado);
    }

    public function testFormataDocumentoComCnpjValido()
    {
        $cnpj = '12345678000195';
        $resultado = TGx4::formataDocumento($cnpj);

        $this->assertEquals('12.345.678/0001-95', $resultado);
    }

    public function testFormataDocumentoComCpfFormatado()
    {
        $cpf = '123.456.789-09'; // já formatado, deve ser tratado corretamente
        $resultado = TGx4::formataDocumento($cpf);

        $this->assertEquals('123.456.789-09', $resultado);
    }

    public function testFormataDocumentoComCnpjFormatado()
    {
        $cnpj = '12.345.678/0001-95'; // já formatado, deve ser tratado corretamente
        $resultado = TGx4::formataDocumento($cnpj);

        $this->assertEquals('12.345.678/0001-95', $resultado);
    }

    public function testFormataDocumentoInvalido()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Documento inválido para formatação.');

        TGx4::formataDocumento('123'); // número com tamanho inválido
    }

    public function testSemanaRetornaDomingo()
    {
        $resultado = TGx4::semana(0);
        $this->assertEquals(['numero' => 0, 'descricao' => 'Domingo'], $resultado);
    }

    public function testSemanaRetornaSegunda()
    {
        $resultado = TGx4::semana(1);
        $this->assertEquals(['numero' => 1, 'descricao' => 'Segunda-feira'], $resultado);
    }

    public function testSemanaRetornaTerca()
    {
        $resultado = TGx4::semana(2);
        $this->assertEquals(['numero' => 2, 'descricao' => 'Terça-feira'], $resultado);
    }

    public function testSemanaRetornaQuarta()
    {
        $resultado = TGx4::semana(3);
        $this->assertEquals(['numero' => 3, 'descricao' => 'Quarta-feira'], $resultado);
    }

    public function testSemanaRetornaQuinta()
    {
        $resultado = TGx4::semana(4);
        $this->assertEquals(['numero' => 4, 'descricao' => 'Quinta-feira'], $resultado);
    }

    public function testSemanaRetornaSexta()
    {
        $resultado = TGx4::semana(5);
        $this->assertEquals(['numero' => 5, 'descricao' => 'Sexta-feira'], $resultado);
    }

    public function testSemanaRetornaSabado()
    {
        $resultado = TGx4::semana(6);
        $this->assertEquals(['numero' => 6, 'descricao' => 'Sábado'], $resultado);
    }

    public function testSemanaComValorInvalidoDisparaExcecao()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Dia da semana inválido! Deve ser um número entre 0 e 6.");

        TGx4::semana(7);
    }

    public function testSemanaComValorNegativoDisparaExcecao()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Dia da semana inválido! Deve ser um número entre 0 e 6.");

        TGx4::semana(-1);
    }

    public function testNormalizaTextoComMaiusculas()
    {
        $texto = "ÀçãäÔ";
        $esperado = "ACAAO";

        $this->assertEquals($esperado, TGx4::normalizaTexto($texto, true));
    }

    public function testNormalizaTextoComMinusculas()
    {
        $texto = "ÀçãäÔ";
        $esperado = "acaao";

        $this->assertEquals($esperado, TGx4::normalizaTexto($texto, false));
    }

    public function testNormalizaTextoComCaracteresEspeciais()
    {
        $texto = "Olá, mundo! R$100,00 #TOP";
        $esperado = "OLA MUNDO R10000 TOP";

        $this->assertEquals($esperado, TGx4::normalizaTexto($texto, true));
    }

    public function testNormalizaTextoComEspacosExtras()
    {
        $texto = "   Olá     Mundo   ";
        $esperado = "OLA     MUNDO"; // Espaços internos são mantidos, apenas trim aplicado

        $this->assertEquals($esperado, TGx4::normalizaTexto($texto, true));
    }

    public function testNormalizaTextoComCaracteresEmMaiuscula()
    {
        $texto = "ÁÉÍÓÚÇ½¿#";
        $esperado = "AEIOUC";

        $this->assertEquals($esperado, TGx4::normalizaTexto($texto, true));
    }

    public function testNormalizaTextoComCaracteresEmMinuscula()
    {
        $texto = "áéíóúç";
        $esperado = "aeiouc";

        $this->assertEquals($esperado, TGx4::normalizaTexto($texto, false));
    }

    public function testApplyMaskWithValidMaskAndValue()
    {
        $mask = '###-###';
        $value = '123456';
        $resultado = TGx4::applyMask($mask, $value);

        $this->assertEquals('123-456', $resultado);
    }

    public function testApplyMaskWithValueLargerThanMask()
    {
        $mask = '###-###';
        $value = '123456789';
        $resultado = TGx4::applyMask($mask, $value);

        $this->assertEquals('123-456', $resultado); // Trunca o valor excedente
    }

    public function testApplyMaskWithValueSmallerThanMask()
    {
        $mask = '###-###';
        $value = '12';
        $resultado = TGx4::applyMask($mask, $value);

        $this->assertEquals('12#-###', $resultado); // Deixa os campos não preenchidos
    }

    public function testApplyMaskWithEmptyValue()
    {
        $mask = '###-###';
        $value = '';
        $resultado = TGx4::applyMask($mask, $value);

        $this->assertEquals('', $resultado); // Retorna o valor vazio
    }

    public function testApplyMaskWithSpacesInValue()
    {
        $mask = '###-###';
        $value = ' 123  456 ';
        $resultado = TGx4::applyMask($mask, $value);

        $this->assertEquals('123-456', $resultado); // Remove os espaços extras
    }

    public function testApplyMaskWithCustomMask()
    {
        $mask = '(##) #####-####';
        $value = '1234567890';
        $resultado = TGx4::applyMask($mask, $value);

        $this->assertEquals('(12) 34567-890#', $resultado); // Aplica a máscara personalizada
    }

    public function testApplyMaskWithValueShorterThanMaskWithNoResult()
    {
        $mask = '####-####';
        $value = '12';
        $resultado = TGx4::applyMask($mask, $value);

        $this->assertEquals('12##-####', $resultado); // Deve manter o restante da máscara intacto
    }

    public function testApplyMaskWithNoMask()
    {
        $mask = '';
        $value = '123456';
        $resultado = TGx4::applyMask($mask, $value);

        $this->assertEquals('123456', $resultado); // Não aplica nenhuma máscara
    }

    public function testGeneratePasswordWithValidLength()
    {
        // Teste com comprimento válido
        $password = TGx4::generatePassword(12);
        $this->assertEquals(12, strlen($password)); // A senha gerada deve ter 12 caracteres
        $this->assertNotEmpty($password); // A senha não deve ser vazia
    }

    public function testGeneratePasswordWithInvalidLengthZero()
    {
        // Teste com comprimento inválido (0)
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("O tamanho da senha deve ser maior que 0.");

        TGx4::generatePassword(0);
    }

    public function testGeneratePasswordWithInvalidLengthNegative()
    {
        // Teste com comprimento negativo
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("O tamanho da senha deve ser maior que 0.");

        TGx4::generatePassword(-5);
    }

    public function testGeneratePasswordWithLengthOne()
    {
        // Teste com comprimento de 1
        $password = TGx4::generatePassword(1);
        $this->assertEquals(1, strlen($password)); // A senha gerada deve ter 1 caractere
        $this->assertNotEmpty($password); // A senha não deve ser vazia
    }

    public function testGeneratePasswordWithLengthFifteen()
    {
        // Teste com comprimento de 15
        $password = TGx4::generatePassword(15);
        $this->assertEquals(15, strlen($password)); // A senha gerada deve ter 15 caracteres
        $this->assertNotEmpty($password); // A senha não deve ser vazia
    }

    public function testGeneratedPasswordIsRandom()
    {
        // Teste para garantir que as senhas geradas são aleatórias
        $password1 = TGx4::generatePassword(12);
        $password2 = TGx4::generatePassword(12);
        $this->assertNotEquals($password1, $password2); // A senha gerada duas vezes deve ser diferente
    }

    public function testRemoveMaskWithValidValue()
    {
        // Teste com um valor que inclui máscaras (como pontos e traços)
        $value = '123.456-789';
        $result = TGx4::removeMask($value);
        $this->assertEquals('123456789', $result); // A máscara deve ser removida
    }

    public function testRemoveMaskWithOnlyNumbers()
    {
        // Teste com um valor numérico sem máscara
        $value = '123456789';
        $result = TGx4::removeMask($value);
        $this->assertEquals('123456789', $result); // O valor já está sem máscara, deve retornar igual
    }

    public function testRemoveMaskWithAlphanumericValue()
    {
        // Teste com um valor alfanumérico
        $value = 'abc123!@#xyz';
        $result = TGx4::removeMask($value);
        $this->assertEquals('abc123xyz', $result); // A máscara e caracteres especiais devem ser removidos
    }

    public function testRemoveMaskWithEmptyValue()
    {
        // Teste com um valor vazio
        $value = '';
        $result = TGx4::removeMask($value);
        $this->assertEquals('', $result); // O valor vazio deve retornar vazio
    }

    public function testRemoveMaskWithOnlySpecialCharacters()
    {
        // Teste com valor composto apenas por caracteres especiais
        $value = '!@#$%^&*()';
        $result = TGx4::removeMask($value);
        $this->assertEquals('', $result); // Todos os caracteres especiais devem ser removidos
    }

    public function testRemoveMaskWithWhitespace()
    {
        // Teste com espaços em branco no valor
        $value = '   123  456   ';
        $result = TGx4::removeMask($value);
        $this->assertEquals('123456', $result); // Os espaços em branco devem ser removidos
    }

    public function testMbStrPadRight()
    {
        // Teste de preenchimento à direita
        $str = 'Test';
        $result = TGx4::mbStrPad($str, 10, '*', STR_PAD_RIGHT);
        $this->assertEquals('Test******', $result); // A string deve ser preenchida à direita
    }

    public function testMbStrPadLeft()
    {
        // Teste de preenchimento à esquerda
        $str = 'Test';
        $result = TGx4::mbStrPad($str, 10, '*', STR_PAD_LEFT);
        $this->assertEquals('******Test', $result); // A string deve ser preenchida à esquerda
    }

    public function testMbStrPadBoth()
    {
        // Teste de preenchimento em ambos os lados
        $str = 'Test';
        $result = TGx4::mbStrPad($str, 10, '*', STR_PAD_BOTH);
        $this->assertEquals('***Test***', $result); // A string deve ser preenchida igualmente à esquerda e à direita
    }

    public function testMbStrPadNoPaddingNeeded()
    {
        // Teste quando a string já tem o tamanho adequado ou superior
        $str = 'Test';
        $result = TGx4::mbStrPad($str, 4, '*', STR_PAD_RIGHT);
        $this->assertEquals('Test', $result); // Não deve aplicar preenchimento
    }

    public function testMbStrPadLongerString()
    {
        // Teste com uma string maior que o comprimento desejado
        $str = 'LongTestString';
        $result = TGx4::mbStrPad($str, 10, '*', STR_PAD_RIGHT);
        $this->assertEquals('LongTestString', $result); // A string não deve ser alterada
    }

    public function testMbStrPadWithDifferentPadCharacter()
    {
        // Teste com caractere de preenchimento diferente
        $str = 'Test';
        $result = TGx4::mbStrPad($str, 10, '#', STR_PAD_RIGHT);
        $this->assertEquals('Test######', $result); // O caractere de preenchimento deve ser '#'
    }

    public function testMbStrPadWithZeroLength()
    {
        // Teste com comprimento zero
        $str = 'Test';
        $result = TGx4::mbStrPad($str, 0, '*', STR_PAD_RIGHT);
        $this->assertEquals('Test', $result); // O comprimento é zero, a string deve permanecer inalterada
    }

    public function testSaveIniFileWithoutSections(): void
    {
        $data = [
            'host' => 'localhost',
            'port' => 3306,
            'debug' => true,
            'name' => 'Gustavo',
        ];

        $tempFile = tempnam(sys_get_temp_dir(), 'ini_test_');
        TGx4::saveIniFile($data, $tempFile, false);

        $expected = file_get_contents(__DIR__ . '/config/expected_config_without_sections.ini');
        $actual = file_get_contents($tempFile);

        $this->assertSame(
            str_replace(["\r\n", "\r"], "\n", $expected),
            str_replace(["\r\n", "\r"], "\n", $actual)
        );

        unlink($tempFile);
    }

    public function testSaveIniFileWithSections(): void
    {
        $data = [
            'database' => [
                'host' => 'localhost',
                'port' => 3306,
            ],
            'app' => [
                'debug' => true,
                'env' => 'production',
            ],
        ];

        $tempFile = tempnam(sys_get_temp_dir(), 'ini_test_');
        TGx4::saveIniFile($data, $tempFile, true);

        $expected = file_get_contents(__DIR__ . '/config/expected_config_with_sections.ini');
        $actual   = file_get_contents($tempFile);

        $this->assertSame(
            str_replace(["\r\n", "\r"], "\n", $expected),
            str_replace(["\r\n", "\r"], "\n", $actual)
        );

        unlink($tempFile);
    }

    public function testFormatIniValueString(): void
    {
        $method = new ReflectionMethod(TGx4::class, 'formatIniValue');
        $method->setAccessible(true);

        $result = $method->invoke(null, 'host', 'localhost');
        $this->assertSame('host = "localhost"' . "\n", $result);
    }

    public function testFormatIniValueInt(): void
    {
        $method = new ReflectionMethod(TGx4::class, 'formatIniValue');
        $method->setAccessible(true);

        $result = $method->invoke(null, 'port', 3306);
        $this->assertSame('port = "3306"' . "\n", $result);
    }

    public function testFormatIniValueBooleanTrue(): void
    {
        $method = new ReflectionMethod(TGx4::class, 'formatIniValue');
        $method->setAccessible(true);

        $result = $method->invoke(null, 'debug', true);
        $this->assertSame('debug = "1"' . "\n", $result);
    }

    public function testFormatIniValueBooleanFalse(): void
    {
        $method = new ReflectionMethod(TGx4::class, 'formatIniValue');
        $method->setAccessible(true);

        $result = $method->invoke(null, 'debug', false);
        $this->assertSame('debug = "0"' . "\n", $result);
    }

    public function testFormatIniValueWithoutNewline(): void
    {
        $method = new ReflectionMethod(TGx4::class, 'formatIniValue');
        $method->setAccessible(true);

        $result = $method->invoke(null, 'name', 'Gustavo', false);
        $this->assertSame('name = "Gustavo"', $result);
    }

    public function testFormatIniValueNull(): void
    {
        $method = new ReflectionMethod(TGx4::class, 'formatIniValue');
        $method->setAccessible(true);

        $result = $method->invoke(null, 'token', null);
        $this->assertSame('token = ""' . "\n", $result); // null é convertido para string vazia
    }

    public function testFormatIniValueArray(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Valor do tipo array não é permitido em arquivos .ini.');

        $method = new ReflectionMethod(TGx4::class, 'formatIniValue');
        $method->setAccessible(true);

        $method->invoke(null, 'values', ['a', 'b']);
    }

    public function testFormatIniValueObject(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Valor do tipo object não é permitido em arquivos .ini.');

        $method = new ReflectionMethod(TGx4::class, 'formatIniValue');
        $method->setAccessible(true);

        $method->invoke(null, 'objectKey', new stdClass());
    }

    public function testFormatIniValueResource(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Valor do tipo resource não é permitido em arquivos .ini.');

        $method = new ReflectionMethod(TGx4::class, 'formatIniValue');
        $method->setAccessible(true);

        $handle = fopen('php://temp', 'r');
        try {
            $method->invoke(null, 'resourceKey', $handle);
        } finally {
            fclose($handle);
        }
    }

    public function testIsAValidEAN13ValidEAN(): void
    {
        $validEAN = '1234567890128'; // Um exemplo de EAN-13 válido
        $result = (new TGx4())->isAValidEAN13($validEAN);

        $this->assertTrue($result);
    }

    public function testIsAValidEAN13InvalidEAN(): void
    {
        $invalidEAN = '1234567890123'; // Um exemplo de EAN-13 inválido
        $result = (new TGx4())->isAValidEAN13($invalidEAN);

        $this->assertFalse($result);
    }

    public function testIsAValidEAN13InvalidEANLength(): void
    {
        $invalidEAN = '12345'; // Um EAN com menos de 13 dígitos
        $result = (new TGx4())->isAValidEAN13($invalidEAN);

        $this->assertFalse($result);
    }

    public function testIsAValidEAN13NonNumericEAN(): void
    {
        $invalidEAN = '12345ABCDE678'; // EAN com caracteres não numéricos
        $result = (new TGx4())->isAValidEAN13($invalidEAN);

        $this->assertFalse($result);
    }

    public function testIsAValidEAN13EmptyEAN(): void
    {
        $invalidEAN = ''; // EAN vazio
        $result = (new TGx4())->isAValidEAN13($invalidEAN);

        $this->assertFalse($result);
    }

    public function testIsAValidEAN13ValidEANWithLeadingZeros(): void
    {
        $validEAN = '0000000000000'; // Um EAN-13 válido com zeros à esquerda
        $result = (new TGx4())->isAValidEAN13($validEAN);

        $this->assertTrue($result);
    }

    public function testIsValidBarcodeValidEAN8(): void
    {
        $validBarcode = '12345670'; // Exemplo de código de barras EAN-8 válido
        $result = (new TGx4())->isValidBarcode($validBarcode);

        $this->assertTrue($result);
    }

    public function testIsValidBarcodeValidEAN12(): void
    {
        $validBarcode = '123456789012'; // Exemplo de código de barras EAN-12 válido
        $result = (new TGx4())->isValidBarcode($validBarcode);

        $this->assertTrue($result);
    }

    public function testIsValidBarcodeValidEAN13(): void
    {
        $validBarcode = '1234567890128'; // Exemplo de código de barras EAN-13 válido
        $result = (new TGx4())->isValidBarcode($validBarcode);

        $this->assertTrue($result);
    }

    public function testIsValidBarcodeValidEAN14(): void
    {
        $validBarcode = '1845678901001'; // Exemplo de código de barras EAN-14 válido
        $result = (new TGx4())->isValidBarcode($validBarcode);

        $this->assertTrue($result);
    }

    public function testIsValidBarcodeInvalidLength(): void
    {
        $invalidBarcode = '12345'; // Código de barras com comprimento inválido
        $result = (new TGx4())->isValidBarcode($invalidBarcode);

        $this->assertFalse($result);
    }

    public function testIsValidBarcodeNonNumericBarcode(): void
    {
        $invalidBarcode = '12345ABCDE'; // Código de barras com caracteres não numéricos
        $result = (new TGx4())->isValidBarcode($invalidBarcode);

        $this->assertFalse($result);
    }

    public function testIsValidBarcodeInvalidChecksum(): void
    {
        $invalidBarcode = '1234567890129'; // Exemplo de código de barras com erro no dígito verificador
        $result = (new TGx4())->isValidBarcode($invalidBarcode);

        $this->assertFalse($result);
    }

    public function testIsValidBarcodeValidEAN18(): void
    {
        $validBarcode = '000000000000001236'; // Exemplo de código de barras EAN-18 válido
        $result = (new TGx4())->isValidBarcode($validBarcode);

        $this->assertTrue($result);
    }

    public function testIsValidBarcodeInvalidChecksumWithInvalidLength(): void
    {
        $invalidBarcode = '123456789012345'; // Código de barras com comprimento inválido e erro no dígito verificador
        $result = (new TGx4())->isValidBarcode($invalidBarcode);

        $this->assertFalse($result);
    }
}
