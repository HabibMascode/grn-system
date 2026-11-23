<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'pengguna';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'username',
        'password',
        'name',
        'role'
    ];

    /**
     * Cari user berdasarkan username
     *
     * @param string $username
     * @return array|null
     */
    public function findByUsername(string $username): ?array
    {
        return $this->where('username', $username)->first();
    }

    /**
     * Verifikasi password (mendukung hash maupun plain text database lama)
     *
     * @param string $inputPassword
     * @param string $storedPassword
     * @return bool
     */
    public function verifyPassword(string $inputPassword, string $storedPassword): bool
    {
        // Cek jika password cocok dengan hash bcrypt / argon2
        if (password_verify($inputPassword, $storedPassword)) {
            return true;
        }

        // Cek jika password tersimpan dalam bentuk plain text
        if ($inputPassword === $storedPassword) {
            return true;
        }

        return false;
    }
}
