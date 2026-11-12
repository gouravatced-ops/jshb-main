@extends('layouts.public')

@section('title', 'Project Work Report & Live Workflow | Jharkhand State Housing Board')

@push('styles')
<style>
    /* ==========================================================================
       JSHB OFFICIAL LIGHT THEME — PROJECT WORK REPORT & FLOW VISUALIZER
       Matches landing.blade.php (Navy #1e3a8a, Yellow #facc15, Maroon #7f1d1d, Light Dotted #f1f5f9)
       ========================================================================== */

    .wf-hero {
        position: relative;
        padding-top: 7.5rem;
        padding-bottom: 4.5rem;
        overflow: hidden;
        background: linear-gradient(135deg, var(--secondary-dark) 0%, var(--secondary) 100%);
        color: #ffffff;
    }

    .wf-hero-pattern {
        position: absolute;
        inset: 0;
        opacity: 0.06;
        background-image: radial-gradient(#ffffff 1px, transparent 1px);
        background-size: 32px 32px;
        pointer-events: none;
    }

    .wf-hero-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 2.5rem;
        align-items: center;
        position: relative;
        z-index: 2;
    }

    @media (min-width: 1024px) {
        .wf-hero-grid {
            grid-template-columns: 1.12fr 0.88fr;
            gap: 3rem;
        }
    }

    .wf-hero-title {
        font-family: "Outfit", sans-serif;
        font-size: clamp(2rem, 4vw, 3.2rem);
        font-weight: 800;
        line-height: 1.15;
        color: #ffffff;
        margin-bottom: 1.15rem;
    }

    .wf-hero-title span {
        color: var(--primary);
    }

    .wf-hero-desc {
        font-size: 1.05rem;
        color: #e2e8f0;
        line-height: 1.65;
        margin-bottom: 1.85rem;
        max-width: 650px;
    }

    .wf-hero-btns {
        display: flex;
        flex-wrap: wrap;
        gap: 0.85rem;
    }

    /* Right Hero Summary Card */
    .wf-summary-card {
        background: #ffffff;
        border-radius: 1.5rem;
        padding: 1.75rem;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        border: 4px solid rgba(255, 255, 255, 0.25);
        color: var(--text-dark);
    }

    .wf-summary-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-bottom: 0.9rem;
        margin-bottom: 1rem;
        border-bottom: 1px solid #e2e8f0;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .wf-summary-header h3 {
        font-size: 1.15rem;
        font-weight: 800;
        color: var(--secondary);
        margin: 0;
    }

    .wf-grade-pill {
        background: #fef9c3;
        color: #854d0e;
        border: 1px solid #fde047;
        padding: 0.35rem 0.85rem;
        border-radius: 9999px;
        font-size: 0.78rem;
        font-weight: 800;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }

    /* Segmented Overall Progress Bar inside Hero Card */
    .wf-overall-bar-wrap {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        padding: 0.9rem 1rem;
        margin-bottom: 1rem;
    }

    .wf-overall-bar-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.82rem;
        font-weight: 800;
        color: var(--secondary);
        margin-bottom: 0.5rem;
    }

    .wf-segmented-track {
        height: 10px;
        width: 100%;
        background: #e2e8f0;
        border-radius: 9999px;
        overflow: hidden;
        display: flex;
        gap: 2px;
    }

    .wf-seg-done {
        background: #16a34a;
        height: 100%;
        transition: width 0.5s ease;
    }

    .wf-seg-pending {
        background: #f59e0b;
        height: 100%;
        transition: width 0.5s ease;
    }

    .wf-seg-upcoming {
        background: #3b82f6;
        height: 100%;
        transition: width 0.5s ease;
    }

    .wf-seg-legend {
        display: flex;
        flex-wrap: wrap;
        gap: 0.85rem;
        margin-top: 0.55rem;
        font-size: 0.72rem;
        font-weight: 700;
        color: var(--text-muted);
    }

    .wf-seg-legend span {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }

    .wf-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
    }

    .wf-kpi-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.8rem;
    }

    @media (max-width: 480px) {
        .wf-kpi-grid {
            grid-template-columns: 1fr;
        }
    }

    .wf-kpi-box {
        padding: 0.95rem 1rem;
        border-radius: 1rem;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        cursor: pointer;
        text-align: left;
    }

    .wf-kpi-box:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(15, 23, 42, 0.06);
    }

    .wf-kpi-box.emerald { background: #f0fdf4; border-color: #bbf7d0; }
    .wf-kpi-box.amber   { background: #fffbeb; border-color: #fde68a; }
    .wf-kpi-box.blue    { background: #eff6ff; border-color: #bfdbfe; }
    .wf-kpi-box.rose    { background: #fff1f2; border-color: #fecdd3; }

    .wf-kpi-icon {
        font-size: 1.15rem;
        margin-bottom: 0.35rem;
        display: inline-block;
    }

    .wf-kpi-box.emerald .wf-kpi-icon { color: #16a34a; }
    .wf-kpi-box.amber .wf-kpi-icon   { color: #d97706; }
    .wf-kpi-box.blue .wf-kpi-icon    { color: #2563eb; }
    .wf-kpi-box.rose .wf-kpi-icon    { color: #e11d48; }

    .wf-kpi-val {
        font-family: "Outfit", sans-serif;
        font-size: 1.35rem;
        font-weight: 800;
        color: var(--text-dark);
        line-height: 1.15;
    }

    .wf-kpi-lbl {
        font-size: 0.76rem;
        color: var(--text-muted);
        font-weight: 600;
        margin-top: 0.15rem;
    }

    /* ==========================================================================
       PROJECT JOURNEY MILESTONE STRIP (COMPLETED -> PENDING -> COMING SOON)
       ========================================================================== */
    .wf-milestone-strip {
        background: #ffffff;
        border-bottom: 1px solid #e2e8f0;
        padding: 1.75rem 0;
        box-shadow: 0 4px 15px rgba(15, 23, 42, 0.03);
    }

    .wf-milestone-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1rem;
    }

    @media (min-width: 768px) {
        .wf-milestone-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (min-width: 1140px) {
        .wf-milestone-grid {
            grid-template-columns: repeat(6, 1fr);
        }
    }

    .wf-ms-step {
        position: relative;
        padding: 1rem 1.1rem;
        border-radius: 1rem;
        border: 1.5px solid #e2e8f0;
        background: #f8fafc;
        transition: all 0.25s ease;
        cursor: pointer;
    }

    .wf-ms-step:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(15, 23, 42, 0.06);
    }

    .wf-ms-step.done {
        background: #f0fdf4;
        border-color: #86efac;
    }

    .wf-ms-step.pending {
        background: #fffbeb;
        border-color: #fbbf24;
        box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.12);
    }

    .wf-ms-step.upcoming {
        background: #eff6ff;
        border-color: #93c5fd;
    }

    .wf-ms-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        font-size: 0.68rem;
        font-weight: 800;
        text-transform: uppercase;
        padding: 0.2rem 0.55rem;
        border-radius: 9999px;
        margin-bottom: 0.45rem;
    }

    .wf-ms-step.done .wf-ms-badge {
        background: #dcfce7;
        color: #166534;
    }

    .wf-ms-step.pending .wf-ms-badge {
        background: #fef3c7;
        color: #92400e;
    }

    .wf-ms-step.upcoming .wf-ms-badge {
        background: #dbeafe;
        color: #1e40af;
    }

    .wf-ms-title {
        font-family: "Outfit", sans-serif;
        font-size: 0.95rem;
        font-weight: 800;
        color: var(--secondary);
        margin-bottom: 0.2rem;
    }

    .wf-ms-sub {
        font-size: 0.76rem;
        color: var(--text-muted);
        line-height: 1.35;
    }

    /* ==========================================================================
       SECTION 2: INTERACTIVE LIGHT-THEME HTML5 CANVAS VISUALIZER
       ========================================================================== */
    .wf-canvas-wrapper {
        background: #ffffff;
        border-radius: 1.5rem;
        border: 1px solid #cbd5e1;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.01);
        overflow: hidden;
    }

    .wf-canvas-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
        padding: 1.1rem 1.5rem;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
    }

    .wf-tab-group {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .wf-canvas-tab {
        padding: 0.6rem 1.05rem;
        border-radius: 9999px;
        font-size: 0.82rem;
        font-weight: 700;
        border: 1px solid #cbd5e1;
        background: #ffffff;
        color: var(--text-muted);
        cursor: pointer;
        transition: all 0.25s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
    }

    .wf-canvas-tab:hover {
        border-color: var(--secondary);
        color: var(--secondary);
    }

    .wf-canvas-tab.active {
        background: var(--secondary);
        color: #ffffff;
        border-color: var(--secondary);
        box-shadow: 0 4px 12px rgba(30, 58, 138, 0.2);
    }

    .wf-canvas-tab.active i {
        color: var(--primary);
    }

    .wf-canvas-actions {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .wf-tool-btn {
        padding: 0.5rem 0.85rem;
        border-radius: 9999px;
        font-size: 0.8rem;
        font-weight: 600;
        border: 1px solid #cbd5e1;
        background: #ffffff;
        color: var(--text-dark);
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        transition: all 0.2s;
    }

    .wf-tool-btn:hover {
        background: #f1f5f9;
        border-color: var(--secondary);
        color: var(--secondary);
    }

    /* Status Legend Bar above Canvas */
    .wf-canvas-legend-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 0.75rem;
        padding: 0.65rem 1.5rem;
        background: #ffffff;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.8rem;
        font-weight: 700;
    }

    .wf-legend-items {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 1.25rem;
    }

    .wf-legend-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        color: var(--text-dark);
    }

    .wf-legend-badge {
        padding: 0.15rem 0.55rem;
        border-radius: 9999px;
        font-size: 0.7rem;
        font-weight: 800;
    }

    .wf-legend-badge.done { background: #dcfce7; color: #166534; border: 1px solid #86efac; }
    .wf-legend-badge.pending { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
    .wf-legend-badge.upcoming { background: #dbeafe; color: #1e40af; border: 1px solid #93c5fd; }

    /* Responsive Canvas Container */
    .wf-canvas-stage {
        position: relative;
        width: 100%;
        height: 530px;
        background-color: #f8fafc;
        overflow: hidden;
        cursor: grab;
    }

    .wf-canvas-stage:active {
        cursor: grabbing;
    }

    @media (max-width: 768px) {
        .wf-canvas-stage {
            height: 680px;
        }
        .wf-canvas-toolbar {
            padding: 1rem;
        }
        .wf-tab-group {
            width: 100%;
        }
        .wf-canvas-tab {
            flex: 1 1 auto;
            justify-content: center;
            font-size: 0.78rem;
            padding: 0.5rem 0.75rem;
        }
    }

    #jshbLightCanvas {
        width: 100%;
        height: 100%;
        display: block;
    }

    /* Bottom Step Details Bar below Canvas */
    .wf-canvas-infobar {
        padding: 1.25rem 1.5rem;
        background: #ffffff;
        border-top: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .wf-infobar-left {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        max-width: 820px;
    }

    .wf-infobar-icon {
        width: 2.75rem;
        height: 2.75rem;
        border-radius: 0.85rem;
        background: rgba(30, 58, 138, 0.08);
        color: var(--secondary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }

    .wf-infobar-title {
        font-family: "Outfit", sans-serif;
        font-weight: 800;
        font-size: 1.05rem;
        color: var(--secondary);
        margin-bottom: 0.2rem;
    }

    .wf-infobar-desc {
        font-size: 0.875rem;
        color: var(--text-muted);
        line-height: 1.5;
    }

    /* ==========================================================================
       SECTION 3: WORK REPORT FILTER TABS (COMPLETED / PENDING / COMING SOON)
       ========================================================================== */
    .wf-status-tabs {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.85rem;
        margin-bottom: 1.25rem;
    }

    @media (min-width: 992px) {
        .wf-status-tabs {
            grid-template-columns: repeat(4, 1fr);
        }
    }

    .wf-status-tab-btn {
        background: #ffffff;
        border: 2px solid #e2e8f0;
        border-radius: 1.15rem;
        padding: 1rem 1.2rem;
        text-align: left;
        cursor: pointer;
        transition: all 0.25s ease;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        box-shadow: 0 4px 10px rgba(15, 23, 42, 0.03);
    }

    .wf-status-tab-btn:hover {
        transform: translateY(-2px);
        border-color: #94a3b8;
    }

    .wf-status-tab-btn.active.all-tab {
        border-color: var(--secondary);
        background: #eff6ff;
        box-shadow: 0 8px 20px rgba(30, 58, 138, 0.12);
    }

    .wf-status-tab-btn.active.done-tab {
        border-color: #16a34a;
        background: #f0fdf4;
        box-shadow: 0 8px 20px rgba(22, 163, 74, 0.12);
    }

    .wf-status-tab-btn.active.pending-tab {
        border-color: #d97706;
        background: #fffbeb;
        box-shadow: 0 8px 20px rgba(217, 119, 6, 0.12);
    }

    .wf-status-tab-btn.active.upcoming-tab {
        border-color: #2563eb;
        background: #eff6ff;
        box-shadow: 0 8px 20px rgba(37, 99, 235, 0.12);
    }

    .wf-st-left {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .wf-st-icon {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    .all-tab .wf-st-icon { background: #e0e7ff; color: #1e3a8a; }
    .done-tab .wf-st-icon { background: #dcfce7; color: #15803d; }
    .pending-tab .wf-st-icon { background: #fef3c7; color: #b45309; }
    .upcoming-tab .wf-st-icon { background: #dbeafe; color: #1d4ed8; }

    .wf-st-title {
        font-family: "Outfit", sans-serif;
        font-size: 0.98rem;
        font-weight: 800;
        color: var(--text-dark);
        display: block;
    }

    .wf-st-sub {
        font-size: 0.74rem;
        color: var(--text-muted);
        font-weight: 600;
    }

    .wf-st-count {
        font-family: "Outfit", sans-serif;
        font-size: 1.15rem;
        font-weight: 800;
        padding: 0.25rem 0.7rem;
        border-radius: 9999px;
        background: #f1f5f9;
        color: var(--secondary);
    }

    .wf-filter-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 2.25rem;
        background: #ffffff;
        padding: 1rem 1.25rem;
        border-radius: 1.25rem;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
    }

    .wf-portal-pills {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .wf-pill-btn {
        padding: 0.55rem 1.15rem;
        border-radius: 9999px;
        font-size: 0.82rem;
        font-weight: 700;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        color: var(--text-muted);
        cursor: pointer;
        transition: all 0.2s;
    }

    .wf-pill-btn:hover {
        color: var(--secondary);
        border-color: #cbd5e1;
    }

    .wf-pill-btn.active {
        background: var(--secondary);
        color: #ffffff;
        border-color: var(--secondary);
        box-shadow: 0 4px 12px rgba(30, 58, 138, 0.2);
    }

    .wf-pill-btn.active.maroon-pill {
        background: #7f1d1d;
        border-color: #7f1d1d;
        box-shadow: 0 4px 12px rgba(127, 29, 29, 0.25);
    }

    .wf-search-wrap {
        position: relative;
        flex: 1;
        max-width: 340px;
        min-width: 240px;
    }

    @media (max-width: 768px) {
        .wf-search-wrap {
            max-width: 100%;
            width: 100%;
        }
    }

    .wf-search-input {
        width: 100%;
        padding: 0.65rem 1rem 0.65rem 2.5rem;
        border-radius: 9999px;
        border: 1px solid #cbd5e1;
        background: #f8fafc;
        font-size: 0.875rem;
        color: var(--text-dark);
        outline: none;
        transition: all 0.2s;
    }

    .wf-search-input:focus {
        background: #ffffff;
        border-color: var(--secondary);
        box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
    }

    .wf-search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #64748b;
        font-size: 0.85rem;
    }

    /* Module Cards Grid — Matches Image 2 (.feature-card) */
    .wf-modules-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.75rem;
    }

    @media (min-width: 768px) {
        .wf-modules-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (min-width: 1140px) {
        .wf-modules-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    .wf-module-card {
        background: #ffffff;
        border-radius: 1.5rem;
        padding: 2rem 1.75rem;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.01);
        border: 1px solid rgba(226, 232, 240, 0.85);
        border-top: 5px solid #16a34a;
        transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
        position: relative;
        overflow: hidden;
    }

    .wf-module-card.status-pending {
        border-top-color: #f59e0b;
    }

    .wf-module-card.status-upcoming {
        border-top-color: #2563eb;
    }

    .wf-module-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.09), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    .wf-card-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 0.75rem;
        margin-bottom: 1.15rem;
    }

    .wf-icon-box {
        width: 3.5rem;
        height: 3.5rem;
        border-radius: 1rem;
        background: rgba(30, 58, 138, 0.06);
        color: var(--secondary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        transition: transform 0.35s ease, background 0.3s, color 0.3s;
        flex-shrink: 0;
    }

    .wf-module-card.status-pending .wf-icon-box {
        background: #fef3c7;
        color: #b45309;
    }

    .wf-module-card.status-upcoming .wf-icon-box {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .wf-module-card:hover .wf-icon-box {
        transform: scale(1.08) rotate(4deg);
        background: var(--secondary);
        color: var(--primary);
    }

    .wf-card-badges {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 0.35rem;
    }

    .wf-status-pill {
        font-size: 0.68rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        padding: 0.25rem 0.7rem;
        border-radius: 9999px;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }

    .wf-status-pill.completed {
        background: #dcfce7;
        color: #166534;
        border: 1px solid #86efac;
    }

    .wf-status-pill.pending {
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fde68a;
    }

    .wf-status-pill.upcoming {
        background: #dbeafe;
        color: #1e40af;
        border: 1px solid #93c5fd;
    }

    .wf-portal-tag {
        font-size: 0.68rem;
        font-weight: 700;
        padding: 0.2rem 0.65rem;
        border-radius: 9999px;
        background: #f1f5f9;
        color: #475569;
    }

    .wf-module-card h3 {
        font-family: "Outfit", sans-serif;
        font-size: 1.25rem;
        color: var(--secondary);
        font-weight: 800;
        margin-bottom: 0.45rem;
    }

    .wf-module-summary {
        color: var(--text-muted);
        font-size: 0.89rem;
        line-height: 1.55;
        margin-bottom: 1rem;
    }

    /* Progress Bar inside Each Card */
    .wf-card-progress-wrap {
        margin-bottom: 1rem;
        background: #f8fafc;
        padding: 0.6rem 0.85rem;
        border-radius: 0.75rem;
        border: 1px solid #f1f5f9;
    }

    .wf-card-progress-head {
        display: flex;
        justify-content: space-between;
        font-size: 0.75rem;
        font-weight: 800;
        margin-bottom: 0.35rem;
        color: var(--text-dark);
    }

    .wf-card-progress-bar {
        height: 6px;
        background: #e2e8f0;
        border-radius: 9999px;
        overflow: hidden;
    }

    .wf-card-progress-fill {
        height: 100%;
        border-radius: 9999px;
    }

    .wf-card-progress-fill.completed { background: #16a34a; }
    .wf-card-progress-fill.pending   { background: #f59e0b; }
    .wf-card-progress-fill.upcoming  { background: #3b82f6; }

    .wf-feature-list {
        list-style: none;
        margin: 0;
        padding: 0;
        display: flex;
        flex-direction: column;
        gap: 0.65rem;
        border-top: 1px solid #f1f5f9;
        padding-top: 1rem;
        margin-top: auto;
    }

    .wf-feature-item {
        display: flex;
        align-items: flex-start;
        gap: 0.65rem;
        font-size: 0.86rem;
        color: var(--text-dark);
        line-height: 1.45;
    }

    .wf-feature-item i.icon-completed {
        color: #16a34a;
        margin-top: 0.22rem;
        font-size: 0.85rem;
        flex-shrink: 0;
    }

    .wf-feature-item i.icon-pending {
        color: #d97706;
        margin-top: 0.22rem;
        font-size: 0.85rem;
        flex-shrink: 0;
    }

    .wf-feature-item i.icon-upcoming {
        color: #2563eb;
        margin-top: 0.22rem;
        font-size: 0.85rem;
        flex-shrink: 0;
    }

    .wf-feature-item strong {
        color: var(--secondary-dark);
        font-weight: 700;
    }

    /* ==========================================================================
       SECTION 4: MAROON HIGHLIGHT SECTION (ALLOTTEE ONBOARDING & MAIL WORKFLOW)
       Matches Image 2 Bottom Maroon Section (#7f1d1d)
       ========================================================================== */
    .wf-maroon-section {
        background-color: #7f1d1d;
        color: #ffffff;
        padding: 5.5rem 0;
        position: relative;
        overflow: hidden;
    }

    .wf-maroon-steps {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.5rem;
        margin-top: 2.5rem;
    }

    @media (min-width: 768px) {
        .wf-maroon-steps {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (min-width: 1024px) {
        .wf-maroon-steps {
            grid-template-columns: repeat(4, 1fr);
        }
    }

    .wf-maroon-card {
        background: rgba(255, 255, 255, 0.07);
        border: 1px solid rgba(255, 255, 255, 0.16);
        border-radius: 1.25rem;
        padding: 1.75rem 1.5rem;
        transition: transform 0.3s ease, background 0.3s ease;
    }

    .wf-maroon-card:hover {
        transform: translateY(-5px);
        background: rgba(255, 255, 255, 0.11);
    }

    .wf-step-num {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        background: var(--primary);
        color: var(--secondary-dark);
        font-size: 0.75rem;
        font-weight: 800;
        margin-bottom: 1rem;
        text-transform: uppercase;
    }

    .wf-maroon-card h4 {
        font-family: "Outfit", sans-serif;
        font-size: 1.2rem;
        font-weight: 800;
        color: #ffffff;
        margin-bottom: 0.65rem;
    }

    .wf-maroon-card p {
        font-size: 0.9rem;
        color: #f1f5f9;
        line-height: 1.6;
    }

    /* ==========================================================================
       SECTION 5: SYSTEM DESIGN SCORECARD & JSON DRAWER
       ========================================================================== */
    .wf-score-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.25rem;
    }

    @media (min-width: 768px) {
        .wf-score-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (min-width: 1024px) {
        .wf-score-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    .wf-score-card {
        background: #ffffff;
        border-radius: 1.25rem;
        padding: 1.5rem;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
    }

    .wf-score-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.75rem;
    }

    .wf-score-name {
        font-weight: 800;
        color: var(--secondary);
        font-size: 0.95rem;
    }

    .wf-score-badge {
        background: #eff6ff;
        color: var(--secondary);
        font-weight: 800;
        font-size: 0.85rem;
        padding: 0.2rem 0.65rem;
        border-radius: 9999px;
    }

    .wf-progress-track {
        width: 100%;
        height: 8px;
        background: #e2e8f0;
        border-radius: 9999px;
        overflow: hidden;
        margin-bottom: 0.65rem;
    }

    .wf-progress-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--secondary), #2563eb);
        border-radius: 9999px;
    }

    .wf-json-collapse {
        display: none;
        margin-top: 2rem;
        background: #ffffff;
        border-radius: 1.25rem;
        border: 1px solid #cbd5e1;
        overflow: hidden;
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
    }

    .wf-json-collapse.open {
        display: block;
    }

    .wf-json-pre {
        padding: 1.5rem;
        background: #f8fafc;
        color: #0f172a;
        font-family: "Consolas", "Monaco", monospace;
        font-size: 0.82rem;
        line-height: 1.55;
        max-height: 440px;
        overflow-y: auto;
        white-space: pre-wrap;
        word-break: break-word;
    }

    /* Quick Add Task Modal (For Live Presentations) */
    .wf-modal-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.55);
        backdrop-filter: blur(4px);
        z-index: 9999;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }

    .wf-modal-backdrop.open {
        display: flex;
    }

    .wf-modal-card {
        background: #ffffff;
        border-radius: 1.5rem;
        max-width: 540px;
        width: 100%;
        padding: 2rem;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        border: 1px solid #e2e8f0;
    }

    .wf-form-group {
        margin-bottom: 1rem;
    }

    .wf-form-group label {
        display: block;
        font-size: 0.82rem;
        font-weight: 700;
        color: var(--secondary);
        margin-bottom: 0.35rem;
    }

    .wf-form-control {
        width: 100%;
        padding: 0.65rem 0.9rem;
        border-radius: 0.75rem;
        border: 1px solid #cbd5e1;
        font-size: 0.88rem;
        color: var(--text-dark);
        outline: none;
    }

    .wf-form-control:focus {
        border-color: var(--secondary);
    }
</style>
@endpush

@section('content')

<!-- ======================================================================= -->
<!-- 1. HERO SECTION — PROJECT WORK REPORT (COMPLETED, PENDING & UPCOMING)   -->
<!-- ======================================================================= -->
<section class="wf-hero">
    <div class="wf-hero-pattern"></div>

    <div class="container wf-hero-grid">
        <div>
            <div class="live-badge">
                <span class="ping">
                    <span class="ping-circle"></span>
                    <span class="ping-dot"></span>
                </span>
                Official Project Work Report & Live Roadmap
            </div>

            <h1 class="wf-hero-title">
                Project Work Report & <br>
                <span>Live Workflow Map.</span>
            </h1>

            <p class="wf-hero-desc">
                A complete visual progress report of the Jharkhand State Housing Board platform — showing <strong>Completed Work (17 Modules)</strong>, <strong>Pending / In-Progress Work (5 Modules)</strong>, and <strong>Upcoming Roadmap Features (5 Modules)</strong> with interactive end-to-end flowcharts.
            </p>

            <div class="wf-hero-btns">
                <a href="#interactive-flow" class="btn-large btn-dark">
                    View Visual Progress Flow <i class="fa-solid fa-diagram-project"></i>
                </a>
                <a href="#work-report-cards" class="btn-large btn-outline">
                    <i class="fa-solid fa-list-check"></i> Completed, Pending & Upcoming
                </a>
                <button type="button" onclick="openAddTaskModal()" class="btn-large btn-outline" style="cursor: pointer;">
                    <i class="fa-solid fa-plus-circle"></i> Add Work Item
                </button>
            </div>
        </div>

        <!-- Right Summary Card: Live Work Breakdown -->
        <div>
            <div class="wf-summary-card">
                <div class="wf-summary-header">
                    <div>
                        <span style="font-size: 0.72rem; font-weight: 800; text-transform: uppercase; color: var(--primary-dark); display: block;">Live Project Progress Tracker</span>
                        <h3>Where Our Work Stands Today</h3>
                    </div>
                    <span class="wf-grade-pill">
                        <i class="fa-solid fa-star"></i> Architecture: 8.7 / 10
                    </span>
                </div>

                <!-- Segmented Overall Completion Bar -->
                <div class="wf-overall-bar-wrap">
                    <div class="wf-overall-bar-top">
                        <span>Overall Platform Progress</span>
                        <span style="color: #16a34a;">78% Completed & Live</span>
                    </div>
                    <div class="wf-segmented-track">
                        <div class="wf-seg-done" style="width: 68%;" title="Completed (17 Modules)"></div>
                        <div class="wf-seg-pending" style="width: 17%;" title="Pending / In Progress (5 Modules)"></div>
                        <div class="wf-seg-upcoming" style="width: 15%;" title="Coming Soon (5 Modules)"></div>
                    </div>
                    <div class="wf-seg-legend">
                        <span><i class="wf-dot" style="background: #16a34a;"></i> Completed (17)</span>
                        <span><i class="wf-dot" style="background: #f59e0b;"></i> Pending / Active (5)</span>
                        <span><i class="wf-dot" style="background: #3b82f6;"></i> Coming Soon (5)</span>
                    </div>
                </div>

                <div class="wf-kpi-grid">
                    <div class="wf-kpi-box emerald" onclick="jumpToStatusFilter('completed')">
                        <i class="fa-solid fa-circle-check wf-kpi-icon"></i>
                        <div class="wf-kpi-val" id="heroCountCompleted">17 Modules</div>
                        <div class="wf-kpi-lbl">✓ Completed & Live (64+ Tasks)</div>
                    </div>
                    <div class="wf-kpi-box amber" onclick="jumpToStatusFilter('pending')">
                        <i class="fa-solid fa-clock-rotate-left wf-kpi-icon"></i>
                        <div class="wf-kpi-val" id="heroCountPending">5 Modules</div>
                        <div class="wf-kpi-lbl">⏳ Pending / In Progress</div>
                    </div>
                    <div class="wf-kpi-box blue" onclick="jumpToStatusFilter('upcoming')">
                        <i class="fa-solid fa-rocket wf-kpi-icon"></i>
                        <div class="wf-kpi-val" id="heroCountUpcoming">5 Modules</div>
                        <div class="wf-kpi-lbl">🚀 Coming Soon (Next Phase)</div>
                    </div>
                    <div class="wf-kpi-box rose" onclick="jumpToStatusFilter('all')">
                        <i class="fa-solid fa-layer-group wf-kpi-icon"></i>
                        <div class="wf-kpi-val" id="heroCountTotal">27 Modules</div>
                        <div class="wf-kpi-lbl">Complete Project Scope</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ======================================================================= -->
<!-- 1B. STEP-BY-STEP PROJECT MILESTONE BAR (ABHI TAK KAHA TAK HUA HAI)      -->
<!-- ======================================================================= -->
<section class="wf-milestone-strip">
    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 1rem;">
            <div style="font-family: 'Outfit', sans-serif; font-weight: 800; font-size: 1.05rem; color: var(--secondary);">
                <i class="fa-solid fa-route" style="color: var(--primary-dark); margin-right: 6px;"></i>
                Step-by-Step Project Delivery Journey (Phase 1 to Phase 6)
            </div>
            <span style="font-size: 0.8rem; color: var(--text-muted); font-weight: 600;">
                Click any phase below to filter its modules
            </span>
        </div>

        <div class="wf-milestone-grid">
            <div class="wf-ms-step done" onclick="jumpToStatusFilter('completed')">
                <span class="wf-ms-badge"><i class="fa-solid fa-check"></i> Phase 1 • Done</span>
                <div class="wf-ms-title">Core Admin & Security</div>
                <div class="wf-ms-sub">Divisions, Schemes, Staff Roles & 1Hr/24Hr Lockout</div>
            </div>

            <div class="wf-ms-step done" onclick="jumpToStatusFilter('completed')">
                <span class="wf-ms-badge"><i class="fa-solid fa-check"></i> Phase 2 • Done</span>
                <div class="wf-ms-title">Allottee & Mail Drop</div>
                <div class="wf-ms-sub">4-Step Add, 1-Click Portal Sync, Welcome Mail & Auto-Apply</div>
            </div>

            <div class="wf-ms-step done" onclick="jumpToStatusFilter('completed')">
                <span class="wf-ms-badge"><i class="fa-solid fa-check"></i> Phase 3 • Done</span>
                <div class="wf-ms-title">Official File Portal</div>
                <div class="wf-ms-sub">Forward/Approve, Hindi Noting, Pusher Live Sync & Audit</div>
            </div>

            <div class="wf-ms-step done" onclick="jumpToStatusFilter('completed')">
                <span class="wf-ms-badge"><i class="fa-solid fa-check"></i> Phase 4 • Done</span>
                <div class="wf-ms-title">360° List & EMI Modal</div>
                <div class="wf-ms-sub">Allottee Accordion, Dynamic One-Time & EMI Payment Modal</div>
            </div>

            <div class="wf-ms-step pending" onclick="jumpToStatusFilter('pending')">
                <span class="wf-ms-badge"><i class="fa-solid fa-spinner"></i> Phase 5 • Pending</span>
                <div class="wf-ms-title">NOC, Registry & Transfer</div>
                <div class="wf-ms-sub">Final EMI Settlement, Extra Construction, NOC & Deed</div>
            </div>

            <div class="wf-ms-step upcoming" onclick="jumpToStatusFilter('upcoming')">
                <span class="wf-ms-badge"><i class="fa-solid fa-rocket"></i> Phase 6 • Coming Soon</span>
                <div class="wf-ms-title">Payment Gateway & DSC</div>
                <div class="wf-ms-sub">Online UPI/NetBanking, Live E-Lottery Draw & e-Sign</div>
            </div>
        </div>
    </div>
</section>

<!-- ======================================================================= -->
<!-- 2. INTERACTIVE LIGHT-THEME HTML5 CANVAS DRAWFLOW SECTION                -->
<!-- ======================================================================= -->
<section id="interactive-flow" class="section-padding bg-subtle-pattern" style="padding-bottom: 3rem;">
    <div class="container">
        <div class="section-title-wrapper" style="margin-bottom: 2.25rem;">
            <span class="section-subtitle" style="color: var(--primary-dark);">Interactive Visual Workflow Map</span>
            <h2 class="section-title">Visual Project Flow: Completed, <br>Pending & Upcoming Work</h2>
            <p style="color: var(--text-muted); max-width: 720px; margin: 0.75rem auto 0; font-size: 0.98rem;">
                Switch between the tabs below to view where our work has reached so far (<strong style="color:#166534;">Completed ✓</strong>), what is currently being finalized (<strong style="color:#b45309;">Pending ⏳</strong>), and what is planned next (<strong style="color:#1e40af;">Coming Soon 🚀</strong>).
            </p>
        </div>

        <div class="wf-canvas-wrapper">
            <!-- Canvas Top Toolbar -->
            <div class="wf-canvas-toolbar">
                <div class="wf-tab-group">
                    <button type="button" class="wf-canvas-tab active" onclick="setCanvasFlow('roadmap', this)">
                        <i class="fa-solid fa-flag-checkered"></i> 1. Full Project Progress Flow (Done → Pending → Coming Soon)
                    </button>
                    <button type="button" class="wf-canvas-tab" onclick="setCanvasFlow('allottee', this)">
                        <i class="fa-solid fa-user-plus"></i> 2. Allottee & Mail Flow (Completed)
                    </button>
                    <button type="button" class="wf-canvas-tab" onclick="setCanvasFlow('office', this)">
                        <i class="fa-solid fa-file-signature"></i> 3. Official File Review Flow (Completed)
                    </button>
                    <button type="button" class="wf-canvas-tab" onclick="setCanvasFlow('pending_upcoming', this)">
                        <i class="fa-solid fa-clock"></i> 4. Pending & Coming Soon Flow
                    </button>
                </div>

                <div class="wf-canvas-actions">
                    <button type="button" class="wf-tool-btn" onclick="zoomLightCanvas(1.15)" title="Zoom In">
                        <i class="fa-solid fa-magnifying-glass-plus"></i>
                    </button>
                    <button type="button" class="wf-tool-btn" onclick="zoomLightCanvas(0.85)" title="Zoom Out">
                        <i class="fa-solid fa-magnifying-glass-minus"></i>
                    </button>
                    <button type="button" class="wf-tool-btn" onclick="fitCanvasToScreen()">
                        <i class="fa-solid fa-expand"></i> Fit Screen
                    </button>
                    <button type="button" class="wf-tool-btn" onclick="saveLightCanvasPng()">
                        <i class="fa-solid fa-download"></i> Save Flow Image
                    </button>
                </div>
            </div>

            <!-- Color Status Legend Bar -->
            <div class="wf-canvas-legend-bar">
                <div class="wf-legend-items">
                    <span class="wf-legend-pill">
                        <span class="wf-legend-badge done">✓ COMPLETED</span> Work Finished & Live on Portal
                    </span>
                    <span class="wf-legend-pill">
                        <span class="wf-legend-badge pending">⏳ PENDING</span> Work Currently in Progress
                    </span>
                    <span class="wf-legend-pill">
                        <span class="wf-legend-badge upcoming">🚀 COMING SOON</span> Upcoming Phase
                    </span>
                </div>
                <span style="color: var(--text-muted); font-size: 0.78rem;">
                    <i class="fa-solid fa-hand-pointer"></i> Click any box on the canvas to read full details below
                </span>
            </div>

            <!-- Interactive HTML5 Canvas Stage -->
            <div class="wf-canvas-stage" id="lightCanvasContainer">
                <canvas id="jshbLightCanvas"></canvas>
            </div>

            <!-- Bottom Selected Step Explanation Bar -->
            <div class="wf-canvas-infobar">
                <div class="wf-infobar-left">
                    <div class="wf-infobar-icon" id="canvasInfoIcon">
                        <i class="fa-solid fa-circle-info"></i>
                    </div>
                    <div>
                        <div class="wf-infobar-title" id="canvasInfoTitle">PHASE 01: Core Admin, Schemes & Security Setup</div>
                        <div class="wf-infobar-desc" id="canvasInfoDesc">
                            All foundational setup including Divisions, Sub-Divisions, Property Categories, Housing Schemes, Super-Admin Staff Onboarding, and 1-Hour / 24-Hour Login Security is 100% completed.
                        </div>
                    </div>
                </div>
                <div>
                    <span class="wf-grade-pill" id="canvasInfoStatusPill" style="background: #dcfce7; color: #166534; border-color: #86efac;">
                        <i class="fa-solid fa-circle-check"></i> Status: Completed & Live
                    </span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ======================================================================= -->
<!-- 3. WORK REPORT CARDS — COMPLETED, PENDING & COMING SOON                 -->
<!-- ======================================================================= -->
<section id="work-report-cards" class="section-padding bg-subtle-pattern" style="padding-top: 2rem;">
    <div class="container">
        <div class="section-title-wrapper" style="margin-bottom: 2.25rem;">
            <span class="section-subtitle" style="color: var(--primary-dark);">Detailed Work Status Registry</span>
            <h2 class="section-title">Completed Work, Pending Tasks & <br>Upcoming Roadmap</h2>
        </div>

        <!-- Big 4 Status Filter Cards -->
        <div class="wf-status-tabs">
            <button type="button" class="wf-status-tab-btn all-tab active" data-status="all" onclick="filterByStatus('all', this)">
                <div class="wf-st-left">
                    <div class="wf-st-icon"><i class="fa-solid fa-layer-group"></i></div>
                    <div>
                        <span class="wf-st-title">All Project Work</span>
                        <span class="wf-st-sub">Complete Scope</span>
                    </div>
                </div>
                <span class="wf-st-count" id="tabCountAll">27</span>
            </button>

            <button type="button" class="wf-status-tab-btn done-tab" data-status="completed" onclick="filterByStatus('completed', this)">
                <div class="wf-st-left">
                    <div class="wf-st-icon"><i class="fa-solid fa-circle-check"></i></div>
                    <div>
                        <span class="wf-st-title">Completed Work</span>
                        <span class="wf-st-sub">100% Done & Live</span>
                    </div>
                </div>
                <span class="wf-st-count" id="tabCountCompleted" style="background:#dcfce7; color:#166534;">17</span>
            </button>

            <button type="button" class="wf-status-tab-btn pending-tab" data-status="pending" onclick="filterByStatus('pending', this)">
                <div class="wf-st-left">
                    <div class="wf-st-icon"><i class="fa-solid fa-clock-rotate-left"></i></div>
                    <div>
                        <span class="wf-st-title">Pending Work</span>
                        <span class="wf-st-sub">Currently In Progress</span>
                    </div>
                </div>
                <span class="wf-st-count" id="tabCountPending" style="background:#fef3c7; color:#92400e;">5</span>
            </button>

            <button type="button" class="wf-status-tab-btn upcoming-tab" data-status="upcoming" onclick="filterByStatus('upcoming', this)">
                <div class="wf-st-left">
                    <div class="wf-st-icon"><i class="fa-solid fa-rocket"></i></div>
                    <div>
                        <span class="wf-st-title">Coming Soon</span>
                        <span class="wf-st-sub">Upcoming Next Phase</span>
                    </div>
                </div>
                <span class="wf-st-count" id="tabCountUpcoming" style="background:#dbeafe; color:#1e40af;">5</span>
            </button>
        </div>

        <!-- Portal Filter & Search Bar -->
        <div class="wf-filter-bar">
            <div class="wf-portal-pills">
                <button type="button" class="wf-pill-btn active" onclick="filterModuleCards('all', this)">
                    <i class="fa-solid fa-border-all"></i> All Portals
                </button>
                <button type="button" class="wf-pill-btn" onclick="filterModuleCards('admin_portal', this)">
                    <i class="fa-solid fa-user-shield"></i> 1. Admin & System Portal
                </button>
                <button type="button" class="wf-pill-btn maroon-pill" onclick="filterModuleCards('office_portal', this)">
                    <i class="fa-solid fa-building-columns"></i> 2. Official Office Portal
                </button>
                <button type="button" class="wf-pill-btn" onclick="filterModuleCards('post_allotment', this)">
                    <i class="fa-solid fa-house-circle-check"></i> 3. Post-Allotment & Citizen Services
                </button>
            </div>

            <div style="display: flex; align-items: center; gap: 0.65rem; flex-wrap: wrap; width: auto;">
                <div class="wf-search-wrap">
                    <i class="fa-solid fa-magnifying-glass wf-search-icon"></i>
                    <input type="text" id="wfSearchInput" class="wf-search-input" placeholder="Search completed, pending, or upcoming work..." oninput="searchModuleCards(this.value)">
                </div>
                <button type="button" class="btn-primary" style="padding: 0.65rem 1.15rem; font-size: 0.82rem; border-radius: 9999px; cursor: pointer;" onclick="openAddTaskModal()">
                    <i class="fa-solid fa-plus"></i> Add Work Item
                </button>
            </div>
        </div>

        <!-- Dynamic Cards Grid (Populated from JSON) -->
        <div class="wf-modules-grid" id="wfModulesGrid"></div>
    </div>
</section>

<!-- ======================================================================= -->
<!-- 4. MAROON HIGHLIGHT SECTION — ALLOTTEE WORKFLOW SUMMARY                 -->
<!-- ======================================================================= -->
<section class="wf-maroon-section">
    <div class="container">
        <div style="max-width: 740px;">
            <span class="live-badge" style="background: rgba(255,255,255,0.12); color: var(--primary); border-color: rgba(250, 204, 21, 0.35);">
                <i class="fa-solid fa-star"></i> Core Completed Workflow Highlight
            </span>
            <h2 class="section-title" style="color: #ffffff; margin-bottom: 1rem;">
                How Allottee Onboarding, Mail Drop & Auto-Application Work
            </h2>
            <p style="color: #f1f5f9; font-size: 1.05rem; line-height: 1.65;">
                The moment an allottee is added or synced from the Citizen Portal, the system automatically creates their login account, sends the Welcome & Confidential Credentials emails, and opens the Allotment Letter application file for the division officer.
            </p>
        </div>

        <div class="wf-maroon-steps">
            <div class="wf-maroon-card">
                <span class="wf-step-num">Stage 01 • Completed</span>
                <h4>Add Allottee & Second Sync</h4>
                <p>
                    Onboard applicants through the <strong>4-Step Manual Form</strong> (Lottery Receipt, Personal KYC, Address, Review) or use <strong>1-Click Second Sync</strong> to bring online Citizen Portal applicants directly into live records.
                </p>
            </div>

            <div class="wf-maroon-card">
                <span class="wf-step-num">Stage 02 • Completed</span>
                <h4>Automatic Allotment File</h4>
                <p>
                    Without filling any extra form, the system automatically creates the <strong>Allotment Letter Application</strong>, attaches the Lottery Payment receipt, and forwards the file to the Division's Dealing Assistant.
                </p>
            </div>

            <div class="wf-maroon-card">
                <span class="wf-step-num">Stage 03 • Completed</span>
                <h4>Welcome & Confidential Mails</h4>
                <p>
                    Automatically sends the <strong>Welcome Email</strong>, <strong>Confidential Login ID & Password Mail</strong> (to Allottee + Admin/Super-Admin copy), and <strong>File Verification Alert</strong> to the assigned officer.
                </p>
            </div>

            <div class="wf-maroon-card">
                <span class="wf-step-num">Stage 04 • Completed</span>
                <h4>Allottee List & Payment Modal</h4>
                <p>
                    Expand any allottee row to view 4 info cards, live Audit Trail, and the <strong>Dynamic Payment History Modal</strong> that automatically switches between <strong>One-Time Receipts</strong> and <strong>Monthly EMI Schedules</strong>.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- ======================================================================= -->
<!-- 5. SYSTEM DESIGN SCORECARD & JSON DATA EXPORT SECTION                   -->
<!-- ======================================================================= -->
<section class="section-padding bg-soft-emerald">
    <div class="container">
        <div class="section-title-wrapper" style="margin-bottom: 3rem;">
            <span class="section-subtitle" style="color: var(--primary-dark);">System Design Rating: 8.7 / 10</span>
            <h2 class="section-title light">Architectural Scorecard & <br>Work Report JSON Data</h2>
        </div>

        <div class="wf-score-grid" id="wfScoreGrid"></div>

        <div style="text-align: center; margin-top: 3rem; display: flex; justify-content: center; flex-wrap: wrap; gap: 1rem;">
            <button type="button" class="btn-primary" style="padding: 0.9rem 2rem; font-size: 0.95rem; cursor: pointer;" onclick="toggleJsonCollapse()">
                <i class="fa-solid fa-code"></i> View / Hide Full Work Report JSON
            </button>
            <button type="button" class="btn-maroon" style="padding: 0.9rem 2rem; font-size: 0.95rem; cursor: pointer;" onclick="downloadWorkflowJson()">
                <i class="fa-solid fa-download"></i> Download Work Report (.json)
            </button>
        </div>

        <div class="wf-json-collapse" id="wfJsonCollapse">
            <div style="padding: 1rem 1.5rem; background: var(--secondary); color: #ffffff; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem;">
                <strong><i class="fa-solid fa-database" style="color: var(--primary); margin-right: 6px;"></i> Master Work Report JSON (Completed, Pending & Upcoming)</strong>
                <button type="button" onclick="copyWorkflowJson()" class="btn-primary" style="padding: 0.4rem 1rem; font-size: 0.8rem; cursor: pointer;">
                    <i class="fa-regular fa-copy"></i> Copy JSON
                </button>
            </div>
            <pre class="wf-json-pre" id="wfJsonPre"></pre>
        </div>
    </div>
</section>

<!-- ======================================================================= -->
<!-- QUICK ADD WORK ITEM MODAL (ADD CUSTOM PENDING / UPCOMING / DONE ITEM)   -->
<!-- ======================================================================= -->
<div class="wf-modal-backdrop" id="addTaskModal">
    <div class="wf-modal-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
            <h3 style="font-family: 'Outfit', sans-serif; font-size: 1.35rem; font-weight: 800; color: var(--secondary); margin: 0;">
                Add Work Report Item
            </h3>
            <button type="button" onclick="closeAddTaskModal()" style="background: none; border: none; font-size: 1.25rem; color: #64748b; cursor: pointer;">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form onsubmit="saveCustomWorkItem(event)">
            <div class="wf-form-group">
                <label>Work Status</label>
                <select id="newWorkStatus" class="wf-form-control" required>
                    <option value="pending">⏳ Pending / Currently In Progress</option>
                    <option value="upcoming">🚀 Coming Soon / Upcoming Phase</option>
                    <option value="completed">✓ Completed & Live</option>
                </select>
            </div>

            <div class="wf-form-group">
                <label>Portal / Category</label>
                <select id="newWorkPortal" class="wf-form-control" required>
                    <option value="post_allotment">3. Post-Allotment & Citizen Services</option>
                    <option value="admin_portal">1. Admin & System Portal</option>
                    <option value="office_portal">2. Official Office Portal</option>
                </select>
            </div>

            <div class="wf-form-group">
                <label>Module / Work Title</label>
                <input type="text" id="newWorkTitle" class="wf-form-control" placeholder="e.g., Online Water & Maintenance Bill Module" required>
            </div>

            <div class="wf-form-group">
                <label>Short Explanation</label>
                <input type="text" id="newWorkSummary" class="wf-form-control" placeholder="Brief summary of this work module..." required>
            </div>

            <div class="wf-form-group">
                <label>Key Tasks / Features (One per line: Title - Description)</label>
                <textarea id="newWorkFeatures" class="wf-form-control" rows="3" placeholder="Bill Generation - Monthly auto bill calculation&#10;Online Receipt - Instant PDF download" required></textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 1.5rem;">
                <button type="button" class="wf-tool-btn" onclick="closeAddTaskModal()">Cancel</button>
                <button type="submit" class="btn-primary" style="padding: 0.6rem 1.5rem; border-radius: 9999px; cursor: pointer;">
                    Save to Work Report
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ======================================================================= -->
<!-- MASTER JSON DATASET (COMPLETED, PENDING & UPCOMING WORK + FLOWS)        -->
<!-- ======================================================================= -->
<script id="jshbMasterWorkflowJson" type="application/json">
{
  "project_meta": {
    "project_name": "Jharkhand State Housing Board (JSHB) — Digital Governance & Workflow Platform",
    "overall_progress_percent": 78,
    "completed_modules": 17,
    "pending_modules": 5,
    "upcoming_modules": 5,
    "system_design_rating": {
      "overall_score": 8.7,
      "max_score": 10.0,
      "grade": "Grade A — Enterprise Ready",
      "pillars": [
        { "name": "Dual-Database Isolation (Allottees vs Office DB)", "score": 9.2, "note": "Separates public citizen traffic from internal officer workflow tables." },
        { "name": "Dual Onboarding (Manual Form + 1-Click Portal Sync)", "score": 9.0, "note": "Supports both 4-Step manual entry and instant online staging migration." },
        { "name": "Automated Workflow & 23-Step Blueprint Engine", "score": 9.0, "note": "Auto-applies Allotment Letter file and routes to Division Officer." },
        { "name": "Audit Trail, File Noting & Real-Time Pusher Sync", "score": 9.0, "note": "Tracks every file movement, Hindi/English noting, and live image uploads." },
        { "name": "Financial Ledger & Dynamic EMI / One-Time Engine", "score": 8.8, "note": "Maintains running ledger balance, EMI schedules, and dynamic modals." },
        { "name": "Multi-Channel Mail Drop & Notification Router", "score": 8.5, "note": "Dispatches Welcome Mail, Confidential Credentials, SMS, and Officer Alerts." }
      ]
    }
  },
  "modules": [
    {
      "id": "allottee_management",
      "status": "completed",
      "progress": 100,
      "portal": "admin_portal",
      "portal_label": "1. Admin Portal",
      "title": "Allottee Management",
      "icon": "fa-solid fa-user-check",
      "summary": "Complete lifecycle management of housing board allottees, onboarding, second sync, automated mail drop, and payment tracking.",
      "features": [
        { "name": "Allottee List", "detail": "Complete registry of allottees with 4-card expandable 360° profile, finance, and application status." },
        { "name": "Payment History Modal", "detail": "Dynamic modals that automatically switch between One-Time Payment receipts and Monthly EMI schedules." },
        { "name": "Add Allottee & Second Sync", "detail": "4-step form to onboard new allottees plus 1-Click Citizen Portal Second Sync." },
        { "name": "Welcome Mail, Credentials & Auto-Apply", "detail": "Automatic Welcome Mail, Confidential Login Credentials, and Auto-Apply of Allotment Letter Application." },
        { "name": "Cancellations", "detail": "Overdue cancellation tracking & management." },
        { "name": "Document Management", "detail": "Smart uploads integrated with production servers." }
      ]
    },
    {
      "id": "member_management",
      "status": "completed",
      "progress": 100,
      "portal": "admin_portal",
      "portal_label": "1. Admin Portal",
      "title": "Member Management (Super Admin)",
      "icon": "fa-solid fa-users-gear",
      "summary": "Super Admin exclusive module to onboard and manage internal JSHB officials and division staff.",
      "features": [
        { "name": "Manage Members", "detail": "Complete directory of internal staff, JSHB Officials, and division members." },
        { "name": "Add Member", "detail": "Role-based onboarding for internal staff mapped to divisions and sub-divisions." }
      ]
    },
    {
      "id": "notifications_comm",
      "status": "completed",
      "progress": 100,
      "portal": "admin_portal",
      "portal_label": "1. Admin Portal",
      "title": "Notifications & Communication",
      "icon": "fa-solid fa-bell",
      "summary": "Centralized broadcast and alert engine across Email, SMS, WhatsApp, and in-portal inboxes.",
      "features": [
        { "name": "Notify JSHB Officials", "detail": "Direct targeted alerts to field JSHB Officials." },
        { "name": "Notices", "detail": "Public & internal announcements broadcast module." },
        { "name": "Communication Settings", "detail": "Manage Email/SMS gateways, role toggles & templates." },
        { "name": "My Notifications", "detail": "Personal notification inbox for every logged-in user." }
      ]
    },
    {
      "id": "workflow_management",
      "status": "completed",
      "progress": 100,
      "portal": "admin_portal",
      "portal_label": "1. Admin Portal",
      "title": "Workflow Management",
      "icon": "fa-solid fa-diagram-project",
      "summary": "Configurable multi-step approval pipelines with encrypted routing for all application types.",
      "features": [
        { "name": "Workflow List", "detail": "Master list of active application and approval workflows." },
        { "name": "Dynamic Routing", "detail": "Encrypted route model binding for secure access." },
        { "name": "Add/Edit Workflow", "detail": "Dynamic JS builder to define custom steps (role, action type, requirements)." }
      ]
    },
    {
      "id": "bypass_requests",
      "status": "completed",
      "progress": 100,
      "portal": "admin_portal",
      "portal_label": "1. Admin Portal",
      "title": "Bypass Requests",
      "icon": "fa-solid fa-forward-fast",
      "summary": "Supervised workflow step-skipping mechanism with real-time badge counters and audit history.",
      "features": [
        { "name": "Pending Requests", "detail": "Approval interface with dynamic counter badge (auto-approves after 24 hrs)." },
        { "name": "Bypass History", "detail": "Complete audit log of previously processed bypass requests." }
      ]
    },
    {
      "id": "master_components",
      "status": "completed",
      "progress": 100,
      "portal": "admin_portal",
      "portal_label": "1. Admin Portal",
      "title": "Components & Master Data",
      "icon": "fa-solid fa-city",
      "summary": "Core organizational hierarchy and property classification master setup.",
      "features": [
        { "name": "Division", "detail": "List & add structural housing board divisions." },
        { "name": "Sub Division", "detail": "List & add regional sub-divisions." },
        { "name": "Categories", "detail": "Property categories, property types & sub-types." },
        { "name": "Quarter Type", "detail": "Setup & manage quarter layouts (EWS, LIG, MIG, HIG)." }
      ]
    },
    {
      "id": "schemes_management",
      "status": "completed",
      "progress": 100,
      "portal": "admin_portal",
      "portal_label": "1. Admin Portal",
      "title": "Schemes Management",
      "icon": "fa-solid fa-helmet-safety",
      "summary": "End-to-end housing scheme setup, unit limits, and financial calculation parameters.",
      "features": [
        { "name": "Scheme List", "detail": "Detailed view of all JSHB housing schemes." },
        { "name": "Add/Edit Scheme", "detail": "Multi-step creation (location, units, property limits)." },
        { "name": "Financial Tracking", "detail": "Deposit, EMI, EMI-with-penalty & lottery info saves accurately." }
      ]
    },
    {
      "id": "system_security",
      "status": "completed",
      "progress": 100,
      "portal": "admin_portal",
      "portal_label": "1. Admin Portal",
      "title": "System & Security",
      "icon": "fa-solid fa-shield-halved",
      "summary": "Enterprise security controls, quick privacy lock, and two-tier brute-force login protection.",
      "features": [
        { "name": "Change Password", "detail": "Internal password & login password reset modals." },
        { "name": "Quick PIN Setup", "detail": "Fast-access PIN system for instant verification." },
        { "name": "Lock Screen", "detail": "Quick privacy lock feature when stepping away." },
        { "name": "1-Hour Lockout Rule", "detail": "5 failed login attempts automatically block the account for 1 hour." },
        { "name": "24-Hour Escalated Block", "detail": "After unblocking, another 5 failed attempts result in a 24-hour block against brute-force attacks." }
      ]
    },
    {
      "id": "dashboards_rbac",
      "status": "completed",
      "progress": 100,
      "portal": "admin_portal",
      "portal_label": "1. Admin Portal",
      "title": "Dashboard & Role-Based Access",
      "icon": "fa-solid fa-gauge-high",
      "summary": "Tailored role-specific workspaces for Admin, 6 Official Engineering/Estate cadres, and Allottees.",
      "features": [
        { "name": "Admin Dashboard", "detail": "Dedicated command center for Admin & Super-Admin." },
        { "name": "JSHB Officials Dashboard", "detail": "Role portals for Executive Engineer, Dealing Assistant, Office Superintendent, Estate Officer, Assistant Engineer & Junior Engineer." },
        { "name": "Allottee / User Dashboard", "detail": "Dedicated citizen portal for property owners and applicants." }
      ]
    },
    {
      "id": "workflow_actions",
      "status": "completed",
      "progress": 100,
      "portal": "office_portal",
      "portal_label": "2. Office Portal",
      "title": "Workflow-Based Actions",
      "icon": "fa-solid fa-sliders",
      "summary": "Official file movement, inspection, and approval actions inside the JSHB Office Portal.",
      "features": [
        { "name": "Forward", "detail": "Pass application to the next role in workflow." },
        { "name": "Send Back", "detail": "Return application to a previous step for corrections." },
        { "name": "Reject", "detail": "Terminate process with mandatory rejection remarks." },
        { "name": "Approve", "detail": "Final approval at the last workflow step." },
        { "name": "Site Verification", "detail": "Integrated field inspection form when mandated." },
        { "name": "Upload Document", "detail": "Attach internal processing documents and reports." },
        { "name": "Verify & Upload", "detail": "Verify, sign & upload finalized copies (e.g. Allotment Letter)." },
        { "name": "View Workflow", "detail": "Visual roadmap of the application's current stage." }
      ]
    },
    {
      "id": "application_details",
      "status": "completed",
      "progress": 100,
      "portal": "office_portal",
      "portal_label": "2. Office Portal",
      "title": "Application Details",
      "icon": "fa-solid fa-clipboard-list",
      "summary": "Comprehensive file header displaying type, status, SLA priority, and timeline metrics.",
      "features": [
        { "name": "Application Type", "detail": "Supports Allotment, Agreement, and Possession applications." },
        { "name": "Current Status", "detail": "Real-time status: Pending, Forwarded, Approved, Rejected." },
        { "name": "Priority Level", "detail": "Color-coded priority: Normal, Urgent, Overdue." },
        { "name": "Timeline Tracking", "detail": "Tracks Date Created vs Expected Completion date." }
      ]
    },
    {
      "id": "allottee_info_office",
      "status": "completed",
      "progress": 100,
      "portal": "office_portal",
      "portal_label": "2. Office Portal",
      "title": "Allottee Information",
      "icon": "fa-solid fa-id-card",
      "summary": "Bilingual applicant identity and property details panel for reviewing officers.",
      "features": [
        { "name": "Applicant Name (English & Hindi)", "detail": "Supports both English & Hindi (KrutiDev) fonts." },
        { "name": "Property & Allotment Details", "detail": "Displays property number, username, and contact info." },
        { "name": "Deep Dive ('Show More' Modal)", "detail": "Interactive modal for extended property and category data." }
      ]
    },
    {
      "id": "doc_verification",
      "status": "completed",
      "progress": 100,
      "portal": "office_portal",
      "portal_label": "2. Office Portal",
      "title": "Document Management & Verification",
      "icon": "fa-solid fa-folder-open",
      "summary": "Central document repository with live in-app preview, checklist mapping, and re-upload requests.",
      "features": [
        { "name": "Attached Documents", "detail": "Central repository of files from both allottee & staff." },
        { "name": "Live Preview", "detail": "Secure in-app document viewer for PDFs and images." },
        { "name": "Document Status Checklist", "detail": "Auto-mapped required vs uploaded documents checklist." },
        { "name": "Request Additional Document", "detail": "Raise formal requests to allottees for missing/rejected files." }
      ]
    },
    {
      "id": "office_correspondences",
      "status": "completed",
      "progress": 100,
      "portal": "office_portal",
      "portal_label": "2. Office Portal",
      "title": "Office Correspondences",
      "icon": "fa-solid fa-envelope-open-text",
      "summary": "Official communication generator for Letters, Office Orders, and Drafts.",
      "features": [
        { "name": "Letter (LT)", "detail": "Formal outward and inward official letters." },
        { "name": "Office Order (OO)", "detail": "Numbered administrative office orders." },
        { "name": "Office Draft (OD)", "detail": "Internal drafts for approval." },
        { "name": "Full Publishing Lifecycle", "detail": "Drafting, editing & publishing supported for all correspondence types." }
      ]
    },
    {
      "id": "notes_remarks",
      "status": "completed",
      "progress": 100,
      "portal": "office_portal",
      "portal_label": "2. Office Portal",
      "title": "Notes & Remarks (File Noting)",
      "icon": "fa-solid fa-pen-nib",
      "summary": "Digital green-sheet noting with Hindi support, PDF export, stamp management, and Pusher live sync.",
      "features": [
        { "name": "Internal Noting System", "detail": "Rich-text remarks with English & Hindi typing support." },
        { "name": "PDF Export", "detail": "Notes exported/printed as standard official PDF sheets." },
        { "name": "Asset Management", "detail": "Manage, upload, update, and reuse official signatures, stamps, and assets." },
        { "name": "Live Image Upload (Pusher)", "detail": "Upload images in real time during noting using Pusher." },
        { "name": "Real-Time Synchronization", "detail": "Uploaded images and noting updates appear instantly without a page refresh." }
      ]
    },
    {
      "id": "movement_history",
      "status": "completed",
      "progress": 100,
      "portal": "office_portal",
      "portal_label": "2. Office Portal",
      "title": "Movement History (Audit Trail)",
      "icon": "fa-solid fa-clock-rotate-left",
      "summary": "Complete chronological log of every file movement across roles and officers.",
      "features": [
        { "name": "Transition Date & Time", "detail": "Tracks every file transition with exact date & time." },
        { "name": "Actor Tracking", "detail": "Identifies who moved the file (Transferred From → Transferred To)." },
        { "name": "Action & Remarks Log", "detail": "Logs the exact action taken and official remarks provided." }
      ]
    },
    {
      "id": "system_alerts",
      "status": "completed",
      "progress": 100,
      "portal": "office_portal",
      "portal_label": "2. Office Portal",
      "title": "System Alerts",
      "icon": "fa-solid fa-triangle-exclamation",
      "summary": "High-visibility status banners inside the file view for bypass approvals and prior rejections.",
      "features": [
        { "name": "Bypass Requests Alert", "detail": "Highlights pending junior-official bypass approvals." },
        { "name": "Rejection Banner", "detail": "Displays prior rejection status & the exact rejection reason." }
      ]
    },
    {
      "id": "pending_noc_settlement",
      "status": "pending",
      "progress": 65,
      "portal": "post_allotment",
      "portal_label": "3. Post-Allotment",
      "title": "NOC & Final EMI Settlement Workflow",
      "icon": "fa-solid fa-file-circle-check",
      "summary": "Final payment reconciliation and No Objection Certificate (NOC) generation after full payment or EMI completion.",
      "features": [
        { "name": "One-Time Payment NOC Clearance", "detail": "Auto-verifies 100% payment receipt before unlocking the NOC certificate step." },
        { "name": "Final EMI Calculation & Closure", "detail": "Computes total principal, interest, and late penalty balance prior to NOC issuance." },
        { "name": "Officer NOC Approval & Upload", "detail": "Routes NOC file through Dealing Assistant and Estate Officer for final signed delivery." }
      ]
    },
    {
      "id": "pending_extra_construction",
      "status": "pending",
      "progress": 55,
      "portal": "post_allotment",
      "portal_label": "3. Post-Allotment",
      "title": "Extra Construction & Penalty Assessment",
      "icon": "fa-solid fa-ruler-combined",
      "summary": "Field engineering inspection and regularization fee calculation for additional construction on allotted units.",
      "features": [
        { "name": "Site Measurement & Inspection Report", "detail": "Junior Engineer records plot dimensions, extra built-up area, and site photos." },
        { "name": "Extra Construction Charge Calculation", "detail": "Adds applicable regularization fee or deviation penalty directly to the allottee ledger." },
        { "name": "Dues Clearance Link", "detail": "Ensures extra construction dues are settled before Registry NOC is approved." }
      ]
    },
    {
      "id": "pending_registry_deed",
      "status": "pending",
      "progress": 50,
      "portal": "post_allotment",
      "portal_label": "3. Post-Allotment",
      "title": "Property Registry & Lease Deed Execution",
      "icon": "fa-solid fa-stamp",
      "summary": "Documentation request, stamp duty verification, and digital archiving of the final Property Registry Deed.",
      "features": [
        { "name": "Request for Registry Documentation", "detail": "Collects stamp papers, witness KYC, and final affidavit documents from the allottee." },
        { "name": "Upload & Verify Registry Deed", "detail": "Official verification and permanent digital archiving of executed Registry Deed." },
        { "name": "Registry NOC Certificate", "detail": "Generates final Registry NOC required for sub-registrar property execution." }
      ]
    },
    {
      "id": "pending_name_transfer",
      "status": "pending",
      "progress": 45,
      "portal": "post_allotment",
      "portal_label": "3. Post-Allotment",
      "title": "Property Name Transfer & Mutation",
      "icon": "fa-solid fa-people-arrows",
      "summary": "Workflow to transfer property ownership to legal heirs, family members, or approved transferees.",
      "features": [
        { "name": "Legal Heir & Succession Verification", "detail": "Checklist for death certificate, succession certificate, family tree, and indemnity bond." },
        { "name": "Transfer Fee & No-Dues Check", "detail": "Verifies zero pending EMI arrears and collects the official transfer fee." },
        { "name": "Ownership Record Update", "detail": "Updates the new allottee name on the property card while preserving historical records." }
      ]
    },
    {
      "id": "pending_freehold_conversion",
      "status": "pending",
      "progress": 40,
      "portal": "post_allotment",
      "portal_label": "3. Post-Allotment",
      "title": "Leasehold to Freehold Conversion",
      "icon": "fa-solid fa-building-lock",
      "summary": "End-to-end processing for allottees applying to convert leasehold housing units into freehold ownership.",
      "features": [
        { "name": "Lease Tenure & Eligibility Check", "detail": "Verifies minimum lease duration compliance and full payment clearance." },
        { "name": "Conversion Charge Demand Note", "detail": "Calculates freehold conversion charges and raises online demand notice." },
        { "name": "Freehold Sanction & Certificate", "detail": "Multi-officer approval workflow and final Freehold Certificate upload." }
      ]
    },
    {
      "id": "upcoming_payment_gateway",
      "status": "upcoming",
      "progress": 15,
      "portal": "post_allotment",
      "portal_label": "3. Post-Allotment",
      "title": "Live Online Payment Gateway Integration",
      "icon": "fa-solid fa-credit-card",
      "summary": "Direct online payment collection for monthly EMIs, demand orders, and processing fees via UPI, NetBanking, and Cards.",
      "features": [
        { "name": "UPI, QR, NetBanking & Card Checkout", "detail": "Seamless 1-click online payment directly from the Allottee Portal." },
        { "name": "Auto-Reconciliation with Payment Modal", "detail": "Automatically updates EMI Paid count and ledger balance upon bank webhook confirmation." },
        { "name": "Instant Digital Payment Receipt", "detail": "Generates downloadable PDF receipt with bank transaction reference number." }
      ]
    },
    {
      "id": "upcoming_elottery_engine",
      "status": "upcoming",
      "progress": 10,
      "portal": "admin_portal",
      "portal_label": "1. Admin Portal",
      "title": "Automated E-Lottery Live Draw Engine",
      "icon": "fa-solid fa-ticket",
      "summary": "Computerized randomized lottery draw for new housing schemes with instant winner and waiting list publication.",
      "features": [
        { "name": "Category & Quota Randomized Draw", "detail": "Automated draw across EWS, LIG, MIG, HIG, and reserved categories." },
        { "name": "Live Public Result Board", "detail": "Publishes selected winners and waiting list ranks directly on the public portal." },
        { "name": "Direct Winner-to-Allottee Push", "detail": "1-click migration of lottery winners into the Allottee Onboarding & Mail Drop workflow." }
      ]
    },
    {
      "id": "upcoming_whatsapp_sms",
      "status": "upcoming",
      "progress": 15,
      "portal": "admin_portal",
      "portal_label": "1. Admin Portal",
      "title": "WhatsApp Business Bot & EMI Auto-Reminders",
      "icon": "fa-brands fa-whatsapp",
      "summary": "Automated monthly EMI reminder alerts and PDF document delivery over official WhatsApp Business API and SMS.",
      "features": [
        { "name": "Automated Monthly EMI Due Alerts", "detail": "Sends scheduled WhatsApp & SMS reminders 7 days before monthly EMI due date." },
        { "name": "Allotment Letter & Receipt on WhatsApp", "detail": "Delivers approved PDF letters and payment receipts directly to the allottee's phone." },
        { "name": "Citizen Status Inquiry Bot", "detail": "Allows applicants to check live file status via WhatsApp message." }
      ]
    },
    {
      "id": "upcoming_dsc_esign",
      "status": "upcoming",
      "progress": 10,
      "portal": "office_portal",
      "portal_label": "2. Office Portal",
      "title": "Digital Signature (DSC / Aadhaar e-Sign)",
      "icon": "fa-solid fa-file-signature",
      "summary": "Cryptographic digital signing of Allotment Letters, NOCs, and Office Orders using USB DSC tokens or e-Sign.",
      "features": [
        { "name": "1-Click USB DSC Token Signing", "detail": "Enables Executive Engineers and Estate Officers to digitally sign PDFs inside the portal." },
        { "name": "QR-Code Verifiable Certificates", "detail": "Embeds tamper-proof verification QR codes on every issued Allotment Letter and NOC." },
        { "name": "Paperless Approval Output", "detail": "Eliminates manual printing, physical signing, and re-scanning of official letters." }
      ]
    },
    {
      "id": "upcoming_executive_analytics",
      "status": "upcoming",
      "progress": 10,
      "portal": "admin_portal",
      "portal_label": "1. Admin Portal",
      "title": "Executive Revenue Analytics & SLA Escalation",
      "icon": "fa-solid fa-chart-pie",
      "summary": "High-level visual reports for Board leadership covering division revenue, EMI arrears, and automatic delay escalations.",
      "features": [
        { "name": "Division-Wise Collection vs Arrears Chart", "detail": "Live breakdown of monthly EMI revenue collected vs overdue defaulters." },
        { "name": "Automatic Overdue File Escalation", "detail": "Highlights applications exceeding expected SLA days to higher authorities." },
        { "name": "Public Grievance Resolution Tracker", "detail": "Tracks citizen complaints and maintenance tickets from submission to closure." }
      ]
    }
  ],
  "canvas_flows": {
    "roadmap": {
      "nodes": [
        { "id": "r1", "status": "completed", "badge": "PHASE 01", "title": "Core Admin, Schemes & Security", "sub": "Divisions, Schemes, Staff RBAC & 1Hr/24Hr Lockout", "color": "#16a34a", "desc": "COMPLETED (100%): Master setup of Divisions, Sub-Divisions, Property Categories, Quarter Types, Housing Schemes, Super-Admin Member Onboarding, and 5-Attempt 1Hr/24Hr Brute-Force Protection." },
        { "id": "r2", "status": "completed", "badge": "PHASE 02", "title": "Allottee Onboarding & Mail Drop", "sub": "4-Step Form + 1-Click Sync + Welcome/Login Mails", "color": "#16a34a", "desc": "COMPLETED (100%): Onboarding via 4-Step Wizard or 1-Click Citizen Portal Second Sync, automatic Property/Allotment No generation, Welcome Mail + Confidential Login Mail, and Auto-Apply Allotment File." },
        { "id": "r3", "status": "completed", "badge": "PHASE 03", "title": "Official File Review & Noting", "sub": "Forward/Approve + Hindi Noting + Pusher Live Sync", "color": "#16a34a", "desc": "COMPLETED (100%): JSHB Official Office Portal with 8 Workflow Actions, KrutiDev Hindi support, In-App PDF Viewer, Letters (LT/OO/OD), Digital Green-Sheet Noting with Stamp Reuse & Pusher Live Images, and Audit Trail." },
        { "id": "r4", "status": "completed", "badge": "PHASE 04", "title": "360° Allottee List & Payment Modal", "sub": "Expandable Profile + Dynamic One-Time & EMI Modal", "color": "#16a34a", "desc": "COMPLETED (100%): Complete Allottee Registry with 4 expandable cards, 4 tracking modals, Overdue Cancellation management, and Dynamic Payment History Modal for One-Time Receipts & Monthly EMI Schedules." },
        { "id": "r5", "status": "pending", "badge": "PHASE 05", "title": "NOC, Registry & Name Transfer", "sub": "Final EMI Settlement, Extra Construction & Deed", "color": "#d97706", "desc": "PENDING / IN PROGRESS (55%): Post-possession lifecycle steps including Final EMI Calculation, Extra Construction Assessment, NOC Generation, Registry Deed Execution, Name Transfer, and Freehold Conversion." },
        { "id": "r6", "status": "upcoming", "badge": "PHASE 06", "title": "Payment Gateway, E-Lottery & DSC", "sub": "Online UPI/NetBanking, Live Draw & e-Sign Token", "color": "#2563eb", "desc": "COMING SOON (NEXT PHASE): Live Online Payment Gateway (UPI/NetBanking/Cards) with auto-reconciliation, Automated E-Lottery Draw Engine, WhatsApp EMI Reminders, and USB Digital Signature (DSC) integration." }
      ],
      "edges": [
        { "from": 0, "to": 1, "label": "100% Done" },
        { "from": 1, "to": 2, "label": "100% Done" },
        { "from": 2, "to": 3, "label": "100% Done" },
        { "from": 3, "to": 4, "label": "Now Working ⏳" },
        { "from": 4, "to": 5, "label": "Next Phase 🚀" }
      ]
    },
    "allottee": {
      "nodes": [
        { "id": "a1", "status": "completed", "badge": "STEP 01", "title": "Add Allottee / Portal Sync", "sub": "4-Step Office Form OR 1-Click Citizen Sync", "color": "#1e3a8a", "desc": "COMPLETED: New allottees are onboarded either manually via the 4-Step Wizard (Property/Lottery -> Personal KYC -> Address -> Submit) or migrated in 1-Click from the online Citizen Portal waiting list." },
        { "id": "a2", "status": "completed", "badge": "STEP 02", "title": "Account & Ledger Setup", "sub": "Unique Property No + Allotment No + Ledger", "color": "#1e3a8a", "desc": "COMPLETED: The system automatically generates a unique Property Number (English & Hindi), official Allotment Number, Portal Login ID/Password, and records the Lottery Payment in the financial ledger." },
        { "id": "a3", "status": "completed", "badge": "STEP 03", "title": "Auto-Apply Allotment File", "sub": "Zero-Touch Application + Officer Routing", "color": "#7f1d1d", "desc": "COMPLETED: Without any manual form, the system automatically creates the Allotment Letter Application (ALT-...), attaches the Lottery Receipt, and forwards it to the Division's Dealing Assistant." },
        { "id": "a4", "status": "completed", "badge": "STEP 04", "title": "Automated Mail Drop", "sub": "Welcome Mail + Confidential ID/Pass + Alert", "color": "#047857", "desc": "COMPLETED: Drops 4 automated messages: (1) Welcome Email to Allottee, (2) Confidential Login Credentials to Allottee, (3) Credentials Copy to Admin/Super-Admin, and (4) Verification Alert to the Officer." },
        { "id": "a5", "status": "completed", "badge": "STEP 05", "title": "Allottee List (Complete Info)", "sub": "360° Expandable Cards & 4 Tracking Modals", "color": "#1e3a8a", "desc": "COMPLETED: Admin manages all allottees in one place with expandable Profile, Personal KYC, Asset/Finance, and Application Tracking cards (Audit Trail, Comm Track, Correspondence, Bypass)." },
        { "id": "a6", "status": "completed", "badge": "STEP 06", "title": "Payment History Modal", "sub": "Dynamic One-Time Receipt & EMI Schedule", "color": "#0f766e", "desc": "COMPLETED: Clicking 'Payment History' opens a smart modal that automatically displays either One-Time Payment Receipts or the Monthly EMI Schedule (Paid X/Y, Principal, Interest, Penalty, Balance)." }
      ],
      "edges": [
        { "from": 0, "to": 1, "label": "Submit / Sync" },
        { "from": 1, "to": 2, "label": "Auto Trigger" },
        { "from": 1, "to": 3, "label": "Event Dispatch" },
        { "from": 2, "to": 4, "label": "Live Status" },
        { "from": 3, "to": 4, "label": "Logged in Track" },
        { "from": 4, "to": 5, "label": "1-Click Popup" }
      ]
    },
    "office": {
      "nodes": [
        { "id": "o1", "status": "completed", "badge": "STAGE 01", "title": "File Arrives at Officer Desk", "sub": "Dealing Assistant / Junior Engineer Inbox", "color": "#1e3a8a", "desc": "COMPLETED: The auto-created application appears on the assigned Division/Sub-Division Officer's dashboard with SLA Due Date and Priority Level (Normal, Urgent, Overdue)." },
        { "id": "o2", "status": "completed", "badge": "STAGE 02", "title": "Document & KYC Verification", "sub": "In-App PDF Viewer + Checklist + Re-Request", "color": "#1e3a8a", "desc": "COMPLETED: Officers inspect uploaded PDFs/images in the Live Preview viewer, verify the required document checklist, or raise a 'Request Additional Document' alert to the allottee." },
        { "id": "o3", "status": "completed", "badge": "STAGE 03", "title": "Digital File Noting (Pusher)", "sub": "Hindi/English Notes + Stamps + Live Images", "color": "#047857", "desc": "COMPLETED: Officers write official green-sheet noting in English/Hindi, attach reusable digital signatures/stamps, and upload live images that sync across screens in real time via Pusher." },
        { "id": "o4", "status": "completed", "badge": "STAGE 04", "title": "Site Verification & Letters", "sub": "Inspection Map + Letters (LT) / Orders (OO)", "color": "#7f1d1d", "desc": "COMPLETED: When required, field engineers complete the Site Verification report & map, and draft/publish official Letters (LT), Office Orders (OO), or Drafts (OD)." },
        { "id": "o5", "status": "completed", "badge": "STAGE 05", "title": "Workflow Action (OTP Secured)", "sub": "Forward / Send Back / Bypass / Reject", "color": "#1e3a8a", "desc": "COMPLETED: Protected by OTP verification, the officer Forwards the file to the next cadre (AE -> EE -> Estate Officer -> OS -> MD), Sends Back for correction, or requests a Hierarchy Bypass." },
        { "id": "o6", "status": "completed", "badge": "STAGE 06", "title": "Final Approval & Signed Copy", "sub": "Internal Password + Signed Letter Upload", "color": "#0f766e", "desc": "COMPLETED: Upon final approval (protected by Internal Password) and signed Allotment Letter upload, the 15% Demand Order is auto-generated and the citizen is notified." }
      ],
      "edges": [
        { "from": 0, "to": 1, "label": "Open File" },
        { "from": 1, "to": 2, "label": "Checklist OK" },
        { "from": 2, "to": 3, "label": "Add Noting" },
        { "from": 3, "to": 4, "label": "Verify OTP" },
        { "from": 4, "to": 5, "label": "Final Sanction" }
      ]
    },
    "pending_upcoming": {
      "nodes": [
        { "id": "p1", "status": "pending", "badge": "PENDING 01", "title": "Extra Construction & Final Calc", "sub": "Site Measurement + Final EMI Settlement", "color": "#d97706", "desc": "PENDING / IN PROGRESS (60%): Field inspection for extra construction charges and final EMI principal/interest/penalty settlement prior to NOC." },
        { "id": "p2", "status": "pending", "badge": "PENDING 02", "title": "NOC Certificate Issuance", "sub": "One-Time & EMI Full Clearance NOC", "color": "#d97706", "desc": "PENDING / IN PROGRESS (65%): Automated generation and division-officer approval of the No Objection Certificate (NOC) once all dues are cleared." },
        { "id": "p3", "status": "pending", "badge": "PENDING 03", "title": "Registry Deed & Name Transfer", "sub": "Lease Deed Upload + Mutation + Freehold", "color": "#d97706", "desc": "PENDING / IN PROGRESS (45%): Registry documentation collection, Lease Deed execution, Property Name Transfer (Legal Heir/Sale), and Leasehold-to-Freehold conversion." },
        { "id": "u1", "status": "upcoming", "badge": "COMING 01", "title": "Online Payment Gateway Live", "sub": "UPI / QR / NetBanking / Card Auto-Sync", "color": "#2563eb", "desc": "COMING SOON: Direct online payment checkout for monthly EMIs and fees with automatic ledger and Payment History Modal reconciliation." },
        { "id": "u2", "status": "upcoming", "badge": "COMING 02", "title": "Automated E-Lottery Live Draw", "sub": "Quota Draw Engine + 1-Click Winner Sync", "color": "#2563eb", "desc": "COMING SOON: Computerized randomized lottery draw for housing schemes with instant public result board and 1-click winner onboarding." },
        { "id": "u3", "status": "upcoming", "badge": "COMING 03", "title": "WhatsApp API & DSC e-Sign", "sub": "Auto EMI Reminders + USB Token Signing", "color": "#2563eb", "desc": "COMING SOON: Automated WhatsApp/SMS monthly EMI due reminders and cryptographic USB Digital Signature (DSC / Aadhaar e-Sign) for officers." }
      ],
      "edges": [
        { "from": 0, "to": 1, "label": "Dues Cleared" },
        { "from": 1, "to": 2, "label": "NOC Approved" },
        { "from": 2, "to": 3, "label": "Next Phase 🚀" },
        { "from": 3, "to": 4, "label": "Gateway Ready" },
        { "from": 4, "to": 5, "label": "Full Automation" }
      ]
    }
  }
}
</script>

@endsection

@push('scripts')
<script>
    const jshbData = JSON.parse(document.getElementById('jshbMasterWorkflowJson').textContent);

    // Load any custom items added by user in localStorage
    const savedCustomItems = localStorage.getItem('jshb_custom_work_items');
    if (savedCustomItems) {
        try {
            const parsed = JSON.parse(savedCustomItems);
            if (Array.isArray(parsed)) {
                jshbData.modules.push(...parsed);
            }
        } catch (e) {}
    }

    // =========================================================================
    // 1. RENDER MODULE CARDS GRID, STATUS COUNTERS & SCORECARD FROM JSON
    // =========================================================================
    let currentStatusFilter = 'all';
    let currentPortalFilter = 'all';
    let currentSearchQuery = '';

    function updateStatusCounters() {
        const completedCount = jshbData.modules.filter(m => m.status === 'completed').length;
        const pendingCount   = jshbData.modules.filter(m => m.status === 'pending').length;
        const upcomingCount  = jshbData.modules.filter(m => m.status === 'upcoming').length;
        const totalCount     = jshbData.modules.length;

        document.getElementById('tabCountAll').textContent       = totalCount;
        document.getElementById('tabCountCompleted').textContent = completedCount;
        document.getElementById('tabCountPending').textContent   = pendingCount;
        document.getElementById('tabCountUpcoming').textContent  = upcomingCount;

        document.getElementById('heroCountCompleted').textContent = `${completedCount} Modules`;
        document.getElementById('heroCountPending').textContent   = `${pendingCount} Modules`;
        document.getElementById('heroCountUpcoming').textContent  = `${upcomingCount} Modules`;
        document.getElementById('heroCountTotal').textContent     = `${totalCount} Modules`;
    }

    function renderModulesGrid() {
        const grid = document.getElementById('wfModulesGrid');
        if (!grid) return;

        updateStatusCounters();

        const filtered = jshbData.modules.filter(m => {
            const matchStatus = (currentStatusFilter === 'all') || (m.status === currentStatusFilter);
            const matchPortal = (currentPortalFilter === 'all') || (m.portal === currentPortalFilter);
            const q = currentSearchQuery.trim().toLowerCase();
            const matchSearch = !q ||
                m.title.toLowerCase().includes(q) ||
                m.summary.toLowerCase().includes(q) ||
                m.features.some(f => f.name.toLowerCase().includes(q) || f.detail.toLowerCase().includes(q));
            return matchStatus && matchPortal && matchSearch;
        });

        if (filtered.length === 0) {
            grid.innerHTML = `
                <div style="grid-column: 1 / -1; text-align: center; padding: 3rem; background: #fff; border-radius: 1.5rem; border: 1px dashed #cbd5e1;">
                    <i class="fa-solid fa-magnifying-glass" style="font-size: 2rem; color: #94a3b8; margin-bottom: 0.75rem;"></i>
                    <h3 style="color: var(--secondary); font-size: 1.2rem;">No matching work items found</h3>
                    <p style="color: var(--text-muted); font-size: 0.9rem;">Try selecting "All Project Work" or clearing your search box.</p>
                </div>
            `;
            return;
        }

        grid.innerHTML = filtered.map(mod => {
            const iconClass = mod.icon_alt || mod.icon || 'fa-solid fa-cube';
            const status = mod.status || 'completed';
            const progress = mod.progress ?? (status === 'completed' ? 100 : (status === 'pending' ? 50 : 15));

            let statusPillHtml = '';
            let featureIconClass = 'fa-solid fa-circle-check icon-completed';
            let progressLabel = '100% Completed & Live';

            if (status === 'completed') {
                statusPillHtml = `<span class="wf-status-pill completed"><i class="fa-solid fa-check"></i> Completed</span>`;
                featureIconClass = 'fa-solid fa-circle-check icon-completed';
                progressLabel = '100% Completed & Live';
            } else if (status === 'pending') {
                statusPillHtml = `<span class="wf-status-pill pending"><i class="fa-solid fa-clock"></i> Pending / In Progress</span>`;
                featureIconClass = 'fa-solid fa-spinner icon-pending';
                progressLabel = `${progress}% Work In Progress`;
            } else {
                statusPillHtml = `<span class="wf-status-pill upcoming"><i class="fa-solid fa-rocket"></i> Coming Soon</span>`;
                featureIconClass = 'fa-solid fa-calendar-plus icon-upcoming';
                progressLabel = 'Planned for Next Phase';
            }

            const featuresHtml = mod.features.map(f => `
                <li class="wf-feature-item">
                    <i class="${featureIconClass}"></i>
                    <div><strong>${f.name}</strong> — ${f.detail}</div>
                </li>
            `).join('');

            return `
                <div class="wf-module-card status-${status}">
                    <div class="wf-card-top">
                        <div class="wf-icon-box">
                            <i class="${iconClass}"></i>
                        </div>
                        <div class="wf-card-badges">
                            ${statusPillHtml}
                            <span class="wf-portal-tag">${mod.portal_label} • ${mod.features.length} Items</span>
                        </div>
                    </div>
                    <h3>${mod.title}</h3>
                    <p class="wf-module-summary">${mod.summary}</p>

                    <div class="wf-card-progress-wrap">
                        <div class="wf-card-progress-head">
                            <span>Work Status</span>
                            <span>${progressLabel}</span>
                        </div>
                        <div class="wf-card-progress-bar">
                            <div class="wf-card-progress-fill ${status}" style="width: ${progress}%;"></div>
                        </div>
                    </div>

                    <ul class="wf-feature-list">
                        ${featuresHtml}
                    </ul>
                </div>
            `;
        }).join('');
    }

    function filterByStatus(status, btn) {
        document.querySelectorAll('.wf-status-tab-btn').forEach(b => b.classList.remove('active'));
        if (btn) btn.classList.add('active');
        currentStatusFilter = status;
        renderModulesGrid();
    }

    function jumpToStatusFilter(status) {
        const targetBtn = document.querySelector(`.wf-status-tab-btn[data-status="${status}"]`);
        filterByStatus(status, targetBtn);
        const section = document.getElementById('work-report-cards');
        if (section) {
            section.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }

    function filterModuleCards(portal, btn) {
        document.querySelectorAll('.wf-pill-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        currentPortalFilter = portal;
        renderModulesGrid();
    }

    function searchModuleCards(val) {
        currentSearchQuery = val;
        renderModulesGrid();
    }

    function renderScorecard() {
        const scoreGrid = document.getElementById('wfScoreGrid');
        if (!scoreGrid) return;

        scoreGrid.innerHTML = jshbData.project_meta.system_design_rating.pillars.map(p => {
            const pct = Math.round((p.score / 10) * 100);
            return `
                <div class="wf-score-card">
                    <div class="wf-score-head">
                        <span class="wf-score-name">${p.name}</span>
                        <span class="wf-score-badge">${p.score} / 10</span>
                    </div>
                    <div class="wf-progress-track">
                        <div class="wf-progress-fill" style="width: ${pct}%;"></div>
                    </div>
                    <p style="font-size: 0.85rem; color: var(--text-muted); line-height: 1.5; margin: 0;">${p.note}</p>
                </div>
            `;
        }).join('');

        const jsonPre = document.getElementById('wfJsonPre');
        if (jsonPre) {
            jsonPre.textContent = JSON.stringify(jshbData, null, 2);
        }
    }

    // Quick Add Work Item Modal Functions
    function openAddTaskModal() {
        document.getElementById('addTaskModal').classList.add('open');
    }

    function closeAddTaskModal() {
        document.getElementById('addTaskModal').classList.remove('open');
    }

    function saveCustomWorkItem(e) {
        e.preventDefault();
        const status = document.getElementById('newWorkStatus').value;
        const portal = document.getElementById('newWorkPortal').value;
        const title = document.getElementById('newWorkTitle').value.trim();
        const summary = document.getElementById('newWorkSummary').value.trim();
        const rawFeatures = document.getElementById('newWorkFeatures').value.trim();

        const portalLabels = {
            'admin_portal': '1. Admin Portal',
            'office_portal': '2. Office Portal',
            'post_allotment': '3. Post-Allotment'
        };

        const features = rawFeatures.split('\n').filter(Boolean).map(line => {
            const parts = line.split('-');
            if (parts.length > 1) {
                return { name: parts[0].trim(), detail: parts.slice(1).join('-').trim() };
            }
            return { name: line.trim(), detail: 'Scheduled in project work report.' };
        });

        const newItem = {
            id: 'custom_' + Date.now(),
            status: status,
            progress: status === 'completed' ? 100 : (status === 'pending' ? 50 : 15),
            portal: portal,
            portal_label: portalLabels[portal] || '3. Post-Allotment',
            title: title,
            icon: status === 'completed' ? 'fa-solid fa-circle-check' : (status === 'pending' ? 'fa-solid fa-clock' : 'fa-solid fa-rocket'),
            summary: summary,
            features: features
        };

        jshbData.modules.push(newItem);

        // Persist in localStorage
        const existing = JSON.parse(localStorage.getItem('jshb_custom_work_items') || '[]');
        existing.push(newItem);
        localStorage.setItem('jshb_custom_work_items', JSON.stringify(existing));

        closeAddTaskModal();
        jumpToStatusFilter(status);
        renderScorecard();
    }

    function toggleJsonCollapse() {
        const el = document.getElementById('wfJsonCollapse');
        el.classList.toggle('open');
    }

    function downloadWorkflowJson() {
        const blob = new Blob([JSON.stringify(jshbData, null, 2)], { type: 'application/json' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'jshb-project-work-report.json';
        a.click();
        URL.revokeObjectURL(url);
    }

    function copyWorkflowJson() {
        navigator.clipboard.writeText(JSON.stringify(jshbData, null, 2));
        alert('Work Report JSON copied to clipboard!');
    }

    // =========================================================================
    // 2. LIGHT-THEME RESPONSIVE HTML5 CANVAS DRAWFLOW ENGINE
    // =========================================================================
    const canvas = document.getElementById('jshbLightCanvas');
    const ctx = canvas.getContext('2d');
    let activeFlowKey = 'roadmap';
    let cam = { x: 0, y: 0, zoom: 1 };
    let isPanning = false;
    let panOrigin = { x: 0, y: 0 };
    let computedNodes = [];
    let computedEdges = [];
    let selectedStepIdx = 0;
    let hoveredStepIdx = -1;
    let flowParticles = [];

    function setupLightCanvas() {
        const container = document.getElementById('lightCanvasContainer');
        const dpr = window.devicePixelRatio || 1;
        canvas.width = container.clientWidth * dpr;
        canvas.height = container.clientHeight * dpr;
        ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
        layoutFlowNodes();
        fitCanvasToScreen();
    }

    function layoutFlowNodes() {
        const flow = jshbData.canvas_flows[activeFlowKey];
        const isMobileLayout = canvas.clientWidth < 768;

        computedNodes = [];
        computedEdges = flow.edges;
        flowParticles = [];

        if (isMobileLayout) {
            const cardW = 300;
            const cardH = 92;
            const startX = 180;
            const startY = 65;
            const gapY = 115;

            flow.nodes.forEach((n, idx) => {
                computedNodes.push({
                    ...n,
                    idx: idx,
                    x: startX,
                    y: startY + idx * gapY,
                    w: cardW,
                    h: cardH
                });
            });
        } else {
            const cardW = 310;
            const cardH = 108;

            const desktopPositions = activeFlowKey === 'allottee'
                ? [
                    { x: 200, y: 265 },
                    { x: 540, y: 265 },
                    { x: 880, y: 155 },
                    { x: 880, y: 375 },
                    { x: 1220, y: 155 },
                    { x: 1220, y: 375 }
                ]
                : [
                    { x: 210, y: 165 },
                    { x: 585, y: 165 },
                    { x: 960, y: 165 },
                    { x: 960, y: 365 },
                    { x: 585, y: 365 },
                    { x: 210, y: 365 }
                ];

            flow.nodes.forEach((n, idx) => {
                const pos = desktopPositions[idx] || { x: 210 + (idx % 3) * 375, y: 165 + Math.floor(idx / 3) * 200 };
                computedNodes.push({
                    ...n,
                    idx: idx,
                    x: pos.x,
                    y: pos.y,
                    w: cardW,
                    h: cardH
                });
            });
        }

        computedEdges.forEach(e => {
            flowParticles.push({
                fromIdx: e.from,
                toIdx: e.to,
                t: Math.random(),
                speed: 0.0045
            });
        });
    }

    function fitCanvasToScreen() {
        if (computedNodes.length === 0) return;
        const cw = canvas.clientWidth;
        const ch = canvas.clientHeight;

        let minX = Infinity, maxX = -Infinity, minY = Infinity, maxY = -Infinity;
        computedNodes.forEach(n => {
            minX = Math.min(minX, n.x - n.w / 2);
            maxX = Math.max(maxX, n.x + n.w / 2);
            minY = Math.min(minY, n.y - n.h / 2);
            maxY = Math.max(maxY, n.y + n.h / 2);
        });

        const graphW = (maxX - minX) + 90;
        const graphH = (maxY - minY) + 90;

        cam.zoom = Math.min(cw / graphW, ch / graphH, 1.05);
        cam.zoom = Math.max(cam.zoom, 0.55);

        const centerX = (minX + maxX) / 2;
        const centerY = (minY + maxY) / 2;

        cam.x = cw / 2 - centerX * cam.zoom;
        cam.y = ch / 2 - centerY * cam.zoom;
    }

    function setCanvasFlow(flowKey, btn) {
        document.querySelectorAll('.wf-canvas-tab').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        activeFlowKey = flowKey;
        selectedStepIdx = 0;
        layoutFlowNodes();
        fitCanvasToScreen();
        updateInfoBar(computedNodes[0]);
    }

    function updateInfoBar(node) {
        if (!node) return;
        document.getElementById('canvasInfoTitle').textContent = `${node.badge}: ${node.title}`;
        document.getElementById('canvasInfoDesc').textContent = node.desc;

        const pill = document.getElementById('canvasInfoStatusPill');
        if (!pill) return;

        if (node.status === 'pending') {
            pill.style.background = '#fef3c7';
            pill.style.color = '#92400e';
            pill.style.borderColor = '#fde68a';
            pill.innerHTML = '<i class="fa-solid fa-clock"></i> Status: Pending / In Progress';
        } else if (node.status === 'upcoming') {
            pill.style.background = '#dbeafe';
            pill.style.color = '#1e40af';
            pill.style.borderColor = '#93c5fd';
            pill.innerHTML = '<i class="fa-solid fa-rocket"></i> Status: Coming Soon (Next Phase)';
        } else {
            pill.style.background = '#dcfce7';
            pill.style.color = '#166534';
            pill.style.borderColor = '#86efac';
            pill.innerHTML = '<i class="fa-solid fa-circle-check"></i> Status: Completed & Live';
        }
    }

    function drawLightCanvas() {
        const w = canvas.clientWidth;
        const h = canvas.clientHeight;
        ctx.clearRect(0, 0, w, h);

        // 1. Light Subtle Navy Dotted Grid Background
        const dotSpacing = 24 * cam.zoom;
        if (dotSpacing > 10) {
            ctx.fillStyle = 'rgba(30, 58, 138, 0.10)';
            const ox = cam.x % dotSpacing;
            const oy = cam.y % dotSpacing;
            for (let x = ox; x < w; x += dotSpacing) {
                for (let y = oy; y < h; y += dotSpacing) {
                    ctx.beginPath();
                    ctx.arc(x, y, 1.2, 0, Math.PI * 2);
                    ctx.fill();
                }
            }
        }

        ctx.save();
        ctx.translate(cam.x, cam.y);
        ctx.scale(cam.zoom, cam.zoom);

        // 2. Draw Smooth Connector Curves
        computedEdges.forEach(e => {
            const n1 = computedNodes[e.from];
            const n2 = computedNodes[e.to];
            if (!n1 || !n2) return;

            ctx.save();
            ctx.beginPath();
            const cp1x = (n1.x + n2.x) / 2;
            const cp1y = n1.y;
            const cp2x = (n1.x + n2.x) / 2;
            const cp2y = n2.y;

            ctx.moveTo(n1.x, n1.y);
            ctx.bezierCurveTo(cp1x, cp1y, cp2x, cp2y, n2.x, n2.y);

            if (n2.status === 'pending') {
                ctx.strokeStyle = '#f59e0b';
            } else if (n2.status === 'upcoming') {
                ctx.strokeStyle = '#3b82f6';
            } else {
                ctx.strokeStyle = '#16a34a';
            }

            ctx.lineWidth = 2.5;
            ctx.setLineDash([6, 4]);
            ctx.stroke();
            ctx.setLineDash([]);

            // Connector Pill Label
            if (e.label) {
                const mx = (n1.x + n2.x) / 2;
                const my = (n1.y + n2.y) / 2;
                ctx.font = '700 10.5px Inter, sans-serif';
                const tw = ctx.measureText(e.label).width + 18;

                if (n2.status === 'pending') {
                    ctx.fillStyle = '#fef3c7';
                } else if (n2.status === 'upcoming') {
                    ctx.fillStyle = '#dbeafe';
                } else {
                    ctx.fillStyle = '#dcfce7';
                }

                drawRoundedRect(ctx, mx - tw / 2, my - 10, tw, 20, 10);
                ctx.fill();

                ctx.fillStyle = n2.status === 'pending' ? '#92400e' : (n2.status === 'upcoming' ? '#1e40af' : '#166534');
                ctx.textAlign = 'center';
                ctx.fillText(e.label, mx, my + 4);
            }
            ctx.restore();
        });

        // 3. Animated Particles along Connectors
        flowParticles.forEach(p => {
            p.t += p.speed;
            if (p.t > 1) p.t = 0;
            const n1 = computedNodes[p.fromIdx];
            const n2 = computedNodes[p.toIdx];
            if (!n1 || !n2) return;

            const pt = cubicBezierPoint(
                n1.x, n1.y,
                (n1.x + n2.x) / 2, n1.y,
                (n1.x + n2.x) / 2, n2.y,
                n2.x, n2.y,
                p.t
            );

            ctx.save();
            ctx.beginPath();
            ctx.arc(pt.x, pt.y, 5, 0, Math.PI * 2);
            ctx.fillStyle = n2.status === 'pending' ? '#d97706' : (n2.status === 'upcoming' ? '#2563eb' : '#16a34a');
            ctx.fill();
            ctx.lineWidth = 2;
            ctx.strokeStyle = '#ffffff';
            ctx.stroke();
            ctx.restore();
        });

        // 4. Draw Clean White Elevated Feature Nodes with Status Pill
        computedNodes.forEach(node => {
            const x = node.x - node.w / 2;
            const y = node.y - node.h / 2;
            const isSelected = node.idx === selectedStepIdx;
            const isHovered = node.idx === hoveredStepIdx;

            ctx.save();

            // Card Soft Shadow
            ctx.shadowColor = isSelected
                ? 'rgba(30, 58, 138, 0.22)'
                : 'rgba(15, 23, 42, 0.08)';
            ctx.shadowBlur = isSelected ? 24 : 14;
            ctx.shadowOffsetY = 6;

            // Card White Background
            ctx.fillStyle = '#ffffff';
            drawRoundedRect(ctx, x, y, node.w, node.h, 16);
            ctx.fill();

            ctx.shadowBlur = 0;
            ctx.shadowOffsetY = 0;

            // Top Coloured Header Strip (Green = Done, Amber = Pending, Blue = Upcoming)
            const stripColor = node.status === 'pending'
                ? '#f59e0b'
                : (node.status === 'upcoming' ? '#2563eb' : (node.color || '#16a34a'));

            ctx.fillStyle = stripColor;
            drawRoundedRect(ctx, x, y, node.w, 8, { tl: 16, tr: 16, br: 0, bl: 0 });
            ctx.fill();

            // Card Border
            ctx.lineWidth = isSelected ? 2.5 : (isHovered ? 2 : 1.2);
            ctx.strokeStyle = isSelected ? stripColor : (isHovered ? '#64748b' : '#cbd5e1');
            drawRoundedRect(ctx, x, y, node.w, node.h, 16);
            ctx.stroke();

            // Step Badge Pill (Left)
            ctx.fillStyle = isSelected ? '#facc15' : '#f1f5f9';
            drawRoundedRect(ctx, x + 14, y + 17, 76, 22, 11);
            ctx.fill();

            ctx.font = '800 10px Inter, sans-serif';
            ctx.fillStyle = '#1e3a8a';
            ctx.textAlign = 'left';
            ctx.fillText(node.badge, x + 23, y + 31);

            // Status Badge Pill on Top Right (COMPLETED / PENDING / COMING SOON)
            let statusText = '✓ COMPLETED';
            let statusBg = '#dcfce7';
            let statusFg = '#15803d';

            if (node.status === 'pending') {
                statusText = '⏳ PENDING';
                statusBg = '#fef3c7';
                statusFg = '#b45309';
            } else if (node.status === 'upcoming') {
                statusText = '🚀 COMING SOON';
                statusBg = '#dbeafe';
                statusFg = '#1d4ed8';
            }

            ctx.font = '800 9.5px Inter, sans-serif';
            const stW = ctx.measureText(statusText).width + 16;
            ctx.fillStyle = statusBg;
            drawRoundedRect(ctx, x + node.w - stW - 14, y + 17, stW, 22, 11);
            ctx.fill();

            ctx.fillStyle = statusFg;
            ctx.textAlign = 'center';
            ctx.fillText(statusText, x + node.w - stW / 2 - 14, y + 31);

            // Node Title (Navy Blue)
            ctx.textAlign = 'left';
            ctx.font = '800 14px Outfit, Inter, sans-serif';
            ctx.fillStyle = '#1e3a8a';
            ctx.fillText(node.title, x + 14, y + 62);

            // Node Subtitle (Slate Muted)
            ctx.font = '500 11.5px Inter, sans-serif';
            ctx.fillStyle = '#475569';
            ctx.fillText(node.sub, x + 14, y + 83);

            ctx.restore();
        });

        ctx.restore();
        requestAnimationFrame(drawLightCanvas);
    }

    function drawRoundedRect(ctx, x, y, w, h, r) {
        if (typeof r === 'number') {
            r = { tl: r, tr: r, br: r, bl: r };
        }
        ctx.beginPath();
        ctx.moveTo(x + r.tl, y);
        ctx.lineTo(x + w - r.tr, y);
        ctx.quadraticCurveTo(x + w, y, x + w, y + r.tr);
        ctx.lineTo(x + w, y + h - r.br);
        ctx.quadraticCurveTo(x + w, y + h, x + w - r.br, y + h);
        ctx.lineTo(x + r.bl, y + h);
        ctx.quadraticCurveTo(x, y + h, x, y + h - r.bl);
        ctx.lineTo(x, y + r.tl);
        ctx.quadraticCurveTo(x, y, x + r.tl, y);
        ctx.closePath();
    }

    function cubicBezierPoint(x0, y0, x1, y1, x2, y2, x3, y3, t) {
        const cx = 3 * (x1 - x0);
        const bx = 3 * (x2 - x1) - cx;
        const ax = x3 - x0 - cx - bx;
        const cy = 3 * (y1 - y0);
        const by = 3 * (y2 - y1) - cy;
        const ay = y3 - y0 - cy - by;
        return {
            x: ax * t * t * t + bx * t * t + cx * t + x0,
            y: ay * t * t * t + by * t * t + cy * t + y0
        };
    }

    function hitTestLightNode(sx, sy) {
        const wx = (sx - cam.x) / cam.zoom;
        const wy = (sy - cam.y) / cam.zoom;
        for (let i = computedNodes.length - 1; i >= 0; i--) {
            const n = computedNodes[i];
            if (wx >= n.x - n.w / 2 && wx <= n.x + n.w / 2 && wy >= n.y - n.h / 2 && wy <= n.y + n.h / 2) {
                return n;
            }
        }
        return null;
    }

    function bindCanvasInteractions() {
        canvas.addEventListener('mousedown', e => {
            const hit = hitTestLightNode(e.offsetX, e.offsetY);
            if (hit) {
                selectedStepIdx = hit.idx;
                updateInfoBar(hit);
                return;
            }
            isPanning = true;
            panOrigin.x = e.clientX - cam.x;
            panOrigin.y = e.clientY - cam.y;
        });

        canvas.addEventListener('mousemove', e => {
            if (isPanning) {
                cam.x = e.clientX - panOrigin.x;
                cam.y = e.clientY - panOrigin.y;
                return;
            }
            const hit = hitTestLightNode(e.offsetX, e.offsetY);
            hoveredStepIdx = hit ? hit.idx : -1;
            canvas.style.cursor = hit ? 'pointer' : 'grab';
        });

        window.addEventListener('mouseup', () => { isPanning = false; });

        // Touch Support for Mobile Responsiveness
        let lastTouch = null;
        canvas.addEventListener('touchstart', e => {
            if (e.touches.length === 1) {
                const rect = canvas.getBoundingClientRect();
                const tx = e.touches[0].clientX - rect.left;
                const ty = e.touches[0].clientY - rect.top;
                const hit = hitTestLightNode(tx, ty);
                if (hit) {
                    selectedStepIdx = hit.idx;
                    updateInfoBar(hit);
                }
                lastTouch = { x: e.touches[0].clientX - cam.x, y: e.touches[0].clientY - cam.y };
            }
        }, { passive: true });

        canvas.addEventListener('touchmove', e => {
            if (e.touches.length === 1 && lastTouch) {
                cam.x = e.touches[0].clientX - lastTouch.x;
                cam.y = e.touches[0].clientY - lastTouch.y;
            }
        }, { passive: true });
    }

    function zoomLightCanvas(factor) {
        const cw = canvas.clientWidth / 2;
        const ch = canvas.clientHeight / 2;
        const wx = (cw - cam.x) / cam.zoom;
        const wy = (ch - cam.y) / cam.zoom;
        cam.zoom = Math.min(1.6, Math.max(0.45, cam.zoom * factor));
        cam.x = cw - wx * cam.zoom;
        cam.y = ch - wy * cam.zoom;
    }

    function saveLightCanvasPng() {
        const a = document.createElement('a');
        a.download = `jshb-${activeFlowKey}-work-report.png`;
        a.href = canvas.toDataURL('image/png');
        a.click();
    }

    document.addEventListener('DOMContentLoaded', () => {
        renderModulesGrid();
        renderScorecard();
        setupLightCanvas();
        bindCanvasInteractions();
        updateInfoBar(computedNodes[0]);
        requestAnimationFrame(drawLightCanvas);

        let resizeTimer;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(setupLightCanvas, 150);
        });
    });
</script>
@endpush
