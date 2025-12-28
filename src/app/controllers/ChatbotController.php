<?php


require_once BASE_PATH . 'app/models/ChatbotModel.php';

class ChatbotController extends BaseController {
    private $chatbotModel;
    
    public function __construct() {
        parent::__construct();
        $this->chatbotModel = new ChatbotModel();
    }
    
    private function analyzeContext($sessionId) {
        try {
            $history = $this->chatbotModel->layLichSuTinNhan($sessionId, 3);
            
            $context = [
                'hasSearchedJobs' => false,
                'lastLocation' => null,
                'lastIndustry' => null,
                'isFollowUp' => false
            ];
            
            foreach ($history as $msg) {
                if (stripos($msg['message'], 'tìm việc') !== false || 
                    stripos($msg['message'], 'việc làm') !== false) {
                    $context['hasSearchedJobs'] = true;
                }
            }
            
            return $context;
        } catch (Exception $e) {
            return ['hasSearchedJobs' => false, 'lastLocation' => null, 'lastIndustry' => null, 'isFollowUp' => false];
        }
    }
    
    public function chat() {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                $this->json(['error' => 'Invalid request method'], 405);
            }
            
            $input = json_decode(file_get_contents('php://input'), true);
            
            // Check if JSON parsing failed
            if (json_last_error() !== JSON_ERROR_NONE) {
                $this->json(['error' => 'Invalid JSON format'], 400);
            }
            
            $message = trim($input['message'] ?? '');
            $sessionId = trim($input['sessionId'] ?? '');
            
            if (empty($message)) {
                $this->json(['error' => 'Message is required'], 400);
            }
            
            // Tạo session ID nếu chưa có
            if (empty($sessionId)) {
                $sessionId = uniqid('chat_', true);
            }
            
            // Phân tích ý định người dùng (truyền cả message gốc)
            $response = $this->processMessage($message, $input);
            
            // Lưu tin nhắn vào database chỉ khi đã đăng nhập
            if (isset($_SESSION['nguoidung_id'])) {
                $this->saveMessage($sessionId, $message, $response);
            }
            
            $this->json([
                'success' => true,
                'response' => $response,
                'sessionId' => $sessionId
            ]);
        } catch (Exception $e) {
            error_log("Chatbot error: " . $e->getMessage());
            $this->json([
                'error' => 'Loi he thong',
                'message' => DEBUG_MODE ? $e->getMessage() : 'Vui long thu lai'
            ], 500);
        }
    }
    
    public function history() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['error' => 'Invalid request method'], 405);
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        
        // Check if JSON parsing failed
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->json(['error' => 'Invalid JSON format'], 400);
        }
        
        $sessionId = trim($input['sessionId'] ?? '');
        $userId = trim($input['userId'] ?? '');
        
        // Nếu chưa đăng nhập, không load lịch sử
        if (!isset($_SESSION['nguoidung_id'])) {
            $this->json(['success' => true, 'messages' => []]);
            return;
        }
        
        try {
            $nguoidungId = $_SESSION['nguoidung_id'];
            
            // Lấy lịch sử theo nguoidung_id thay vì session_id
            $messages = $this->chatbotModel->layLichSuTheoNguoiDung($nguoidungId);
            
            // Lấy session_id từ message gần nhất (nếu có)
            $latestSessionId = null;
            if (!empty($messages)) {
                $latestSessionId = $messages[count($messages) - 1]['session_id'];
            }
            
            // Đảm bảo response được format đúng
            foreach ($messages as &$msg) {
                if ($msg['message_type'] === 'bot' && !empty($msg['response'])) {
                    // Kiểm tra xem response đã là JSON chưa
                    $decoded = json_decode($msg['response'], true);
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        // Nếu không phải JSON hợp lệ, wrap lại
                        $msg['response'] = json_encode(['type' => 'text', 'message' => $msg['response']], JSON_UNESCAPED_UNICODE);
                    }
                }
                // Xóa session_id khỏi response
                unset($msg['session_id']);
            }
            
            $this->json([
                'success' => true,
                'messages' => $messages,
                'sessionId' => $latestSessionId // Trả về session_id để client cập nhật
            ]);
        } catch (Exception $e) {
            error_log("Chatbot history error: " . $e->getMessage());
            $this->json(['success' => true, 'messages' => []]);
        }
    }
    
    public function newConversation() {
        try {
            // Nếu người dùng đã đăng nhập, xóa tin nhắn cũ của họ
            if (isset($_SESSION['nguoidung_id'])) {
                $nguoidungId = $_SESSION['nguoidung_id'];
                $this->chatbotModel->xoaHoiThoaiTheoNguoiDung($nguoidungId);
            }
            
            // Tạo session ID mới
            $sessionId = uniqid('chat_', true);
            
            $this->json([
                'success' => true,
                'sessionId' => $sessionId,
                'message' => 'Đã bắt đầu cuộc trò chuyện mới'
            ]);
        } catch (Exception $e) {
            error_log("Chatbot newConversation error: " . $e->getMessage());
            // Vẫn trả về session mới dù có lỗi khi xóa
            $sessionId = uniqid('chat_', true);
            $this->json([
                'success' => true,
                'sessionId' => $sessionId,
                'message' => 'Đã bắt đầu cuộc trò chuyện mới'
            ]);
        }
    }
    
    private function saveMessage($sessionId, $message, $response) {
        try {
            $responseText = is_array($response) ? json_encode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : $response;
            $nguoidungId = isset($_SESSION['nguoidung_id']) ? $_SESSION['nguoidung_id'] : null;
            $this->chatbotModel->luuTinNhan($sessionId, $message, $responseText, $nguoidungId);
        } catch (Exception $e) {
            error_log("Chatbot save message error: " . $e->getMessage());
        }
    }

    private function normalizeText($text) {
        $text = mb_strtolower($text, 'UTF-8');
        // Loại bỏ khoảng trắng thừa
        $text = preg_replace('/\s+/', ' ', $text);
        $text = trim($text);
        
        // Chuẩn hóa một số từ viết tắt và typo phổ biến
        $replacements = [
            'cntt' => 'công nghệ thông tin',
            'it' => 'công nghệ thông tin',
            'tphcm' => 'hồ chí minh',
            'tp hcm' => 'hồ chí minh',
            'tp.hcm' => 'hồ chí minh',
            'sài gòn' => 'hồ chí minh',
            'hn' => 'hà nội',
            'đn' => 'đà nẵng',
            'cv' => 'công việc',
            'ntd' => 'nhà tuyển dụng',
            'uv' => 'ứng viên',
            'ứng viên' => 'ứng viên',
            'full time' => 'toàn thời gian',
            'fulltime' => 'toàn thời gian',
            'part time' => 'bán thời gian',
            'parttime' => 'bán thời gian',
            'wfh' => 'làm từ xa',
            'work from home' => 'làm từ xa'
        ];
        
        foreach ($replacements as $old => $new) {
            $text = str_replace($old, $new, $text);
        }
        
        return $text;
    }

    private function processMessage($message, $input = []) {
        $message = $this->normalizeText($message);
        
        // Tìm việc gần tôi / theo địa chỉ - ưu tiên cao
        if ($this->containsKeywords($message, ['gần tôi', 'gần đây', 'quanh đây', 'địa chỉ của tôi', 'vị trí của tôi', 'nơi tôi ở'])) {
            return $this->timViecGanToi();
        }
        
        // Tìm việc làm - kiểm tra thông minh với nhiều điều kiện
        if ($this->isTimViecIntent($message)) {
            // Kiểm tra xem có địa điểm không (dùng message gốc)
            $originalMessage = isset($input['message']) ? mb_strtolower($input['message'], 'UTF-8') : $message;
            $diaDiemTest = $this->timDiaDiemTrongMessage($originalMessage);
            
            // Nếu có từ khóa địa điểm (ở, tại) nhưng không tìm thấy trong DB
            if (($this->containsKeywords($message, ['ở', 'tại', 'khu vực']) || 
                 preg_match('/\b(hà|thành|tỉnh|huyện|quận|phố|xã)\b/i', $message)) 
                && !$diaDiemTest) {
                // Thử tìm tên địa điểm trong message
                $words = explode(' ', $originalMessage);
                $possibleLocation = '';
                foreach ($words as $i => $word) {
                    if (in_array($word, ['ở', 'tại', 'khu', 'vùng']) && isset($words[$i+1])) {
                        $possibleLocation = $words[$i+1];
                        if (isset($words[$i+2])) {
                            $possibleLocation .= ' ' . $words[$i+2];
                        }
                        break;
                    }
                }
                
                if (!empty($possibleLocation)) {
                    return [
                        'type' => 'text',
                        'message' => "Xin lỗi, hiện tại hệ thống chưa hỗ trợ tìm việc tại '{$possibleLocation}'.\n\nBạn có thể thử:\n• Tìm việc ở các tỉnh/thành phố lớn: Hà Nội, TP.HCM, Đà Nẵng\n• Nhấn 'địa điểm' để xem danh sách đầy đủ\n• Thử tìm theo ngành nghề hoặc mức lương",
                        'suggestions' => [
                            'Địa điểm',
                            'Tìm việc IT Hà Nội',
                            'Việc làm TP.HCM',
                            'Ngành nghề'
                        ]
                    ];
                }
            }
            
            return $this->timViecLam($message);
        }
        
        // Hướng dẫn nộp đơn
        if ($this->containsKeywords($message, ['nộp đơn', 'ứng tuyển', 'apply', 'cv'])) {
            return $this->huongDanNopDon();
        }
        
        // Đăng ký tài khoản
        if ($this->containsKeywords($message, ['đăng ký', 'tạo tài khoản', 'register'])) {
            return $this->huongDanDangKy();
        }
        
        // Trở thành nhà tuyển dụng
        if ($this->containsKeywords($message, ['nhà tuyển dụng', 'đăng tin', 'tuyển người'])) {
            return $this->huongDanNhaTuyenDung();
        }
        
        // Mức lương
        if ($this->containsKeywords($message, ['lương', 'salary', 'thu nhập'])) {
            return $this->thongTinMucLuong();
        }
        
        // Ngành nghề
        if ($this->containsKeywords($message, ['ngành nghề', 'lĩnh vực', 'chuyên ngành'])) {
            return $this->danhSachNganhNghe();
        }
        
        // Địa điểm
        if ($this->containsKeywords($message, ['địa điểm', 'nơi làm việc', 'tỉnh', 'thành phố', 'ở đâu', 'khu vực nào'])) {
            return $this->danhSachDiaDiem();
        }
        
        // Câu hỏi về cách thức
        if (preg_match('/(làm.*sao|làm.*thế.*nào|cách.*nào|how.*to)/i', $message)) {
            if ($this->containsKeywords($message, ['nộp', 'ứng tuyển', 'apply'])) {
                return $this->huongDanNopDon();
            }
            if ($this->containsKeywords($message, ['đăng ký', 'tạo tài khoản'])) {
                return $this->huongDanDangKy();
            }
            if ($this->containsKeywords($message, ['đăng tin', 'tuyển người'])) {
                return $this->huongDanNhaTuyenDung();
            }
            return $this->menuGiupDo();
        }
        
        // Câu hỏi yes/no
        if (preg_match('/(có.*không|có.*nào|có.*gì)/i', $message)) {
            if ($this->containsKeywords($message, ['việc', 'job', 'tuyển dụng'])) {
                return $this->timViecLam($message);
            }
        }
        
        // Chào hỏi
        if ($this->containsKeywords($message, ['xin chào', 'hello', 'hi', 'chào'])) {
            return $this->chaoMung();
        }
        
        // Cảm ơn
        if ($this->containsKeywords($message, ['cảm ơn', 'thank', 'thanks'])) {
            return [
                'type' => 'text',
                'message' => 'Rất vui được giúp đỡ bạn! Nếu cần thêm hỗ trợ, đừng ngần ngại nhắn tin cho tôi nhé.'
            ];
        }
        
        // Giúp đỡ
        if ($this->containsKeywords($message, ['giúp', 'help', 'hỗ trợ', 'trợ giúp'])) {
            return $this->menuGiupDo();
        }
        
        // Xử lý câu hỏi ngắn (1-2 từ)
        $words = explode(' ', trim($message));
        if (count($words) <= 2) {
            // Câu hỏi 1 từ
            if (count($words) == 1) {
                $word = $words[0];
                if ($word == 'lương' || $word == 'salary') return $this->thongTinMucLuong();
                if ($word == 'ngành' || $word == 'nghề') return $this->danhSachNganhNghe();
                if ($word == 'địa' || $word == 'nơi' || $word == 'đâu') return $this->danhSachDiaDiem();
                if ($word == 'gần') return $this->timViecGanToi();
                
                // Thử tìm địa điểm
                $diaDiem = $this->timDiaDiemTrongMessage($message);
                if ($diaDiem) {
                    return $this->timViecLam('tìm việc ' . $message);
                }
            }
            
            // Câu hỏi 2 từ có thể là "ngành nghề", "mức lương", "địa điểm"
            if ($this->containsKeywords($message, ['ở', 'tại', 'khu'])) {
                return $this->timViecLam('tìm việc ' . $message);
            }
        }
        
        // Mặc định - không hiểu
        return $this->khongHieu();
    }

    private function isTimViecIntent($message) {
        // Từ khóa tìm việc chính với regex pattern
        $timKiemPatterns = [
            '/tìm.*việc/',
            '/tìm.*công việc/',
            '/tìm.*job/',
            '/việc.*làm/',
            '/tuyển.*dụng/',
            '/\bjob\b/',
            '/cho.*tôi.*việc/',
            '/giới.*thiệu.*việc/',
            '/có.*việc.*nào/',
            '/xin.*việc/',
            '/apply/',
            '/ứng.*tuyển/'
        ];
        
        // Kiểm tra pattern tìm việc
        foreach ($timKiemPatterns as $pattern) {
            if (preg_match($pattern, $message)) {
                return true;
            }
        }
        
        // Kiểm tra từ khóa tiêu chí tìm kiếm (ngành, lương, loại CV)
        $tieuChiKeywords = ['lương', 'triệu', 'toàn thời gian', 'bán thời gian', 'remote', 'thực tập', 'freelance'];
        foreach ($tieuChiKeywords as $keyword) {
            if (mb_strpos($message, $keyword, 0, 'UTF-8') !== false) {
                return true;
            }
        }
        
        // Kiểm tra pattern "ở + địa điểm" - rất quan trọng!
        $diaDiem = $this->timDiaDiemTrongMessage($message);
        if ($diaDiem && (mb_strpos($message, 'ở', 0, 'UTF-8') !== false || 
                         mb_strpos($message, 'tại', 0, 'UTF-8') !== false ||
                         mb_strpos($message, 'khu vực', 0, 'UTF-8') !== false)) {
            return true;
        }
        
        // Kiểm tra xem có nhắc đến ngành nghề hoặc địa điểm (có thể là tìm việc)
        if ($this->timNganhNgheTrongMessage($message) || $diaDiem) {
            // Nếu có ngành/địa điểm VÀ có từ "công việc" hoặc dạng câu hỏi
            if (mb_strpos($message, 'công việc', 0, 'UTF-8') !== false || 
                mb_strpos($message, 'cv', 0, 'UTF-8') !== false) {
                return true;
            }
        }
        
        return false;
    }

    private function containsKeywords($message, $keywords) {
        foreach ($keywords as $keyword) {
            if (mb_strpos($message, $keyword, 0, 'UTF-8') !== false) {
                return true;
            }
        }
        return false;
    }

    private function timViecLam($message) {
        $filters = [
            'limit' => 5
        ];
        
        // Tìm theo địa điểm nếu có
        $diaDiem = $this->timDiaDiemTrongMessage($message);
        if ($diaDiem) {
            $diaDiemData = $this->chatbotModel->timDiaDiem($diaDiem);
            if ($diaDiemData) {
                $filters['tinhthanh_id'] = $diaDiemData['id'];
            }
        }
        
        // Tìm theo ngành nghề nếu có
        $nganhNghe = $this->timNganhNgheTrongMessage($message);
        if ($nganhNghe) {
            $nganhNgheData = $this->chatbotModel->layDanhSachNganhNghe();
            foreach ($nganhNgheData as $nn) {
                if (stripos($nn['tennganh'], $nganhNghe) !== false) {
                    $filters['nganhnghe_id'] = $nn['id'];
                    break;
                }
            }
        }
        
        // Tìm theo mức lương nếu có
        $mucLuong = $this->timMucLuongTrongMessage($message);
        if ($mucLuong) {
            $mucLuongData = $this->chatbotModel->layDanhSachMucLuong();
            foreach ($mucLuongData as $ml) {
                if (stripos($ml['tenmucluong'], $mucLuong) !== false) {
                    $filters['mucluong_id'] = $ml['id'];
                    break;
                }
            }
        }
        
        // Tìm theo loại công việc nếu có
        $loaiCongViec = $this->timLoaiCongViecTrongMessage($message);
        if ($loaiCongViec) {
            $loaiCVData = $this->chatbotModel->layDanhSachLoaiCongViec();
            foreach ($loaiCVData as $lcv) {
                if (stripos($lcv['tenloai'], $loaiCongViec) !== false) {
                    $filters['loaicongviec_id'] = $lcv['id'];
                    break;
                }
            }
        }
        
        $jobs = $this->chatbotModel->timKiemViecLam($filters);
        
        if (empty($jobs)) {
            $thongBaoChiTiet = "Hiện tại chưa có tin tuyển dụng phù hợp";
            
            if ($diaDiem) {
                $diaDiemData = $this->chatbotModel->timDiaDiem($diaDiem);
                
                if (!$diaDiemData) {
                    $thongBaoChiTiet = "Xin lỗi, hiện tại hệ thống chưa hỗ trợ tìm việc tại '{$diaDiem}'.\n\n";
                    $thongBaoChiTiet .= "Bạn có thể thử:\n";
                    $thongBaoChiTiet .= "• Tìm việc ở các tỉnh/thành phố lớn: Hà Nội, TP.HCM, Đà Nẵng\n";
                    $thongBaoChiTiet .= "• Nhấn 'địa điểm' để xem danh sách đầy đủ\n";
                    $thongBaoChiTiet .= "• Thử tìm theo ngành nghề hoặc mức lương";
                    
                    return [
                        'type' => 'text',
                        'message' => $thongBaoChiTiet,
                        'suggestions' => [
                            'Địa điểm',
                            'Tìm việc IT Hà Nội',
                            'Việc làm TP.HCM',
                            'Ngành nghề'
                        ]
                    ];
                } else {
                    $thongBaoChiTiet .= " tại {$diaDiem}";
                }
            }
            
            if ($nganhNghe) $thongBaoChiTiet .= " về {$nganhNghe}";
            if ($mucLuong) $thongBaoChiTiet .= " với mức lương {$mucLuong}";
            if ($loaiCongViec) $thongBaoChiTiet .= " loại hình {$loaiCongViec}";
            
            $thongBaoChiTiet .= ".\n\nGợi ý:\n";
            $thongBaoChiTiet .= "• Thử mở rộng khu vực tìm kiếm\n";
            $thongBaoChiTiet .= "• Xem các ngành nghề khác\n";
            $thongBaoChiTiet .= "• Điều chỉnh mức lương mong muốn\n";
            $thongBaoChiTiet .= "\nNhấn 'giúp đỡ' để xem thêm tùy chọn tìm kiếm.";
            
            return [
                'type' => 'text',
                'message' => $thongBaoChiTiet,
                'suggestions' => [
                    'Giúp đỡ',
                    'Địa điểm',
                    'Ngành nghề',
                    'Tìm việc IT'
                ]
            ];
        }
        
        $count = count($jobs);
        $messageText = "Tìm thấy {$count} việc làm";
        if ($nganhNghe) $messageText .= " về {$nganhNghe}";
        if ($diaDiem) $messageText .= " tại {$diaDiem}";
        if ($mucLuong) $messageText .= " với mức lương {$mucLuong}";
        if ($loaiCongViec) $messageText .= " loại hình {$loaiCongViec}";
        $messageText .= ":";
        
        return [
            'type' => 'jobs',
            'message' => $messageText,
            'jobs' => $jobs,
            'footer' => 'Nhấn vào công việc để xem chi tiết và ứng tuyển ngay!'
        ];
    }

    private function timDiaDiemTrongMessage($message) {
        $message = mb_strtolower($message, 'UTF-8');
        
        $danhSach = $this->chatbotModel->layDanhSachTinhThanh();
        
        foreach ($danhSach as $item) {
            $tinh = mb_strtolower($item['tentinh'], 'UTF-8');
            if (mb_strpos($message, $tinh, 0, 'UTF-8') !== false) {
                return $item['tentinh'];
            }
        }
        
        return null;
    }

    private function timNganhNgheTrongMessage($message) {
        $danhSach = $this->chatbotModel->layDanhSachNganhNghe();
        
        foreach ($danhSach as $item) {
            $nganh = mb_strtolower($item['tennganh'], 'UTF-8');
            if (mb_strpos($message, $nganh, 0, 'UTF-8') !== false) {
                return $item['tennganh'];
            }
        }
        
        // Kiểm tra các từ khóa ngành nghề phổ biến với nhiều biến thể
        $keywords = [
            'công nghệ thông tin' => 'Công nghệ thông tin',
            'công nghệ' => 'Công nghệ thông tin',
            'lập trình' => 'Công nghệ thông tin',
            'developer' => 'Công nghệ thông tin',
            'software' => 'Công nghệ thông tin',
            'code' => 'Công nghệ thông tin',
            'coder' => 'Công nghệ thông tin',
            'lập trình viên' => 'Công nghệ thông tin',
            'dev' => 'Công nghệ thông tin',
            'frontend' => 'Công nghệ thông tin',
            'backend' => 'Công nghệ thông tin',
            'fullstack' => 'Công nghệ thông tin',
            'web' => 'Công nghệ thông tin',
            'mobile' => 'Công nghệ thông tin',
            'app' => 'Công nghệ thông tin',
            'marketing' => 'Marketing',
            'quảng cáo' => 'Marketing',
            'truyền thông' => 'Marketing',
            'mkt' => 'Marketing',
            'digital marketing' => 'Marketing',
            'seo' => 'Marketing',
            'content' => 'Marketing',
            'kinh doanh' => 'Kinh doanh',
            'bán hàng' => 'Kinh doanh',
            'sales' => 'Kinh doanh',
            'telesale' => 'Kinh doanh',
            'business' => 'Kinh doanh',
            'kế toán' => 'Kế toán',
            'tài chính' => 'Kế toán',
            'accountant' => 'Kế toán',
            'accounting' => 'Kế toán',
            'finance' => 'Kế toán',
            'nhân sự' => 'Nhân sự',
            'hr' => 'Nhân sự',
            'tuyển dụng' => 'Nhân sự',
            'human resource' => 'Nhân sự',
            'thiết kế' => 'Thiết kế',
            'design' => 'Thiết kế',
            'đồ họa' => 'Thiết kế',
            'graphic' => 'Thiết kế',
            'ui' => 'Thiết kế',
            'ux' => 'Thiết kế',
            'designer' => 'Thiết kế',
            'xây dựng' => 'Xây dựng',
            'kiến trúc' => 'Xây dựng',
            'construction' => 'Xây dựng',
            'architect' => 'Xây dựng'
        ];
        
        foreach ($keywords as $keyword => $nganh) {
            if (mb_strpos($message, $keyword, 0, 'UTF-8') !== false) {
                // Kiểm tra xem ngành này có tồn tại trong database không
                foreach ($danhSach as $item) {
                    if (mb_strtolower($item['tennganh'], 'UTF-8') == mb_strtolower($nganh, 'UTF-8')) {
                        return $item['tennganh'];
                    }
                }
                // Nếu không có trong DB, vẫn trả về để tìm trong tiêu đề/mô tả
                return $nganh;
            }
        }
        
        return null;
    }

    private function timMucLuongTrongMessage($message) {
        $danhSach = $this->chatbotModel->layDanhSachMucLuong();
        
        foreach ($danhSach as $item) {
            $luong = mb_strtolower($item['tenmucluong'], 'UTF-8');
            if (mb_strpos($message, $luong, 0, 'UTF-8') !== false) {
                return $item['tenmucluong'];
            }
        }
        
        // Kiểm tra các từ khóa về mức lương (số tiền cụ thể)
        $patterns = [
            '/trên\s*(\d+)\s*triệu/' => function($matches) {
                $amount = (int)$matches[1];
                if ($amount >= 20) return 'Trên 20 triệu';
                if ($amount >= 15) return 'Từ 15 - 20 triệu';
                if ($amount >= 10) return 'Từ 10 - 15 triệu';
                if ($amount >= 7) return 'Từ 7 - 10 triệu';
                return null;
            },
            '/dưới\s*(\d+)\s*triệu/' => function($matches) {
                $amount = (int)$matches[1];
                if ($amount <= 5) return 'Dưới 5 triệu';
                if ($amount <= 7) return 'Từ 5 - 7 triệu';
                if ($amount <= 10) return 'Từ 7 - 10 triệu';
                return null;
            },
            '/(\d+)\s*-\s*(\d+)\s*triệu/' => function($matches) {
                $min = (int)$matches[1];
                $max = (int)$matches[2];
                if ($min >= 15 && $max <= 20) return 'Từ 15 - 20 triệu';
                if ($min >= 10 && $max <= 15) return 'Từ 10 - 15 triệu';
                if ($min >= 7 && $max <= 10) return 'Từ 7 - 10 triệu';
                if ($min >= 5 && $max <= 7) return 'Từ 5 - 7 triệu';
                return null;
            },
            '/(\d+)\s*triệu/' => function($matches) {
                $amount = (int)$matches[1];
                if ($amount >= 20) return 'Trên 20 triệu';
                if ($amount >= 15) return 'Từ 15 - 20 triệu';
                if ($amount >= 10) return 'Từ 10 - 15 triệu';
                if ($amount >= 7) return 'Từ 7 - 10 triệu';
                if ($amount >= 5) return 'Từ 5 - 7 triệu';
                return 'Dưới 5 triệu';
            }
        ];
        
        foreach ($patterns as $pattern => $handler) {
            if (preg_match($pattern, $message, $matches)) {
                $result = $handler($matches);
                if ($result) return $result;
            }
        }
        
        return null;
    }

    private function timLoaiCongViecTrongMessage($message) {
        $danhSach = $this->chatbotModel->layDanhSachLoaiCongViec();
        
        foreach ($danhSach as $item) {
            $loai = mb_strtolower($item['tenloai'], 'UTF-8');
            if (mb_strpos($message, $loai, 0, 'UTF-8') !== false) {
                return $item['tenloai'];
            }
        }
        
        // Kiểm tra các từ khóa liên quan
        $keywords = [
            'toàn thời gian' => 'Toàn thời gian',
            'full time' => 'Toàn thời gian',
            'fulltime' => 'Toàn thời gian',
            'bán thời gian' => 'Bán thời gian',
            'part time' => 'Bán thời gian',
            'parttime' => 'Bán thời gian',
            'thực tập' => 'Thực tập',
            'intern' => 'Thực tập',
            'internship' => 'Thực tập',
            'làm từ xa' => 'Làm từ xa',
            'remote' => 'Làm từ xa',
            'work from home' => 'Làm từ xa',
            'wfh' => 'Làm từ xa',
            'theo dự án' => 'Theo dự án',
            'freelance' => 'Theo dự án',
            'project based' => 'Theo dự án'
        ];
        
        foreach ($keywords as $keyword => $loai) {
            if (mb_strpos($message, $keyword, 0, 'UTF-8') !== false) {
                return $loai;
            }
        }
        
        return null;
    }

    private function extractAddressParts($diachi) {
        $diachiLower = mb_strtolower($diachi, 'UTF-8');
        $parts = [
            'duong' => null,
            'phuong' => null,
            'quan' => null,
            'tinh' => null
        ];
        
        // Danh sách tỉnh/thành phố
        $danhSachTinh = [
            'tp.hồ chí minh' => 'TP.Hồ Chí Minh', 'tp. hồ chí minh' => 'TP.Hồ Chí Minh',
            'hồ chí minh' => 'TP.Hồ Chí Minh', 'sài gòn' => 'TP.Hồ Chí Minh', 'tp.hcm' => 'TP.Hồ Chí Minh',
            'tp.hà nội' => 'TP.Hà Nội', 'tp. hà nội' => 'TP.Hà Nội', 'hà nội' => 'TP.Hà Nội',
            'tp.đà nẵng' => 'TP.Đà Nẵng', 'tp. đà nẵng' => 'TP.Đà Nẵng', 'đà nẵng' => 'TP.Đà Nẵng',
            'tp.cần thơ' => 'TP.Cần Thơ', 'tp. cần thơ' => 'TP.Cần Thơ', 'cần thơ' => 'TP.Cần Thơ',
            'tp.hải phòng' => 'TP.Hải Phòng', 'tp. hải phòng' => 'TP.Hải Phòng', 'hải phòng' => 'TP.Hải Phòng',
            'tp.huế' => 'TP.Huế', 'tp. huế' => 'TP.Huế', 'huế' => 'TP.Huế', 'thừa thiên huế' => 'TP.Huế',
            'vĩnh long' => 'Vĩnh Long', 'an giang' => 'An Giang', 'bà rịa vũng tàu' => 'Bà Rịa - Vũng Tàu',
            'bắc giang' => 'Bắc Giang', 'bắc kạn' => 'Bắc Kạn', 'bạc liêu' => 'Bạc Liêu',
            'bắc ninh' => 'Bắc Ninh', 'bến tre' => 'Bến Tre', 'bình định' => 'Bình Định',
            'bình dương' => 'Bình Dương', 'bình phước' => 'Bình Phước', 'bình thuận' => 'Bình Thuận',
            'cà mau' => 'Cà Mau', 'cao bằng' => 'Cao Bằng', 'đắk lắk' => 'Đắk Lắk',
            'đắk nông' => 'Đắk Nông', 'điện biên' => 'Điện Biên', 'đồng nai' => 'Đồng Nai',
            'đồng tháp' => 'Đồng Tháp', 'gia lai' => 'Gia Lai', 'hà giang' => 'Hà Giang',
            'hà nam' => 'Hà Nam', 'hà tĩnh' => 'Hà Tĩnh', 'hải dương' => 'Hải Dương',
            'hậu giang' => 'Hậu Giang', 'hòa bình' => 'Hòa Bình', 'hưng yên' => 'Hưng Yên',
            'khánh hòa' => 'Khánh Hòa', 'kiên giang' => 'Kiên Giang', 'kon tum' => 'Kon Tum',
            'lai châu' => 'Lai Châu', 'lâm đồng' => 'Lâm Đồng', 'lạng sơn' => 'Lạng Sơn',
            'lào cai' => 'Lào Cai', 'long an' => 'Long An', 'nam định' => 'Nam Định',
            'nghệ an' => 'Nghệ An', 'ninh bình' => 'Ninh Bình', 'ninh thuận' => 'Ninh Thuận',
            'phú thọ' => 'Phú Thọ', 'phú yên' => 'Phú Yên', 'quảng bình' => 'Quảng Bình',
            'quảng nam' => 'Quảng Nam', 'quảng ngãi' => 'Quảng Ngãi', 'quảng ninh' => 'Quảng Ninh',
            'quảng trị' => 'Quảng Trị', 'sóc trăng' => 'Sóc Trăng', 'sơn la' => 'Sơn La',
            'tây ninh' => 'Tây Ninh', 'thái bình' => 'Thái Bình', 'thái nguyên' => 'Thái Nguyên',
            'thanh hóa' => 'Thanh Hóa', 'tiền giang' => 'Tiền Giang', 'trà vinh' => 'Trà Vinh',
            'tuyên quang' => 'Tuyên Quang', 'vĩnh phúc' => 'Vĩnh Phúc', 'yên bái' => 'Yên Bái'
        ];
        
        // Tìm tỉnh/thành phố
        foreach ($danhSachTinh as $keyword => $tenTinh) {
            if (strpos($diachiLower, $keyword) !== false) {
                $parts['tinh'] = $tenTinh;
                break;
            }
        }
        
        // Tìm quận/huyện
        if (preg_match('/(quận|huyện|thành phố|thị xã|tp\.)\s*([^,]+)/iu', $diachi, $matches)) {
            $parts['quan'] = trim($matches[0]);
        }
        
        // Tìm phường/xã
        if (preg_match('/(phường|xã|thị trấn)\s*([^,]+)/iu', $diachi, $matches)) {
            $parts['phuong'] = trim($matches[0]);
        }
        
        return $parts;
    }
    
    private function timViecGanToi() {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['nguoidung_id'])) {
            return [
                'type' => 'text',
                'message' => 'Bạn cần đăng nhập để sử dụng tính năng này. Hoặc bạn có thể tìm việc trực tiếp: "tìm việc ở Hà Nội"'
            ];
        }
        
        try {
            $userId = $_SESSION['nguoidung_id'];
            require_once BASE_PATH . 'app/models/NguoiDungModel.php';
            $nguoiDungModel = new NguoiDungModel();
            $diachi = $nguoiDungModel->layDiaChi($userId);
            
            if (empty($diachi)) {
                return [
                    'type' => 'text',
                    'message' => 'Bạn chưa cập nhật địa chỉ trong hồ sơ. Vui lòng cập nhật để tôi có thể tìm việc phù hợp!'
                ];
            }
            
            $diachiLower = mb_strtolower($diachi, 'UTF-8');
            
            // Tách các thành phần địa chỉ (đường, phường, quận, tỉnh)
            $addressParts = $this->extractAddressParts($diachi);
            
            // Tìm việc làm dựa trên địa chỉ người dùng
            $jobs = $this->chatbotModel->timViecTheoViTri($diachiLower, $addressParts);
            
            if (empty($jobs)) {
                // Nếu không tìm thấy, thử tìm theo tỉnh/thành phố
                $diaDiem = $addressParts['tinh'] ?? null;
                
                if ($diaDiem) {
                    $diaDiemData = $this->chatbotModel->timDiaDiem($diaDiem);
                    if ($diaDiemData) {
                        $jobs = $this->chatbotModel->timKiemViecLam([
                            'tinhthanh_id' => $diaDiemData['id'],
                            'limit' => 10
                        ]);
                    }
                }
                
                if (empty($jobs)) {
                    $message = "Hiện tại chưa có việc làm nào gần bạn";
                    if ($diaDiem) {
                        $message .= " tại {$diaDiem}";
                    }
                    $message .= ".\n\nGợi ý:\n• Thử mở rộng khu vực tìm kiếm\n• Tìm việc theo ngành nghề\n• Xem các tỉnh/thành phố khác";
                    
                    return [
                        'type' => 'text',
                        'message' => $message,
                        'suggestions' => [
                            'Tìm việc IT',
                            'Địa điểm',
                            'Ngành nghề',
                            'Giúp đỡ'
                        ]
                    ];
                }
            }
            
            // Lấy tên địa điểm để hiển thị
            $displayLocation = $addressParts['tinh'] ?? 'khu vực của bạn';
            if (!empty($addressParts['quan'])) {
                $displayLocation = $addressParts['quan'] . ', ' . $displayLocation;
            }
            
            return [
                'type' => 'jobs',
                'message' => "Tìm thấy " . count($jobs) . " việc làm gần {$displayLocation}:",
                'jobs' => $jobs
            ];
            
        } catch (Exception $e) {
            error_log("timViecGanToi error: " . $e->getMessage());
            return [
                'type' => 'text',
                'message' => 'Xin lỗi, có lỗi xảy ra. Vui lòng thử lại sau!'
            ];
        }
    }

    private function huongDanNopDon() {
        return [
            'type' => 'guide',
            'message' => 'Hướng dẫn nộp đơn ứng tuyển:',
            'steps' => [
                '1. Tìm công việc phù hợp trên trang chủ',
                '2. Nhấn vào tin tuyển dụng để xem chi tiết',
                '3. Nhấn nút "Ứng tuyển ngay"',
                '4. Upload CV của bạn (PDF, DOC, DOCX - tối đa 5MB)',
                '5. Viết thư ứng tuyển (giới thiệu bản thân)',
                '6. Nhấn "Gửi hồ sơ" để hoàn tất'
            ],
            'link' => BASE_URL . 'timkiem',
            'linkText' => 'Tìm việc ngay'
        ];
    }

    private function huongDanDangKy() {
        return [
            'type' => 'guide',
            'message' => 'Hướng dẫn đăng ký tài khoản:',
            'steps' => [
                '1. Nhấn vào nút "Đăng ký" trên menu',
                '2. Điền đầy đủ thông tin: Họ tên, Email, SĐT, Mật khẩu',
                '3. Nhấn "Đăng ký" để tạo tài khoản',
                '4. Đăng nhập bằng email và mật khẩu vừa tạo',
                '5. Hoàn thiện hồ sơ để tăng cơ hội được tuyển dụng'
            ],
            'link' => BASE_URL . 'dangky',
            'linkText' => 'Đăng ký ngay'
        ];
    }

    private function huongDanNhaTuyenDung() {
        return [
            'type' => 'guide',
            'message' => 'Hướng dẫn trở thành nhà tuyển dụng:',
            'steps' => [
                '1. Đăng nhập vào tài khoản của bạn',
                '2. Vào "Tài khoản" > "Trở thành nhà tuyển dụng"',
                '3. Điền thông tin công ty (tên, mã số thuế, địa chỉ...)',
                '4. Upload logo công ty',
                '5. Gửi yêu cầu và chờ Admin phê duyệt (1-2 ngày)',
                '6. Sau khi được duyệt, bạn có thể đăng tin tuyển dụng'
            ],
            'link' => BASE_URL . 'ungvien/yeucautuyendung',
            'linkText' => 'Gửi yêu cầu ngay'
        ];
    }

    private function thongTinMucLuong() {
        $mucLuong = $this->chatbotModel->layDanhSachMucLuong();
        
        return [
            'type' => 'list',
            'message' => 'Các mức lương phổ biến:',
            'items' => array_map(function($item) {
                return $item['tenmucluong'];
            }, $mucLuong),
            'footer' => 'Bạn có thể lọc việc làm theo mức lương khi tìm kiếm.'
        ];
    }

    private function danhSachNganhNghe() {
        $nganhNghe = $this->chatbotModel->layTopNganhNghe(100);
        
        return [
            'type' => 'list',
            'message' => 'Các ngành nghề (' . count($nganhNghe) . ' ngành):',
            'items' => array_map(function($item) {
                $count = $item['soluong'] > 0 ? " ({$item['soluong']} việc)" : '';
                return $item['tennganh'] . $count;
            }, $nganhNghe),
            'footer' => 'Bạn có thể hỏi: "tìm việc [tên ngành nghề]" để xem chi tiết'
        ];
    }

    private function danhSachDiaDiem() {
        $tinhThanh = $this->chatbotModel->layDanhSachTinhThanh();
        
        return [
            'type' => 'list',
            'message' => 'Các địa điểm tuyển dụng (' . count($tinhThanh) . ' tỉnh/thành phố):',
            'items' => array_map(function($item) {
                $count = $item['soluong'] > 0 ? " ({$item['soluong']} việc)" : '';
                return $item['tentinh'] . $count;
            }, $tinhThanh),
            'footer' => 'Bạn có thể hỏi: "tìm việc ở [tên tỉnh/thành]" để xem chi tiết'
        ];
    }

    private function chaoMung() {
        return [
            'type' => 'text',
            'message' => "Xin chào! Tôi là trợ lý ảo của website Tìm Việc Làm. Tôi có thể giúp bạn:

- Tìm việc làm phù hợp
- Hướng dẫn nộp đơn ứng tuyển
- Hướng dẫn đăng ký tài khoản
- Hỗ trợ nhà tuyển dụng đăng tin

Ví dụ câu hỏi:
• \"tìm việc IT Hà Nội\"
• \"việc làm remote lương 10 triệu\"
• \"thực tập marketing\"

Bạn cần tôi giúp gì?",
            'suggestions' => [
                'Tìm việc gần tôi',
                'Ngành nghề',
                'Mức lương',
                'Hướng dẫn nộp đơn'
            ]
        ];
    }

    private function menuGiupDo() {
        return [
            'type' => 'menu',
            'message' => 'Tôi có thể giúp bạn:',
            'options' => [
                ['text' => 'Tìm việc làm', 'value' => 'tìm việc làm'],
                ['text' => 'Hướng dẫn nộp đơn', 'value' => 'nộp đơn ứng tuyển'],
                ['text' => 'Đăng ký tài khoản', 'value' => 'đăng ký tài khoản'],
                ['text' => 'Trở thành nhà tuyển dụng', 'value' => 'nhà tuyển dụng'],
                ['text' => 'Xem mức lương', 'value' => 'mức lương'],
                ['text' => 'Xem ngành nghề', 'value' => 'ngành nghề']
            ],
            'suggestions' => [
                'Tìm việc IT',
                'Việc làm remote',
                'Tìm việc gần tôi'
            ]
        ];
    }

    private function khongHieu() {
        return [
            'type' => 'text',
            'message' => "Xin lỗi, tôi chưa hiểu câu hỏi của bạn.

Bạn có thể hỏi tôi về:
- Tìm việc làm
- Nộp đơn ứng tuyển
- Đăng ký tài khoản
- Trở thành nhà tuyển dụng
- Mức lương, ngành nghề, địa điểm

Hoặc nhấn 'giúp đỡ' để xem menu hỗ trợ.",
            'suggestions' => [
                'Giúp đỡ',
                'Tìm việc IT Hà Nội',
                'Ngành nghề',
                'Tìm việc gần tôi'
            ]
        ];
    }
}
?>

