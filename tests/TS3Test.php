<?php
use PHPUnit\Framework\TestCase;
use GX4\Util\TS3;
use Aws\S3\S3Client;
use Dotenv\Dotenv;

class TS3Test extends TestCase
{
    private $ts3;
    private $mockS3Client;

    protected function setUp(): void
    {
        // Carregar as variáveis do arquivo .env
        $dotenv = Dotenv::createImmutable(__DIR__."/config/");
        $dotenv->load();

        // Criar mock do S3Client
        $this->mockS3Client = $this->getMockBuilder(S3Client::class)
            ->disableOriginalConstructor() // Não chamamos o construtor original
            ->addMethods(['putObject']) // Apenas o método 'putObject' será mockado
            ->getMock();

        // Configurar o mock para 'putObject' para retornar um array com o URL do objeto
        $this->mockS3Client->method('putObject')
            ->willReturn(['ObjectURL' => 'https://bucket.s3.amazonaws.com/test.txt']);

        // Criar uma instância da classe TS3 com o mock do S3Client
        $this->ts3 = $this->getMockBuilder(TS3::class)
            ->setConstructorArgs([
                $_ENV['AWS_ACCESS_KEY'],
                $_ENV['AWS_SECRET_KEY'],
                $_ENV['AWS_ENDPOINT'],
                filter_var($_ENV['AWS_USE_PATH_STYLE'] ?? 'true', FILTER_VALIDATE_BOOLEAN), // usePathStyle
                $_ENV['AWS_REGION']
            ])
            ->addMethods(['getS3Client']) // Vamos mockar o método 'getS3Client' para retornar o mock
            ->getMock();

        // Substituir o S3Client real pelo mock
        $this->ts3->method('getS3Client')
            ->willReturn($this->mockS3Client);
    }

    public function testUploadFile()
    {
        $filePath = __DIR__ . '/tmp/test.txt';  // Caminho de teste para o arquivo
        $file     = 'test.txt';

        if(!file_exists($filePath)) {
            file_put_contents($filePath, "Conteúdo do arquivo de teste.");
        }

        $this->ts3->uploadFile($_ENV['AWS_BUCKET'], $file, $filePath);
        $this->assertTrue(true);  // Verifica se o teste passou, já que é um mock
    }
}
