class Chatbot {
    constructor() {
        this.isOpen = false;
        this.messages = [];
        this.sessionId = this.getSessionId();
        this.unreadCount = 0;
        this.hasOpenedBefore = this.checkIfOpenedBefore();
        this.init();
    }
    
    init() {
        this.createChatbotHTML();
        this.attachEventListeners();
        this.loadHistory();
    }
    
    getSessionId() {
        // Lấy user_id từ PHP session (nếu có)
        const userId = this.getCurrentUserId();
        
        // Tạo key riêng cho từng user
        const storageKey = userId ? `chatbot_session_${userId}` : 'chatbot_session_guest';
        
        // Lấy session ID từ localStorage
        let sessionId = localStorage.getItem(storageKey);
        if (!sessionId) {
            sessionId = 'chat_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
            localStorage.setItem(storageKey, sessionId);
        }
        return sessionId;
    }
    
    getCurrentUserId() {
        // Lấy user_id từ thẻ meta hoặc body data attribute
        const metaUserId = document.querySelector('meta[name="user-id"]');
        if (metaUserId) {
            return metaUserId.content;
        }
        
        const bodyUserId = document.body.dataset.userId;
        if (bodyUserId) {
            return bodyUserId;
        }
        
        return null;
    }
    
    checkIfOpenedBefore() {
        return false; // Không cần kiểm tra nữa
    }
    
    markAsOpened() {
        // Không cần lưu gì
    }
    
    updateBadge(count) {
        const badge = document.getElementById('chatbotBadge');
        if (count > 0) {
            badge.textContent = count > 99 ? '99+' : count;
            badge.style.display = 'flex';
        } else {
            badge.style.display = 'none';
        }
    }
    
    incrementUnreadCount() {
        if (!this.isOpen) {
            this.unreadCount++;
            this.updateBadge(this.unreadCount);
        }
    }
    
    resetUnreadCount() {
        this.unreadCount = 0;
        this.updateBadge(0);
    }
    
    createChatbotHTML() {
        const chatbotHTML = `
            <!-- Nút mở chatbot -->
            <div class="chatbot-toggle" id="chatbotToggle">
                <i class="fas fa-comments"></i>
            </div>
            
            <!-- Container chatbot -->
            <div class="chatbot-container" id="chatbotContainer">
                <div class="chatbot-header">
                    <div class="chatbot-header-info">
                        <div class="chatbot-avatar">
                            <i class="fas fa-robot"></i>
                        </div>
                        <div class="chatbot-title">
                            <h3>Trợ lý tìm việc</h3>
                            <div class="chatbot-status">
                                <span class="status-dot"></span>
                                <span>Đang online</span>
                            </div>
                        </div>
                    </div>
                    <div style="display: flex; gap: 8px;">
                        <button class="chatbot-close" id="chatbotNew" title="Cuộc trò chuyện mới">
                            <i class="fas fa-redo"></i>
                        </button>
                        <button class="chatbot-close" id="chatbotClose" title="Đóng">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                
                <div class="chatbot-body" id="chatbotBody">
                    <!-- Messages sẽ được thêm vào đây -->
                </div>
                
                <div class="chatbot-footer">
                    <div class="chatbot-input-group">
                        <input 
                            type="text" 
                            class="chatbot-input" 
                            id="chatbotInput" 
                            placeholder="Nhập tin nhắn..."
                            autocomplete="off"
                        >
                        <button class="chatbot-send" id="chatbotSend">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        document.body.insertAdjacentHTML('beforeend', chatbotHTML);
    }
    
    attachEventListeners() {
        const toggle = document.getElementById('chatbotToggle');
        const close = document.getElementById('chatbotClose');
        const newChat = document.getElementById('chatbotNew');
        const send = document.getElementById('chatbotSend');
        const input = document.getElementById('chatbotInput');
        
        toggle.addEventListener('click', () => this.toggleChat());
        close.addEventListener('click', () => this.closeChat());
        newChat.addEventListener('click', () => this.newConversation());
        send.addEventListener('click', () => this.sendMessage());
        input.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                this.sendMessage();
            }
        });
    }
    
    async loadHistory() {
        try {
            const response = await fetch(BASE_URL + 'chatbot/history', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ 
                    sessionId: this.sessionId,
                    userId: this.getCurrentUserId() // Gửi thêm userId
                })
            });
            
            if (!response.ok) {
                throw new Error('HTTP error ' + response.status);
            }
            
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                console.warn('Response is not JSON, showing welcome message');
                this.showWelcomeMessage();
                return;
            }
            
            const data = await response.json();
            
            if (data.success && data.messages && data.messages.length > 0) {
                // Có lịch sử chat - cập nhật sessionId từ server
                if (data.sessionId) {
                    this.sessionId = data.sessionId;
                    const userId = this.getCurrentUserId();
                    const storageKey = userId ? `chatbot_session_${userId}` : 'chatbot_session_guest';
                    localStorage.setItem(storageKey, data.sessionId);
                }
                
                // Đếm TỔNG số tin nhắn bot
                let botMessageCount = 0;
                
                // Nhóm tin nhắn thành cặp user-bot
                let currentUserMsg = null;
                const messagePairs = [];
                
                data.messages.forEach(msg => {
                    if (msg.message_type === 'user') {
                        // Nếu có tin user trước đó chưa có bot reply, thêm vào
                        if (currentUserMsg) {
                            messagePairs.push({ user: currentUserMsg, bot: null });
                        }
                        currentUserMsg = msg;
                    } else {
                        // Tin nhắn bot - ghép với user message hiện tại
                        messagePairs.push({ 
                            user: currentUserMsg, 
                            bot: msg 
                        });
                        currentUserMsg = null;
                        botMessageCount++;
                    }
                });
                
                // Nếu còn user message cuối chưa có reply
                if (currentUserMsg) {
                    messagePairs.push({ user: currentUserMsg, bot: null });
                }
                
                // Hiển thị theo cặp: user trước, bot sau
                messagePairs.forEach(pair => {
                    if (pair.user) {
                        this.addMessageToUI(pair.user.message, 'user', false);
                    }
                    if (pair.bot) {
                        try {
                            const responseData = JSON.parse(pair.bot.response);
                            this.addMessageToUI('', 'bot', false, responseData);
                        } catch (e) {
                            console.error('Parse response error:', e);
                        }
                    }
                });
                
                this.scrollToBottom();
            } else {
                // Không có lịch sử, hiển thị welcome message
                this.showWelcomeMessage();
            }
        } catch (error) {
            console.error('Load history error:', error);
            this.showWelcomeMessage();
        }
    }
    
    showWelcomeMessage() {
        setTimeout(() => {
            const welcomeMsg = {
                type: 'text',
                message: 'Xin chào! 👋 Tôi là trợ lý ảo của website Tìm Việc Làm. Tôi có thể giúp bạn:\n\n• Tìm việc làm phù hợp\n• Hướng dẫn nộp đơn ứng tuyển\n• Hướng dẫn đăng ký tài khoản\n• Hỗ trợ nhà tuyển dụng đăng tin\n\nBạn cần tôi giúp gì?'
            };
            this.addMessageToUI('', 'bot', true, welcomeMsg);
        }, 1000);
    }
    
    async newConversation() {
        if (!confirm('Bạn có chắc muốn bắt đầu cuộc trò chuyện mới? Lịch sử chat hiện tại sẽ bị xóa.')) {
            return;
        }
        
        try {
            const response = await fetch(BASE_URL + 'chatbot/newConversation', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Xóa localStorage cũ (theo user)
                const userId = this.getCurrentUserId();
                const storageKey = userId ? `chatbot_session_${userId}` : 'chatbot_session_guest';
                
                // Lưu session ID mới
                this.sessionId = data.sessionId;
                localStorage.setItem(storageKey, this.sessionId);
                
                // Xóa UI
                document.getElementById('chatbotBody').innerHTML = '';
                
                // Hiển thị welcome message
                this.showWelcomeMessage();
            }
        } catch (error) {
            console.error('New conversation error:', error);
        }
    }
    
    // Hàm clear session khi đăng xuất (gọi từ logout)
    static clearSession() {
        // Xóa tất cả chatbot sessions
        const keys = Object.keys(localStorage);
        keys.forEach(key => {
            if (key.startsWith('chatbot_session_')) {
                localStorage.removeItem(key);
            }
        });
    }
    
    toggleChat() {
        this.isOpen = !this.isOpen;
        const container = document.getElementById('chatbotContainer');
        const toggle = document.getElementById('chatbotToggle');
        
        if (this.isOpen) {
            container.classList.add('active');
            toggle.classList.add('active');
            document.getElementById('chatbotInput').focus();
        } else {
            container.classList.remove('active');
            toggle.classList.remove('active');
        }
    }
    
    closeChat() {
        this.isOpen = false;
        document.getElementById('chatbotContainer').classList.remove('active');
        document.getElementById('chatbotToggle').classList.remove('active');
    }
    
    sendMessage() {
        const input = document.getElementById('chatbotInput');
        const message = input.value.trim();
        
        if (!message) return;
        
        // Hiển thị tin nhắn của user
        this.addMessageToUI(message, 'user', true);
        input.value = '';
        
        // Hiển thị typing indicator
        this.showTyping();
        
        // Gửi request đến server
        this.sendToServer(message);
    }
    
    addMessageToUI(content, type = 'bot', shouldScroll = true, data = null) {
        const body = document.getElementById('chatbotBody');
        const time = this.getCurrentTime();
        
        let messageHTML = '';
        
        if (type === 'user') {
            messageHTML = `
                <div class="chat-message user">
                    <div class="message-content">
                        <div class="message-bubble">${this.escapeHtml(content)}</div>
                        <div class="message-time">${time}</div>
                    </div>
                </div>
            `;
        } else {
            messageHTML = `
                <div class="chat-message bot">
                    <div class="message-avatar">
                        <i class="fas fa-robot"></i>
                    </div>
                    <div class="message-content">
                        <div class="message-bubble">
                            ${this.formatBotMessage(content, data)}
                        </div>
                        <div class="message-time">${time}</div>
                    </div>
                </div>
            `;
        }
        
        body.insertAdjacentHTML('beforeend', messageHTML);
        if (shouldScroll) {
            this.scrollToBottom();
        }
    }
    
    formatBotMessage(content, data) {
        if (!data) {
            return this.escapeHtml(content).replace(/\n/g, '<br>');
        }
        
        let html = `<p>${this.escapeHtml(data.message).replace(/\n/g, '<br>')}</p>`;
        
        // Jobs list
        if (data.type === 'jobs' && data.jobs) {
            data.jobs.forEach(job => {
                html += `
                    <div class="job-card" onclick="window.location.href='${BASE_URL}tintuyendung/chitiet/${job.id}'">
                        <h4>${this.escapeHtml(job.tieude)}</h4>
                        <p><strong>Công ty:</strong> ${this.escapeHtml(job.tencongty || 'Chưa cập nhật')}</p>
                        <p><strong>Ngành nghề:</strong> ${this.escapeHtml(job.tennganh || 'N/A')}</p>
                        <p><strong>Địa điểm:</strong> ${this.escapeHtml(job.tentinh || 'N/A')}</p>
                        <p><strong>Mức lương:</strong> ${this.escapeHtml(job.tenmucluong || 'Thỏa thuận')}</p>
                        ${job.tenloai ? `<p><strong>Loại hình:</strong> ${this.escapeHtml(job.tenloai)}</p>` : ''}
                    </div>
                `;
            });
            
            if (data.footer) {
                html += `<p style="margin-top: 10px; font-size: 13px; color: #4A5568; font-style: italic;">${this.escapeHtml(data.footer)}</p>`;
            }
        }
        
        // Guide steps
        if (data.type === 'guide' && data.steps) {
            html += '<div class="guide-steps"><ol>';
            data.steps.forEach(step => {
                html += `<li>${this.escapeHtml(step)}</li>`;
            });
            html += '</ol></div>';
            
            if (data.link) {
                html += `<a href="${data.link}" class="guide-link">${this.escapeHtml(data.linkText)}</a>`;
            }
        }
        
        // List items
        if (data.type === 'list' && data.items) {
            html += '<div class="list-items"><ul>';
            data.items.forEach(item => {
                html += `<li>${this.escapeHtml(item)}</li>`;
            });
            html += '</ul></div>';
            
            if (data.footer) {
                html += `<p style="margin-top: 10px; font-size: 12px; color: #718096;">${this.escapeHtml(data.footer)}</p>`;
            }
        }
        
        // Menu options
        if (data.type === 'menu' && data.options) {
            html += '<div class="quick-options">';
            data.options.forEach(option => {
                html += `<button class="quick-option" onclick="chatbot.sendQuickReply('${this.escapeHtml(option.value)}')">${this.escapeHtml(option.text)}</button>`;
            });
            html += '</div>';
        }
        
        // Suggestion buttons (quick replies)
        if (data.suggestions && data.suggestions.length > 0) {
            html += '<div class="suggestion-buttons">';
            data.suggestions.forEach(suggestion => {
                html += `<button class="suggestion-btn" onclick="chatbot.sendQuickReply('${this.escapeHtml(suggestion)}')">${this.escapeHtml(suggestion)}</button>`;
            });
            html += '</div>';
        }
        
        return html;
    }
    
    sendQuickReply(message) {
        document.getElementById('chatbotInput').value = message;
        this.sendMessage();
    }
    
    showTyping() {
        const body = document.getElementById('chatbotBody');
        const typingHTML = `
            <div class="chat-message bot" id="typingIndicator">
                <div class="message-avatar">
                    <i class="fas fa-robot"></i>
                </div>
                <div class="message-content">
                    <div class="typing-indicator">
                        <div class="typing-dot"></div>
                        <div class="typing-dot"></div>
                        <div class="typing-dot"></div>
                    </div>
                </div>
            </div>
        `;
        body.insertAdjacentHTML('beforeend', typingHTML);
        this.scrollToBottom();
    }
    
    hideTyping() {
        const typing = document.getElementById('typingIndicator');
        if (typing) {
            typing.remove();
        }
    }
    
    async sendToServer(message) {
        try {
            const response = await fetch(BASE_URL + 'chatbot/chat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ 
                    message: message,
                    sessionId: this.sessionId 
                })
            });
            
            const data = await response.json();
            
            // Delay để typing indicator hiển thị tự nhiên hơn
            setTimeout(() => {
                this.hideTyping();
                
                if (data.success && data.response) {
                    // Cập nhật sessionId nếu có
                    if (data.sessionId) {
                        this.sessionId = data.sessionId;
                        localStorage.setItem('chatbot_session_id', this.sessionId);
                    }
                    
                    this.addMessageToUI('', 'bot', true, data.response);
                } else {
                    this.addMessageToUI('Xin lỗi, đã có lỗi xảy ra. Vui lòng thử lại sau.', 'bot', true);
                }
            }, 800);
            
        } catch (error) {
            console.error('Chatbot error:', error);
            setTimeout(() => {
                this.hideTyping();
                this.addMessageToUI('Không thể kết nối đến server. Vui lòng kiểm tra kết nối mạng.', 'bot', true);
            }, 800);
        }
    }
    
    scrollToBottom() {
        const body = document.getElementById('chatbotBody');
        setTimeout(() => {
            body.scrollTop = body.scrollHeight;
        }, 100);
    }
    
    getCurrentTime() {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        return `${hours}:${minutes}`;
    }
    
    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
}

// Khởi tạo chatbot khi DOM loaded
let chatbot;
document.addEventListener('DOMContentLoaded', function() {
    chatbot = new Chatbot();
});
