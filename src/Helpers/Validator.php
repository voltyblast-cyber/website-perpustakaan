<?php

/**
 * Validator - Validasi input dari form
 *
 * Helper class untuk memvalidasi data input pengguna
 * sebelum diproses atau disimpan ke database.
 *
 * Mendemonstrasikan:
 * - Namespace terpisah (App\Helpers) — Req 8
 * - Fungsi/prosedur — Req 4
 * - Penggunaan array — Req 5
 * - Control structures — Req 3
 *
 * @package    App\Helpers
 * @author     Developer
 * @version    1.0.0
 */

namespace App\Helpers;

/**
 * Class Validator
 *
 * Menyediakan validasi input dengan aturan yang fleksibel.
 */
class Validator
{
    /** @var array Daftar error validasi */
    private array $errors = [];

    /** @var array Data yang sedang divalidasi */
    private array $data = [];

    /**
     * Menjalankan validasi pada data.
     *
     * Menggunakan array rules dan foreach loop (Req 3, 5).
     *
     * @param array $data  Data input yang akan divalidasi
     * @param array $rules Aturan validasi dalam format ['field' => 'required|min:3|max:100']
     * @return bool True jika valid, false jika ada error
     */
    public function validate(array $data, array $rules): bool
    {
        $this->data   = $data;
        $this->errors = [];

        // Loop foreach pada rules (Req 3: pengulangan, Req 5: array)
        foreach ($rules as $field => $ruleString) {
            $fieldRules = explode('|', $ruleString);
            $value = $data[$field] ?? null;

            // Loop foreach pada setiap aturan
            foreach ($fieldRules as $rule) {
                $this->applyRule($field, $value, $rule);
            }
        }

        return empty($this->errors);
    }

    /**
     * Menerapkan satu aturan validasi.
     *
     * Menggunakan if/elseif/else (Req 3: percabangan).
     *
     * @param string      $field Nama field
     * @param mixed       $value Nilai field
     * @param string      $rule  Aturan validasi
     * @return void
     */
    private function applyRule(string $field, $value, string $rule): void
    {
        $fieldLabel = ucfirst(str_replace('_', ' ', $field));

        // Percabangan if/elseif/else (Req 3)
        if ($rule === 'required') {
            if (empty($value) && $value !== '0') {
                $this->errors[$field][] = "{$fieldLabel} wajib diisi.";
            }
        } elseif (str_starts_with($rule, 'min:')) {
            $min = (int) substr($rule, 4);
            if (strlen((string) $value) < $min) {
                $this->errors[$field][] = "{$fieldLabel} minimal {$min} karakter.";
            }
        } elseif (str_starts_with($rule, 'max:')) {
            $max = (int) substr($rule, 4);
            if (strlen((string) $value) > $max) {
                $this->errors[$field][] = "{$fieldLabel} maksimal {$max} karakter.";
            }
        } elseif ($rule === 'email') {
            if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $this->errors[$field][] = "{$fieldLabel} harus berformat email valid.";
            }
        } elseif ($rule === 'numeric') {
            if (!empty($value) && !is_numeric($value)) {
                $this->errors[$field][] = "{$fieldLabel} harus berupa angka.";
            }
        } elseif ($rule === 'date') {
            if (!empty($value) && !strtotime($value)) {
                $this->errors[$field][] = "{$fieldLabel} harus berformat tanggal valid.";
            }
        }
    }

    /**
     * Mendapatkan semua error.
     *
     * @return array Daftar error
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Mendapatkan error untuk field tertentu.
     *
     * @param string $field Nama field
     * @return array Error untuk field tersebut
     */
    public function getFieldErrors(string $field): array
    {
        return $this->errors[$field] ?? [];
    }

    /**
     * Cek apakah field tertentu memiliki error.
     *
     * @param string $field Nama field
     * @return bool True jika ada error
     */
    public function hasError(string $field): bool
    {
        return isset($this->errors[$field]);
    }

    /**
     * Mendapatkan pesan error pertama.
     *
     * @return string Pesan error pertama atau string kosong
     */
    public function getFirstError(): string
    {
        foreach ($this->errors as $fieldErrors) {
            if (!empty($fieldErrors)) {
                return $fieldErrors[0];
            }
        }
        return '';
    }
}
