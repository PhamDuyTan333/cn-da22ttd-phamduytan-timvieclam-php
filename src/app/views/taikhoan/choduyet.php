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
        <p class="text-muted mt-4">
            <i class="fas fa-info-circle"></i> 
            Quản trị viên sẽ xem xét yêu cầu của bạn trong thời gian sớm nhất. 
            Bạn sẽ nhận được thông báo qua email khi tài khoản được phê duyệt.
        </p>
        <a href="<?php echo BASE_URL; ?>" class="btn btn-primary mt-3">
            <i class="fas fa-home"></i> Về trang chủ
        </a>
    </div>
</div>

<style>
.waiting-approval {
    max-width: 600px;
    margin: 50px auto;
    text-align: center;
    padding: 40px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.waiting-icon {
    color: #ffc107;
    margin-bottom: 30px;
}

.waiting-approval h2 {
    color: #333;
    margin-bottom: 15px;
}

.waiting-approval .lead {
    color: #666;
    font-size: 18px;
    margin-bottom: 30px;
}

.info-box {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    margin: 30px 0;
    text-align: left;
}

.info-box h4 {
    margin-bottom: 15px;
    color: #007bff;
}

.info-box table {
    margin: 0;
}

.info-box th {
    width: 150px;
    font-weight: 600;
    color: #555;
}

.badge-warning {
    background-color: #ffc107;
    color: #000;
    padding: 5px 10px;
    border-radius: 4px;
}
</style>

<?php require_once BASE_PATH . 'app/views/layouts/footer.php'; ?>
