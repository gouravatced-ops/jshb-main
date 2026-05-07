<div class="review-section">
    <!-- Header with Application Number -->
    <div class="review-header">
        <h3 class="review-title">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M9 12l2 2 4-4"></path>
                <circle cx="12" cy="12" r="10"></circle>
            </svg>
            Review Your Application
        </h3>
            <div class="application-badge">
                <span class="badge-label">Application No:</span>
                <span class="badge-value">4646456</span>
            </div>
    </div>

    <!-- Personal Details Table -->
    <div class="review-table-container">
        <div class="table-header" style="background: linear-gradient(90deg, #aa7700, #ffb703);">
            <div class="header-icon">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
            </div>
            <div class="header-content">
                <h4>Personal Details</h4>
                <p>Allottee information verification</p>
            </div>
        </div>
    </div>
</div>
<style>
    .review-section {
        margin: 0 auto;
        /* font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; */
    }

    .review-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f0f0f0;
    }

    .review-title {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 0;
        font-size: 1.5rem;
        font-weight: 600;
        color: #333;
    }

    .review-title svg {
        color: #aa7700;
    }

    .application-badge {
        background: #f8f9fa;
        padding: 8px 16px;
        border-radius: 30px;
        border: 1px solid #e0e0e0;
        font-size: 0.9rem;
    }

    .badge-label {
        color: #666;
        margin-right: 8px;
    }

    .badge-value {
        color: #aa7700;
        font-weight: 600;
        /* font-family: monospace; */
        font-size: 1rem;
    }

    .review-table-container {
        margin-bottom: 25px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        background: white;
    }

    .table-header {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 15px 20px;
        color: white;
    }

    .header-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 8px;
    }

    .header-content h4 {
        margin: 0 0 4px;
        font-size: 1rem;
        font-weight: 600;
        display: flex;
        align-items: center;
    }

    .header-content p {
        margin: 0;
        font-size: 0.8rem;
        opacity: 0.9;
    }

    .review-table {
        width: 100%;
        border-collapse: collapse;
        background: white;
    }

    .review-table tr {
        border-bottom: 1px solid #f0f0f0;
    }

    .review-table tr:last-child {
        border-bottom: none;
    }

    .review-table td {
        padding: 12px 15px;
        font-size: 0.9rem;
    }

    .label-cell {
        background: #f8f9fa;
        font-weight: 500;
        color: #666;
        width: 15%;
        border-right: 1px solid #f0f0f0;
    }

    .value-cell {
        color: #333;
        font-weight: 400;
        width: 35%;
    }

    .review-table th {
        background: #f8f9fa;
        padding: 10px 15px;
        font-size: 0.85rem;
        font-weight: 600;
        color: #555;
        text-align: left;
        border-bottom: 2px solid #e0e0e0;
    }

    .table-subhead {
        background: #f8f9fa;
        font-size: 0.85rem;
        font-weight: 600;
        color: #555;
    }

    .mono {
        /* font-family: 'Courier New', monospace; */
        font-weight: 500;
    }

    .review-actions {
        display: flex;
        gap: 15px;
        justify-content: flex-end;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 2px solid #f0f0f0;
    }

    .btn-edit,
    .btn-confirm {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 24px;
        border: none;
        border-radius: 30px;
        font-size: 0.9rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-edit {
        background: white;
        color: #666;
        border: 1px solid #ddd;
    }

    .btn-edit:hover {
        background: #f8f9fa;
        border-color: #999;
    }

    .btn-confirm {
        background: #aa7700;
        color: white;
    }

    .btn-confirm:hover {
        background: #8b6200;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(170, 119, 0, 0.2);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .review-section {
            padding: 15px;
        }

        .review-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }

        .review-table,
        .review-table tbody,
        .review-table tr,
        .review-table td {
            display: block;
        }

        .review-table tr {
            margin-bottom: 10px;
            border: 1px solid #f0f0f0;
            border-radius: 8px;
        }

        .review-table td {
            display: flex;
            padding: 10px;
            border: none;
        }

        .label-cell {
            width: 40%;
            background: none;
            border: none;
        }

        .value-cell {
            width: 60%;
        }

        .review-table th {
            display: none;
        }
    }

    /* Compact Mode */
    @media (min-width: 1200px) {
        .review-table td {
            padding: 10px 15px;
            font-size: 0.85rem;
        }

        .review-table-container {
            margin-bottom: 20px;
        }

        .table-header {
            padding: 12px 20px;
        }
    }
</style>
