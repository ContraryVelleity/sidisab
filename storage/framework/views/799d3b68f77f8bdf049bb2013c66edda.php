<?php $__env->startSection('title', 'Manajemen Pengajuan Alat Bantu'); ?>

<?php $__env->startSection('content'); ?>
<div class="row g-4">
    <!-- Form Tambah Pengajuan -->
    <div class="col-lg-4">
        <div class="surface-card p-4">
            <h5 class="fw-bold mb-3">Tambah Permohonan Baru</h5>
            <form action="<?php echo e(route('admin.pengajuan.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="mb-3">
                    <label for="warga_id" class="form-label fw-bold small">Pilih Warga Terdata</label>
                    <select id="warga_id" name="warga_id" class="form-select" required>
                        <option value="">-- Pilih Warga --</option>
                        <?php $__currentLoopData = $wargas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $w): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($w->id); ?>"><?php echo e($w->nama_lengkap); ?> (<?php echo e($w->kelurahan); ?>)</option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="jenis_alat" class="form-label fw-bold small">Jenis Alat Bantu</label>
                    <select id="jenis_alat" name="jenis_alat" class="form-select" required>
                        <option value="Kursi Roda">Kursi Roda</option>
                        <option value="Kruk">Kruk</option>
                        <option value="Tongkat Penuntun">Tongkat Penuntun</option>
                        <option value="Alat Bantu Dengar">Alat Bantu Dengar</option>
                        <option value="Kaki/Tangan Palsu">Kaki/Tangan Palsu</option>
                        <option value="Lainnya">Lainnya</option>
                        <optgroup label="Jenis dari BNBA 2026">
                            <option value="Kursi Roda Standart">Kursi Roda Standart</option>
                            <option value="Kursi Roda 3 in 1">Kursi Roda 3 in 1</option>
                            <option value="Walker">Walker</option>
                            <option value="Nutrisi">Nutrisi</option>
                            <option value="Sepatu AFO (Ankle Foot Orthosis)">Sepatu AFO (Ankle Foot Orthosis)</option>
                            <option value="Tongkat Kaki 3 atau 4">Tongkat Kaki 3 atau 4</option>
                            <option value="Kursi Roda Adaptif anak">Kursi Roda Adaptif anak</option>
                            <option value="Kursi Roda Adaptif Anak">Kursi Roda Adaptif Anak</option>
                            <option value="Kursi Roda CP">Kursi Roda CP</option>
                            <option value="Kaki Palsu Diatas Lutut">Kaki Palsu Diatas Lutut</option>
                            <option value="Kaki Palsu Dibawah Lutut">Kaki Palsu Dibawah Lutut</option>
                        </optgroup>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="tanggal_pengajuan" class="form-label fw-bold small">Tanggal Pengajuan</label>
                    <input type="date" id="tanggal_pengajuan" name="tanggal_pengajuan" class="form-control tabular-nums" value="<?php echo e(date('Y-m-d')); ?>" required>
                </div>
                <div class="mb-4">
                    <label for="keterangan_kondisi" class="form-label fw-bold small">Keterangan Kondisi</label>
                    <textarea id="keterangan_kondisi" name="keterangan_kondisi" class="form-control" rows="3"></textarea>
                </div>
                <button type="submit" class="btn-civic-primary w-100">Simpan Pengajuan</button>
            </form>
        </div>
    </div>

    <!-- Tabel Daftar Permohonan -->
    <div class="col-lg-8">
        <div class="surface-card overflow-hidden">
            <div class="p-4" style="border-bottom: 1px solid var(--border-hairline);">
                <h5 class="fw-bold mb-0">Daftar Permohonan &amp; Verifikasi Status</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-minimal mb-0">
                    <thead>
                        <tr>
                            <th>Pemohon &amp; Wilayah</th>
                            <th>Alat Bantu &amp; Kondisi</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Perbarui Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $pengajuans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <div class="fw-bold"><?php echo e($p->warga->nama_lengkap ?? '-'); ?></div>
                                <div class="small text-secondary">Kel. <?php echo e($p->warga->kelurahan ?? '-'); ?> (<?php echo e($p->warga->kecamatan ?? '-'); ?>)</div>
                            </td>
                            <td>
                                <div class="fw-bold"><?php echo e($p->jenis_alat); ?></div>
                                <div class="small text-secondary"><?php echo e($p->keterangan_kondisi ?: '-'); ?></div>
                            </td>
                            <td class="tabular-nums small"><?php echo e($p->tanggal_pengajuan ? $p->tanggal_pengajuan->format('d/m/Y') : '-'); ?></td>
                            <td>
                                <span class="status-pill status-<?php echo e($p->status); ?>"><?php echo e($p->status); ?></span>
                            </td>
                            <td>
                                <form action="<?php echo e(route('admin.pengajuan.status', $p->id)); ?>" method="POST" class="d-flex gap-1">
                                    <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                    <select name="status" class="form-select form-select-sm" aria-label="Ubah status bantuan">
                                        <option value="Menunggu" <?php echo e($p->status == 'Menunggu' ? 'selected' : ''); ?>>Menunggu</option>
                                        <option value="Diproses" <?php echo e($p->status == 'Diproses' ? 'selected' : ''); ?>>Diproses</option>
                                        <option value="Terealisasi" <?php echo e($p->status == 'Terealisasi' ? 'selected' : ''); ?>>Terealisasi</option>
                                    </select>
                                    <button type="submit" class="btn btn-sm btn-dark rounded-3 px-3">Simpan</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-secondary">Belum ada data pengajuan alat bantu.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\sidisab\resources\views/admin/pengajuan.blade.php ENDPATH**/ ?>