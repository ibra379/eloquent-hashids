<?php

namespace DialloIbrahima\EloquentHashids;

/**
 * A simple, dependency-free hashid encoder/decoder.
 *
 * Uses Base62 encoding with salt-based alphabet shuffling to create
 * unique, reversible hashids from integer IDs.
 */
class HashidEncoder
{
    private const DEFAULT_ALPHABET = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

    private string $alphabet;

    private int $minLength;

    public function __construct(
        private string $salt = '',
        int $minLength = 16,
        ?string $alphabet = null
    ) {
        $this->minLength = max(1, $minLength);
        $this->alphabet = $this->shuffleAlphabet($alphabet ?? self::DEFAULT_ALPHABET);
    }

    /**
     * Encode an integer ID to a hashid string.
     */
    public function encode(int $id): string
    {
        if ($id < 0) {
            return '';
        }

        $base = strlen($this->alphabet);
        $hash = '';

        do {
            $hash = $this->alphabet[$id % $base] . $hash;
            $id = intdiv($id, $base);
        } while ($id > 0);

        // Pad to minimum length using consistent padding based on salt
        while (strlen($hash) < $this->minLength) {
            $padIndex = (ord($this->salt[strlen($hash) % max(1, strlen($this->salt))] ?? 'a') + strlen($hash)) % $base;
            $hash = $this->alphabet[$padIndex] . $hash;
        }

        return $hash;
    }

    /**
     * Decode a hashid string back to the original integer ID.
     */
    public function decode(string $hash): ?int
    {
        if ($hash === '') {
            return null;
        }

        // Remove padding (characters at the start that were added for min length)
        $hash = $this->removePadding($hash);

        if ($hash === '') {
            return 0;
        }

        $base = strlen($this->alphabet);
        $id = 0;

        for ($i = 0; $i < strlen($hash); $i++) {
            $pos = strpos($this->alphabet, $hash[$i]);

            if ($pos === false) {
                return null; // Invalid character
            }

            $id = $id * $base + $pos;
        }

        return $id;
    }

    /**
     * Remove the padding characters from the beginning of the hash.
     */
    private function removePadding(string $hash): string
    {
        $base = strlen($this->alphabet);

        while (strlen($hash) > 1) {
            $expectedPadIndex = (ord($this->salt[(strlen($hash) - 1) % max(1, strlen($this->salt))] ?? 'a') + (strlen($hash) - 1)) % $base;

            if ($hash[0] === $this->alphabet[$expectedPadIndex]) {
                $hash = substr($hash, 1);
            } else {
                break;
            }
        }

        return $hash;
    }

    /**
     * Shuffle the alphabet based on the salt for unique encoding per application.
     */
    private function shuffleAlphabet(string $alphabet): string
    {
        if ($this->salt === '') {
            return $alphabet;
        }

        $chars = str_split($alphabet);
        $saltLength = strlen($this->salt);

        for ($i = count($chars) - 1, $v = 0, $p = 0; $i > 0; $i--, $v++) {
            $v %= $saltLength;
            $p += $int = ord($this->salt[$v]);
            $j = ($int + $v + $p) % $i;

            [$chars[$i], $chars[$j]] = [$chars[$j], $chars[$i]];
        }

        return implode('', $chars);
    }
}
