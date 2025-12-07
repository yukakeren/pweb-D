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

let chartInstance = null;

// Set default tanggal (bulan ini)
const today = new Date();
const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
document.getElementById('tanggal_dari').value = firstDay.toISOString().split('T')[0];
document.getElementById('tanggal_sampai').value = today.toISOString().split('T')[0];

// Load laporan
async function loadLaporan() {
    const tanggal_dari = document.getElementById('tanggal_dari').value;
    const tanggal_sampai = document.getElementById('tanggal_sampai').value;
    
    if (!tanggal_dari || !tanggal_sampai) {
        alert('Harap isi periode tanggal');
        return;
    }
    
    try {
        const response = await fetch(`api/laporan.php?dari=${tanggal_dari}&sampai=${tanggal_sampai}`);
        const data = await response.json();
        
        if (data.success) {
            // Update summary
            document.getElementById('totalTransaksi').textContent = data.summary.total_transaksi;
            document.getElementById('totalPendapatan').textContent = 
                'Rp ' + parseInt(data.summary.total_pendapatan).toLocaleString('id-ID');
            document.getElementById('rataRata').textContent = 
                'Rp ' + parseInt(data.summary.rata_rata).toLocaleString('id-ID');
            
            // Update grafik
            updateChart(data.grafik);
            
            // Update tabel
            displayLaporan(data.detail);
        }
    } catch (error) {
        console.error('Error loading laporan:', error);
    }
}

// Update chart
function updateChart(grafikData) {
    const ctx = document.getElementById('grafikPendapatan').getContext('2d');
    
    if (chartInstance) {
        chartInstance.destroy();
    }
    
    chartInstance = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: grafikData.map(g => formatDate(g.tanggal)),
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: grafikData.map(g => g.total),
                backgroundColor: 'rgba(75, 192, 192, 0.5)',
                borderColor: 'rgb(75, 192, 192)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
}

// Display laporan
function displayLaporan(data) {
    const tbody = document.getElementById('laporanTable');
    
    if (data.length === 0) {
        tbody.innerHTML = '<tr><td colspan="7" class="text-center">Tidak ada data</td></tr>';
        return;
    }
    
    tbody.innerHTML = data.map(t => `
        <tr>
            <td>${formatDate(t.tanggal_masuk)}</td>
            <td>${t.id_transaksi}</td>
            <td>${t.nama_pelanggan}</td>
            <td>${t.nama_layanan}</td>
            <td>${t.berat}</td>
            <td>Rp ${parseInt(t.total_harga).toLocaleString('id-ID')}</td>
            <td>
                <span class="badge bg-${t.status === 'Proses' ? 'warning' : t.status === 'Selesai' ? 'success' : 'info'}">
                    ${t.status}
                </span>
            </td>
        </tr>
    `).join('');
}

// Format tanggal
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
}

// Export laporan
async function exportLaporan() {
    const tanggal_dari = document.getElementById('tanggal_dari').value;
    const tanggal_sampai = document.getElementById('tanggal_sampai').value;
    
    if (!tanggal_dari || !tanggal_sampai) {
        alert('Harap isi periode tanggal');
        return;
    }
    
    window.open(`api/export_laporan.php?dari=${tanggal_dari}&sampai=${tanggal_sampai}`, '_blank');
}

// Load laporan awal
loadLaporan();
