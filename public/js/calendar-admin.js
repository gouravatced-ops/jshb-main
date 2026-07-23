(function() {
    // window.adminNotices and window.adminNotifications should be set by the blade template
    const notices = window.adminNotices || [];
    const notifications = window.adminNotifications || [];
    const forwardedApps = window.adminForwardedApps || [];

    // Group items by date
    const noticeMap = new Map();

    notices.forEach(notice => {
        if (!notice.start_date) return;
        // Extract YYYY-MM-DD (works for 'YYYY-MM-DD HH:MM:SS' and 'YYYY-MM-DDTHH:MM:SSZ')
        const dateKey = notice.start_date.substring(0, 10);

        if (!noticeMap.has(dateKey)) {
            noticeMap.set(dateKey, { notices: [], notifications: [], forwardedApps: [] });
        }
        noticeMap.get(dateKey).notices.push(notice);
    });

    notifications.forEach(notification => {
        if (!notification.created_at) return;
        const dateKey = notification.created_at.substring(0, 10);

        if (!noticeMap.has(dateKey)) {
            noticeMap.set(dateKey, { notices: [], notifications: [], forwardedApps: [] });
        }
        noticeMap.get(dateKey).notifications.push(notification);
    });

    forwardedApps.forEach(app => {
        if (!app.created_date) return;
        const dateKey = app.created_date.substring(0, 10);

        if (!noticeMap.has(dateKey)) {
            noticeMap.set(dateKey, { notices: [], notifications: [], forwardedApps: [] });
        }
        noticeMap.get(dateKey).forwardedApps.push(app);
    });

    const monthYearDisplay = document.getElementById('monthYearDisplay');
    const daysGrid = document.getElementById('daysGrid');
    const prevBtn = document.getElementById('prevMonthBtn');
    const nextBtn = document.getElementById('nextMonthBtn');

    // Array of 7 light pastel colors
    const lightColors = ['#e8f4fd', '#e8fde8', '#fde8f4', '#fdfde8', '#e8fdfd', '#fdeee8', '#f4e8fd'];

    let currentYear = new Date().getFullYear();
    let currentMonth = new Date().getMonth();

    function formatDateKey(year, month, day) {
        return `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
    }

    function renderCalendar(year, month) {
        if(!daysGrid) return;
        daysGrid.innerHTML = '';

        const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

        let monthOptions = monthNames.map((m, i) => `<option value="${i}" ${i === month ? 'selected' : ''}>${m}</option>`).join('');

        // Generate years (5 years back and 5 years forward)
        let baseYear = new Date().getFullYear();
        let yearOptions = '';
        for(let y = baseYear - 5; y <= baseYear + 5; y++) {
            yearOptions += `<option value="${y}" ${y === year ? 'selected' : ''}>${y}</option>`;
        }

        monthYearDisplay.innerHTML = `
            <select id="monthSelect" class="form-select form-select-sm d-inline-block w-auto border-0 shadow-none bg-transparent p-0 pe-3" style="font-weight: bold; font-size: 1.15rem; color: #323a46; cursor: pointer; outline: none !important;">${monthOptions}</select>
            <select id="yearSelect" class="form-select form-select-sm d-inline-block w-auto border-0 shadow-none bg-transparent p-0 pe-3" style="font-weight: bold; font-size: 1.15rem; color: #323a46; cursor: pointer; outline: none !important; margin-left: 4px;">${yearOptions}</select>
        `;

        document.getElementById('monthSelect').addEventListener('change', function() {
            currentMonth = parseInt(this.value);
            renderCalendar(currentYear, currentMonth);
        });

        document.getElementById('yearSelect').addEventListener('change', function() {
            currentYear = parseInt(this.value);
            renderCalendar(currentYear, currentMonth);
        });

        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const today = new Date();
        const todayDateKey = formatDateKey(today.getFullYear(), today.getMonth(), today.getDate());

        const daysInPrevMonth = new Date(year, month, 0).getDate();
        const totalCells = Math.ceil((firstDay + daysInMonth) / 7) * 7;

        for (let i = 0; i < totalCells; i++) {
            let dayNumber, dateKey, isCurrentMonth = false;
            let cell = document.createElement('div');
            cell.className = 'day-cell';

            if (i < firstDay) {
                dayNumber = daysInPrevMonth - firstDay + i + 1;
                cell.classList.add('empty');
            } else if (i >= firstDay + daysInMonth) {
                dayNumber = i - firstDay - daysInMonth + 1;
                cell.classList.add('empty');
            } else {
                dayNumber = i - firstDay + 1;
                dateKey = formatDateKey(year, month, dayNumber);
                isCurrentMonth = true;
            }

            cell.textContent = dayNumber;

            if (isCurrentMonth) {
                if (dateKey === todayDateKey) cell.classList.add('today');

                // Check for notices/notifications/forwardedApps on this day
                const dayData = noticeMap.get(dateKey);
                if (dayData && (dayData.notices.length > 0 || dayData.notifications.length > 0 || dayData.forwardedApps.length > 0)) {

                    // Assign random background color for cells with events
                    const randomColor = lightColors[Math.floor(Math.random() * lightColors.length)];
                    cell.style.backgroundColor = randomColor;

                    const indicatorContainer = document.createElement('div');
                    indicatorContainer.className = 'notice-indicators';

                    let totalItems = dayData.notices.length + dayData.notifications.length + dayData.forwardedApps.length;
                    let tooltipHtml = `<strong>${totalItems} Item(s)</strong><br>`;

                    let renderedDots = 0;

                    // Show dots for notices (up to 3 total dots)
                    dayData.notices.forEach(n => {
                        if (renderedDots >= 3) return;
                        const dot = document.createElement('div');
                        dot.className = `notice-dot ${n.notice_type || 'announcement'}`;
                        indicatorContainer.appendChild(dot);
                        renderedDots++;
                    });

                    // Show dots for notifications
                    dayData.notifications.forEach(n => {
                        if (renderedDots >= 3) return;
                        const dot = document.createElement('div');
                        // Use a distinct color for notifications (e.g., purple/indigo)
                        dot.className = `notice-dot`;
                        dot.style.backgroundColor = '#8b5cf6';
                        indicatorContainer.appendChild(dot);
                        renderedDots++;
                    });

                    if(dayData.forwardedApps.length > 0) {
                        const appBadge = document.createElement('div');
                        appBadge.style.fontSize = '9px';
                        appBadge.style.background = '#fa5c7c';
                        appBadge.style.color = 'white';
                        appBadge.style.padding = '1px 4px';
                        appBadge.style.borderRadius = '4px';
                        appBadge.style.marginTop = '2px';
                        appBadge.style.fontWeight = 'bold';
                        appBadge.textContent = `${dayData.forwardedApps.length} App(s)`;
                        indicatorContainer.appendChild(appBadge);
                    }

                    if(totalItems > 3 && renderedDots >= 3 && dayData.forwardedApps.length === 0) {
                        const extra = document.createElement('div');
                        extra.style.fontSize = '8px';
                        extra.style.color = '#98a6ad';
                        extra.textContent = '+';
                        indicatorContainer.appendChild(extra);
                    }

                    dayData.notices.forEach(n => {
                        const type = (n.notice_type || 'notice').toUpperCase();
                        tooltipHtml += `<div style="margin-top:4px; font-size:10px;">[${type}] ${n.title}</div>`;
                    });
                    dayData.notifications.forEach(n => {
                        tooltipHtml += `<div style="margin-top:4px; font-size:10px;">[NOTIF] ${n.subject || 'Notification'}</div>`;
                    });
                    dayData.forwardedApps.forEach(app => {
                        tooltipHtml += `<div style="margin-top:4px; font-size:10px;">[APP] ${app.application_no || 'Application'}</div>`;
                    });

                    // Add interaction instruction to tooltip
                    tooltipHtml += `<div style="margin-top:8px; padding-top:6px; border-top:1px solid rgba(255,255,255,0.2); font-size:9px; color:#a6b0cf; text-align:center; line-height: 1.4;">`;
                    tooltipHtml += `👆 <strong>Single Click:</strong> View Popup Details`;
                    if (dayData.forwardedApps.length > 0) {
                        tooltipHtml += `<br>🖱️ <strong>Double Click:</strong> Go to Applications`;
                    }
                    tooltipHtml += `</div>`;

                    const tooltip = document.createElement('div');
                    tooltip.className = 'notice-tooltip';
                    tooltip.innerHTML = tooltipHtml;

                    cell.appendChild(indicatorContainer);
                    cell.appendChild(tooltip);

                    let clickTimeout = null;

                    // Click handler
                    cell.addEventListener('click', () => {
                        if (clickTimeout) clearTimeout(clickTimeout);

                        clickTimeout = setTimeout(() => {
                            if (typeof window.showNoticesModal === 'function') {
                                window.showNoticesModal(dateKey, dayData);
                            }
                        }, 250);
                    });

                    // Double click handler
                    cell.addEventListener('dblclick', () => {
                        if (clickTimeout) clearTimeout(clickTimeout);

                        if (dayData.forwardedApps && dayData.forwardedApps.length > 0) {
                            const basePath = window.location.pathname.split('/')[1] || 'engineer';
                            window.location.href = `/${basePath}/applications?created_date_from=${dateKey}`;
                        }
                    });
                }
            }
            daysGrid.appendChild(cell);
        }
    }

    if(prevBtn) {
        prevBtn.addEventListener('click', () => {
            currentMonth = currentMonth === 0 ? 11 : currentMonth - 1;
            if (currentMonth === 11) currentYear--;
            renderCalendar(currentYear, currentMonth);
        });
    }

    if(nextBtn) {
        nextBtn.addEventListener('click', () => {
            currentMonth = currentMonth === 11 ? 0 : currentMonth + 1;
            if (currentMonth === 0) currentYear++;
            renderCalendar(currentYear, currentMonth);
        });
    }

    if (document.readyState === 'complete' || document.readyState === 'interactive') {
        setTimeout(() => renderCalendar(currentYear, currentMonth), 100);
    } else {
        document.addEventListener('DOMContentLoaded', () => renderCalendar(currentYear, currentMonth));
    }
})();
