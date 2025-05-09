<?php

namespace GX4\Exception;

class TGx4Exception extends \PDOException
{
    protected $detailedMessage;

    /**
     * TGx4Exception constructor.
     *
     * @param \Exception|null $exception
     */
    public function __construct(\Exception $exception = null)
    {
        $this->detailedMessage = $this->extractDetailedMessage($exception->getMessage());
        parent::__construct($this->detailedMessage, (int) $exception->getCode(), $exception);
    }

    /**
     * Extrai a mensagem detalhada do erro retornado pelo PostgreSQL.
     *
     * @param string $message
     * @return string
     */
    private function extractDetailedMessage($message)
    {
        $matches = [];
        if (preg_match('/ERROR:\s*(.*?)(?:\s*CONTEXT:|$)/', $message, $matches)) {
            return trim($matches[1]);
        }
        return trim($message); // Fallback caso a regex não funcione
    }

    /**
     * Retorna a mensagem detalhada extraída.
     *
     * @return string
     */
    public function getDetailedMessage()
    {
        return $this->detailedMessage;
    }
}
