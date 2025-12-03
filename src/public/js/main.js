document.addEventListener('DOMContentLoaded', function() {
    
    const hamburger = document.querySelector('.hamburger');
    const navMenu = document.querySelector('.nav-menu');
    const body = document.body;
    
    if (hamburger && navMenu) {
        hamburger.addEventListener('click', function(e) {
            e.stopPropagation();
            navMenu.classList.toggle('active');
            hamburger.classList.toggle('active');
            body.classList.toggle('menu-open');
        });
        
        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.closest('.navbar') && navMenu.classList.contains('active')) {
                navMenu.classList.remove('active');
                hamburger.classList.remove('active');
                body.classList.remove('menu-open');
            }
        });
        
        // Close menu when clicking nav link
        const navLinks = navMenu.querySelectorAll('a');
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth <= 768) {
                    navMenu.classList.remove('active');
                    hamburger.classList.remove('active');
                    body.classList.remove('menu-open');
                }
            });
        });
    }
    
    // Dropdown menu toggle
    const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
    dropdownToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const dropdown = this.parentElement;
            dropdown.classList.toggle('show');
        });
    });
    
        // 2. MOBILE FILTER SIDEBAR TOGGLE
        const filterToggle = document.querySelector('.filter-toggle');
    const filterSidebar = document.querySelector('.filter-sidebar');
    
    if (filterToggle && filterSidebar) {
        filterToggle.addEventListener('click', function() {
            filterSidebar.classList.toggle('active');
            this.classList.toggle('active');
        });
        
        // Close filter when clicking outside on mobile
        document.addEventListener('click', function(event) {
            if (window.innerWidth <= 768 && 
                !event.target.closest('.filter-sidebar') && 
                !event.target.closest('.filter-toggle') &&
                filterSidebar.classList.contains('active')) {
                filterSidebar.classList.remove('active');
                filterToggle.classList.remove('active');
            }
        });
    }
    
        // 3. ENHANCED ALERTS WITH AUTO-HIDE
        const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        // Add close button if not exists
        if (!alert.querySelector('.alert-close')) {
            const closeBtn = document.createElement('button');
            closeBtn.className = 'alert-close';
            closeBtn.innerHTML = '&times;';
            closeBtn.onclick = function() {
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-10px)';
                setTimeout(() => alert.remove(), 300);
            };
            alert.appendChild(closeBtn);
        }
        
        // Auto-hide after 5 seconds
        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-10px)';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });
    
        // 4. FORM VALIDATION WITH REAL-TIME FEEDBACK
        const forms = document.querySelectorAll('form[data-validate]');
    forms.forEach(form => {
        // Submit validation
        form.addEventListener('submit', function(e) {
            if (!validateForm(this)) {
                e.preventDefault();
                showToast('Vui lòng kiểm tra lại thông tin!', 'error');
            } else {
                // Show loading state
                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
                }
            }
        });
        
        // Real-time validation for inputs
        const inputs = form.querySelectorAll('input, textarea, select');
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                validateInput(this);
            });
            
            input.addEventListener('input', function() {
                if (this.classList.contains('is-invalid')) {
                    validateInput(this);
                }
            });
        });
    });
    
        // 5. FILE INPUT WITH PREVIEW & VALIDATION
        const fileInputs = document.querySelectorAll('input[type="file"]');
    fileInputs.forEach(input => {
        input.addEventListener('change', function() {
            const file = this.files[0];
            if (!file) return;
            
            // Show file name
            const label = this.nextElementSibling;
            if (label && label.classList.contains('file-label')) {
                label.textContent = file.name;
            }
            
            // Image preview for avatar/logo
            if (file.type.startsWith('image/')) {
                const preview = this.parentNode.querySelector('.image-preview');
                if (preview) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                }
            }
            
            // File size validation (5MB)
            if (file.size > 5 * 1024 * 1024) {
                showToast('File quá lớn! Vui lòng chọn file nhỏ hơn 5MB', 'error');
                this.value = '';
            }
        });
    });
    
    // Confirm delete actions
    const deleteButtons = document.querySelectorAll('[data-confirm-delete]');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('Bạn có chắc chắn muốn xóa không?')) {
                e.preventDefault();
            }
        });
    });
    
    // Character counter for textarea
    const textareas = document.querySelectorAll('textarea[data-max-length]');
    textareas.forEach(textarea => {
        const maxLength = textarea.getAttribute('data-max-length');
        const counter = document.createElement('div');
        counter.className = 'char-counter';
        counter.style.textAlign = 'right';
        counter.style.fontSize = '0.875rem';
        counter.style.color = '#6b7280';
        counter.style.marginTop = '0.25rem';
        textarea.parentNode.appendChild(counter);
        
        const updateCounter = () => {
            const remaining = maxLength - textarea.value.length;
            counter.textContent = `Còn ${remaining} ký tự`;
            counter.style.color = remaining < 50 ? '#dc2626' : '#6b7280';
        };
        
        textarea.addEventListener('input', updateCounter);
        updateCounter();
    });
    
    // Counter animation helper function
    function animateCounter(element, target) {
        let current = 0;
        const increment = target / 50;
        const duration = 1500;
        const stepTime = duration / 50;
        
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                element.textContent = target.toLocaleString('vi-VN');
                clearInterval(timer);
            } else {
                element.textContent = Math.floor(current).toLocaleString('vi-VN');
            }
        }, stepTime);
    }
    
        // 6. COUNTER ANIMATION FOR STATS
        const statNumbers = document.querySelectorAll('.stat-item h3, .stat-card .stat-number');
    const observerOptions = {
        threshold: 0.5,
        rootMargin: '0px'
    };
    
    const statsObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && !entry.target.classList.contains('counted')) {
                entry.target.classList.add('counted');
                
                // Lấy số từ data-target hoặc textContent
                let target = 0;
                if (entry.target.hasAttribute('data-target')) {
                    target = parseInt(entry.target.getAttribute('data-target'));
                } else {
                    target = parseInt(entry.target.textContent.replace(/\D/g, '')) || 0;
                }
                
                animateCounter(entry.target, target);
            }
        });
    }, observerOptions);
    
    statNumbers.forEach(stat => statsObserver.observe(stat));
    
        // 7. STICKY CTA BUTTON
        const applyButton = document.querySelector('.apply-box');
    if (applyButton) {
        window.addEventListener('scroll', () => {
            const scrollY = window.scrollY;
            if (scrollY > 400) {
                applyButton.classList.add('sticky');
            } else {
                applyButton.classList.remove('sticky');
            }
        });
    }
    
        // 8. SMOOTH SCROLL TO TOP
        const scrollTopBtn = document.createElement('button');
    scrollTopBtn.className = 'scroll-top-btn';
    scrollTopBtn.innerHTML = '<i class="fas fa-arrow-up"></i>';
    document.body.appendChild(scrollTopBtn);
    
    window.addEventListener('scroll', () => {
        if (window.scrollY > 300) {
            scrollTopBtn.classList.add('show');
        } else {
            scrollTopBtn.classList.remove('show');
        }
    });
    
    scrollTopBtn.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
    
        // 9. HEADER SCROLL EFFECT
        const header = document.querySelector('.header');
    let lastScroll = 0;
    
    window.addEventListener('scroll', () => {
        const currentScroll = window.scrollY;
        
        if (currentScroll > 100) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
        
        lastScroll = currentScroll;
    });
    
        // 10. LAZY LOADING IMAGES
        const lazyImages = document.querySelectorAll('img[data-src]');
    const imageObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.add('loaded');
                imageObserver.unobserve(img);
            }
        });
    });
    
    lazyImages.forEach(img => imageObserver.observe(img));
});

// HELPER FUNCTIONS

// Form Validation Function
function validateForm(form) {
    let isValid = true;
    const requiredFields = form.querySelectorAll('[required]');
    
    requiredFields.forEach(field => {
        if (!validateInput(field)) {
            isValid = false;
        }
    });
    
    return isValid;
}

// Validate Individual Input
function validateInput(field) {
    // Remove previous error
    const existingError = field.parentNode.querySelector('.error-message');
    if (existingError) {
        existingError.remove();
    }
    field.classList.remove('is-invalid', 'error');
    field.classList.remove('is-valid');
    
    // Check if field is empty (for required fields)
    if (field.hasAttribute('required') && !field.value.trim()) {
        showInputError(field, 'Trường này là bắt buộc');
        return false;
    }
    
    // Email validation
    if (field.type === 'email' && field.value.trim()) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(field.value)) {
            showInputError(field, 'Email không hợp lệ');
            return false;
        }
    }
    
    // Phone validation
    if (field.name === 'sodienthoai' && field.value.trim()) {
        const phoneRegex = /^[0-9]{10,11}$/;
        if (!phoneRegex.test(field.value)) {
            showInputError(field, 'Số điện thoại phải gồm 10-11 chữ số');
            return false;
        }
    }
    
    // Password match
    if (field.type === 'password' && field.name === 'xacnhanmatkhau') {
        const password = field.form.querySelector('input[name="matkhau"]');
        if (password && field.value !== password.value) {
            showInputError(field, 'Mật khẩu không khớp');
            return false;
        }
    }
    
    // Password strength
    if (field.type === 'password' && field.name === 'matkhau' && field.value.trim()) {
        if (field.value.length < 6) {
            showInputError(field, 'Mật khẩu phải có ít nhất 6 ký tự');
            return false;
        }
    }
    
    // If all validations pass
    if (field.value.trim()) {
        field.classList.add('is-valid');
    }
    
    return true;
}

// Show Input Error
function showInputError(field, message) {
    field.classList.add('is-invalid', 'error');
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message invalid-feedback';
    errorDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
    field.parentNode.appendChild(errorDiv);
}

// Toast Notification
function showToast(message, type = 'info') {
    // Remove existing toast
    const existingToast = document.querySelector('.toast-notification');
    if (existingToast) {
        existingToast.remove();
    }
    
    const toast = document.createElement('div');
    toast.className = `toast-notification toast-${type}`;
    
    let icon = 'fa-info-circle';
    if (type === 'success') icon = 'fa-check-circle';
    if (type === 'error') icon = 'fa-exclamation-circle';
    if (type === 'warning') icon = 'fa-exclamation-triangle';
    
    toast.innerHTML = `
        <i class="fas ${icon}"></i>
        <span>${message}</span>
        <button class="toast-close">&times;</button>
    `;
    
    document.body.appendChild(toast);
    
    // Show toast
    setTimeout(() => toast.classList.add('show'), 100);
    
    // Close button
    toast.querySelector('.toast-close').addEventListener('click', () => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 300);
    });
    
    // Auto-hide after 4 seconds
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 300);
    }, 4000);
}

// Show/Hide Loading
function showLoading() {
    const loader = document.createElement('div');
    loader.className = 'page-loader';
    loader.innerHTML = `
        <div class="loader-spinner">
            <i class="fas fa-spinner fa-spin"></i>
            <p>Đang tải...</p>
        </div>
    `;
    document.body.appendChild(loader);
}

function hideLoading() {
    const loader = document.querySelector('.page-loader');
    if (loader) {
        loader.remove();
    }
}

// Debounce Function
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Format Number
function formatNumber(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

// Format Currency VND
function formatCurrency(amount) {
    return formatNumber(amount) + ' đ';
}

// Ajax Helper Function
function ajax(url, method, data, callback) {
    const xhr = new XMLHttpRequest();
    xhr.open(method, url, true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    
    xhr.onload = function() {
        if (xhr.status === 200) {
            callback(null, JSON.parse(xhr.responseText));
        } else {
            callback(new Error('Request failed'), null);
        }
    };
    
    xhr.onerror = function() {
        callback(new Error('Network error'), null);
    };
    
    xhr.send(JSON.stringify(data));
}

// Show notification
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type}`;
    notification.textContent = message;
    notification.style.position = 'fixed';
    notification.style.top = '20px';
    notification.style.right = '20px';
    notification.style.zIndex = '9999';
    notification.style.minWidth = '300px';
    notification.style.animation = 'slideIn 0.3s ease-out';
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.opacity = '0';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Format currency
function formatCurrency(amount) {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(amount);
}

// Format date
function formatDate(date) {
    const d = new Date(date);
    const day = String(d.getDate()).padStart(2, '0');
    const month = String(d.getMonth() + 1).padStart(2, '0');
    const year = d.getFullYear();
    return `${day}/${month}/${year}`;
}

// Debounce function for search
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// CSS Animation
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    .error {
        border-color: #dc2626 !important;
    }
`;
document.head.appendChild(style);

document.head.appendChild(style);
