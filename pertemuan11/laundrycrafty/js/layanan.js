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

let layananData = [];

// Load layanan
async function loadLayanan() {
    try {
        const response = await fetch('api/layanan.php');
        const data = await response.json();
        
        if (data.success) {
            layananData = data.data;
            displayLayanan(layananData);
        }
    } catch (error) {
        console.error('Error loading layanan:', error);
    }
}

// Display layanan
function displayLayanan(data) {
    const tbody = document.getElementById('layananTable');
    
    if (data.length === 0) {
        tbody.innerHTML = '<tr><td colspan="4" class="text-center">Tidak ada data</td></tr>';
        return;
    }
    
    tbody.innerHTML = data.map(l => `
        <tr>
            <td>${l.id_layanan}</td>
            <td>${l.nama_layanan}</td>
            <td>Rp ${parseInt(l.harga_per_kg).toLocaleString('id-ID')}</td>
            <td>
                <button class="btn btn-sm btn-warning" onclick="editLayanan(${l.id_layanan})">
                    <i class="bi bi-pencil"></i>
                </button>
                <button class="btn btn-sm btn-danger" onclick="deleteLayanan(${l.id_layanan})">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        </tr>
    `).join('');
}

// Reset form
function resetForm() {
    document.getElementById('layananForm').reset();
    document.getElementById('id_layanan').value = '';
    document.getElementById('modalTitle').textContent = 'Tambah Layanan';
}

// Save layanan
async function saveLayanan() {
    const id = document.getElementById('id_layanan').value;
    const nama_layanan = document.getElementById('nama_layanan').value;
    const harga_per_kg = document.getElementById('harga_per_kg').value;
    
    const data = { nama_layanan, harga_per_kg };
    if (id) data.id_layanan = id;
    
    try {
        const response = await fetch('api/layanan.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            bootstrap.Modal.getInstance(document.getElementById('layananModal')).hide();
            loadLayanan();
            alert(result.message);
        } else {
            alert(result.message);
        }
    } catch (error) {
        alert('Terjadi kesalahan');
    }
}

// Edit layanan
function editLayanan(id) {
    const layanan = layananData.find(l => l.id_layanan == id);
    if (layanan) {
        document.getElementById('id_layanan').value = layanan.id_layanan;
        document.getElementById('nama_layanan').value = layanan.nama_layanan;
        document.getElementById('harga_per_kg').value = layanan.harga_per_kg;
        document.getElementById('modalTitle').textContent = 'Edit Layanan';
        
        new bootstrap.Modal(document.getElementById('layananModal')).show();
    }
}

// Delete layanan
async function deleteLayanan(id) {
    if (!confirm('Yakin ingin menghapus layanan ini?')) return;
    
    try {
        const response = await fetch('api/layanan.php', {
            method: 'DELETE',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id_layanan: id })
        });
        
        const result = await response.json();
        
        if (result.success) {
            loadLayanan();
            alert(result.message);
        } else {
            alert(result.message);
        }
    } catch (error) {
        alert('Terjadi kesalahan');
    }
}

loadLayanan();
