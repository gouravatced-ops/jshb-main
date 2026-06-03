@extends('layouts.main')
@section('title', 'Edit Allottee | JSHB')
@section('content')
    <style>
        :root {
            --ink: #000000;
            --deep: #111827;
            --surface: #f5f4f0;
            --card: #ffffff;
            --muted: #777a7f;
            --border: #e4e2dc;
            --gold: #686868;
            --gold-lt: #f5ecd5;
            --gold-dk: #a07c30;
            --success: #15803d;
            --success-lt: #d6f0e3;
            --danger: #b53b3b;
            --danger-lt: #fde8e8;
            --radius: 14px;
            --radius-sm: 8px;
            --shadow: 0 4px 24px rgba(13, 15, 20, 0.08);
            --shadow-lg: 0 12px 40px rgba(13, 15, 20, 0.14);
        }


        /* ── OUTER CARD ─────────────────────────────────────────── */
        .app-shell {
            background: var(--card);
            box-shadow: var(--shadow);
            overflow: hidden;
            border: 1px solid var(--border);
        }

        /* ── HEADER ─────────────────────────────────────────────── */
        .app-header1 {
            background: var(--success);
            padding: 10px 14px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }

        .app-header1::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse 600px 300px at 80% 50%, rgba(201, 168, 76, 0.18) 0%, transparent 70%);
            pointer-events: none;
        }

        .app-header1::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent 0%, var(--gold) 35%, var(--gold-lt) 50%, var(--gold) 65%, transparent 100%);
        }

        .header-brand {
            z-index: 1;
        }

        .header-eyebrow {
            font-size: 11px;
            font-weight: 500;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: var(--gold);
            margin-bottom: 6px;
        }

        .header-sub {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.5);
            margin-top: 6px;
        }

        .app-number-badge {
            z-index: 1;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(201, 168, 76, 0.3);
            border-radius: 10px;
            padding: 5px 10px;
            text-align: right;
        }

        .app-number-label {
            font-size: 10px;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--light);
        }

        .app-number-value {
            font-size: 18px;
            font-weight: 600;
            color: #f3d303;
            margin-top: 2px;
        }

        /* ── STEPPER ─────────────────────────────────────────────── */
        .stepper-wrapper {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            padding: 15px 10px;
        }

        .stepper {
            display: flex;
            gap: 0;
            position: relative;
        }

        .stepper::before {
            content: '';
            position: absolute;
            top: 12px;
            left: 12px;
            right: 12px;
            height: 2px;
            background: var(--border);
            z-index: 0;
        }

        .stepper-track {
            position: absolute;
            top: 12px;
            left: 12px;
            right: 12px;
            height: 2px;
            background: linear-gradient(90deg, var(--gold), var(--gold-dk));
            z-index: 1;
            transition: width 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .step-item {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            position: relative;
            z-index: 2;
            cursor: pointer;
            transition: opacity 0.2s;
        }

        .step-item:hover:not(.active) {
            opacity: 0.8;
        }

        .step-bubble {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: var(--card);
            border: 2px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 600;
            color: var(--muted);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }

        .step-bubble svg {
            width: 18px;
            height: 18px;
            display: none;
        }

        .step-meta {
            text-align: center;
        }

        .step-label {
            font-size: 12px;
            font-weight: 600;
            color: var(--muted);
            letter-spacing: 0.02em;
            transition: color 0.2s;
        }

        .step-hint {
            font-size: 11px;
            color: var(--muted);
            margin-top: 2px;
            transition: color 0.2s;
        }

        /* Active */
        .step-item.active .step-bubble {
            background: var(--ink);
            border-color: var(--ink);
            color: #fff;
            box-shadow: 0 0 0 6px rgba(201, 168, 76, 0.18), 0 4px 16px rgba(13, 15, 20, 0.25);
            transform: scale(1.08);
        }

        .step-item.active .step-label {
            color: var(--deep);
        }

        .step-item.active .step-hint {
            color: var(--gold);
        }

        /* Completed */
        .step-item.completed .step-bubble {
            background: var(--success);
            border-color: var(--gold);
            color: #fff;
        }

        .step-item.completed .step-bubble span {
            display: none;
        }

        .step-item.completed .step-bubble svg {
            display: block;
        }

        .step-item.completed .step-label {
            color: var(--gold-dk);
            font-size: 14px;
            font-weight: 600;
        }

        /* ── BODY ────────────────────────────────────────────────── */
        .app-body {
            padding: 15px 18px 10px;
        }

        /* Alert */
        #alertContainer {
            margin-bottom: 10px;
        }

        .alert {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            padding: 16px 20px;
            border-radius: var(--radius-sm);
            font-size: 14px;
            border-left: 4px solid;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-success {
            background: var(--success-lt);
            border-color: var(--success);
            color: var(--success);
        }

        .alert-error {
            background: var(--danger-lt);
            border-color: var(--danger);
            color: var(--danger);
        }

        .alert-icon {
            flex-shrink: 0;
            margin-top: 1px;
        }

        /* Step content area */
        .step-content-area {
            min-height: 420px;
            animation: fadeUp 0.35s ease;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(12px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-section {
            background: #fff;
            /* margin-bottom: 10px; */
            overflow: hidden;
            border: none !important;
            padding: 0px !important;
        }

        .form-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(180deg, var(--gold), var(--gold-dk));
            border-radius: 4px 0 0 4px;
        }

        .gradient-header {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 16px;
            color: #fff;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
            margin-bottom: 10px;
            transition: 0.3s ease;
        }

        .gradient-header .section-icon {
            width: 34px;
            height: 34px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .gradient-header .section-title {
            font-size: 15px;
            font-weight: 600;
            margin: 0;
            color: #fff;
        }

        .gradient-header .section-desc {
            font-size: 11px;
            margin: 2px 0 0;
            color: rgba(255, 255, 255, 0.85);
        }

        .col-span-2 {
            grid-column: span 2;
        }

        @media (max-width: 960px) {
            .form-grid3 {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 680px) {

            .form-grid,
            .form-grid3,
            .form-grid4 {
                grid-template-columns: 1fr;
            }

            .col-span-2 {
                grid-column: span 1;
            }

            .stepper-wrapper {
                padding: 20px;
            }

            .app-body {
                padding: 24px;
            }
        }

        /* ── FIELD ───────────────────────────────────────────────── */
        .field {
            display: flex;
            flex-direction: column;
            /* gap: 6px; */
        }

        .field-label {
            font-size: 14px;
            font-weight: 600;
            color: #2a2d34;
            letter-spacing: 0.03em;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .req-star {
            color: var(--danger);
            font-size: 13px;
        }

        .custom-input {
            width: 100%;
            padding: 11px 14px;
            font-size: 14px;
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: var(--radius-sm);
            outline: none;
            transition: all 0.2s ease;
            appearance: none;
            font-weight: 500;
        }

        .custom-input::placeholder {
            color: #c0c4d0;
        }

        .custom-input:hover:not([readonly]):not(:focus) {
            border-color: #c9a84c66;
            background: #fff;
        }

        .custom-input:focus {
            border-color: var(--gold);
            background: #fff;
            box-shadow: 0 0 0 3px rgba(201, 168, 76, 0.15);
        }

        .custom-input.is-invalid {
            border-color: var(--danger);
            background: var(--danger-lt);
        }

        .custom-input.is-invalid:focus {
            box-shadow: 0 0 0 3px rgba(181, 59, 59, 0.12);
        }

        .custom-input[readonly] {
            background: #f0ede6;
            color: var(--muted);
            cursor: not-allowed;
        }

        textarea.custom-input {
            resize: vertical;
            min-height: 90px;
        }

        /* Select arrow */
        select.custom-input {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='none' viewBox='0 0 24 24'%3E%3Cpath stroke='%239399a6' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            padding-right: 38px;
            cursor: pointer;
        }

        /* Dropdown options */
        select.custom-input option:nth-child(even) {
            background-color: #1a7a3f39;
            color: #111111;
        }

        select.custom-input option:hover {
            background-color: #ffeb3b;
        }

        .field-hint {
            font-size: 11px;
            color: var(--muted);
        }

        .field-error {
            font-size: 11px;
            color: var(--danger);
            display: none;
        }

        .custom-input.is-invalid~.field-error {
            display: block;
        }

        .nav-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 16px;
            padding-top: 12px;
            border-top: 1px solid var(--border);
        }

        .nav-info {
            font-size: 14px;
            color: #064d21;
            font-weight: 600;
        }

        .nav-info strong {
            color: var(--ink);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 28px;
            border-radius: 10px;
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.22s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            text-decoration: none;
        }

        .btn svg {
            width: 18px;
            height: 18px;
        }

        .btn-ghost {
            background: var(--gold-dk);
            border: 1.5px solid var(--border);
            color: #ffffff;
        }

        .btn-ghost:hover {
            background: #094fc0;
            border-color: #c0c4d0;
            color: #ffffff;
        }

        .btn-primary {
            background: var(--success);
            color: #fff;
            box-shadow: 0 4px 14px rgba(13, 15, 20, 0.2);
        }

        .btn-primary:hover {
            background: #1c2130;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(13, 15, 20, 0.28);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .btn-primary:disabled {
            opacity: 0.55;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        /* Loading spinner */
        .spinner {
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
            display: none;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* ── FAMILY MEMBER CARD ──────────────────────────────────── */
        .member-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            padding: 20px;
            margin-bottom: 14px;
            position: relative;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .member-card:hover {
            border-color: var(--gold);
            box-shadow: 0 4px 16px rgba(201, 168, 76, 0.1);
        }

        .btn-remove {
            position: absolute;
            top: 14px;
            right: 14px;
            width: 30px;
            height: 30px;
            border-radius: 8px;
            background: var(--danger-lt);
            border: none;
            color: var(--danger);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-remove:hover {
            background: var(--danger);
            color: #fff;
        }

        /* ── ADD BUTTON ──────────────────────────────────────────── */
        .btn-add {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: var(--gold-lt);
            border: 1.5px dashed var(--gold);
            border-radius: var(--radius-sm);
            color: var(--gold-dk);
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 8px;
        }

        .btn-add:hover {
            background: var(--gold);
            color: #fff;
            border-style: solid;
        }

        /* ── REVIEW CARD ─────────────────────────────────────────── */
        .review-section {
            overflow: hidden;
            margin-bottom: 20px;
        }

        .review-section-head {
            background: var(--surface);
            padding: 14px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--border);
        }

        .review-section-title {
            font-family: 'Fraunces', serif;
            font-size: 15px;
            font-weight: 600;
            color: var(--ink);
        }

        .btn-edit-step {
            font-size: 12px;
            font-weight: 600;
            color: var(--gold-dk);
            background: var(--gold-lt);
            border: none;
            border-radius: 6px;
            padding: 5px 12px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-edit-step:hover {
            background: var(--gold);
            color: #fff;
        }

        .review-grid {
            padding: 20px;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 16px;
        }

        .review-pair {}

        .review-pair-label {
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 4px;
        }

        .review-pair-value {
            font-size: 14px;
            font-weight: 500;
            color: var(--ink);
        }

        /* Loading state */
        .load-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 14px;
            padding: 60px 0;
            color: var(--muted);
            font-size: 14px;
        }

        .load-ring {
            width: 40px;
            height: 40px;
            border: 3px solid var(--border);
            border-top-color: var(--gold);
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        /* ── PAYMENT CARD ────────────────────────────────────────── */
        .payment-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            padding: 20px;
            margin-bottom: 14px;
        }

        .payment-head {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 16px;
            padding-bottom: 14px;
            border-bottom: 1px dashed var(--border);
        }

        .chip {
            padding: 3px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.04em;
        }

        .chip-blue {
            background: #e0ecff;
            color: #1a56d6;
        }

        .chip-green {
            background: var(--success-lt);
            color: var(--success);
        }

        .chip-gold {
            background: var(--gold-lt);
            color: var(--gold-dk);
        }

        /* ── Review Cards ── */
        .review-card {
            background: #fff;
            overflow: hidden;
            margin-bottom: 20px;
            transition: box-shadow .2s;
        }

        .review-card:hover {
            box-shadow: 0 4px 20px rgba(13, 15, 20, .07);
        }

        .review-card-head {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px 22px;
            background: var(--surface);
            border-bottom: 1px solid var(--border);
        }

        .review-card-icon {
            width: 36px;
            height: 36px;
            background: var(--gold-lt);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gold-dk);
            flex-shrink: 0;
        }

        .review-card-title {
            font-family: 'Fraunces', serif;
            font-size: 15px;
            font-weight: 600;
            color: var(--ink);
            flex: 1;
        }

        .btn-edit {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            background: var(--gold-lt);
            border: 1px solid rgba(201, 168, 76, .4);
            border-radius: 8px;
            color: var(--gold-dk);
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all .2s;
            font-family: 'DM Sans', sans-serif;
        }

        .btn-edit:hover {
            background: var(--gold);
            color: #fff;
            border-color: var(--gold);
        }

        /* ── Review Grid ── */
        .review-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        }

        .review-pair {
            padding: 14px 22px;
            border-right: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
            transition: background .15s;
        }

        .review-pair:hover {
            background: #faf9f6;
        }

        .review-pair.wide {
            grid-column: span 2;
        }

        .rp-label {
            display: block;
            font-size: 10.5px;
            font-weight: 600;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 5px;
        }

        .rp-value {
            display: block;
            font-size: 13.5px;
            font-weight: 500;
            color: var(--ink);
            line-height: 1.4;
        }

        .rp-value.mono {
            font-family: 'Courier New', monospace;
            font-size: 13px;
            letter-spacing: .04em;
        }

        .rp-value.sm {
            font-size: 11.5px;
        }

        /* Sub-sections */
        .review-subsection {
            border-top: 2px dashed var(--border);
        }

        .review-subsection-title {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: var(--gold-dk);
            padding: 12px 22px 6px;
            background: rgba(201, 168, 76, .06);
            border-bottom: 1px solid var(--border);
        }

        /* Payment rows */
        .payments-review-grid {
            padding: 0;
        }

        .payment-review-row {
            display: flex;
            align-items: stretch;
            border-bottom: 1px solid var(--border);
            transition: background .15s;
        }

        .payment-review-row:last-child {
            border-bottom: none;
        }

        .payment-review-row:hover {
            background: #faf9f6;
        }

        .prr-type {
            padding: 16px 22px;
            min-width: 140px;
            border-right: 1px solid var(--border);
            display: flex;
            align-items: center;
        }

        .prr-label {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .1em;
            color: var(--ink);
            font-family: 'Fraunces', serif;
        }

        .prr-detail {
            padding: 14px 20px;
            flex: 1;
            border-right: 1px solid var(--border);
        }

        .prr-amount {
            padding: 14px 20px;
            border-right: 1px solid var(--border);
            min-width: 130px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .prr-amt-value {
            font-family: 'Fraunces', serif;
            font-size: 16px;
            font-weight: 700;
            color: var(--ink);
            margin-top: 4px;
        }

        .prr-status {
            padding: 14px 18px;
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 11.5px;
            font-weight: 600;
            color: var(--success);
        }

        /* ── Property Summary Pills ── */
        .property-summary {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            padding-bottom: 14px;
            border-bottom: 1px dashed var(--border);
            border-collapse: separate;
        }

        .prop-pill {
            display: flex;
            flex-direction: column;
            gap: 3px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 10px 16px;
            min-width: 120px;
            flex: 1;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .prop-pill:hover {
            border-color: var(--gold);
            box-shadow: 0 2px 8px rgba(201, 168, 76, 0.12);
        }

        .prop-pill-label {
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--muted);
        }

        .prop-pill-value {
            font-size: 13px;
            font-weight: 600;
            color: var(--ink);
        }

        .input-group {
            display: flex;
            width: 100%;
        }

        .prefix-select {
            width: 100px;
            border: 1px solid #ccc;
            border-right: none;
            padding: 11px 14px;
            border-radius: 6px 0 0 6px;
            background: #f9f9f9;
            color: #242524;
            font-weight: 600;
        }

        .input-group input {
            flex: 1;
            border: 1px solid #ccc;
            /* padding: 11px 14px; */
            border-radius: 0 6px 6px 0;
            /* font-size: 1.2rem; */
        }

        .input-group select:focus,
        .input-group input:focus {
            outline: none;
            border-color: #2563eb;
        }

        .bilingual-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px 35px;
        }

        .field-label {
            font-weight: 600;
            margin-bottom: 6px;
            display: block;
        }

        .custom-input {
            width: 100%;
            padding: 8px 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
        }

        .form-section {
            margin-bottom: 30px;
        }

        .field-inline {
            display: flex;
            gap: 20px;
            margin-bottom: 15px;
            font-size: 18px;
        }

        .date-group {
            display: flex;
            gap: 8px;
        }
    </style>
    {{-- Your existing HTML structure remains the same --}}
    <div class="app-shell">
        {{-- Header --}}
        <div class="modern-card-header">
            <div class="header-flex">
                <div>
                    <h1 class="header-title">Edit Allottee Record</h1>
                    <p class="header-subtitle">Update allottee and property details</p>
                </div>
                <div class="flex items-center">
                    <a href="{{ route('admin.allottees.index') }}">
                        <button class="btn btn-info"
                            style="background: linear-gradient(135deg, #ce3d04, #ee5121) !important; color:white;padding: 6px 24px; border-radius: 2px;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M19 12H5M12 19l-7-7 7-7"></path>
                            </svg> Back
                        </button>
                    </a>
                </div>
            </div>
        </div>

        {{-- Stepper --}}
        <div class="stepper-wrapper">
            <div class="stepper" id="stepper">
                <div class="stepper-track" id="stepperTrack"></div>
                @foreach ([0 => 'Payment', 1 => 'Allottee Details', 2 => 'Address Details', 3 => 'Review & Submit'] as $step => $label)
                    <div class="step-item {{ $step === 0 ? 'active' : '' }}" data-step="{{ $step }}"
                        onclick="StepManager.goToStep({{ $step }})">
                        <div class="step-bubble">
                            <span>{{ $loop->iteration }}</span>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <polyline points="20 6 9 17 4 12" />
                            </svg>
                        </div>
                        <div class="step-meta">
                            <div class="step-label">{{ $label }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        {{-- Body --}}
        <div class="app-body">
            {{-- Step Content --}}
            <div id="stepContent" class="step-content-area">
                @include('admin.allottee.step0')
            </div>

            {{-- Navigation --}}
            <div class="nav-bar">
                <div class="nav-info">
                    Step <strong id="stepNum">1</strong> of <strong id="stepTotal">4</strong>
                </div>
                <div style="display:flex; gap:12px; align-items:center;">
                    <button type="button" class="btn btn-ghost" id="prevBtn" onclick="StepManager.prevStep()"
                        style="display:none;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M19 12H5M12 19l-7-7 7-7" />
                        </svg>
                        Previous
                    </button>

                    <button type="button" class="btn btn-primary" id="nextBtn" onclick="StepManager.nextStep()">
                        <div class="spinner" id="btnSpinner"></div>
                        <span id="btnLabel">Save & Continue</span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 12h14M12 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const StepManager = {
            config: {
                currentStep: 0,
                applicantId: {{ isset($applicant) ? $applicant->id : 'null' }},
                steps: {
                    0: '{{ route('admin.apply.step0.save') }}',
                    1: '{{ route('admin.apply.step1.save') }}',
                    2: '{{ route('admin.apply.step2.save') }}',
                    3: '{{ route('admin.apply.step3.save') }}'
                },
                loadStepUrl: '{{ route('admin.apply.step', ['step' => '__STEP__', 'applicantId' => '__ID__']) }}',
                csrfToken: '{{ csrf_token() }}'
            },

            stepHandlers: {},
            currentHandler: null,
            isProcessing: false, // Add flag to prevent multiple submissions
            lastToastTime: 0, // Add throttling for toasts
            toastTimeout: null, // Track toast timeout

            init: function() {
                // Prevent double initialization
                if (this._initialized) {
                    console.log('StepManager already initialized');
                    return;
                }
                this._initialized = true;

                this.config.currentStep = 0;
                setTimeout(() => {
                    this.loadStepHandler(0);
                }, 100);
                this.updateStepper(this.config.currentStep);
                this.loadStep(0);
                this.bindEvents();
            },

            bindEvents: function() {
                document.addEventListener('input', e => {
                    if (e.target.classList.contains('is-invalid') && e.target.value.trim()) {
                        e.target.classList.remove('is-invalid');
                    }
                });
            },

            updateStepper: function(step) {
                const items = document.querySelectorAll('.step-item');
                items.forEach((item) => {
                    const s = parseInt(item.dataset.step, 10);
                    item.classList.remove('active', 'completed');
                    if (Number.isNaN(s)) return;
                    if (s < step) item.classList.add('completed');
                    if (s === step) item.classList.add('active');
                });

                const maxStep = Math.max(0, items.length - 1);
                const pct = maxStep > 0 ? (step / maxStep) * 100 : 0;
                const track = document.getElementById('stepperTrack');
                if (track) track.style.width = pct + '%';

                this.config.currentStep = step;

                const stepNum = document.getElementById('stepNum');
                if (stepNum) stepNum.textContent = step + 1;

                console.log('Step updated to:', step);
            },

            goToStep: function(step) {
                const item = document.querySelector(`.step-item[data-step="${step}"]`);
                if (item && (item.classList.contains('completed') || step === this.config.currentStep)) {
                    this.loadStep(step);
                }
            },

            loadStep: function(step) {
                // Prevent loading if already processing
                if (this.isProcessing) {
                    console.log('Already processing, skipping loadStep');
                    return;
                }

                // Steps after payment require an applicant id
                if (!this.config.applicantId && step > 0) {
                    this.showAlert('Please complete payment details first.', 'error');
                    return;
                }

                this.isProcessing = true;

                // Show loading state
                const stepContent = document.getElementById('stepContent');
                if (stepContent) {
                    stepContent.innerHTML =
                        '<div class="load-state"><div class="load-ring"></div><span>Loading...</span></div>';
                }

                const url = this.config.loadStepUrl
                    .replace('__STEP__', step)
                    .replace('__ID__', this.config.applicantId || '');

                fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(res => {
                        if (!res.ok) throw new Error('Failed to load step');
                        return res.text();
                    })
                    .then(html => {
                        if (stepContent) {
                            stepContent.innerHTML = html;
                        }
                        this.updateStepper(step);
                        setTimeout(() => {
                            this.loadStepHandler(step);
                            this.attachButtonEvents();
                            this.isProcessing = false;
                        }, 100);
                    })
                    .catch(err => {
                        console.error('Load step error:', err);
                        this.showAlert('Failed to load step. Please try again.', 'error');
                        this.isProcessing = false;
                    });
            },

            attachButtonEvents: function() {
                const nextBtn = document.getElementById('nextBtn');
                const prevBtn = document.getElementById('prevBtn');

                // Remove existing listeners by replacing with clone (cleaner approach)
                if (nextBtn) {
                    const newNextBtn = nextBtn.cloneNode(true);
                    nextBtn.parentNode.replaceChild(newNextBtn, nextBtn);
                    newNextBtn.addEventListener('click', (e) => {
                        e.preventDefault();
                        e.stopPropagation(); // Prevent event bubbling
                        this.nextStep();
                    });
                    console.log('Next button attached');
                }

                if (prevBtn) {
                    const newPrevBtn = prevBtn.cloneNode(true);
                    prevBtn.parentNode.replaceChild(newPrevBtn, prevBtn);
                    newPrevBtn.addEventListener('click', (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        this.prevStep();
                    });
                    console.log('Prev button attached');
                }

                this.updateButtonStates();
            },

            updateButtonStates: function() {
                const prevBtn = document.getElementById('prevBtn');
                const nextBtn = document.getElementById('nextBtn');
                const btnLabel = document.getElementById('btnLabel');

                if (prevBtn) {
                    prevBtn.style.display = this.config.currentStep === 0 ? 'none' : 'inline-flex';
                }

                if (btnLabel) {
                    btnLabel.innerHTML = this.config.currentStep === 3 ? 'Submit Application' : 'Save & Continue';
                }

                if (nextBtn) {
                    nextBtn.disabled = false;
                    nextBtn.style.opacity = '1';
                    nextBtn.style.cursor = 'pointer';
                }

                const spinner = document.getElementById('btnSpinner');
                if (spinner) {
                    spinner.style.display = 'none';
                }
            },

            loadStepHandler: function(step) {
                if (this.currentHandler && typeof this.currentHandler.destroy === 'function') {
                    this.currentHandler.destroy();
                }

                const Handler = this.stepHandlers[step];
                if (Handler) {
                    this.currentHandler = Object.create(Handler);
                    this.currentHandler.manager = this;
                    if (typeof this.currentHandler.init === 'function') {
                        this.currentHandler.init();
                    }
                    console.log(`Step ${step} handler initialized`);
                }
            },

            registerHandler: function(step, handler) {
                this.stepHandlers[step] = handler;
            },

            validateStep: function() {
                if (this.currentHandler && typeof this.currentHandler.validate === 'function') {
                    return this.currentHandler.validate();
                }

                const form = document.querySelector('#stepContent form');
                if (!form) return true;

                let valid = true;
                form.querySelectorAll('[required]').forEach(field => {
                    field.classList.remove('is-invalid');
                    if (!field.value.trim()) {
                        field.classList.add('is-invalid');
                        valid = false;
                    }
                });
                return valid;
            },

            nextStep: function() {
                // Prevent multiple clicks
                if (this.isProcessing) {
                    console.log('Already processing, skipping nextStep');
                    return;
                }

                console.log('nextStep called, current step:', this.config.currentStep);

                if (!this.validateStep()) {
                    this.showAlert('Please fill all required fields.', 'error');
                    return;
                }

                const nextBtn = document.getElementById('nextBtn');
                const spinner = document.getElementById('btnSpinner');
                const btnLabel = document.getElementById('btnLabel');

                // Disable button and show spinner
                this.isProcessing = true;
                if (nextBtn) {
                    nextBtn.disabled = true;
                    nextBtn.style.opacity = '0.6';
                    nextBtn.style.cursor = 'not-allowed';
                }
                if (spinner) spinner.style.display = 'inline-block';
                if (btnLabel) btnLabel.textContent = 'Saving...';

                // Handle final submission
                if (this.config.currentStep === 3) {
                    this.submitApplication();
                    return;
                }

                const form = document.querySelector('#stepContent form');

                if (!form) {
                    this.loadStep(this.config.currentStep + 1);
                    this.resetButton();
                    return;
                }

                const formData = new FormData(form);
                if (this.config.applicantId) {
                    formData.append('applicant_id', this.config.applicantId);
                }

                fetch(this.config.steps[this.config.currentStep], {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': this.config.csrfToken,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            // Only show ONE success message
                            this.showAlert(data.message, 'success');

                            if (data.applicant_id) {
                                this.config.applicantId = data.applicant_id;
                                console.log('Applicant ID set to:', this.config.applicantId);
                            }

                            const nextStep = data.next_step || (this.config.currentStep + 1);
                            console.log('Moving to step:', nextStep);

                            // Reset processing flag before loading next step
                            this.isProcessing = false;
                            this.loadStep(nextStep);
                        } else {
                            const msg = data.errors ? Object.values(data.errors).flat().join('<br>') : (data
                                .message || 'Error saving data.');
                            this.showAlert(msg, 'error');
                            this.resetButton();
                            this.isProcessing = false;
                        }
                    })
                    .catch(err => {
                        console.error('Save error:', err);
                        this.showAlert('An error occurred. Please try again.', 'error');
                        this.resetButton();
                        this.isProcessing = false;
                    });
            },

            prevStep: function() {
                if (this.isProcessing) return;

                console.log('prevStep called, current step:', this.config.currentStep);
                if (this.config.currentStep > 0) {
                    this.loadStep(this.config.currentStep - 1);
                }
            },

            resetButton: function() {
                const nextBtn = document.getElementById('nextBtn');
                const spinner = document.getElementById('btnSpinner');
                const btnLabel = document.getElementById('btnLabel');

                if (nextBtn) {
                    nextBtn.disabled = false;
                    nextBtn.style.opacity = '1';
                    nextBtn.style.cursor = 'pointer';
                }
                if (spinner) spinner.style.display = 'none';
                if (btnLabel) {
                    btnLabel.innerHTML = this.config.currentStep === 3 ? 'Submit Application' : 'Save & Continue';
                }
            },

            submitApplication: function() {
                fetch('{{ route('admin.apply.step3.save') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': this.config.csrfToken,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            final_submission: true,
                            applicant_id: this.config.applicantId
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            this.showAlert('Application submitted successfully!', 'success');
                            setTimeout(() => {
                                window.location.href = data.redirect_url ||
                                    '{{ route('admin.dashboard') }}';
                            }, 2000);
                        } else {
                            this.showAlert(data.message || 'Submission failed.', 'error');
                            this.resetButton();
                            this.isProcessing = false;
                        }
                    })
                    .catch(err => {
                        console.error('Submit error:', err);
                        this.showAlert('Error submitting application.', 'error');
                        this.resetButton();
                        this.isProcessing = false;
                    });
            },

            showAlert: function(message, type) {
                // Throttle toasts - prevent duplicates within 500ms
                const now = Date.now();
                if (now - this.lastToastTime < 500) {
                    console.log('Toast throttled:', message);
                    return;
                }
                this.lastToastTime = now;

                // Clear any pending toast timeout
                if (this.toastTimeout) {
                    clearTimeout(this.toastTimeout);
                }

                if (typeof showToast === 'function') {
                    console.log('Showing toast:', message, type);
                    showToast('Allottee', message, type);
                } else {
                    alert(message);
                }
            }
        };

        // Initialize on DOM ready - ensure only once
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => {
                StepManager.init();
            });
        } else {
            StepManager.init();
        }
    </script>

    {{-- Load Step Handlers --}}
    <script src="{{ asset('js/stepjs/step0-handler.js') }}"></script>
    <script src="{{ asset('js/stepjs/step1-handler.js') }}"></script>
    <script src="{{ asset('js/stepjs/step2-handler.js') }}"></script>
    <script src="{{ asset('js/stepjs/step3-handler.js') }}"></script>
@endsection
