<?php require_once BASE_PATH . 'app/views/layouts/header.php'; ?>

<div class="container" style="margin-top: 100px; margin-bottom: 50px;">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>admin"><i class="fas fa-home"></i> Dashboard</a></li>
            <li class="breadcrumb-item active">Thống kê hệ thống</li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="mb-2"><i class="fas fa-chart-bar me-3 text-primary"></i>Thống kê hệ thống</h1>
                <p class="text-muted mb-0">Báo cáo chi tiết về hoạt động và phân tích dữ liệu</p>
            </div>
            <div>
                <span class="badge bg-info" style="font-size: 0.9rem; padding: 8px 15px;">
                    <i class="fas fa-sync-alt me-2"></i>Cập nhật realtime
                </span>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Thống kê người dùng theo vai trò -->
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0"><i class="fas fa-users me-2 text-primary"></i>Người dùng theo vai trò</h5>
                    <small class="text-muted">Phân bố vai trò trong hệ thống</small>
                </div>
                <div class="card-body">
                    <canvas id="chartVaitro" height="250"></canvas>
                </div>
            </div>
        </div>

        <!-- Thống kê tin theo trạng thái -->
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0"><i class="fas fa-briefcase me-2 text-success"></i>Tin tuyển dụng theo trạng thái</h5>
                    <small class="text-muted">Tình trạng tin tuyển dụng</small>
                </div>
                <div class="card-body">
                    <canvas id="chartTin" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-1">
        <!-- Thống kê đơn ứng tuyển -->
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0"><i class="fas fa-file-alt me-2 text-warning"></i>Đơn ứng tuyển theo trạng thái</h5>
                    <small class="text-muted">Tình trạng xử lý đơn</small>
                </div>
                <div class="card-body">
                    <canvas id="chartDon" height="250"></canvas>
                </div>
            </div>
        </div>

        <!-- Thống kê ngành nghề phổ biến -->
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0"><i class="fas fa-industry me-2 text-info"></i>Top 10 ngành nghề hot</h5>
                    <small class="text-muted">Ngành nghề có nhiều tin nhất</small>
                </div>
                <div class="card-body">
                    <canvas id="chartNganh" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Biểu đồ xu hướng theo thời gian -->
    <div class="row g-4 mt-1">
        <!-- Người dùng đăng ký -->
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div>
                            <h5 class="mb-1"><i class="fas fa-user-plus me-2 text-success"></i>Người dùng đăng ký</h5>
                            <small class="text-muted">Số lượng người dùng mới theo thời gian (30 ngày gần nhất)</small>
                        </div>
                        <div>
                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button" class="btn btn-outline-primary active" onclick="showUserChart('day')">
                                    <i class="fas fa-calendar-day me-1"></i>Theo ngày
                                </button>
                                <button type="button" class="btn btn-outline-primary" onclick="showUserChart('week')">
                                    <i class="fas fa-calendar-week me-1"></i>Theo tuần
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="chartNguoiDung" height="80"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Hoạt động tổng hợp -->
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-1"><i class="fas fa-chart-line me-2 text-danger"></i>Hoạt động tổng hợp</h5>
                    <small class="text-muted">Tin tuyển dụng và đơn ứng tuyển (30 ngày gần nhất)</small>
                </div>
                <div class="card-body">
                    <canvas id="chartXuhuong" height="80"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let userChartInstance = null;
let statsData = null; // Lưu dữ liệu từ API

// Hàm hiển thị biểu đồ người dùng
function showUserChart(type) {
    // Update button active state
    document.querySelectorAll('.btn-group button').forEach(btn => {
        btn.classList.remove('active');
    });
    event.target.closest('button').classList.add('active');
    
    if (!statsData || !statsData.xuhuong) {
        console.warn('Chưa có dữ liệu');
        return;
    }
    
    // Xử lý dữ liệu theo loại
    let chartData = {
        labels: [],
        data: []
    };
    
    if (type === 'day') {
        // Hiển thị theo ngày (mặc định)
        chartData.labels = statsData.xuhuong.map(item => item.ngay);
        chartData.data = statsData.xuhuong.map(item => item.nguoidungmoi || 0);
    } else if (type === 'week') {
        // Tính tổng theo tuần
        let weekData = {};
        statsData.xuhuong.forEach(item => {
            // Lấy số tuần từ ngày (đơn giản hóa: nhóm mỗi 7 ngày)
            let parts = item.ngay.split('/');
            let day = parseInt(parts[0]);
            let weekNum = Math.ceil(day / 7);
            let weekLabel = `Tuần ${weekNum}/${parts[1]}`;
            
            if (!weekData[weekLabel]) {
                weekData[weekLabel] = 0;
            }
            weekData[weekLabel] += parseInt(item.nguoidungmoi) || 0;
        });
        
        chartData.labels = Object.keys(weekData);
        chartData.data = Object.values(weekData);
    } else if (type === 'month') {
        // Tính tổng theo tháng
        let monthData = {};
        statsData.xuhuong.forEach(item => {
            let parts = item.ngay.split('/');
            let monthLabel = `Tháng ${parts[1]}`;
            
            if (!monthData[monthLabel]) {
                monthData[monthLabel] = 0;
            }
            monthData[monthLabel] += parseInt(item.nguoidungmoi) || 0;
        });
        
        chartData.labels = Object.keys(monthData);
        chartData.data = Object.values(monthData);
    }
    
    // Destroy chart cũ nếu có
    if (userChartInstance) {
        userChartInstance.destroy();
    }
    
    // Tạo gradient
    const ctx = document.getElementById('chartNguoiDung').getContext('2d');
    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, 'rgba(40, 167, 69, 0.3)');
    gradient.addColorStop(1, 'rgba(40, 167, 69, 0.0)');
    
    // Tạo chart mới
    userChartInstance = new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartData.labels,
            datasets: [{
                label: 'Người dùng mới',
                data: chartData.data,
                borderColor: '#28a745',
                backgroundColor: gradient,
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointHoverRadius: 6,
                pointBackgroundColor: '#28a745',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointHoverBackgroundColor: '#28a745',
                pointHoverBorderColor: '#fff',
                pointHoverBorderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 15,
                        font: {
                            size: 12,
                            weight: '600'
                        }
                    }
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: {
                        size: 14,
                        weight: 'bold'
                    },
                    bodyFont: {
                        size: 13
                    },
                    callbacks: {
                        label: function(context) {
                            return 'Người dùng mới: ' + context.parsed.y + ' người';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        font: {
                            size: 11
                        }
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    ticks: {
                        maxRotation: 45,
                        minRotation: 45,
                        font: {
                            size: 10
                        }
                    },
                    grid: {
                        display: false
                    }
                }
            },
            interaction: {
                mode: 'nearest',
                axis: 'x',
                intersect: false
            }
        }
    });
}

// Lấy dữ liệu từ server
fetch('<?php echo BASE_URL; ?>api/thongke')
    .then(response => response.json())
    .then(data => {
        // Lưu dữ liệu vào biến global
        statsData = data;
        // Biểu đồ vai trò
        new Chart(document.getElementById('chartVaitro'), {
            type: 'doughnut',
            data: {
                labels: ['Ứng viên', 'Nhà tuyển dụng', 'Chờ duyệt', 'Admin'],
                datasets: [{
                    data: [
                        data.vaitro?.ungvien || 0,
                        data.vaitro?.tuyendung || 0,
                        data.vaitro?.choduyet || 0,
                        data.vaitro?.admin || 0
                    ],
                    backgroundColor: ['#007bff', '#28a745', '#ffc107', '#dc3545']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true
            }
        });

        // Biểu đồ tin tuyển dụng
        new Chart(document.getElementById('chartTin'), {
            type: 'bar',
            data: {
                labels: ['Đang mở', 'Chờ duyệt', 'Đã ẩn', 'Hết hạn'],
                datasets: [{
                    label: 'Số lượng',
                    data: [
                        data.tin?.dangmo || 0,
                        data.tin?.choduyet || 0,
                        data.tin?.an || 0,
                        data.tin?.hethan || 0
                    ],
                    backgroundColor: ['#28a745', '#ffc107', '#6c757d', '#dc3545']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Biểu đồ đơn ứng tuyển
        new Chart(document.getElementById('chartDon'), {
            type: 'pie',
            data: {
                labels: ['Mới', 'Đang xem', 'Phỏng vấn', 'Nhận việc', 'Từ chối'],
                datasets: [{
                    data: [
                        data.don?.moi || 0,
                        data.don?.dangxem || 0,
                        data.don?.phongvan || 0,
                        data.don?.nhanviec || 0,
                        data.don?.tuchoi || 0
                    ],
                    backgroundColor: ['#17a2b8', '#007bff', '#ffc107', '#28a745', '#dc3545']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true
            }
        });

        // Biểu đồ ngành nghề
        if (data.nganh) {
            new Chart(document.getElementById('chartNganh'), {
                type: 'bar',
                data: {
                    labels: data.nganh.map(item => item.ten),
                    datasets: [{
                        label: 'Số tin',
                        data: data.nganh.map(item => item.soluong),
                        backgroundColor: '#667eea'
                    }]
                },
                options: {
                    indexAxis: 'y', // Hiển thị ngang
                    responsive: true,
                    maintainAspectRatio: true,
                    scales: {
                        x: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        // Biểu đồ người dùng đăng ký - Gọi function để hiển thị theo ngày (mặc định)
        if (data.xuhuong) {
            showUserChart('day');
        }

        // Biểu đồ xu hướng tổng hợp
        if (data.xuhuong) {
            new Chart(document.getElementById('chartXuhuong'), {
                type: 'line',
                data: {
                    labels: data.xuhuong.map(item => item.ngay),
                    datasets: [
                        {
                            label: 'Tin đăng mới',
                            data: data.xuhuong.map(item => item.tinmoi || 0),
                            borderColor: '#667eea',
                            backgroundColor: 'rgba(102, 126, 234, 0.1)',
                            borderWidth: 2,
                            tension: 0.4,
                            fill: true,
                            pointRadius: 3,
                            pointHoverRadius: 5
                        },
                        {
                            label: 'Đơn nộp',
                            data: data.xuhuong.map(item => item.donnop || 0),
                            borderColor: '#f5576c',
                            backgroundColor: 'rgba(245, 87, 108, 0.1)',
                            borderWidth: 2,
                            tension: 0.4,
                            fill: true,
                            pointRadius: 3,
                            pointHoverRadius: 5
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 15,
                                font: {
                                    size: 12,
                                    weight: '600'
                                }
                            }
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                font: {
                                    size: 11
                                }
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            ticks: {
                                maxRotation: 45,
                                minRotation: 45,
                                font: {
                                    size: 10
                                }
                            },
                            grid: {
                                display: false
                            }
                        }
                    },
                    interaction: {
                        mode: 'nearest',
                        axis: 'x',
                        intersect: false
                    }
                }
            });
        }
    })
    .catch(error => {
        console.error('Lỗi tải dữ liệu thống kê:', error);
    });
</script>

<?php require_once BASE_PATH . 'app/views/layouts/footer.php'; ?>
