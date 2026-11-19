<?php
namespace App\Controllers;

use App\Models\PengeluaranModel;

class Laporan extends BaseController
{
    public function index()
    {
        $model = new PengeluaranModel();

        $data = [
            'title' => 'Laporan & Statistik',
            'pengeluaran' => $model->orderBy('tanggal', 'DESC')->findAll(),
            'total' => $model->selectSum('nominal')->first()['nominal'] ?? 0,
        ];

        return view('laporan/index', $data);
    }
}

