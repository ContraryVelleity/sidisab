
<?php $__env->startSection('title', 'Ringkasan Eksekutif Dapil III'); ?>

<?php $__env->startSection('content'); ?>
<!-- Metrik Utama -->
<div class="row g-4 mb-5 tabular-nums">
    <div class="col-md-3">
        <div class="surface-card p-4 h-100">
            <span class="text-secondary small d-block mb-1">Warga Disabilitas Terdata</span>
            <div class="fs-2 fw-bold"><?php echo e($totalWarga); ?> <small class="fs-6 fw-normal text-secondary">Jiwa</small></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="surface-card p-4 h-100">
            <span class="text-secondary small d-block mb-1">Total Permohonan Alat</span>
            <div class="fs-2 fw-bold"><?php echo e($totalAjuan); ?> <small class="fs-6 fw-normal text-secondary">Ajuan</small></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="surface-card p-4 h-100">
            <span class="text-secondary small d-block mb-1">Bantuan Terealisasi</span>
            <div class="fs-2 fw-bold text-success"><?php echo e($statusCount['Terealisasi']); ?> <small class="fs-6 fw-normal text-secondary">Unit</small></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="surface-card p-4 h-100">
            <span class="text-secondary small d-block mb-1">Ajuan Mandiri Baru</span>
            <div class="fs-2 fw-bold" style="color: #D97706;"><?php echo e($belumVerifikasi); ?> <small class="fs-6 fw-normal text-secondary">Perlu Cek</small></div>
        </div>
    </div>
</div>

<!-- Grafik Visual -->
<div class="row g-4 mb-5">
    <div class="col-lg-7">
        <div class="surface-card p-4 h-100">
            <h5 class="fw-bold mb-1">Progres Penyaluran Alat Bantu</h5>
            <p class="text-secondary small mb-4">Perbandingan jumlah permohonan yang menunggu, diproses, dan terealisasi.</p>
            <canvas id="chartStatus" height="130"></canvas>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="surface-card p-4 h-100">
            <h5 class="fw-bold mb-1">Proporsi Wilayah Dapil III</h5>
            <p class="text-secondary small mb-4">Sebaran warga antara Kecamatan Cipondoh dan Kecamatan Pinang.</p>
            <canvas id="chartKecamatan" height="130"></canvas>
        </div>
    </div>
</div>

<!-- Tabel 5 Permohonan Terbaru -->
<div class="surface-card overflow-hidden">
    <div class="p-4 d-flex justify-content-between align-items-center" style="border-bottom: 1px solid var(--border-hairline);">
        <h5 class="fw-bold mb-0">Permohonan Alat Bantu Terbaru</h5>
        <a href="<?php echo e(route('admin.pengajuan.index')); ?>" class="btn btn-sm btn-outline-primary rounded-3 fw-bold">Kelola Semua</a>
    </div>
    <div class="table-responsive">
        <table class="table table-minimal mb-0">
            <thead>
                <tr>
                    <th>Nama Warga</th>
                    <th>Wilayah</th>
                    <th>Kategori &amp; Alat</th>
                    <th>Tanggal Ajuan</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $ajuanTerbaru; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="fw-bold"><?php echo e($item->warga->nama_lengkap ?? '-'); ?></td>
                        <td>Kel. <?php echo e($item->warga->kelurahan ?? '-'); ?>, Kec. <?php echo e($item->warga->kecamatan ?? '-'); ?></td>
                        <td><?php echo e($item->warga->kategori_disabilitas ?? '-'); ?> — <strong><?php echo e($item->jenis_alat); ?></strong></td>
                        <td class="tabular-nums"><?php echo e($item->tanggal_pengajuan ? $item->tanggal_pengajuan->format('d/m/Y') : '-'); ?></td>
                        <td><span class="status-pill status-<?php echo e($item->status); ?>"><?php echo e($item->status); ?></span></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="5" class="text-center text-secondary py-4">Belum ada data pengajuan.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    new Chart(document.getElementById('chartStatus'), {
        type: 'bar',
        data: {
            labels: ['Menunggu Verifikasi', 'Sedang Diproses', 'Terealisasi'],
            datasets: [{
                label: 'Jumlah Permohonan',
                data: [<?php echo e($statusCount['Menunggu']); ?>, <?php echo e($statusCount['Diproses']); ?>, <?php echo e($statusCount['Terealisasi']); ?>],
                backgroundColor: ['#D97706', '#0F52BA', '#15803D'],
                borderRadius: 8,
                maxBarThickness: 48
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
        }
    });

    new Chart(document.getElementById('chartKecamatan'), {
        type: 'doughnut',
        data: {
            labels: ['Kec. Cipondoh', 'Kec. Pinang'],
            datasets: [{
                data: [<?php echo e($kecamatanCount['Cipondoh']); ?>, <?php echo e($kecamatanCount['Pinang']); ?>],
                backgroundColor: ['#0F52BA', '#4F46E5'],
                borderWidth: 0
            }]
        },
        options: {
            cutout: '68%',
            plugins: { legend: { position: 'bottom' } }
        }
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sidisab\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>