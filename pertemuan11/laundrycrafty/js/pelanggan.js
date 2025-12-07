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

let pelangganData = [];

// Load pelanggan
async function loadPelanggan() {
    try {
        const response = await fetch('api/pelanggan.php');
        const data = await response.json();
        
        if (data.success) {
            pelangganData = data.data;
            displayPelanggan(pelangganData);
        }
    } catch (error) {
        console.error('Error loading pelanggan:', error);
    }
}

// Display pelanggan
function displayPelanggan(data) {
    const tbody = document.getElementById('pelangganTable');
    
    if (data.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5" class="text-center">Tidak ada data</td></tr>';
        return;
    }
    
    tbody.innerHTML = data.map(p => `
        <tr>
            <td>${p.id_pelanggan}</td>
            <td>${p.nama}</td>
            <td>${p.alamat || '-'}</td>
            <td>${p.no_hp || '-'}</td>
            <td>
                <button class="btn btn-sm btn-warning" onclick="editPelanggan(${p.id_pelanggan})">
                    <i class="bi bi-pencil"></i>
                </button>
                <button class="btn btn-sm btn-danger" onclick="deletePelanggan(${p.id_pelanggan})">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        </tr>
    `).join('');
}

// Search pelanggan
document.getElementById('searchPelanggan').addEventListener('input', function(e) {
    const keyword = e.target.value.toLowerCase();
    const filtered = pelangganData.filter(p => 
        p.nama.toLowerCase().includes(keyword) ||
        (p.alamat && p.alamat.toLowerCase().includes(keyword)) ||
        (p.no_hp && p.no_hp.includes(keyword))
    );
    displayPelanggan(filtered);
});

// Reset form
function resetForm() {
    document.getElementById('pelangganForm').reset();
    document.getElementById('id_pelanggan').value = '';
    document.getElementById('modalTitle').textContent = 'Tambah Pelanggan';
}

// Save pelanggan
async function savePelanggan() {
    const id = document.getElementById('id_pelanggan').value;
    const nama = document.getElementById('nama').value;
    const alamat = document.getElementById('alamat').value;
    const no_hp = document.getElementById('no_hp').value;
    
    const data = { nama, alamat, no_hp };
    if (id) data.id_pelanggan = id;
    
    try {
        const response = await fetch('api/pelanggan.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            bootstrap.Modal.getInstance(document.getElementById('pelangganModal')).hide();
            loadPelanggan();
            alert(result.message);
        } else {
            alert(result.message);
        }
    } catch (error) {
        alert('Terjadi kesalahan');
    }
}

// Edit pelanggan
function editPelanggan(id) {
    const pelanggan = pelangganData.find(p => p.id_pelanggan == id);
    if (pelanggan) {
        document.getElementById('id_pelanggan').value = pelanggan.id_pelanggan;
        document.getElementById('nama').value = pelanggan.nama;
        document.getElementById('alamat').value = pelanggan.alamat || '';
        document.getElementById('no_hp').value = pelanggan.no_hp || '';
        document.getElementById('modalTitle').textContent = 'Edit Pelanggan';
        
        new bootstrap.Modal(document.getElementById('pelangganModal')).show();
    }
}

// Delete pelanggan
async function deletePelanggan(id) {
    if (!confirm('Yakin ingin menghapus pelanggan ini?')) return;
    
    try {
        const response = await fetch('api/pelanggan.php', {
            method: 'DELETE',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id_pelanggan: id })
        });
        
        const result = await response.json();
        
        if (result.success) {
            loadPelanggan();
            alert(result.message);
        } else {
            alert(result.message);
        }
    } catch (error) {
        alert('Terjadi kesalahan');
    }
}

loadPelanggan();
