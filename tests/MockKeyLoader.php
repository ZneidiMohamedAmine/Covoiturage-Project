<?php

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

use Lexik\Bundle\JWTAuthenticationBundle\Encoder\LcobucciJWTEncoder;
use Lexik\Bundle\JWTAuthenticationBundle\Services\KeyLoader\KeyLoaderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWSProvider\JWSProviderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Signature\CreatedJWS;

// Mock implementation of KeyLoaderInterface for demonstration purposes
class MockKeyLoader implements KeyLoaderInterface
{
    private $privateKey;
    private $publicKey;

    public function __construct($privateKey, $publicKey)
    {
        $this->privateKey = $privateKey;
        $this->publicKey = $publicKey;
    }

    public function loadKey($type)
    {
        return $type === 'private' ? $this->privateKey : $this->publicKey;
    }

    public function getPassphrase()
    {
        return null; // Or provide an implementation if needed
    }

    public function getPublicKey()
    {
        return $this->publicKey;
    }

    public function getSigningKey()
    {
        return $this->privateKey;
    }

    public function getAdditionalPublicKeys()
    {
        return []; // Or implement according to your application's needs
    }
}

// Implementation of JWSProviderInterface using MockKeyLoader
class MockJWSProvider implements JWSProviderInterface
{
    private $keyLoader;

    public function __construct(KeyLoaderInterface $keyLoader)
    {
        $this->keyLoader = $keyLoader;
    }

    public function create(array $payload, array $header = [])
    {
        // Implement create method using $this->keyLoader
    }

    public function load($token)
    {
        // Implement load method using $this->keyLoader
    }
}

// Simulating the create function with a fake payload and header
function simulateCreate()
{
    // Instantiate your MockJWSProvider with mocked dependencies (MockKeyLoader)
    $keyLoader = new MockKeyLoader('mock_private_key', 'mock_public_key');
    $jwsProvider = new MockJWSProvider($keyLoader);

    // Instantiate your LcobucciJWTEncoder with the MockJWSProvider
    $jwsEncoder = new LcobucciJWTEncoder($jwsProvider, 'HS256', 3600);

    // Simulated payload and header
    $payload = [
        'sub' => '1234567890',
        'email' => 'ayoub@yahoo.com',
        'iat' => time(),
    ];

    $header = [
        'typ' => 'JWT',
        'alg' => 'HS256',
    ];

    // Call the encode function
    try {
        $token = $jwsEncoder->encode($payload, $header);

        // Output the result
        echo "Simulated Created JWS Token: \n";
        echo "Token: " . $token . "\n";
    } catch (\Exception $e) {
        echo "Error occurred: " . $e->getMessage() . "\n";
    }
}

// Call the simulation function
simulateCreate();
