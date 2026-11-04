<?php $__env->startSection('title', $warga ? 'Perbarui Data Warga Disabilitas' : 'Tambah Data Warga Disabilitas'); ?>

<?php $__env->startSection('content'); ?>
<div class="surface-card p-4 p-lg-5">
    <?php if($errors->any()): ?>
        <div class="alert alert-danger rounded-4 border-0 mb-4">
            <ul class="mb-0 small">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $err): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <li><?php echo e($err); ?></li> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?php echo e($warga ? route('admin.warga.update', $warga->id) : route('admin.warga.store')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <?php if($warga): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>

        <div class="row g-3">
            <div class="col-md-4">
                <label for="nik" class="form-label fw-bold">NIK (16 Digit)</label>
                <input type="text" id="nik" name="nik" inputmode="numeric" class="form-control tabular-nums" maxlength="16"
                       value="<?php echo e(old('nik', $warga->nik ?? '')); ?>" required>
            </div>
            <div class="col-md-4">
                <label for="nama_lengkap" class="form-label fw-bold">Nama Lengkap</label>
                <input type="text" id="nama_lengkap" name="nama_lengkap" class="form-control"
                       value="<?php echo e(old('nama_lengkap', $warga->nama_lengkap ?? '')); ?>" required>
            </div>
            <div class="col-md-4">
                <label for="no_hp" class="form-label fw-bold">Nomor HP / WhatsApp</label>
                <input type="text" id="no_hp" name="no_hp" class="form-control tabular-nums"
                       value="<?php echo e(old('no_hp', $warga->no_hp ?? '')); ?>">
            </div>

            <div class="col-md-4">
                <label for="kecamatan" class="form-label fw-bold">Kecamatan (Dapil III)</label>
                <select id="kecamatan" name="kecamatan" class="form-select" required>
                    <option value="Cipondoh" <?php echo e((old('kecamatan', $warga->kecamatan ?? '') == 'Cipondoh') ? 'selected' : ''); ?>>Cipondoh</option>
                    <option value="Pinang" <?php echo e((old('kecamatan', $warga->kecamatan ?? '') == 'Pinang') ? 'selected' : ''); ?>>Pinang</option>
                </select>
            </div>
            <div class="col-md-4">
                <label for="kelurahan" class="form-label fw-bold">Kelurahan</label>
                <input type="text" id="kelurahan" name="kelurahan" class="form-control"
                       value="<?php echo e(old('kelurahan', $warga->kelurahan ?? '')); ?>" required>
            </div>
            <div class="col-md-4">
                <label for="kategori_disabilitas" class="form-label fw-bold">Kategori Disabilitas</label>
                <select id="kategori_disabilitas" name="kategori_disabilitas" class="form-select">
                    <option value="">-- Belum ditentukan --</option>
                    <?php $__currentLoopData = ['Tunadaksa', 'Tunanetra', 'Tunarungu', 'Tunawicara', 'Tunagrahita', 'Disabilitas Ganda']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($kat); ?>" <?php echo e((old('kategori_disabilitas', $warga->kategori_disabilitas ?? '') == $kat) ? 'selected' : ''); ?>><?php echo e($kat); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div class="col-12">
                <label for="alamat_lengkap" class="form-label fw-bold">Alamat Lengkap</label>
                <textarea id="alamat_lengkap" name="alamat_lengkap" class="form-control" rows="2" required><?php echo e(old('alamat_lengkap', $warga->alamat_lengkap ?? '')); ?></textarea>
            </div>

            <div class="col-12 mt-4">
                <label class="form-label fw-bold">Penentuan Titik Koordinat Rumah (Klik Peta atau Geser Pin)</label>
                <div id="mapForm" style="height: 320px;" class="border rounded-4 mb-3"></div>
            </div>

            <div class="col-md-6">
                <label for="lat" class="form-label fw-bold">Latitude</label>
                <input type="text" id="lat" name="latitude" class="form-control tabular-nums"
                       value="<?php echo e(old('latitude', $warga->latitude ?? '-6.19800000')); ?>" required>
            </div>
            <div class="col-md-6">
                <label for="lng" class="form-label fw-bold">Longitude</label>
                <input type="text" id="lng" name="longitude" class="form-control tabular-nums"
                       value="<?php echo e(old('longitude', $warga->longitude ?? '106.67500000')); ?>" required>
            </div>

            <div class="col-12 mt-4 d-flex gap-2">
                <button type="submit" class="btn-civic-primary">Simpan Data Warga</button>
                <a href="<?php echo e(route('admin.warga.index')); ?>" class="btn btn-outline-secondary rounded-3 px-4 d-inline-flex align-items-center">Batal</a>
            </div>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    const latInput = document.getElementById('lat');
    const lngInput = document.getElementById('lng');
    const initLat = parseFloat(latInput.value) || -6.1980;
    const initLng = parseFloat(lngInput.value) || 106.6750;

    const map = L.map('mapForm').setView([initLat, initLng], 14);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    const marker = L.marker([initLat, initLng], { draggable: true }).addTo(map);

    marker.on('dragend', function(e) {
        const pos = e.target.getLatLng();
        latInput.value = pos.lat.toFixed(8);
        lngInput.value = pos.lng.toFixed(8);
    });

    map.on('click', function(e) {
        latInput.value = e.latlng.lat.toFixed(8);
        lngInput.value = e.latlng.lng.toFixed(8);
        marker.setLatLng(e.latlng);
    });

    function syncFromInput() {
        const lat = parseFloat(latInput.value);
        const lng = parseFloat(lngInput.value);
        if (!isNaN(lat) && !isNaN(lng)) {
            marker.setLatLng([lat, lng]);
            map.panTo([lat, lng]);
        }
    }
    latInput.addEventListener('change', syncFromInput);
    lngInput.addEventListener('change', syncFromInput);
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\sidisab\resources\views/admin/warga-form.blade.php ENDPATH**/ ?>