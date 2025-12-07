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

// Load dashboard data
async function loadDashboard() {
    try {
        const response = await fetch('api/dashboard.php');
        const data = await response.json();
        
        if (data.success) {
            document.getElementById('totalPelanggan').textContent = data.totalPelanggan;
            document.getElementById('transaksiHariIni').textContent = data.transaksiHariIni;
            document.getElementById('sedangProses').textContent = data.sedangProses;
            document.getElementById('pendapatanHariIni').textContent = 
                'Rp ' + parseInt(data.pendapatanHariIni).toLocaleString('id-ID');
            
            // Grafik pendapatan
            const ctx = document.getElementById('grafikPendapatan').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.grafikLabels,
                    datasets: [{
                        label: 'Pendapatan (Rp)',
                        data: data.grafikData,
                        borderColor: 'rgb(75, 192, 192)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        tension: 0.1
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
            
            // Transaksi terbaru
            const transaksiDiv = document.getElementById('transaksiTerbaru');
            if (data.transaksiTerbaru.length > 0) {
                transaksiDiv.innerHTML = data.transaksiTerbaru.map(t => `
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between">
                            <strong>${t.nama_pelanggan}</strong>
                            <span class="badge bg-${t.status === 'Proses' ? 'warning' : t.status === 'Selesai' ? 'success' : 'info'}">
                                ${t.status}
                            </span>
                        </div>
                        <small class="text-muted">${t.nama_layanan} - Rp ${parseInt(t.total_harga).toLocaleString('id-ID')}</small>
                    </div>
                `).join('');
            } else {
                transaksiDiv.innerHTML = '<p class="text-muted">Belum ada transaksi</p>';
            }
        }
    } catch (error) {
        console.error('Error loading dashboard:', error);
    }
}

loadDashboard();
