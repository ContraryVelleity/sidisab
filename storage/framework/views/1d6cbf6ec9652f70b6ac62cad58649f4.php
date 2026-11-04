
<?php $__env->startSection('title', 'Peta Spasial Disabilitas (GIS Admin)'); ?>

<?php $__env->startSection('content'); ?>
<div class="surface-card overflow-hidden">
    <div class="p-4 d-flex flex-wrap justify-content-between align-items-center gap-3" style="border-bottom: 1px solid var(--border-hairline);">
        <div class="d-flex flex-wrap align-items-center gap-2">
            <select id="adminFilterKec" class="form-select form-select-sm" style="width: auto;" aria-label="Filter Kecamatan">
                <option value="ALL">Semua Kecamatan</option>
                <option value="Cipondoh">Kec. Cipondoh</option>
                <option value="Pinang">Kec. Pinang</option>
            </select>
            <select id="adminFilterStatus" class="form-select form-select-sm" style="width: auto;" aria-label="Filter Status">
                <option value="ALL">Semua Status Bantuan</option>
                <option value="Menunggu">Menunggu</option>
                <option value="Diproses">Diproses</option>
                <option value="Terealisasi">Terealisasi</option>
            </select>
        </div>
        <a href="<?php echo e(route('admin.warga.create')); ?>" class="btn-civic-primary text-decoration-none btn-sm">
            <i class="bi bi-plus-lg" aria-hidden="true"></i> Tambah Titik Warga
        </a>
    </div>

    <div id="mapAdmin" style="height: 560px; width: 100%;"></div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    const map = L.map('mapAdmin').setView([-6.1980, 106.6750], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap | GIS Admin SIDISAB'
    }).addTo(map);

    const layerGroup = L.layerGroup().addTo(map);
    let adminPoints = [];

    function renderAdminMap() {
        layerGroup.clearLayers();
        const kec = document.getElementById('adminFilterKec').value;
        const st = document.getElementById('adminFilterStatus').value;

        adminPoints.forEach(w => {
            if (kec !== 'ALL' && w.kecamatan !== kec) return;
            if (st !== 'ALL' && w.status !== st) return;

            L.marker([w.lat, w.lng]).addTo(layerGroup)
                .bindPopup(`
                    <div style="min-width: 230px; font-family: 'Atkinson Hyperlegible', sans-serif;">
                        <div class="fw-bold">${escapeHtml(w.nama)}</div>
                        <div class="small text-secondary mb-2">NIK: ${escapeHtml(w.nik)} | WA: ${escapeHtml(w.no_hp)}</div>
                        <div class="small border-top pt-2">
                            <div><strong>Wilayah:</strong> Kel. ${escapeHtml(w.kelurahan)}, Kec. ${escapeHtml(w.kecamatan)}</div>
                            <div><strong>Alamat:</strong> ${escapeHtml(w.alamat)}</div>
                            <div><strong>Disabilitas:</strong> ${escapeHtml(w.kategori)}</div>
                            <div><strong>Alat Bantu:</strong> ${escapeHtml(w.alat)} (${escapeHtml(w.status)})</div>
                        </div>
                        <div class="mt-2 pt-2 border-top">
                            <a href="${escapeHtml(w.edit_url)}" class="btn btn-sm btn-primary w-100 text-white text-decoration-none">Edit Data &amp; Titik</a>
                        </div>
                    </div>
                `);
        });
    }

    fetch("<?php echo e(route('admin.api.map.admin')); ?>")
        .then(res => res.json())
        .then(data => {
            adminPoints = data;
            renderAdminMap();
        });

    document.getElementById('adminFilterKec').addEventListener('change', renderAdminMap);
    document.getElementById('adminFilterStatus').addEventListener('change', renderAdminMap);
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sidisab\resources\views/admin/peta-gis.blade.php ENDPATH**/ ?>