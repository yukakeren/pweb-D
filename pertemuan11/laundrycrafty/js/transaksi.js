// Cek autentikasi
const user = JSON.parse(sessionStorage.getItem('user'));
if (!user) {
    window.location.href = 'login.html';
}

// Logout
document.getElementById('logoutBtn').addEventListener('click', function(e) {
    e.preventDefault();
    sessionStorage.removeItem('user');
    window.location.href = 'login.html';
});

let transaksiData = [];
let pelangganData = [];
let layananData = [];

// Load data awal
async function loadInitialData() {
    try {
        const [pelangganRes, layananRes] = await Promise.all([
            fetch('api/pelanggan.php'),
            fetch('api/layanan.php')
        ]);
        
        const pelanggan = await pelangganRes.json();
        const layanan = await layananRes.json();
        
        if (pelanggan.success) {
            pelangganData = pelanggan.data;
            const selectPelanggan = document.getElementById('id_pelanggan');
            selectPelanggan.innerHTML = '<option value="">Pilih Pelanggan</option>' +
                pelangganData.map(p => `<option value="${p.id_pelanggan}">${p.nama}</option>`).join('');
        }
        
        if (layanan.success) {
            layananData = layanan.data;
            const selectLayanan = document.getElementById('id_layanan');
            selectLayanan.innerHTML = '<option value="">Pilih Layanan</option>' +
                layananData.map(l => `<option value="${l.id_layanan}" data-harga="${l.harga_per_kg}">${l.nama_layanan} - Rp ${parseInt(l.harga_per_kg).toLocaleString('id-ID')}/kg</option>`).join('');
        }
    } catch (error) {
        console.error('Error loading initial data:', error);
    }
}

// Load transaksi
async function loadTransaksi() {
    try {
        const response = await fetch('api/transaksi.php');
        const data = await response.json();
        
        if (data.success) {
            transaksiData = data.data;
            displayTransaksi(transaksiData);
        }
    } catch (error) {
        console.error('Error loading transaksi:', error);
    }
}

// Display transaksi
function displayTransaksi(data) {
    const tbody = document.getElementById('transaksiTable');
    
    if (data.length === 0) {
        tbody.innerHTML = '<tr><td colspan="9" class="text-center">Tidak ada data</td></tr>';
        return;
    }
    
    tbody.innerHTML = data.map(t => `
        <tr>
            <td>${t.id_transaksi}</td>
            <td>${t.nama_pelanggan}</td>
            <td>${t.nama_layanan}</td>
            <td>${formatDate(t.tanggal_masuk)}</td>
            <td>${t.tanggal_selesai ? formatDate(t.tanggal_selesai) : '-'}</td>
            <td>${t.berat}</td>
            <td>Rp ${parseInt(t.total_harga).toLocaleString('id-ID')}</td>
            <td>
                <span class="badge bg-${t.status === 'Proses' ? 'warning' : t.status === 'Selesai' ? 'success' : 'info'}">
                    ${t.status}
                </span>
            </td>
            <td>
                <button class="btn btn-sm btn-info" onclick="openStatusModal(${t.id_transaksi}, '${t.status}')">
                    <i class="bi bi-arrow-repeat"></i>
                </button>
                <button class="btn btn-sm btn-danger" onclick="deleteTransaksi(${t.id_transaksi})">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        </tr>
    `).join('');
}

// Format tanggal
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
}

// Filter transaksi
document.getElementById('filterStatus').addEventListener('change', filterTransaksi);
document.getElementById('filterTanggal').addEventListener('change', filterTransaksi);

function filterTransaksi() {
    const status = document.getElementById('filterStatus').value;
    const tanggal = document.getElementById('filterTanggal').value;
    
    let filtered = transaksiData;
    
    if (status) {
        filtered = filtered.filter(t => t.status === status);
    }
    
    if (tanggal) {
        filtered = filtered.filter(t => t.tanggal_masuk === tanggal);
    }
    
    displayTransaksi(filtered);
}

// Reset form
function resetForm() {
    document.getElementById('transaksiForm').reset();
    document.getElementById('tanggal_masuk').value = new Date().toISOString().split('T')[0];
}

// Hitung total
function hitungTotal() {
    const layananSelect = document.getElementById('id_layanan');
    const berat = parseFloat(document.getElementById('berat').value) || 0;
    const harga = parseFloat(layananSelect.options[layananSelect.selectedIndex].dataset.harga) || 0;
    
    document.getElementById('total_harga').value = berat * harga;
}

// Save transaksi
async function saveTransaksi() {
    const id_pelanggan = document.getElementById('id_pelanggan').value;
    const id_layanan = document.getElementById('id_layanan').value;
    const tanggal_masuk = document.getElementById('tanggal_masuk').value;
    const tanggal_selesai = document.getElementById('tanggal_selesai').value;
    const berat = document.getElementById('berat').value;
    const total_harga = document.getElementById('total_harga').value;
    
    const data = {
        id_pelanggan,
        id_layanan,
        tanggal_masuk,
        tanggal_selesai,
        berat,
        total_harga
    };
    
    try {
        const response = await fetch('api/transaksi.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            bootstrap.Modal.getInstance(document.getElementById('transaksiModal')).hide();
            loadTransaksi();
            alert(result.message);
        } else {
            alert(result.message);
        }
    } catch (error) {
        alert('Terjadi kesalahan');
    }
}

// Open status modal
function openStatusModal(id, currentStatus) {
    document.getElementById('status_id_transaksi').value = id;
    document.getElementById('status_baru').value = currentStatus;
    new bootstrap.Modal(document.getElementById('statusModal')).show();
}

// Update status
async function updateStatus() {
    const id = document.getElementById('status_id_transaksi').value;
    const status = document.getElementById('status_baru').value;
    
    try {
        const response = await fetch('api/transaksi.php', {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id_transaksi: id, status })
        });
        
        const result = await response.json();
        
        if (result.success) {
            bootstrap.Modal.getInstance(document.getElementById('statusModal')).hide();
            loadTransaksi();
            alert(result.message);
        } else {
            alert(result.message);
        }
    } catch (error) {
        alert('Terjadi kesalahan');
    }
}

// Delete transaksi
async function deleteTransaksi(id) {
    if (!confirm('Yakin ingin menghapus transaksi ini?')) return;
    
    try {
        const response = await fetch('api/transaksi.php', {
            method: 'DELETE',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id_transaksi: id })
        });
        
        const result = await response.json();
        
        if (result.success) {
            loadTransaksi();
            alert(result.message);
        } else {
            alert(result.message);
        }
    } catch (error) {
        alert('Terjadi kesalahan');
    }
}

loadInitialData();
loadTransaksi();
