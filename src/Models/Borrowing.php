<?php

/**
 * Borrowing Model - Mengelola data peminjaman buku
 *
 * Mendemonstrasikan:
 * - Inheritance (extends BaseModel)
 * - Polymorphism (override getAll, create)
 * - Penggunaan external library (Carbon)
 * - Control structures (if/else/elseif, for loop)
 *
 * @package    App\Models
 * @author     Developer
 * @version    1.0.0
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class Borrowing
 *
 * Model untuk tabel borrowings.
 * Mengelola proses peminjaman dan pengembalian buku.
 */
class Borrowing extends BaseModel
{
    /** @var string Nama tabel */
    protected string $table = 'borrowings';

    /** @var array Kolom yang boleh diisi */
    protected array $fillable = [
        'book_id', 'member_id', 'user_id',
        'borrow_date', 'due_date', 'return_date', 'status', 'notes'
    ];

    /**
     * Mengambil semua peminjaman dengan JOIN ke buku, anggota, dan user.
     *
     * Override dari BaseModel::getAll() — Polymorphism.
     *
     * @return array Daftar peminjaman lengkap
     */
    public function getAll(): array
    {
        $sql = "SELECT br.*, 
                       b.title as book_title, 
                       m.name as member_name, 
                       m.member_code,
                       u.full_name as user_name
                FROM borrowings br
                JOIN books b ON br.book_id = b.id
                JOIN members m ON br.member_id = m.id
                JOIN users u ON br.user_id = u.id
                ORDER BY br.id DESC";
        $stmt = $this->db->query($sql);
        $results = $stmt->fetchAll();

        // Menggunakan Carbon untuk format tanggal dan cek keterlambatan
        // Menggunakan for loop (Req 3: pengulangan)
        for ($i = 0; $i < count($results); $i++) {
            $dueDate = Carbon::parse($results[$i]['due_date']);
            $now = Carbon::now();

            // Control structure: if/elseif/else (Req 3)
            if ($results[$i]['status'] === 'returned') {
                $results[$i]['status_label'] = 'Dikembalikan';
                $results[$i]['status_class'] = 'badge-success';
            } elseif ($dueDate->lt($now) && $results[$i]['status'] === 'borrowed') {
                $results[$i]['status_label'] = 'Terlambat';
                $results[$i]['status_class'] = 'badge-danger';
                // Update status di database
                $this->updateStatus($results[$i]['id'], 'overdue');
                $results[$i]['status'] = 'overdue';
            } elseif ($results[$i]['status'] === 'overdue') {
                $results[$i]['status_label'] = 'Terlambat';
                $results[$i]['status_class'] = 'badge-danger';
            } else {
                $results[$i]['status_label'] = 'Dipinjam';
                $results[$i]['status_class'] = 'badge-warning';
            }

            // Format tanggal dengan Carbon
            $results[$i]['borrow_date_formatted'] = Carbon::parse($results[$i]['borrow_date'])
                ->translatedFormat('d M Y');
            $results[$i]['due_date_formatted'] = $dueDate->translatedFormat('d M Y');

            if ($results[$i]['return_date']) {
                $results[$i]['return_date_formatted'] = Carbon::parse($results[$i]['return_date'])
                    ->translatedFormat('d M Y');
            }
        }

        return $results;
    }

    /**
     * Membuat peminjaman baru dan mengurangi stok buku.
     *
     * Override dari BaseModel::create() — Polymorphism.
     *
     * @param array $data Data peminjaman
     * @return bool True jika berhasil
     */
    public function create(array $data): bool
    {
        // Set tanggal peminjaman dan tenggat
        if (!isset($data['borrow_date'])) {
            $data['borrow_date'] = Carbon::now()->toDateString();
        }
        if (!isset($data['due_date'])) {
            $data['due_date'] = Carbon::now()->addDays(DEFAULT_BORROW_DAYS)->toDateString();
        }

        $data['status'] = 'borrowed';

        // Simpan peminjaman
        $result = parent::create($data);

        // Kurangi stok buku jika berhasil
        if ($result) {
            $bookModel = new Book();
            $bookModel->decrementStock((int) $data['book_id']);
        }

        return $result;
    }

    /**
     * Proses pengembalian buku.
     *
     * @param int $borrowingId ID peminjaman
     * @return bool True jika berhasil
     */
    public function returnBook(int $borrowingId): bool
    {
        $borrowing = $this->getById($borrowingId);
        if (!$borrowing || $borrowing['status'] === 'returned') {
            return false;
        }

        $result = $this->update($borrowingId, [
            'return_date' => Carbon::now()->toDateString(),
            'status'      => 'returned',
        ]);

        // Tambah kembali stok buku
        if ($result) {
            $bookModel = new Book();
            $bookModel->incrementStock((int) $borrowing['book_id']);
        }

        return $result;
    }

    /**
     * Update status peminjaman.
     *
     * @param int    $id     ID peminjaman
     * @param string $status Status baru
     * @return bool True jika berhasil
     */
    private function updateStatus(int $id, string $status): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE borrowings SET status = :status WHERE id = :id"
        );
        return $stmt->execute(['status' => $status, 'id' => $id]);
    }

    /**
     * Mengambil peminjaman aktif (belum dikembalikan).
     *
     * @return array Daftar peminjaman aktif
     */
    public function getActiveBorrowings(): array
    {
        $sql = "SELECT br.*, b.title as book_title, m.name as member_name
                FROM borrowings br
                JOIN books b ON br.book_id = b.id
                JOIN members m ON br.member_id = m.id
                WHERE br.status IN ('borrowed', 'overdue')
                ORDER BY br.due_date ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Menghitung statistik peminjaman.
     *
     * Menggunakan array untuk menyimpan statistik (Req 5).
     *
     * @return array Statistik peminjaman
     */
    public function getStatistics(): array
    {
        // Array untuk menyimpan statistik (Req 5)
        $stats = [
            'total'    => 0,
            'borrowed' => 0,
            'returned' => 0,
            'overdue'  => 0,
        ];

        $sql = "SELECT status, COUNT(*) as total FROM borrowings GROUP BY status";
        $stmt = $this->db->query($sql);

        // Loop while (Req 3)
        while ($row = $stmt->fetch()) {
            $stats[$row['status']] = (int) $row['total'];
            $stats['total'] += (int) $row['total'];
        }

        return $stats;
    }
}
