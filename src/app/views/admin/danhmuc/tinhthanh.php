<?php require_once BASE_PATH . 'app/views/layouts/header.php'; ?>

<div class="container" style="margin-top: 100px; margin-bottom: 50px;">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>admin"><i class="fas fa-home"></i> Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>danhmuc">Danh mục</a></li>
            <li class="breadcrumb-item active">Tỉnh thành</li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="mb-2"><i class="fas fa-map-marker-alt me-3 text-danger"></i>Quản lý Tỉnh thành</h1>
                <p class="text-muted mb-0">Danh sách các tỉnh thành trong hệ thống</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-primary" onclick="showAddForm()">
                    <i class="fas fa-plus me-2"></i>Thêm tỉnh thành
                </button>
                <a href="<?php echo BASE_URL; ?>danhmuc" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Quay lại
                </a>
            </div>
        </div>
    </div>
    
    <!-- Add Form -->
    <div id="addForm" class="card border-0 shadow-sm mb-4" style="display: none;">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Thêm tỉnh thành mới</h5>
        </div>
        <div class="card-body p-4">
            <form method="POST" action="<?php echo BASE_URL; ?>danhmuc/themtinhthanh">
                <?php echo csrf_field(); ?>
                <div class="mb-3">
                    <label class="form-label fw-bold">Tên tỉnh thành:</label>
                    <input type="text" name="tentinh" class="form-control" required placeholder="Ví dụ: Hồ Chí Minh, Hà Nội">
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-2"></i>Thêm
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="hideAddForm()">
                        <i class="fas fa-times me-2"></i>Hủy
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Edit Form -->
    <div id="editForm" class="card border-0 shadow-sm mb-4" style="display: none;">
        <div class="card-header bg-warning text-dark py-3">
            <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Sửa tỉnh thành</h5>
        </div>
        <div class="card-body p-4">
            <form method="POST" id="formSua">
                <?php echo csrf_field(); ?>
                <div class="mb-3">
                    <label class="form-label fw-bold">Tên tỉnh thành:</label>
                    <input type="text" name="tentinh" id="editTen" class="form-control" required>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save me-2"></i>Cập nhật
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="hideEditForm()">
                        <i class="fas fa-times me-2"></i>Hủy
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Alert Messages -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i><?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle me-2"></i><?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <!-- List -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-light py-3">
            <h5 class="mb-0"><i class="fas fa-list me-2"></i>Danh sách tỉnh thành</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 80px;" class="ps-4">STT</th>
                            <th>Tỉnh thành</th>
                            <th style="width: 200px;" class="text-center pe-4">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($danhSach)): ?>
                        <tr>
                            <td colspan="3" class="text-center py-5">
                                <i class="fas fa-inbox fa-4x text-muted mb-3 d-block"></i>
                                <p class="text-muted mb-0">Chưa có tỉnh thành nào</p>
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php $stt = 1; foreach($danhSach as $item): ?>
                        <tr>
                            <td class="ps-4 fw-bold"><?php echo $stt++; ?></td>
                            <td>
                                <span class="badge bg-danger" style="font-size: 0.95rem; padding: 10px 18px;">
                                    <i class="fas fa-map-marker-alt me-2"></i><?php echo htmlspecialchars($item['tentinh']); ?>
                                </span>
                            </td>
                            <td class="text-center pe-4">
                                <button class="btn btn-sm btn-warning me-1" onclick="editItem(<?php echo $item['id']; ?>, '<?php echo htmlspecialchars($item['tentinh'], ENT_QUOTES); ?>')">
                                    <i class="fas fa-edit"></i> Sửa
                                </button>
                                <button class="btn btn-sm btn-danger" onclick="deleteItem(<?php echo $item['id']; ?>)">
                                    <i class="fas fa-trash"></i> Xóa
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Form Xóa ẩn -->
<form method="POST" id="formXoa" style="display:none;">
    <?php echo csrf_field(); ?>
</form>

<script>
function showAddForm() {
    document.getElementById('addForm').style.display = 'block';
    document.getElementById('editForm').style.display = 'none';
    window.scrollTo({ top: document.getElementById('addForm').offsetTop - 100, behavior: 'smooth' });
}

function hideAddForm() {
    document.getElementById('addForm').style.display = 'none';
}

function showEditForm() {
    document.getElementById('editForm').style.display = 'block';
    document.getElementById('addForm').style.display = 'none';
    window.scrollTo({ top: document.getElementById('editForm').offsetTop - 100, behavior: 'smooth' });
}

function hideEditForm() {
    document.getElementById('editForm').style.display = 'none';
}

function editItem(id, ten) {
    document.getElementById('editTen').value = ten;
    document.getElementById('formSua').action = '<?php echo BASE_URL; ?>danhmuc/suatinhthanh/' + id;
    showEditForm();
}

function deleteItem(id) {
    if (confirm('Bạn có chắc chắn muốn xóa tỉnh thành này?')) {
        document.getElementById('formXoa').action = '<?php echo BASE_URL; ?>danhmuc/xoatinhthanh/' + id;
        document.getElementById('formXoa').submit();
    }
}
</script>

<?php require_once BASE_PATH . 'app/views/layouts/footer.php'; ?>
