<?php require_once BASE_PATH . 'app/views/layouts/header.php'; ?>

<div class="container" style="margin-top: 100px;">
    <div class="waiting-approval">
        <div class="waiting-icon">
            <i class="fas fa-hourglass-half fa-5x"></i>
        </div>
        <h2>Tài khoản đang chờ duyệt</h2>
        <p class="lead">Yêu cầu trở thành nhà tuyển dụng của bạn đang được xem xét.</p>
        
        <div class="info-box">
            <h4>Thông tin tài khoản</h4>
            <table class="table">
                <tr>
                    <th>Họ tên:</th>
                    <td><?php echo htmlspecialchars($_SESSION['hoten']); ?></td>
                </tr>
                <tr>
                    <th>Email:</th>
                    <td><?php echo htmlspecialchars($_SESSION['email']); ?></td>
                </tr>
                <tr>
                    <th>Trạng thái:</th>
                    <td><span class="badge badge-warning">Chờ duyệt</span></td>
                </tr>
            </table>
        </div>
        
        <?php if (isset($thongTinNhaTuyenDung) && $thongTinNhaTuyenDung): ?>
        <div class="company-info">
            <h4><i class="fas fa-building"></i> Thông tin công ty đã nộp</h4>
            <div class="info-grid">
                <div class="info-item">
                    <strong>Tên công ty:</strong>
                    <p><?php echo htmlspecialchars($thongTinNhaTuyenDung['tencongty']); ?></p>
                </div>
                <div class="info-item">
                    <strong>Mã số thuế:</strong>
                    <p><?php echo htmlspecialchars($thongTinNhaTuyenDung['masothue'] ?? 'Chưa có'); ?></p>
                </div>
                <div class="info-item">
                    <strong>Email công ty:</strong>
                    <p><?php echo htmlspecialchars($thongTinNhaTuyenDung['email_congty'] ?? 'Chưa có'); ?></p>
                </div>
                <div class="info-item">
                    <strong>Địa chỉ:</strong>
                    <p><?php echo htmlspecialchars($thongTinNhaTuyenDung['diachi_congty'] ?? 'Chưa có'); ?></p>
                </div>
            </div>
        </div>
        
        <div class="action-buttons">
            <a href="<?php echo BASE_URL; ?>ungvien/suayeucau" class="btn btn-primary">
                <i class="fas fa-edit"></i> Chỉnh sửa yêu cầu
            </a>
            <button type="button" class="btn btn-danger" onclick="confirmCancel()">
                <i class="fas fa-times"></i> Hủy yêu cầu
            </button>
        </div>
        <?php endif; ?>
        
        <p class="text-muted mt-4">
            <i class="fas fa-info-circle"></i> 
            Quản trị viên sẽ xem xét yêu cầu của bạn trong thời gian sớm nhất. 
            Bạn sẽ nhận được thông báo qua email khi tài khoản được phê duyệt.
        </p>
        <a href="<?php echo BASE_URL; ?>" class="btn btn-secondary mt-3">
            <i class="fas fa-home"></i> Về trang chủ
        </a>
    </div>
</div>

<!-- Modal xác nhận hủy -->
<div class="modal fade" id="cancelModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận hủy yêu cầu</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn hủy yêu cầu trở thành nhà tuyển dụng?</p>
                <p class="text-danger"><strong>Lưu ý:</strong> Thông tin công ty đã nộp sẽ bị xóa và tài khoản sẽ trở về vai trò ứng viên.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Không</button>
                <form method="POST" action="<?php echo BASE_URL; ?>ungvien/huyyeucau" style="display: inline;">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-danger">Đồng ý hủy</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmCancel() {
    const modal = document.getElementById('cancelModal');
    modal.style.display = 'block';
    modal.classList.add('show');
    document.body.style.overflow = 'hidden';
}

document.addEventListener('DOMContentLoaded', function() {
    const closeButtons = document.querySelectorAll('.modal .close, .modal [data-dismiss="modal"]');
    closeButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const modal = this.closest('.modal');
            modal.style.display = 'none';
            modal.classList.remove('show');
            document.body.style.overflow = '';
        });
    });
    
    window.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal')) {
            e.target.style.display = 'none';
            e.target.classList.remove('show');
            document.body.style.overflow = '';
        }
    });
});
</script>

<?php require_once BASE_PATH . 'app/views/layouts/footer.php'; ?>