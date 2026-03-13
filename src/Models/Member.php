<?php

/**
 * Member Model - Mengelola data anggota perpustakaan
 *
 * Mendemonstrasikan:
 * - Inheritance (extends BaseModel)
 *
 * @package    App\Models
 * @author     Developer
 * @version    1.0.0
 */

namespace App\Models;

/**
 * Class Member
 *
 * Model untuk tabel members.
 * Mewarisi operasi CRUD dari BaseModel.
 */
class Member extends BaseModel
{
    /** @var string Nama tabel */
    protected string $table = 'members';

    /** @var array Kolom yang boleh diisi */
    protected array $fillable = [
        'member_code', 'name', 'email', 'phone', 'address',
        'membership_date', 'status'
    ];

    /**
     * Generate kode anggota unik.
     *
     * Menggunakan loop do-while untuk memastikan kode unik (Req 3).
     *
     * @return string Kode anggota baru
     */
    public function generateMemberCode(): string
    {
        // Loop do-while: generate sampai kode unik ditemukan (Req 3)
        do {
            $lastMember = $this->db->query(
                "SELECT member_code FROM members ORDER BY id DESC LIMIT 1"
            )->fetch();

            if ($lastMember) {
                $lastNumber = (int) substr($lastMember['member_code'], 4);
                $newNumber  = $lastNumber + 1;
            } else {
                $newNumber = 1;
            }

            $code = 'MBR-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

            // Cek apakah kode sudah ada
            $exists = $this->db->prepare(
                "SELECT COUNT(*) as cnt FROM members WHERE member_code = :code"
            );
            $exists->execute(['code' => $code]);
            $count = $exists->fetch()['cnt'];
        } while ($count > 0);

        return $code;
    }

    /**
     * Mengambil anggota aktif saja.
     *
     * @return array Daftar anggota aktif
     */
    public function getActiveMembers(): array
    {
        $stmt = $this->db->query(
            "SELECT * FROM members WHERE status = 'active' ORDER BY name ASC"
        );
        return $stmt->fetchAll();
    }

    /**
     * Mengambil anggota untuk dropdown select.
     *
     * @return array Array [id => name (code)]
     */
    public function getForSelect(): array
    {
        $stmt = $this->db->query(
            "SELECT id, member_code, name FROM members WHERE status = 'active' ORDER BY name ASC"
        );
        $members = [];
        // Loop foreach (Req 3)
        foreach ($stmt->fetchAll() as $row) {
            $members[$row['id']] = $row['name'] . ' (' . $row['member_code'] . ')';
        }
        return $members;
    }

    /**
     * Mencari anggota.
     *
     * @param string $keyword Keyword pencarian
     * @return array Daftar anggota yang cocok
     */
    public function search(string $keyword): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM members 
             WHERE name LIKE :keyword OR member_code LIKE :keyword2 OR email LIKE :keyword3
             ORDER BY name ASC"
        );
        $stmt->execute([
            'keyword'  => "%{$keyword}%",
            'keyword2' => "%{$keyword}%",
            'keyword3' => "%{$keyword}%",
        ]);
        return $stmt->fetchAll();
    }
}
