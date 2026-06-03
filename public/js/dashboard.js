// Loader
window.addEventListener('load', () => {
    const primaryLoader = document.getElementById('loader-overlay');
    const hasSeenPrimaryLoader = sessionStorage.getItem('epms-primary-loader-seen') === '1';
    const hidePrimaryLoader = () => {
        if (!primaryLoader) return;
        primaryLoader.style.transition = 'opacity 5s ease-out, visibility 5s ease-out';
        primaryLoader.style.opacity = '0.8';
        primaryLoader.style.visibility = 'hidden';
        setTimeout(() => {
            primaryLoader.style.display = 'none';
        }, 1000);
    };

    if (hasSeenPrimaryLoader) {
        hidePrimaryLoader();
        initializeSessionTimer();
        initializeSecondaryLoaderBindings();
        return;
    }

    setTimeout(() => {
        sessionStorage.setItem('epms-primary-loader-seen', '1');
        hidePrimaryLoader();
        initializeSessionTimer();
        initializeSecondaryLoaderBindings();
    }, 1000);
});

function showSecondaryLoader(message = 'Processing...') {
    const overlay = document.getElementById('secondary-loader-overlay');
    const text = document.getElementById('secondaryLoaderText');
    if (!overlay || !text) return;
    text.textContent = message;
    overlay.classList.add('show');
    overlay.setAttribute('aria-hidden', 'false');
}

function hideSecondaryLoader() {
    const overlay = document.getElementById('secondary-loader-overlay');
    if (!overlay) return;
    overlay.classList.remove('show');
    overlay.setAttribute('aria-hidden', 'true');
}

function initializeSecondaryLoaderBindings() {
    document.querySelectorAll('form').forEach((form) => {
        if (form.dataset.loaderBound === '1') return;
        form.dataset.loaderBound = '1';

        form.addEventListener('submit', () => {
            const method = (form.getAttribute('method') || 'GET').toUpperCase();
            if (method === 'GET') return;

            const customMessage = form.dataset.loaderMessage || 'Please wait...';
            showSecondaryLoader(customMessage);
        });
    });

    document.querySelectorAll('a[data-secondary-loader]').forEach((link) => {
        if (link.dataset.loaderBound === '1') return;
        link.dataset.loaderBound = '1';

        link.addEventListener('click', () => {
            const customMessage = link.dataset.loaderMessage || 'Loading...';
            showSecondaryLoader(customMessage);
        });
    });
}

function initializeSessionTimer() {
    const timer = document.getElementById('sessionTimer');
    if (!timer) return;

    const expiryTs = parseInt(timer.dataset.expiryTs, 10);
    const logoutUrl = timer.dataset.logoutUrl;
    const countdown = document.getElementById('sessionCountdown');
    if (!expiryTs || !countdown || !logoutUrl) return;

    const tick = () => {
        const nowSeconds = Math.floor(Date.now() / 1000);
        const remaining = expiryTs - nowSeconds;

        if (remaining <= 0) {
            countdown.textContent = '00:00';
            window.location.href = logoutUrl;
            return;
        }

        const minutes = String(Math.floor(remaining / 60)).padStart(2, '0');
        const seconds = String(remaining % 60).padStart(2, '0');
        countdown.textContent = `${minutes}:${seconds}`;
    };

    tick();
    setInterval(tick, 1000);
}

// Sidebar Toggle
function toggleSidebar() {
    const s = document.getElementById('sidebar');
    const o = document.getElementById('sidebarOverlay');
    s.classList.toggle('open');
    o.classList.toggle('show');
}

// Lock screen action
function activateLockScreen() {
    showSecondaryLoader('Locking screen...');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ||
        document.querySelector('input[name="_token"]')?.value;

    fetch('/lock-screen/lock', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({}),
    })
    .then(() => {
        window.location.href = '/lock-screen';
    })
    .catch((error) => {
        console.error('Lock screen error:', error);
        window.location.href = '/lock-screen';
    });
}

// Submenu
function toggleSubmenu(id, el) {
    const sub = document.getElementById(id);
    const chev = document.getElementById(id + '-chev');
    const isOpen = sub.classList.contains('open');
    
    // Check if submenu or its children contain active items
    const hasActiveChild = sub.querySelector('.submenu-item.active') !== null;
    
    // Close other submenus only if this one doesn't have active items
    if (!isOpen || (isOpen && !hasActiveChild)) {
        document.querySelectorAll('.submenu.open').forEach(s => {
            // Don't close if it has active children
            if (s.querySelector('.submenu-item.active') === null) {
                s.classList.remove('open');
            }
        });
        document.querySelectorAll('.nav-chevron.open').forEach(c => {
            // Don't close if parent submenu has active children
            const submenuId = c.id.replace('-chev', '');
            const submenu = document.getElementById(submenuId);
            if (!submenu || submenu.querySelector('.submenu-item.active') === null) {
                c.classList.remove('open');
            }
        });
    }
    
    // Toggle current submenu
    if (!isOpen) {
        sub.classList.add('open');
        if (chev) chev.classList.add('open');
    } else if (!hasActiveChild) {
        // Only close if it doesn't have active items
        sub.classList.remove('open');
        if (chev) chev.classList.remove('open');
    }
}

// Initialize active submenus on page load
document.addEventListener('DOMContentLoaded', () => {
    // Open submenus that have active items
    document.querySelectorAll('.submenu-item.active').forEach(activeItem => {
        const submenu = activeItem.closest('.submenu');
        if (submenu) {
            submenu.classList.add('open');
            const chev = document.getElementById(submenu.id + '-chev');
            if (chev) chev.classList.add('open');
        }
    });
    
    // Open parent nav link if submenu is active
    document.querySelectorAll('.submenu.open').forEach(submenu => {
        const parentNav = submenu.previousElementSibling;
        if (parentNav && parentNav.classList.contains('nav-link-custom')) {
            parentNav.classList.add('active');
        }
    });
});

// Page navigation
function showPage(page, el) {
    document.querySelectorAll('#main > div').forEach(p => p.style.display = 'none');
    const target = document.getElementById('page-' + page);
    if (target) target.style.display = 'block';
    // Update active nav
    if (el) {
        document.querySelectorAll('.nav-link-custom').forEach(n => n.classList.remove('active'));
        document.querySelectorAll('.submenu-item').forEach(item => item.classList.remove('active'));

        if (el.classList.contains('submenu-item')) {
            el.classList.add('active');
            const parentNav = el.closest('.nav-item-wrap')?.querySelector('.nav-link-custom');
            if (parentNav) {
                parentNav.classList.add('active');
            }
            const parentSub = el.closest('.submenu');
            if (parentSub) {
                parentSub.classList.add('open');
                const chev = document.getElementById(parentSub.id + '-chev');
                if (chev) chev.classList.add('open');
            }
        } else {
            el.classList.add('active');
        }
    }
    const titles = {
        dashboard: ['Dashboard', 'Overview'],
        engineers: ['Engineer Posts', 'Engineers / All Posts'],
        form: ['Create Post', 'Posts / New Post'],
        settings: ['Settings', 'Settings / General']
    };
    const t = titles[page] || ['Dashboard', 'Overview'];
    document.getElementById('pageTitle').textContent = t[0];
    document.getElementById('pageSub').textContent = t[1];
}

// Notifications
function toggleNotif() {
    const d = document.getElementById('notifDropdown');
    const p = document.getElementById('profileDropdown');
    p.classList.remove('show');
    d.classList.toggle('show');
    document.addEventListener('click', closeDropdowns, true);
}

function toggleProfile() {
    const p = document.getElementById('profileDropdown');
    const d = document.getElementById('notifDropdown');
    d.classList.remove('show');
    p.classList.toggle('show');
    document.addEventListener('click', closeDropdowns, true);
}

function closeDropdowns(e) {
    if (!e.target.closest('#notifBtn') && !e.target.closest('#notifDropdown')) document.getElementById('notifDropdown').classList.remove('show');
    if (!e.target.closest('#profileBtn') && !e.target.closest('#profileDropdown')) document.getElementById('profileDropdown').classList.remove('show');
}

// Tab system
function switchTab(btn, tabId) {
    const card = btn.closest('.card');
    card.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    card.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById(tabId).classList.add('active');
}

// Toaster
function showToast(title , msg, type) {
    const wrap = document.getElementById('toasterWrap');
    const colors = {
        success: 'green',
        sky: 'sky',
        info: 'sky',
        error: 'red',
        warning: 'orange'
    };
    const icons = {
        success: 'fa-circle-check',
        sky: 'fa-circle-info',
        info: 'fa-circle-info',
        error: 'fa-circle-xmark',
        warning: 'fa-triangle-exclamation'
    };
    const c = colors[type] || 'pink';
    const ic = icons[type] || 'fa-circle-check';
    const t = document.createElement('div');
    t.className = `toast-item ${c}`;
    t.innerHTML = `<div class="toast-icon ${c}"><i class="fa-solid ${ic}"></i></div><div class="toast-body"><div class="toast-title">${title}</div><div class="toast-msg">${msg}</div></div><button class="toast-close" onclick="this.parentElement.remove()"><i class="fa-solid fa-xmark"></i></button>`;
    console.log(t.innerHTML);
    wrap.appendChild(t);
    setTimeout(() => {
        t.style.opacity = '0';
        t.style.transform = 'translateX(30px)';
        t.style.transition = 'all 0.3s';
        setTimeout(() => t.remove(), 300);
    }, 4000);
}

// Mini bar chart
(function buildChart() {
    const data = [40, 65, 52, 78, 60, 88, 72];
    const days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
    const c = document.getElementById('miniChart');
    if (!c) return;
    const max = Math.max(...data);
    data.forEach((v, i) => {
        const b = document.createElement('div');
        b.className = 'mini-bar';
        b.style.height = Math.round((v / max) * 100) + '%';
        if (i === 5) b.style.background = 'var(--pink-primary)';
        b.innerHTML = `<span>${days[i]}</span>`;
        c.appendChild(b);
    });
})();

// ===========================
// PASSWORD RESET MODAL
// ===========================

let passwordResetState = {
    isOpen: false,
    isRequired: false,
    isSubmitting: false,
};

// Initialize password reset check on page load
window.addEventListener('load', () => {
    checkPasswordExpiry();
});

// Check if password needs to be reset
function checkPasswordExpiry() {
    fetch('/password/check-expiry')
        .then(res => res.json())
        .then(data => {
            if (data.expired) {
                passwordResetState.isRequired = true;
                openPasswordResetModal(null, true);
            }
        })
        .catch(err => console.error('Password check error:', err));
}

// Open password reset modal
function openPasswordResetModal(event, isRequired = false) {
    if (event) event.preventDefault();
    
    const modal = document.getElementById('passwordResetModal');
    const closeBtn = document.getElementById('passwordResetClose');
    const cancelBtn = document.getElementById('passwordResetCancel');
    
    if (isRequired) {
        closeBtn.style.display = 'none';
        cancelBtn.style.display = 'none';
        passwordResetState.isRequired = true;
    } else {
        closeBtn.style.display = 'block';
        cancelBtn.style.display = 'block';
        passwordResetState.isRequired = false;
    }
    
    // Close profile dropdown if open
    const profileDropdown = document.getElementById('profileDropdown');
    if (profileDropdown) profileDropdown.classList.remove('show');
    
    modal.style.display = 'flex';
    passwordResetState.isOpen = true;
    
    // Generate initial captcha
    generateNewCaptcha();
    
    // Focus on first input
    setTimeout(() => {
        document.getElementById('oldPassword').focus();
    }, 100);
}

// Close password reset modal
function closePasswordResetModal() {
    if (passwordResetState.isRequired) return; // Can't close if required
    
    const modal = document.getElementById('passwordResetModal');
    modal.style.display = 'none';
    passwordResetState.isOpen = false;
    
    // Reset form
    document.getElementById('passwordResetForm').reset();
    clearAllErrors();
}

// Generate new captcha
function generateNewCaptcha() {
    const questionDiv = document.getElementById('captchaQuestion');
    questionDiv.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Loading security question...';
    
    fetch('/password/generate-captcha', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || 
                           document.querySelector('input[name="_token"]')?.value,
        },
    })
    .then(res => res.json())
    .then(data => {
        questionDiv.textContent = data.question;
        document.getElementById('captchaAnswer').value = '';
        document.getElementById('captchaAnswer').focus();
    })
    .catch(err => {
        questionDiv.innerHTML = '<span style="color: red;">Error loading question. Please refresh.</span>';
        console.error('Captcha error:', err);
    });
}

// Refresh captcha
function refreshCaptcha() {
    generateNewCaptcha();
}

// Clear all form errors
function clearAllErrors() {
    document.querySelectorAll('.password-form-error').forEach(el => el.textContent = '');
    document.getElementById('passwordResetMessage').style.display = 'none';
}

// Update password strength indicator
document.addEventListener('input', (e) => {
    if (e.target.id === 'newPassword') {
        const password = e.target.value;
        const strengthFill = document.getElementById('passwordStrengthFill');
        const strengthText = document.getElementById('passwordStrengthText');
        
        let strength = 0;
        const strengthMap = {
            0: { width: '0%', color: '#dc2626', text: 'Weak' },
            1: { width: '33%', color: '#f59e0b', text: 'Fair' },
            2: { width: '66%', color: '#3b82f6', text: 'Good' },
            3: { width: '100%', color: '#10b981', text: 'Strong' },
        };
        
        if (password.length >= 8) strength++;
        if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
        if (/\d/.test(password)) strength++;
        if (/[^a-zA-Z\d]/.test(password)) strength++;
        
        if (strength > 3) strength = 3;
        
        const map = strengthMap[strength];
        strengthFill.style.width = map.width;
        strengthFill.style.backgroundColor = map.color;
        strengthText.textContent = map.text;
        strengthText.style.color = map.color;
    }
});

// Submit password reset form
document.addEventListener('DOMContentLoaded', () => {
    const submitBtn = document.getElementById('passwordResetSubmit');
    const cancelBtn = document.getElementById('passwordResetCancel');
    const closeBtn = document.getElementById('passwordResetClose');
    
    if (submitBtn) {
        submitBtn.addEventListener('click', submitPasswordReset);
    }
    if (cancelBtn) {
        cancelBtn.addEventListener('click', closePasswordResetModal);
    }
    if (closeBtn) {
        closeBtn.addEventListener('click', closePasswordResetModal);
    }
    
    // Allow Enter key to submit form when in captcha answer field
    const captchaAnswer = document.getElementById('captchaAnswer');
    if (captchaAnswer) {
        captchaAnswer.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') submitPasswordReset();
        });
    }
});

// Submit form
function submitPasswordReset() {
    if (passwordResetState.isSubmitting) return;
    
    clearAllErrors();
    
    const oldPassword = document.getElementById('oldPassword').value.trim();
    const newPassword = document.getElementById('newPassword').value.trim();
    const confirmPassword = document.getElementById('confirmPassword').value.trim();
    const captchaAnswer = document.getElementById('captchaAnswer').value.trim();
    
    let hasError = false;
    
    // Validation
    if (!oldPassword) {
        document.getElementById('oldPasswordError').textContent = 'Current password is required.';
        hasError = true;
    }
    
    if (!newPassword) {
        document.getElementById('newPasswordError').textContent = 'New password is required.';
        hasError = true;
    } else if (newPassword.length < 8) {
        document.getElementById('newPasswordError').textContent = 'Password must be at least 8 characters.';
        hasError = true;
    }
    
    if (!confirmPassword) {
        document.getElementById('confirmPasswordError').textContent = 'Please confirm your password.';
        hasError = true;
    } else if (newPassword !== confirmPassword) {
        document.getElementById('confirmPasswordError').textContent = 'Passwords do not match.';
        hasError = true;
    }
    
    if (!captchaAnswer) {
        document.getElementById('captchaError').textContent = 'Please answer the security question.';
        hasError = true;
    }
    
    if (hasError) return;
    
    // Submit form
    passwordResetState.isSubmitting = true;
    const submitBtn = document.getElementById('passwordResetSubmit');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Updating...';
    
    const formData = new FormData();
    formData.append('old_password', oldPassword);
    formData.append('new_password', newPassword);
    formData.append('new_password_confirmation', confirmPassword);
    formData.append('captcha', '1');
    formData.append('captcha_answer', captchaAnswer);
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || 
                     document.querySelector('input[name="_token"]')?.value;
    if (csrfToken) {
        formData.append('_token', csrfToken);
    }
    
    showSecondaryLoader('Updating password...');

    fetch('/password/update', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
        },
        body: formData,
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showSuccessMessage(data.message || 'Password updated successfully!');
            setTimeout(() => {
                document.getElementById('passwordResetForm').reset();
                closePasswordResetModal();
                showToast('success', 'Success', 'Your password has been updated.');
            }, 2000);
        } else {
            showErrorMessage(data.message || 'An error occurred.');
        }
    })
    .catch(err => {
        console.error('Submit error:', err);
        showErrorMessage('An error occurred. Please try again.');
    })
    .finally(() => {
        passwordResetState.isSubmitting = false;
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fa-solid fa-check-circle"></i> Update Password';
        hideSecondaryLoader();
    });
}

// Show success message
function showSuccessMessage(message) {
    const msgDiv = document.getElementById('passwordResetMessage');
    msgDiv.textContent = message;
    msgDiv.className = 'password-reset-message success';
    msgDiv.style.display = 'block';
}

// Show error message
function showErrorMessage(message) {
    const msgDiv = document.getElementById('passwordResetMessage');
    msgDiv.textContent = message;
    msgDiv.className = 'password-reset-message error';
    msgDiv.style.display = 'block';
}

// Close modal when clicking outside (only if not required)
document.addEventListener('click', (e) => {
    const modal = document.getElementById('passwordResetModal');
    if (!passwordResetState.isOpen || !modal) return;
    
    if (e.target === modal.querySelector('.password-reset-overlay')) {
        if (!passwordResetState.isRequired) {
            closePasswordResetModal();
        }
    }
});

// Prevent modal close if required password reset
window.addEventListener('beforeunload', (e) => {
    if (passwordResetState.isRequired && passwordResetState.isOpen) {
        e.preventDefault();
        e.returnValue = '';
    }
});
