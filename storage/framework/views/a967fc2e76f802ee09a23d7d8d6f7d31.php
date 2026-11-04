<?php $__env->startSection('title', 'Kelola Data Warga Penyandang Disabilitas'); ?>

<?php $__env->startSection('content'); ?>
<div class="surface-card overflow-hidden">
    <div class="p-4 d-flex flex-wrap justify-content-between align-items-center gap-3" style="border-bottom: 1px solid var(--border-hairline);">
        <div class="flex-grow-1" style="max-width: 380px;">
            <label for="searchWarga" class="visually-hidden">Cari Nama atau NIK Warga</label>
            <input type="search" id="searchWarga" class="form-control" placeholder="Cari nama, NIK, atau kelurahan...">
        </div>
        <a href="<?php echo e(route('admin.warga.create')); ?>" class="btn-civic-primary text-decoration-none">
            <i class="bi bi-plus-lg me-1" aria-hidden="true"></i> Tambah Data Warga
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-minimal mb-0" id="tableWarga">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Identitas Warga</th>
                    <th>Domisili (Kec/Kel)</th>
                    <th>Kategori Disabilitas</th>
                    <th>Titik Koordinat</th>
                    <th class="text-end">Tindakan</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $wargas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $w): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="tabular-nums"><?php echo e($i + 1); ?></td>
                    <td>
                        <div class="fw-bold"><?php echo e($w->nama_lengkap); ?></div>
                        <div class="small text-secondary tabular-nums">NIK: <?php echo e($w->nik); ?> | WA: <?php echo e($w->no_hp ?? '-'); ?></div>
                        <?php if(!$w->is_verified): ?>
                            <span class="badge bg-warning text-dark mt-1">Ajuan Mandiri (Perlu Cek)</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="fw-semibold">Kec. <?php echo e($w->kecamatan); ?></div>
                        <div class="small text-secondary">Kel. <?php echo e($w->kelurahan); ?> — <?php echo e($w->alamat_lengkap); ?></div>
                    </td>
                    <td>
                        <span class="px-2 py-1 rounded-2 small fw-bold" style="background: var(--bg-subtle);">
                            <?php echo e($w->kategori_disabilitas ?? 'Belum diisi'); ?>

                        </span>
                    </td>
                    <td class="tabular-nums small text-secondary">
                        <?php if($w->latitude && $w->longitude): ?>
                            <?php echo e(number_format($w->latitude, 5)); ?>,<br><?php echo e(number_format($w->longitude, 5)); ?>

                        <?php else: ?>
                            <span class="text-danger">Belum diset</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-end">
                        <div class="d-inline-flex gap-1">
                            <a href="<?php echo e(route('admin.warga.edit', $w->id)); ?>" class="btn btn-sm btn-outline-primary rounded-3">Edit</a>
                            <form action="<?php echo e(route('admin.warga.destroy', $w->id)); ?>" method="POST" onsubmit="return confirm('Hapus data warga ini beserta riwayat pengajuannya?')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-3">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" class="text-center py-5 text-secondary">Belum ada data warga terdata.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    document.getElementById('searchWarga').addEventListener('input', function(e) {
        const q = e.target.value.toLowerCase();
        document.querySelectorAll('#tableWarga tbody tr').forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(q) ? '' : 'none';
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sidisab\resources\views/admin/warga-index.blade.php ENDPATH**/ ?>