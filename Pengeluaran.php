<?php
namespace App\Controllers;

use App\Models\PengeluaranModel;

class Pengeluaran extends BaseController
{
    protected PengeluaranModel $pengeluaran;

    public function __construct()
    {
        $this->pengeluaran = new PengeluaranModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Catatan Pengeluaran Mahasiswa',
            'pengeluaran' => $this->pengeluaran->orderBy('tanggal', 'DESC')->orderBy('id', 'DESC')->findAll(),
            'total' => $this->pengeluaran->selectSum('nominal')->first()['nominal'] ?? 0,
            'jumlah' => $this->pengeluaran->countAllResults(),
        ];

        return view('pengeluaran/index', $data);
    }

    public function create()
    {
        return view('pengeluaran/form', [
            'title' => 'Tambah Pengeluaran',
            'mode' => 'tambah',
            'item' => null,
            'validation' => \Config\Services::validation(),
        ]);
    }

    public function store()
    {
        $rules = [
            'tanggal' => 'required|valid_date[Y-m-d]',
            'kategori' => 'required|max_length[100]',
            'keterangan' => 'required',
            'nominal' => 'required|numeric|greater_than[0]',
        ];

        if (! $this->validate($rules)) {
            return view('pengeluaran/form', [
                'title' => 'Tambah Pengeluaran',
                'mode' => 'tambah',
                'item' => null,
                'validation' => $this->validator,
            ]);
        }

        $this->pengeluaran->save([
            'tanggal' => $this->request->getPost('tanggal'),
            'kategori' => $this->request->getPost('kategori'),
            'keterangan' => trim($this->request->getPost('keterangan')),
            'nominal' => $this->request->getPost('nominal'),
        ]);

        return redirect()->to('/')->with('success', 'Pengeluaran berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $item = $this->pengeluaran->find($id);
        if (! $item) {
            return redirect()->to('/')->with('error', 'Data pengeluaran tidak ditemukan.');
        }

        return view('pengeluaran/form', [
            'title' => 'Edit Pengeluaran',
            'mode' => 'edit',
            'item' => $item,
            'validation' => \Config\Services::validation(),
        ]);
    }

    public function update($id)
    {
        if (! $this->pengeluaran->find($id)) {
            return redirect()->to('/')->with('error', 'Data pengeluaran tidak ditemukan.');
        }

        $rules = [
            'tanggal' => 'required|valid_date[Y-m-d]',
            'kategori' => 'required|max_length[100]',
            'keterangan' => 'required',
            'nominal' => 'required|numeric|greater_than[0]',
        ];

        if (! $this->validate($rules)) {
            return view('pengeluaran/form', [
                'title' => 'Edit Pengeluaran',
                'mode' => 'edit',
                'item' => $this->pengeluaran->find($id),
                'validation' => $this->validator,
            ]);
        }

        $this->pengeluaran->update($id, [
            'tanggal' => $this->request->getPost('tanggal'),
            'kategori' => $this->request->getPost('kategori'),
            'keterangan' => trim($this->request->getPost('keterangan')),
            'nominal' => $this->request->getPost('nominal'),
        ]);

        return redirect()->to('/')->with('success', 'Pengeluaran berhasil diperbarui.');
    }

    public function delete($id)
    {
        if ($this->pengeluaran->find($id)) {
            $this->pengeluaran->delete($id);
            return redirect()->to('/')->with('success', 'Pengeluaran berhasil dihapus.');
        }

        return redirect()->to('/')->with('error', 'Data pengeluaran tidak ditemukan.');
    }
}
