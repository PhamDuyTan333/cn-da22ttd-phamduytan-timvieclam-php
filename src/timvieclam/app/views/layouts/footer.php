    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Về chúng tôi</h3>
                    <p>Website tìm việc làm - Kết nối ứng viên và nhà tuyển dụng một cách nhanh chóng và hiệu quả.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-linkedin"></i></a>
                        <a href="#"><i class="fab fa-github"></i></a>
                    </div>
                </div>
                
                <div class="footer-section">
                    <h3>Dành cho ứng viên</h3>
                    <ul>
                        <li><a href="<?php echo BASE_URL; ?>timkiem">Tìm việc làm</a></li>
                        <li><a href="<?php echo BASE_URL; ?>ungvien/hoso">Quản lý hồ sơ</a></li>
                        <li><a href="<?php echo BASE_URL; ?>ungvien/donungtuyen">Đơn ứng tuyển</a></li>
                        <li><a href="<?php echo BASE_URL; ?>huongdan/ungvien">Hướng dẫn</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>Dành cho nhà tuyển dụng</h3>
                    <ul>
                        <li><a href="<?php echo BASE_URL; ?>ungvien/yeucautuyendung">Đăng ký tuyển dụng</a></li>
                        <li><a href="<?php echo BASE_URL; ?>nhatuyendung/dangtin">Đăng tin tuyển dụng</a></li>
                        <li><a href="<?php echo BASE_URL; ?>nhatuyendung">Quản lý tin</a></li>
                        <li><a href="<?php echo BASE_URL; ?>huongdan/nhatuyendung">Hướng dẫn</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>Liên hệ</h3>
                    <ul class="contact-info">
                        <li><i class="fas fa-map-marker-alt"></i> Vĩnh Long</li>
                        <li><i class="fas fa-phone"></i> 0123.456.789</li>
                        <li><i class="fas fa-envelope"></i> cskh.timviec@gmail.com</li>
                        <li><i class="fas fa-clock"></i> T2-T6: 8:00 - 17:00</li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2025 Phạm Duy Tân – Website Tìm Việc Làm</p>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script src="<?php echo BASE_URL; ?>js/main.js"></script>
    <?php if (isset($customJS)): ?>
        <script src="<?php echo BASE_URL; ?>js/<?php echo $customJS; ?>"></script>
    <?php endif; ?>
    
    <!-- Chatbot -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/chatbot.css">
    <script defer src="<?php echo BASE_URL; ?>js/chatbot.js"></script>
</body>
</html>
