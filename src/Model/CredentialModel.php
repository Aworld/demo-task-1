<?php
declare(strict_types=1);

namespace App\Model;

class CredentialModel
{
    private string $clientId = '';
    private string $email = '';
    private string $name = '';

    /**
     * CredentialModel constructor.
     * @param string $clientId
     * @param string $email
     * @param string $name
     */
    public function __construct(string $clientId, string $email, string $name)
    {
        $this->clientId = $clientId;
        $this->email = $email;
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getClientId(): string
    {
        return $this->clientId;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
