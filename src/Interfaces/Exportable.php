<?php

/**
 * Exportable - Interface untuk fitur export data
 *
 * Interface ini mendefinisikan kontrak untuk model
 * yang mendukung export data ke file.
 *
 * @package    App\Interfaces
 * @author     Developer
 * @version    1.0.0
 */

namespace App\Interfaces;

/**
 * Interface Exportable
 *
 * Model yang mengimplementasikan interface ini
 * dapat melakukan export data ke format tertentu.
 */
interface Exportable
{
    /**
     * Export data ke format CSV.
     *
     * @param string $filename Nama file output
     * @return string Path file yang dihasilkan
     */
    public function exportToCsv(string $filename): string;
}
