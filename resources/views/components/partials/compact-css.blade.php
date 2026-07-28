<style>
    /* Refined Layout */
    .compact-wrapper {
        display: grid;
        grid-template-columns: repeat(12, 1fr);
        gap: 20px;
        gap: 20px;
        margin-top: 15px;
    }

    @font-face {
        font-family: 'KrutiDev011';
        src: url("{{ asset('font/KrutiDev011.ttf') }}") format('truetype');
    }

    @font-face {
        font-family: 'notosansdevanagari';
        src: url("{{ asset('font/NotoSansDevanagari.ttf') }}") format('truetype');
    }

    .compact-card {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        border: 1px solid #eef0f2;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .compact-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .compact-card-header {
        padding: 12px 16px;
        font-size: 15px;
        font-weight: 600;
        border-bottom: 1px solid #eaeaea;
        display: flex;
        justify-content: space-between;
        align-items: center;
        letter-spacing: 0.3px;
    }
    
    /* Distinct Header Colors with Subtle Gradients */
    .header-blue { background: linear-gradient(135deg, #e3f2fd, #bbdefb); color: #0d47a1; border-bottom-color: #90caf9; }
    .header-green { background: linear-gradient(135deg, #e8f5e9, #c8e6c9); color: #1b5e20; border-bottom-color: #a5d6a7; }
    .header-orange { background: linear-gradient(135deg, #fff3e0, #ffe0b2); color: #e65100; border-bottom-color: #ffcc80; }
    .header-purple { background: linear-gradient(135deg, #f3e5f5, #e1bee7); color: #4a148c; border-bottom-color: #ce93d8; }
    .header-gray { background: linear-gradient(135deg, #f5f5f5, #e0e0e0); color: #424242; border-bottom-color: #eeeeee; }
    .header-yellow { background: linear-gradient(135deg, #fff9c4, #fff59d); color: #f57f17; border-bottom-color: #fff176; }

    .compact-card-body {
        padding: 15px 16px;
        flex-grow: 1;
        overflow-y: auto;
        font-size: 14px;
        color: #444;
    }

    .compact-table {
        width: 100%;
        border-collapse: collapse;
    }

    .compact-table th, .compact-table td {
        padding: 10px 12px;
        border-bottom: 1px solid #f0f0f0;
        text-align: left;
    }

    .compact-table th {
        color: #777;
        font-weight: 600;
        background: #fcfcfc;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .compact-table tr:last-child td {
        border-bottom: none;
    }

    .data-pair {
        display: flex;
        margin-bottom: 10px;
        border-bottom: 1px dashed #f0f0f0;
        padding-bottom: 8px;
        align-items: center;
    }

    .data-pair:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .data-label {
        color: #666;
        width: 35%;
        font-weight: 500;
        font-size: 13px;
    }

    .data-value {
        color: #222;
        width: 65%;
        font-weight: 600;
        word-break: break-word;
    }

    .badge-compact {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge-normal { background: #e8f5e9; color: #2e7d32; border: 1px solid #c8e6c9; }
    .badge-urgent { background: #ffebee; color: #c62828; border: 1px solid #ffcdd2; }
    .badge-overdue { background: #fff3e0; color: #ef6c00; border: 1px solid #ffe0b2; }

    .btn-compact {
        background: #007bff;
        color: white;
        padding: 5px 12px;
        border-radius: 6px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        font-weight: 500;
        transition: opacity 0.2s, transform 0.1s;
        box-shadow: 0 2px 4px rgba(0,123,255,0.2);
    }
    .btn-compact:hover { opacity: 0.9; color: white; transform: translateY(-1px); }

    .notes-list { list-style: none; padding: 0; margin: 0; }
    .note-item { border-bottom: 1px solid #f0f0f0; padding-bottom: 10px; margin-bottom: 10px; }
    .note-item:last-child { border-bottom: none; padding-bottom: 0; margin-bottom: 0; }
    .note-header { display: flex; justify-content: space-between; margin-bottom: 6px; align-items: center; }
    .note-author { font-weight: 600; color: #4a148c; font-size: 13px; }
    .note-date { color: #888; font-size: 12px; display: flex; align-items: center; gap: 4px; }
    .note-content { color: #333; line-height: 1.5; font-size: 13px; background: #fdfdfd; padding: 10px; border-radius: 6px; border: 1px solid #f5f5f5; }
    .note-content ul { list-style-type: disc !important; padding-left: 25px !important; margin-bottom: 10px; list-style-image: none !important; }
    .note-content ol { list-style-type: decimal !important; padding-left: 25px !important; margin-bottom: 10px; list-style-image: none !important; }
    .note-content li { display: list-item !important; margin-bottom: 4px; list-style-type: disc !important; list-style-image: none !important; margin-left: 25px; }

    /* Layout Grids */
    .col-span-4 { grid-column: span 4; }
    .col-span-8 { grid-column: span 8; }
    .col-span-6 { grid-column: span 6; }
    .col-span-12 { grid-column: span 12; }
    
    @media (max-width: 992px) {
        .col-span-4, .col-span-8, .col-span-6 { grid-column: span 12; }
    }
</style>
