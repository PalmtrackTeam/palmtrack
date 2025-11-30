<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pemasukan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Riwayat Pemasukan</h4>
                    </div>
                    <div class="card-body">
                        <!-- Filter Section -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <label for="tanggal_awal" class="form-label">Tanggal Awal</label>
                                <input type="date" class="form-control" id="tanggal_awal">
                            </div>
                            <div class="col-md-3">
                                <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
                                <input type="date" class="form-control" id="tanggal_akhir">
                            </div>
                            <div class="col-md-3">
                                <label for="sumber_pemasukan" class="form-label">Sumber Pemasukan</label>
                                <select class="form-select" id="sumber_pemasukan">
                                    <option value="">Semua</option>
                                    <option value="penjualan_buah">Penjualan Buah</option>
                                    <option value="lainnya">Lainnya</option>
                                </select>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button class="btn btn-primary w-100 me-2" onclick="filterData()">
                                    <i class="fas fa-filter me-1"></i> Filter
                                </button>
                                <button class="btn btn-outline-secondary w-100" onclick="resetFilter()">
                                    <i class="fas fa-refresh me-1"></i> Reset
                                </button>
                            </div>
                        </div>

                        <!-- Summary Cards -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="card bg-success text-white">
                                    <div class="card-body d-flex justify-content-between">
                                        <div>
                                            <h6 class="card-title">Total Pemasukan</h6>
                                            <h3 id="total-pemasukan">Rp 0</h3>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-money-bill-wave fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-info text-white">
                                    <div class="card-body d-flex justify-content-between">
                                        <div>
                                            <h6 class="card-title">Penjualan Buah</h6>
                                            <h3 id="total-penjualan">Rp 0</h3>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-apple-alt fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-warning text-white">
                                    <div class="card-body d-flex justify-content-between">
                                        <div>
                                            <h6 class="card-title">Pemasukan Lainnya</h6>
                                            <h3 id="total-lainnya">Rp 0</h3>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-coins fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Table -->
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="table-pemasukan">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Sumber Pemasukan</th>
                                        <th>Total Pemasukan</th>
                                        <th>Keterangan</th>
                                        <th>Pencatat</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pemasukan as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                                        <td>
                                            <span class="badge {{ $item->sumber_pemasukan == 'penjualan_buah' ? 'bg-success' : 'bg-warning' }}">
                                                {{ $item->sumber_pemasukan_text }}
                                            </span>
                                        </td>
                                        <td class="fw-bold text-success">Rp {{ number_format($item->total_pemasukan, 0, ',', '.') }}</td>
                                        <td>{{ $item->keterangan ?: '-' }}</td>
                                        <td>{{ $item->pencatat->nama_lengkap ?? 'N/A' }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" onclick="showDetail({{ $item->id_pemasukan }})">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger" onclick="hapusPemasukan({{ $item->id_pemasukan }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            <i class="fas fa-database fa-2x mb-2"></i><br>
                                            Tidak ada data pemasukan
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted">
                                Menampilkan {{ $pemasukan->firstItem() ?? 0 }} - {{ $pemasukan->lastItem() ?? 0 }} dari {{ $pemasukan->total() }} data
                            </div>
                            <nav>
                                {{ $pemasukan->links() }}
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail -->
    <div class="modal fade" id="detailModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Pemasukan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="detailContent"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <div class="modal fade" id="hapusModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus data pemasukan ini?</p>
                    <p class="text-danger"><small>Tindakan ini tidak dapat dibatalkan.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let pemasukanIdToDelete = null;

        function formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka);
        }

        function formatTanggal(tanggal) {
            return new Date(tanggal).toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
        }

        function filterData() {
            const tanggalAwal = document.getElementById('tanggal_awal').value;
            const tanggalAkhir = document.getElementById('tanggal_akhir').value;
            const sumberPemasukan = document.getElementById('sumber_pemasukan').value;
            let url = '{{ route("admin.riwayat-pemasukan") }}?';
            let params = [];
            if (tanggalAwal) params.push(`tanggal_awal=${tanggalAwal}`);
            if (tanggalAkhir) params.push(`tanggal_akhir=${tanggalAkhir}`);
            if (sumberPemasukan) params.push(`sumber_pemasukan=${sumberPemasukan}`);
            window.location.href = url + params.join('&');
        }

        function resetFilter() {
            window.location.href = '{{ route("admin.riwayat-pemasukan") }}';
        }

        function showDetail(id) {
            fetch(`/admin/pemasukan/${id}`)
                .then(res => res.json())
                .then(data => {
                    const pencatat = data.pencatat ? data.pencatat.name : 'N/A';
                    const penjualanInfo = data.penjualan ? `
                        <div class="row mt-3">
                            <div class="col-12">
                                <strong>Info Penjualan:</strong><br>
                                ID Penjualan: ${data.penjualan.id_penjualan}
                            </div>
                        </div>` : '';
                    
                    document.getElementById('detailContent').innerHTML = `
                        <div class="row">
                            <div class="col-md-6"><strong>ID Pemasukan:</strong><br>${data.id_pemasukan}</div>
                            <div class="col-md-6"><strong>Tanggal:</strong><br>${formatTanggal(data.tanggal)}</div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6"><strong>Sumber Pemasukan:</strong><br>
                                <span class="badge ${data.sumber_pemasukan === 'penjualan_buah' ? 'bg-success' : 'bg-warning'}">
                                    ${data.sumber_pemasukan_text}
                                </span>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12"><strong>Total Pemasukan:</strong><br><h4 class="text-success">${formatRupiah(data.total_pemasukan)}</h4></div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12"><strong>Keterangan:</strong><br>${data.keterangan || '-'}</div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6"><strong>Pencatat:</strong><br>${pencatat}</div>
                            <div class="col-md-6"><strong>Dicatat pada:</strong><br>${new Date(data.created_at).toLocaleString('id-ID')}</div>
                        </div>
                        ${penjualanInfo}
                    `;
                    new bootstrap.Modal(document.getElementById('detailModal')).show();
                })
                .catch(err => {
                    console.error(err);
                    Swal.fire('Error', 'Terjadi kesalahan saat mengambil data.', 'error');
                });
        }

        function hapusPemasukan(id) {
            pemasukanIdToDelete = id;
            new bootstrap.Modal(document.getElementById('hapusModal')).show();
        }

        document.getElementById('confirmDelete').addEventListener('click', function() {
            if (!pemasukanIdToDelete) return;
            fetch(`/admin/pemasukan/${pemasukanIdToDelete}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Berhasil!', data.message, 'success').then(() => location.reload());
                } else {
                    Swal.fire('Error!', data.message, 'error');
                }
            })
            .catch(err => {
                console.error(err);
                Swal.fire('Error', 'Terjadi kesalahan saat menghapus.', 'error');
            })
            .finally(() => {
                bootstrap.Modal.getInstance(document.getElementById('hapusModal')).hide();
                pemasukanIdToDelete = null;
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('tanggal_awal')) document.getElementById('tanggal_awal').value = urlParams.get('tanggal_awal');
            if (urlParams.get('tanggal_akhir')) document.getElementById('tanggal_akhir').value = urlParams.get('tanggal_akhir');
            if (urlParams.get('sumber_pemasukan')) document.getElementById('sumber_pemasukan').value = urlParams.get('sumber_pemasukan');
            hitungSummary();
        });

        function hitungSummary() {
            let totalPemasukan = 0, totalPenjualan = 0, totalLainnya = 0;
            document.querySelectorAll('#table-pemasukan tbody tr').forEach(row => {
                const totalCell = row.cells[3];
                const sumberCell = row.cells[2];
                if (totalCell) {
                    const total = parseInt(totalCell.textContent.replace(/[^0-9]/g, '')) || 0;
                    totalPemasukan += total;
                    const sumberText = sumberCell.textContent.toLowerCase();
                    if (sumberText.includes('penjualan') || sumberText.includes('buah')) totalPenjualan += total;
                    else totalLainnya += total;
                }
            });
            document.getElementById('total-pemasukan').textContent = formatRupiah(totalPemasukan);
            document.getElementById('total-penjualan').textContent = formatRupiah(totalPenjualan);
            document.getElementById('total-lainnya').textContent = formatRupiah(totalLainnya);
        }
    </script>

    <style>
        .badge { font-size: 0.75em; }
        .card { border: none; border-radius: 10px; }
        .table th { border-top: none; font-weight: 600; }
        .btn-sm { padding: 0.25rem 0.5rem; font-size: 0.875rem; }
        .table-responsive { border-radius: 8px; overflow: hidden; }
    </style>
</body>
</html>
