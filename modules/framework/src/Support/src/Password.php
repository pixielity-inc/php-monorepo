<?php

declare(strict_types=1);

namespace Pixielity\Support;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

/**
 * Password - Secure Password Generation and Encryption Utility.
 *
 * Provides methods for generating cryptographically secure random passwords
 * and encrypting/decrypting passwords using Laravel's encryption service.
 *
 * ## Features:
 * - Generate random passwords with custom length
 * - Encrypt passwords using AES-256-CBC
 * - Decrypt encrypted passwords
 * - Cryptographically secure random generation
 *
 * ## Security Notes:
 * - Uses Laravel's Crypt facade for encryption (AES-256-CBC)
 * - Random generation uses cryptographically secure random_bytes()
 * - Encrypted passwords are safe for database storage
 * - Never store plaintext passwords
 *
 * ## Examples:
 * ```php
 * // Generate random password
 * $password = Password::generate(16);
 * // 'aB3dE5fG7hJ9kL2m'
 *
 * // Encrypt password for storage
 * $encrypted = Password::encrypt('mySecretPassword');
 * // 'eyJpdiI6IjRGNnQ...' (encrypted string)
 *
 * // Decrypt password
 * $decrypted = Password::decrypt($encrypted);
 * // 'mySecretPassword'
 * ```
 *
 * @see Str::random() For random string generation
 * @see Crypt For Laravel's encryption facade
 * @see Hash For password hashing (recommended for user passwords)
 */
class Password
{
    /**
     * Generate a cryptographically secure random password.
     *
     * Creates a random password of the specified length using Laravel's random
     * string generator, which uses cryptographically secure random_bytes().
     * The password contains alphanumeric characters (uppercase, lowercase, digits).
     *
     * ## Examples:
     * ```php
     * Password::generate();           // 'aB3dE5fG7hJ9' (12 chars)
     * Password::generate(16);         // 'aB3dE5fG7hJ9kL2m' (16 chars)
     * Password::generate(8);          // 'aB3dE5fG' (8 chars)
     * Password::generate(32);         // Long password for high security
     * ```
     *
     * ## Performance:
     * - Time complexity: O(n) where n is password length
     * - Space complexity: O(n)
     * - Uses cryptographically secure random number generator
     *
     * ## Security Notes:
     * - Minimum recommended length: 8 characters
     * - For high-security applications: 16+ characters
     * - Uses random_bytes() for cryptographic security
     * - Does not include special characters by default
     * - Safe for temporary passwords and API keys
     *
     * ## Notes:
     * - Returns null on failure (extremely rare)
     * - Only contains alphanumeric characters
     * - For user passwords, consider using Hash::make() instead
     *
     * @param  int  $len  The length of the password (default: 12)
     * @return string|null The generated random password, or null on failure
     *
     * @see Str::random() For the underlying random generator
     * @see Hash::make() For hashing user passwords
     * @see random_bytes() For the cryptographic random source
     * @since 1.0.0
     */
    public static function generate(int $len = 12): ?string
    {
        return Str::random($len);
    }

    /**
     * Encrypt a password using Laravel's encryption service.
     *
     * Encrypts the plaintext password using AES-256-CBC encryption with a
     * message authentication code (MAC) for integrity verification. The encrypted
     * value is safe for database storage.
     *
     * ## Examples:
     * ```php
     * $encrypted = Password::encrypt('mySecretPassword');
     * // 'eyJpdiI6IjRGNnQ3...' (base64 encoded encrypted string)
     *
     * // Store in database
     * $user->encrypted_password = Password::encrypt($password);
     * $user->save();
     * ```
     *
     * ## Performance:
     * - Time complexity: O(n) where n is password length
     * - Space complexity: O(n) for encrypted output
     * - Includes MAC generation for integrity
     *
     * ## Security Notes:
     * - Uses AES-256-CBC encryption
     * - Includes MAC for tamper detection
     * - Requires APP_KEY to be set in .env
     * - Different output each time (includes random IV)
     * - Safe for database storage
     *
     * ## Notes:
     * - Returns null on encryption failure
     * - Output is base64 encoded JSON
     * - For user authentication, use Hash::make() instead
     * - This is for reversible encryption, not password hashing
     *
     * @param  string  $plain  The plaintext password to encrypt
     * @return string|null The encrypted password, or null on failure
     *
     * @see Crypt::encrypt() For the underlying encryption
     * @see decrypt() For decrypting the password
     * @see Hash::make() For one-way password hashing
     * @since 1.0.0
     */
    public static function encrypt(string $plain): ?string
    {
        // Encrypt the password using the Encryptor facade.
        return Crypt::encrypt($plain);
    }

    /**
     * Decrypt an encrypted password using Laravel's encryption service.
     *
     * Decrypts a password that was encrypted using the encrypt() method.
     * Verifies the MAC to ensure the encrypted value hasn't been tampered with.
     *
     * ## Examples:
     * ```php
     * $encrypted = Password::encrypt('mySecretPassword');
     * $decrypted = Password::decrypt($encrypted);
     * // 'mySecretPassword'
     *
     * // Retrieve from database
     * $encrypted = $user->encrypted_password;
     * $plaintext = Password::decrypt($encrypted);
     * ```
     *
     * ## Performance:
     * - Time complexity: O(n) where n is encrypted string length
     * - Space complexity: O(n) for decrypted output
     * - Includes MAC verification
     *
     * ## Security Notes:
     * - Verifies MAC before decryption
     * - Throws exception if MAC is invalid (tampered data)
     * - Requires same APP_KEY used for encryption
     * - Returns null if decryption fails
     *
     * ## Notes:
     * - Returns null on decryption failure
     * - Throws exception if data is tampered
     * - Input must be from encrypt() method
     * - For password verification, use Hash::check() instead
     *
     * @param  string  $enc  The encrypted password
     * @return string|null The decrypted plaintext password, or null on failure
     *
     * @throws DecryptException If MAC is invalid
     *
     * @see Crypt::decrypt() For the underlying decryption
     * @see encrypt() For encrypting passwords
     * @see Hash::check() For verifying hashed passwords
     * @since 1.0.0
     */
    public static function decrypt(string $enc): ?string
    {
        // Decrypt the password using the Encryptor facade.
        return Crypt::decrypt($enc);
    }
}
