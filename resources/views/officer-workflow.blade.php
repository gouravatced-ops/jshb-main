@extends('layouts.public')

@section('title', 'Administration & Officer Workflow | Jharkhand State Housing Board')

@push('styles')
<style>
    /* ==========================================================================
       JSHB OFFICIAL LIGHT THEME — 100% JSON-DRIVEN ADMIN & OFFICER WORKFLOW
       Matches landing.blade.php (Navy #1e3a8a, Yellow #facc15, Maroon #7f1d1d, Light Dotted #f1f5f9)
       ========================================================================== */

    .ow-hero {
        position: relative;
        padding-top: 7.5rem;
        padding-bottom: 4.25rem;
        overflow: hidden;
        background: linear-gradient(135deg, var(--secondary-dark) 0%, var(--secondary) 100%);
        color: #ffffff;
    }

    .ow-hero-pattern {
        position: absolute;
        inset: 0;
        opacity: 0.06;
        background-image: radial-gradient(#ffffff 1px, transparent 1px);
        background-size: 32px 32px;
        pointer-events: none;
    }

    .ow-hero-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 2.5rem;
        align-items: center;
        position: relative;
        z-index: 2;
    }

    @media (min-width: 1024px) {
        .ow-hero-grid {
            grid-template-columns: 1.15fr 0.85fr;
            gap: 3rem;
        }
    }

    .ow-hero-title {
        font-family: "Outfit", sans-serif;
        font-size: clamp(1.95rem, 3.8vw, 3.1rem);
        font-weight: 800;
        line-height: 1.15;
        color: #ffffff;
        margin-bottom: 1.1rem;
    }

    .ow-hero-title span {
        color: var(--primary);
    }

    .ow-hero-desc {
        font-size: 1.02rem;
        color: #e2e8f0;
        line-height: 1.65;
        margin-bottom: 1.75rem;
        max-width: 650px;
    }

    .ow-hero-btns {
        display: flex;
        flex-wrap: wrap;
        gap: 0.85rem;
    }

    .ow-summary-card {
        background: #ffffff;
        border-radius: 1.5rem;
        padding: 1.65rem;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        border: 4px solid rgba(255, 255, 255, 0.25);
        color: var(--text-dark);
    }

    .ow-kpi-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.75rem;
    }

    @media (max-width: 480px) {
        .ow-kpi-grid {
            grid-template-columns: 1fr;
        }
    }

    .ow-kpi-box {
        padding: 0.9rem 1rem;
        border-radius: 1rem;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
    }

    .ow-kpi-box.emerald { background: #f0fdf4; border-color: #bbf7d0; }
    .ow-kpi-box.amber   { background: #fffbeb; border-color: #fde68a; }
    .ow-kpi-box.blue    { background: #eff6ff; border-color: #bfdbfe; }
    .ow-kpi-box.rose    { background: #fff1f2; border-color: #fecdd3; }

    .ow-kpi-val {
        font-family: "Outfit", sans-serif;
        font-size: 1.25rem;
        font-weight: 800;
        color: var(--secondary);
    }

    .ow-kpi-lbl {
        font-size: 0.76rem;
        color: var(--text-muted);
        font-weight: 600;
        margin-top: 0.15rem;
    }

    /* Sticky Quick Jump Navigation Bar */
    .ow-quick-nav {
        background: #ffffff;
        border-bottom: 1px solid #e2e8f0;
        padding: 0.85rem 0;
        position: sticky;
        top: 72px;
        z-index: 40;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.04);
    }

    .ow-nav-pills {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        overflow-x: auto;
        padding-bottom: 0.2rem;
        scrollbar-width: thin;
    }

    .ow-nav-pill {
        white-space: nowrap;
        padding: 0.45rem 0.95rem;
        border-radius: 9999px;
        font-size: 0.78rem;
        font-weight: 700;
        background: #f8fafc;
        color: var(--text-muted);
        border: 1px solid #e2e8f0;
        text-decoration: none;
        transition: all 0.2s;
    }

    .ow-nav-pill:hover {
        background: var(--secondary);
        color: #ffffff;
        border-color: var(--secondary);
    }

    /* Interactive HTML5 Canvas Box */
    .ow-canvas-card {
        background: #ffffff;
        border-radius: 1.5rem;
        border: 1px solid #cbd5e1;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .ow-canvas-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 0.85rem;
        padding: 1rem 1.4rem;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
    }

    .ow-tab-group {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .ow-canvas-tab {
        padding: 0.55rem 1rem;
        border-radius: 9999px;
        font-size: 0.8rem;
        font-weight: 700;
        border: 1px solid #cbd5e1;
        background: #ffffff;
        color: var(--text-muted);
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }

    .ow-canvas-tab.active {
        background: var(--secondary);
        color: #ffffff;
        border-color: var(--secondary);
    }

    .ow-canvas-tab.active i {
        color: var(--primary);
    }

    .ow-canvas-stage {
        position: relative;
        width: 100%;
        height: 520px;
        background: #f8fafc;
        overflow: hidden;
        cursor: grab;
    }

    .ow-canvas-stage:active {
        cursor: grabbing;
    }

    @media (max-width: 768px) {
        .ow-canvas-stage {
            height: 720px;
        }
    }

    #officerFlowCanvas {
        width: 100%;
        height: 100%;
        display: block;
    }

    .ow-canvas-infobar {
        padding: 1.15rem 1.5rem;
        background: #ffffff;
        border-top: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
    }

    /* Universal Elevated Cards & Grids */
    .ow-grid-2 {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }

    .ow-grid-3 {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }

    .ow-grid-4 {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.25rem;
    }

    @media (min-width: 768px) {
        .ow-grid-2 { grid-template-columns: repeat(2, 1fr); }
        .ow-grid-3 { grid-template-columns: repeat(2, 1fr); }
        .ow-grid-4 { grid-template-columns: repeat(2, 1fr); }
    }

    @media (min-width: 1100px) {
        .ow-grid-3 { grid-template-columns: repeat(3, 1fr); }
        .ow-grid-4 { grid-template-columns: repeat(4, 1fr); }
    }

    .ow-card {
        background: #ffffff;
        border-radius: 1.35rem;
        padding: 1.65rem;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.04);
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .ow-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 18px 30px -8px rgba(15, 23, 42, 0.08);
    }

    .ow-card-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        margin-bottom: 1rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid #f1f5f9;
    }

    .ow-card-title {
        font-family: "Outfit", sans-serif;
        font-size: 1.15rem;
        font-weight: 800;
        color: var(--secondary);
        display: flex;
        align-items: center;
        gap: 0.6rem;
        margin: 0;
    }

    .ow-icon-badge {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 0.75rem;
        background: rgba(30, 58, 138, 0.08);
        color: var(--secondary);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.05rem;
        flex-shrink: 0;
    }

    .ow-pill {
        font-size: 0.7rem;
        font-weight: 800;
        text-transform: uppercase;
        padding: 0.25rem 0.7rem;
        border-radius: 9999px;
        background: #eff6ff;
        color: #1e3a8a;
        border: 1px solid #bfdbfe;
    }

    .ow-pill.green { background: #dcfce7; color: #166534; border-color: #86efac; }
    .ow-pill.amber { background: #fef3c7; color: #92400e; border-color: #fde68a; }
    .ow-pill.maroon { background: #fef2f2; color: #7f1d1d; border-color: #fecaca; }

    /* Key-Value List inside Cards */
    .ow-kv-list {
        display: flex;
        flex-direction: column;
        gap: 0.55rem;
    }

    .ow-kv-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 0.75rem;
        font-size: 0.85rem;
        padding: 0.45rem 0.65rem;
        border-radius: 0.6rem;
        background: #f8fafc;
    }

    .ow-kv-key {
        color: var(--text-muted);
        font-weight: 600;
    }

    .ow-kv-val {
        color: var(--text-dark);
        font-weight: 700;
        text-align: right;
        word-break: break-word;
    }

    /* Field Tags Grid for Steps */
    .ow-field-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 0.45rem;
        margin-top: 0.65rem;
    }

    .ow-field-tag {
        font-size: 0.76rem;
        font-weight: 600;
        padding: 0.3rem 0.65rem;
        border-radius: 0.5rem;
        background: #f1f5f9;
        color: var(--text-dark);
        border: 1px solid #e2e8f0;
    }

    .ow-field-tag.req {
        background: #fef2f2;
        color: #991b1b;
        border-color: #fecaca;
    }

    /* Responsive Data Table */
    .ow-table-wrap {
        width: 100%;
        overflow-x: auto;
        border-radius: 1rem;
        border: 1px solid #e2e8f0;
    }

    .ow-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.84rem;
        background: #ffffff;
    }

    .ow-table th {
        background: #f8fafc;
        color: var(--secondary);
        font-weight: 800;
        text-align: left;
        padding: 0.85rem 1rem;
        border-bottom: 1px solid #e2e8f0;
        white-space: nowrap;
    }

    .ow-table td {
        padding: 0.85rem 1rem;
        border-bottom: 1px solid #f1f5f9;
        color: var(--text-dark);
        vertical-align: top;
    }

    .ow-table tr:last-child td {
        border-bottom: none;
    }

    /* Green-Sheet Official Noting Card */
    .ow-greensheet {
        background: #f0fdf4;
        border: 1.5px solid #86efac;
        border-left: 6px solid #16a34a;
        border-radius: 1rem;
        padding: 1.25rem;
        margin-bottom: 1rem;
    }

    .ow-greensheet-body {
        font-size: 0.9rem;
        line-height: 1.7;
        color: #064e3b;
        white-space: pre-line;
        background: rgba(255, 255, 255, 0.7);
        padding: 1rem;
        border-radius: 0.75rem;
        border: 1px dashed #86efac;
        margin-top: 0.75rem;
    }

    /* Maroon Section */
    .ow-maroon-section {
        background: #7f1d1d;
        color: #ffffff;
        padding: 5rem 0;
    }

    /* Live JSON Editor Modal */
    .ow-modal-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(4px);
        z-index: 9999;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }

    .ow-modal-backdrop.open {
        display: flex;
    }

    .ow-modal-box {
        background: #ffffff;
        border-radius: 1.5rem;
        max-width: 880px;
        width: 100%;
        padding: 1.75rem;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.3);
        max-height: 90vh;
        display: flex;
        flex-direction: column;
    }

    .ow-json-textarea {
        width: 100%;
        height: 420px;
        font-family: "Consolas", "Monaco", monospace;
        font-size: 0.82rem;
        padding: 1rem;
        border-radius: 0.85rem;
        border: 1px solid #cbd5e1;
        background: #f8fafc;
        color: #0f172a;
        resize: vertical;
        outline: none;
    }
</style>
@endpush

@section('content')

<!-- ======================================================================= -->
<!-- 1. HERO SECTION (100% POPULATED FROM JSON)                              -->
<!-- ======================================================================= -->
<section class="ow-hero">
    <div class="ow-hero-pattern"></div>
    <div class="container ow-hero-grid" id="owHeroContainer"></div>
</section>

<!-- ======================================================================= -->
<!-- STICKY STAGE NAVIGATION BAR                                             -->
<!-- ======================================================================= -->
<div class="ow-quick-nav">
    <div class="container ow-nav-pills">
        <a href="#sec-canvas" class="ow-nav-pill"><i class="fa-solid fa-diagram-project"></i> Visual Flowchart</a>
        <a href="#sec-masters" class="ow-nav-pill">1. Masters & Schemes</a>
        <a href="#sec-onboarding" class="ow-nav-pill">2. 4-Step Create & Sync</a>
        <a href="#sec-allottee-list" class="ow-nav-pill">3. Allottee List Record</a>
        <a href="#sec-workflow-roles" class="ow-nav-pill">4. 4-Role Workflow (DA→EE)</a>
        <a href="#sec-officer-dashboard" class="ow-nav-pill">5. Officer Dashboard</a>
        <a href="#sec-application-view" class="ow-nav-pill">6. Application & Hindi Noting</a>
        <a href="#sec-action-modal" class="ow-nav-pill">7. QR Camera, Bypass & OTP</a>
        <a href="#sec-night-batch" class="ow-nav-pill">8. 01:00 AM Batch & Subdomain</a>
    </div>
</div>

<!-- ======================================================================= -->
<!-- 2. INTERACTIVE HTML5 CANVAS FLOWCHART (POWERED BY JSON)                 -->
<!-- ======================================================================= -->
<section id="sec-canvas" class="section-padding bg-subtle-pattern" style="padding-bottom: 2.5rem;">
    <div class="container">
        <div class="section-title-wrapper" style="margin-bottom: 2rem;">
            <span class="section-subtitle" style="color: var(--primary-dark);">Interactive HTML5 Canvas Visualizer</span>
            <h2 class="section-title">Administration & Officer End-to-End Flow</h2>
            <p style="color: var(--text-muted); max-width: 720px; margin: 0.6rem auto 0; font-size: 0.95rem;">
                Every node and connector below is dynamically drawn from the JSON specification. Click any box on the canvas to inspect its rules.
            </p>
        </div>

        <div class="ow-canvas-card">
            <div class="ow-canvas-toolbar">
                <div class="ow-tab-group" id="owCanvasTabs"></div>
                <div style="display: flex; gap: 0.45rem; flex-wrap: wrap;">
                    <button type="button" class="ow-canvas-tab" onclick="zoomOfficerCanvas(1.15)"><i class="fa-solid fa-magnifying-glass-plus"></i></button>
                    <button type="button" class="ow-canvas-tab" onclick="zoomOfficerCanvas(0.85)"><i class="fa-solid fa-magnifying-glass-minus"></i></button>
                    <button type="button" class="ow-canvas-tab" onclick="fitOfficerCanvas()"><i class="fa-solid fa-expand"></i> Fit</button>
                    <button type="button" class="ow-canvas-tab" onclick="downloadOfficerCanvasPng()"><i class="fa-solid fa-download"></i> PNG</button>
                </div>
            </div>

            <div class="ow-canvas-stage" id="officerCanvasWrap">
                <canvas id="officerFlowCanvas"></canvas>
            </div>

            <div class="ow-canvas-infobar">
                <div style="display: flex; align-items: flex-start; gap: 0.9rem; max-width: 820px;">
                    <div class="ow-icon-badge"><i class="fa-solid fa-circle-info"></i></div>
                    <div>
                        <div id="owCanvasNodeTitle" style="font-family: 'Outfit', sans-serif; font-weight: 800; font-size: 1.05rem; color: var(--secondary);"></div>
                        <div id="owCanvasNodeDesc" style="font-size: 0.88rem; color: var(--text-muted); margin-top: 0.2rem;"></div>
                    </div>
                </div>
                <span class="ow-pill green" id="owCanvasNodeBadge">JSON Synced</span>
            </div>
        </div>
    </div>
</section>

<!-- ======================================================================= -->
<!-- 3. DYNAMIC JSON-RENDERED SECTIONS CONTAINER                             -->
<!-- (All 8 Stages + Any Future JSON Sections Render Automatically Here!)    -->
<!-- ======================================================================= -->
<div id="owDynamicSectionsRoot"></div>

<!-- ======================================================================= -->
<!-- LIVE JSON EDITOR MODAL (EDIT JSON IN BROWSER & RE-RENDER INSTANTLY)     -->
<!-- ======================================================================= -->
<div class="ow-modal-backdrop" id="owJsonModal">
    <div class="ow-modal-box">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <div>
                <h3 style="font-family: 'Outfit', sans-serif; font-size: 1.3rem; font-weight: 800; color: var(--secondary); margin: 0;">
                    <i class="fa-solid fa-code" style="color: var(--primary-dark);"></i> Master JSON Specification Editor
                </h3>
                <p style="font-size: 0.82rem; color: var(--text-muted); margin: 0.2rem 0 0;">
                    Add or modify any field, step, division, note, or custom section in JSON below — the entire UI updates automatically without changing HTML!
                </p>
            </div>
            <button type="button" onclick="closeJsonEditorModal()" style="background: none; border: none; font-size: 1.35rem; color: #64748b; cursor: pointer;">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <textarea id="owJsonEditorTextarea" class="ow-json-textarea"></textarea>

        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.75rem; margin-top: 1.15rem;">
            <button type="button" class="ow-canvas-tab" onclick="downloadJsonSpecFile()">
                <i class="fa-solid fa-download"></i> Download .json File
            </button>
            <div style="display: flex; gap: 0.65rem;">
                <button type="button" class="ow-canvas-tab" onclick="closeJsonEditorModal()">Cancel</button>
                <button type="button" class="btn-primary" style="padding: 0.6rem 1.4rem; border-radius: 9999px; cursor: pointer;" onclick="applyLiveJsonChanges()">
                    <i class="fa-solid fa-bolt"></i> Apply JSON & Re-Render UI
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ======================================================================= -->
<!-- MASTER JSON DATA (100% COMPLETE CONVERSION OF Non-technical.vb)         -->
<!-- Add any new object/item here in the future; UI renders automatically!   -->
<!-- ======================================================================= -->
<script id="officerWorkflowJsonData" type="application/json">
{
  "meta": {
    "badge": "Administration & Officer Portal Workflow Specification",
    "title_line_1": "Administration & Officer",
    "title_highlight": "Complete File Workflow.",
    "description": "100% JSON-driven visual blueprint covering Master Setup, Scheme Financials, 4-Step Allottee Creation & Third-Portal Sync, Automated Mail Drops (CC: system@adms.jshb.computered.co.in), 4-Cadre Officer Review (DA → OS → EO → EE), Live QR Mobile Camera Noting, 24-Hr Auto-Bypass, 01:00 AM Night Batch Allotment Letter Generation, and Subdomain Document Storage.",
    "system_audit_email": "system@adms.jshb.computered.co.in",
    "system_audit_rule": "Every outgoing email communication across the entire portal is always CC'd to system@adms.jshb.computered.co.in so the organization stays informed. It also receives Officer Login Alerts and Multiple Failed Login Attempt alerts.",
    "kpis": [
      { "value": "2 Modes", "label": "Manual 4-Step + 3rd Portal Sync", "theme": "emerald" },
      { "value": "4 Officer Roles", "label": "DA → OS → EO → Executive Eng.", "theme": "blue" },
      { "value": "01:00 AM", "label": "Night Scheduler Batch Letter Gen", "theme": "amber" },
      { "value": "Always CC", "label": "system@adms.jshb.computered.co.in", "theme": "rose" }
    ]
  },
  "canvas_flows": [
    {
      "key": "end_to_end",
      "label": "1. Complete Admin-to-Officer Flow",
      "icon": "fa-solid fa-route",
      "nodes": [
        { "badge": "STAGE 01", "title": "Masters & Scheme Setup", "sub": "Divisions, Property Types & 10%/15%/60-EMI", "color": "#1e3a8a", "desc": "Admin configures Divisions (Ranchi, Jamshedpur), Sub-Divisions, Residential/Commercial categories, Flat Sub-Types (1 BHK, 2 BHK), and Scheme Financials (10% e-Lottery, 15% Allotment, 60 EMIs)." },
        { "badge": "STAGE 02", "title": "Allottee Create / Portal Sync", "sub": "4-Step Manual Form OR 3rd Portal Sync", "color": "#1e3a8a", "desc": "Allottee is onboarded either via the 4-Step Wizard (Step 0: 10% Payment -> Step 1: Personal/Hindi -> Step 2: Bilingual Address -> Step 3: Review) or synced directly from the third portal." },
        { "badge": "STAGE 03", "title": "Auto Mail Drop + System CC", "sub": "Credentials + Welcome Mail + CC System", "color": "#047857", "desc": "On creation/sync, the system generates credentials on the fly and drops (1) Credentials Mail and (2) Welcome to JSHB Mail, always CC'ing system@adms.jshb.computered.co.in." },
        { "badge": "STAGE 04", "title": "Auto-Apply Allotment File", "sub": "Auto-Created & Routed to Division DA", "color": "#7f1d1d", "desc": "Immediately in the background, an Allotment Letter Application (application_for = 'allotment') is automatically applied and forwarded to the Dealing Assistant of that Division & Sub-Division." },
        { "badge": "STAGE 05", "title": "4-Role Officer Review Chain", "sub": "DA → OS → Estate Officer → Exec. Engineer", "color": "#1e3a8a", "desc": "File moves sequentially across: (1) Dealing Assistant -> (2) Office Superintendent -> (3) Estate Officer -> (4) Executive Engineer (Final Approval). Each step supports Forward, Send Back, or Reject." },
        { "badge": "STAGE 06", "title": "QR Camera Noting & OTP", "sub": "Live Mobile Scan + Hindi Note + Email OTP", "color": "#0f766e", "desc": "Officers scan a QR code with their phone to capture/upload live images into reusable assets, write English/KrutiDev/Unicode Hindi green-sheet noting (or request a 24-hr auto-approved bypass), and verify via Email OTP." },
        { "badge": "STAGE 07", "title": "01:00 AM Night Batch & Subdomain", "sub": "Queue Letter Gen + Subdomain Storage", "color": "#16a34a", "desc": "Upon approval, citizen gets a 24-hr alert mail. At 01:00 AM, the Night Scheduler Queue generates the Allotment Letter PDF, stores all 6 document types on a dedicated subdomain, and emails the allottee (CC: system)." }
      ],
      "edges": [
        { "from": 0, "to": 1, "label": "Scheme Ready" },
        { "from": 1, "to": 2, "label": "On Submit" },
        { "from": 1, "to": 3, "label": "Background Trigger" },
        { "from": 2, "to": 4, "label": "CC System Mail" },
        { "from": 3, "to": 4, "label": "Routed to DA" },
        { "from": 4, "to": 5, "label": "Green-Sheet Note" },
        { "from": 5, "to": 6, "label": "Approved -> 01:00 AM" }
      ]
    },
    {
      "key": "wizard_steps",
      "label": "2. 4-Step Allottee Creation Flow",
      "icon": "fa-solid fa-user-plus",
      "nodes": [
        { "badge": "STEP 0", "title": "Payment & Scheme Selection", "sub": "Division → Scheme + 10% e-Lottery Proof", "color": "#1e3a8a", "desc": "Select Division, Sub-Division, Category, Property Type & Sub-Type to filter Scheme List. Enter Email ID, auto-fetch 10% Property Value, Payment Date (D/M/Y), Payment Proof File, UTR (Optional) & Transaction No (Required)." },
        { "badge": "STEP 1", "title": "Allottee Personal & Relation", "sub": "English + KrutiDev Hindi + PAN & Aadhar", "color": "#1e3a8a", "desc": "Enter Application No/Date, English & KrutiDev Hindi Name (Prefix, First, Middle, Surname), Relation (Father/Mother/Husband/Wife in Eng & Hindi), Marital Status, Gender, Caste, PAN (Req), Aadhar (Req), Nationality & DOB." },
        { "badge": "STEP 2", "title": "Bilingual Address & Contact", "sub": "Present & Permanent (Eng/Hindi) + Phones", "color": "#047857", "desc": "Enter Present Address in English & KrutiDev Hindi (Address, State, District, Pincode, Post Office, Police Station), toggle 'Same as Present Address' for Permanent Address, plus Primary/Alt Mobile, WhatsApp, Landline & Email." },
        { "badge": "STEP 3", "title": "Review & Final Submit", "sub": "Complete Summary Verification", "color": "#7f1d1d", "desc": "Review all captured payment, bilingual personal KYC, and address details on a single screen and click Final Submit (same downstream flow runs for Third-Portal Sync)." },
        { "badge": "AUTO 01", "title": "Credentials & Welcome Mails", "sub": "On-the-Fly Password + CC System Mail", "color": "#0f766e", "desc": "Immediately dispatches Login Credentials Mail (with CC to system@adms.jshb.computered.co.in) and Welcome to JSHB Mail to the Allottee." },
        { "badge": "AUTO 02", "title": "Auto Allotment Application", "sub": "Forwarded to Division Dealing Assistant", "color": "#16a34a", "desc": "Automatically creates the Allotment Letter Application in the background and assigns it to the Dealing Assistant of the selected Division and Sub-Division." }
      ],
      "edges": [
        { "from": 0, "to": 1, "label": "Save & Next" },
        { "from": 1, "to": 2, "label": "Save & Next" },
        { "from": 2, "to": 3, "label": "Save & Next" },
        { "from": 3, "to": 4, "label": "Final Submit" },
        { "from": 3, "to": 5, "label": "Auto Apply" }
      ]
    },
    {
      "key": "officer_pipeline",
      "label": "3. 4-Role Officer Approval Pipeline",
      "icon": "fa-solid fa-user-tie",
      "nodes": [
        { "badge": "ROLE 01", "title": "Dealing Assistant Review", "sub": "Forward | Send Back | Reject", "color": "#1e3a8a", "desc": "Application arrives on Dealing Assistant Dashboard (e.g., Ritik Kumar / Sunil Kumar). Officer reviews KYC, Lottery Receipt, adds Hindi/English noting, verifies Email OTP, and Forwards." },
        { "badge": "ROLE 02", "title": "Office Superintendent Review", "sub": "Forward | Send Back | Reject", "color": "#1e3a8a", "desc": "Forwarded to Office Superintendent (e.g., Shivasya Kumari). Supports QR live image capture, green-sheet noting, 24-hour auto-approved workflow bypass if unavailable, and OTP-secured Forwarding." },
        { "badge": "ROLE 03", "title": "Estate Officer Review", "sub": "Forward | Send Back | Reject", "color": "#7f1d1d", "desc": "Reviewed by Estate Officer (e.g., Srivastav Pandey). Publishes Office Correspondences (LT / OO / OD), verifies additional requested documents (Aadhar/Income Cert), and Forwards with OTP." },
        { "badge": "ROLE 04", "title": "Executive Engineer Review", "sub": "Approve | Send Back | Reject", "color": "#047857", "desc": "Final stage by Executive Engineer. Uses the same unified noting & OTP verification modal to grant final Approval (or Send Back / Reject)." },
        { "badge": "MAIL & SLA", "title": "Approval Mail (24-Hr Notice)", "sub": "Allottee Alert + CC System Email", "color": "#0f766e", "desc": "On Approval, an automated email informs the allottee that their application is approved and the Allotment Letter will be uploaded within 24 hours (CC: system@adms.jshb.computered.co.in)." },
        { "badge": "01:00 AM", "title": "Night Batch Queue & Subdomain", "sub": "Auto PDF Generation at 01:00 Hrs", "color": "#16a34a", "desc": "At 01:00 AM server time, the scheduled batch queue command generates the Allotment Letter PDF, saves it on the dedicated document subdomain, updates the DB, and notifies the allottee." }
      ],
      "edges": [
        { "from": 0, "to": 1, "label": "Forward (OTP)" },
        { "from": 1, "to": 2, "label": "Forward (OTP)" },
        { "from": 2, "to": 3, "label": "Forward (OTP)" },
        { "from": 3, "to": 4, "label": "Approve (OTP)" },
        { "from": 4, "to": 5, "label": "01:00 AM Cron" }
      ]
    }
  ],
  "masters_and_schemes": {
    "divisions": [
      {
        "name": "Ranchi Division",
        "sub_divisions": ["Harmu Colony", "Durwa Colony"]
      },
      {
        "name": "Jamshedpur Division",
        "sub_divisions": ["Adityapur", "Dindli Basti", "Bagbera-Jamshedpur"]
      }
    ],
    "property_categories": ["Residential", "Commercial"],
    "property_types": [
      { "type": "House", "sub_types": ["Independent House / Duplex"] },
      { "type": "Plot", "sub_types": ["Residential / Commercial Plot"] },
      { "type": "Flat", "sub_types": ["1 BHK", "2 BHK"] }
    ],
    "scheme_attributes": [
      { "field": "Scheme Code", "rule": "Unique identifier for every housing scheme" },
      { "field": "Number of Units", "rule": "Total allottee inventory capacity in the scheme" },
      { "field": "Scheme Title (English)", "rule": "Official English title of the scheme" },
      { "field": "Scheme Title (Hindi)", "rule": "Official Hindi title of the scheme" },
      { "field": "Hierarchy Mapping", "rule": "Mapped to Division, Sub-Division, Property Category & Property Type" }
    ],
    "scheme_financials": [
      { "parameter": "Property Value", "value": "100% Base Valuation of Scheme Unit" },
      { "parameter": "e-Lottery Time Deposit", "value": "10% of Property Value (Auto-Fetched Default)" },
      { "parameter": "Allotment Time Deposit", "value": "15% of Property Value" },
      { "parameter": "EMI Tenure Count", "value": "60 Monthly Installments" },
      { "parameter": "EMI Amount (emi_amount)", "value": "Standard Monthly Installment Amount" },
      { "parameter": "Late EMI Amount (emi_late_amount)", "value": "Installment Amount with Overdue Penalty" }
    ]
  },
  "allottee_creation": {
    "methods": [
      {
        "id": "Method 01",
        "title": "Manual Allottee Onboarding (4-Step Form)",
        "description": "Step-by-step administrative entry capturing scheme selection, 10% e-Lottery payment proof, bilingual personal KYC (English & KrutiDev Hindi), and present/permanent addresses."
      },
      {
        "id": "Method 02",
        "title": "Automated Sync from Third Portal",
        "description": "1-Click synchronization from the external application portal that automatically triggers the exact same Credential Mail Drop, Welcome Mail, and Allotment Application creation."
      }
    ],
    "steps": [
      {
        "step_no": "Step 0",
        "title": "Payment & Scheme Selection",
        "button_label": "Save & Next",
        "groups": [
          {
            "group_title": "Hierarchy & Scheme Filter",
            "fields": [
              { "name": "Select Division", "required": true },
              { "name": "Select Sub-Division (According to Division)", "required": true },
              { "name": "Select Property Category", "required": true },
              { "name": "Select Property Type", "required": true },
              { "name": "Select Property Sub-Type (According to Property Type)", "required": true },
              { "name": "Filtered Scheme List Display & Selection", "required": true },
              { "name": "Allottee Email ID", "required": true }
            ]
          },
          {
            "group_title": "e-Lottery Payment Section (10% Default)",
            "fields": [
              { "name": "Auto-Fetch 10% Value of Property (Default Set)", "required": true },
              { "name": "Payment Amount (10%)", "required": true },
              { "name": "Payment Date (Select Day, Select Month, Select Year)", "required": true },
              { "name": "Payment Screenshot or Proof (File Upload)", "required": true },
              { "name": "UTR Number (Optional)", "required": false },
              { "name": "Transaction Number (Required)", "required": true }
            ]
          }
        ]
      },
      {
        "step_no": "Step 1",
        "title": "Allottee Personal & Relation Details",
        "button_label": "Save & Next",
        "groups": [
          {
            "group_title": "Application & Bilingual Name (English + KrutiDev Hindi)",
            "fields": [
              { "name": "Application Number & Application Date", "required": true },
              { "name": "English: Prefix Dropdown, First Name, Middle Name, Surname", "required": true },
              { "name": "Hindi (KrutiDev): Hindi Prefix Dropdown, First Name, Middle Name, Surname", "required": true }
            ]
          },
          {
            "group_title": "Relation & Mandatory KYC Details",
            "fields": [
              { "name": "Relation Prefix (English): Father, Mother, Husband, Wife", "required": true },
              { "name": "Relation Prefix (Hindi): पिता, माता, पति, पत्नी", "required": true },
              { "name": "Relation Name (English & Hindi)", "required": true },
              { "name": "Marital Status, Gender & Caste Category", "required": true },
              { "name": "PAN Card Number (Required)", "required": true },
              { "name": "Aadhar Card Number (Required)", "required": true },
              { "name": "Nationality (Indian)", "required": true },
              { "name": "Date of Birth (Select Day, Select Month, Select Year)", "required": true }
            ]
          }
        ]
      },
      {
        "step_no": "Step 2",
        "title": "Bilingual Address & Contact Details",
        "button_label": "Save & Next",
        "groups": [
          {
            "group_title": "Present & Permanent Address (English + KrutiDev Hindi)",
            "fields": [
              { "name": "Present Address (English): Address, State, District, Pincode, Post Office, Police Station", "required": true },
              { "name": "Present Address (KrutiDev Hindi): Address, State, District, Pincode, Post Office, Police Station", "required": true },
              { "name": "Checkbox: [✓] Same as Present Address", "required": false },
              { "name": "Permanent Address (English & KrutiDev Hindi)", "required": true }
            ]
          },
          {
            "group_title": "Communication Contact Details of Applicant",
            "fields": [
              { "name": "Primary Mobile Number", "required": true },
              { "name": "Alternative Mobile Number", "required": false },
              { "name": "WhatsApp Number", "required": false },
              { "name": "Landline Number", "required": false },
              { "name": "Email ID", "required": true }
            ]
          }
        ]
      },
      {
        "step_no": "Step 3",
        "title": "Review & Final Submit",
        "button_label": "Final Submit",
        "groups": [
          {
            "group_title": "Complete Summary Verification & Background Triggers",
            "fields": [
              { "name": "Full Read-Only Preview of Payment, Personal KYC & Address Sections", "required": true },
              { "name": "Auto-Trigger 1: On-the-Fly Login Credentials Mail to Allottee (CC: system@adms.jshb.computered.co.in)", "required": true },
              { "name": "Auto-Trigger 2: 'Welcome to JSHB' Onboarding Email to Allottee", "required": true },
              { "name": "Auto-Trigger 3: Background Auto-Creation of Allotment Letter Application & Routing to Division Dealing Assistant", "required": true }
            ]
          }
        ]
      }
    ]
  },
  "allottee_list_sample": {
    "record_header": {
      "Allottee Name": "Pooja Pandey",
      "Sync Status": "Synced",
      "Username": "JSRMI2026BGB42109",
      "Division": "Jamshedpur Division",
      "Sub Division": "Bagbera-Jamshedpur",
      "Category": "Residential",
      "Property No": "N-7352",
      "Payment Status": "Pending"
    },
    "panels": [
      {
        "title": "1. PROFILE & IDs",
        "icon": "fa-solid fa-id-badge",
        "items": {
          "Username": "JSRMI2026BGB42109",
          "Application No": "JSHB120639",
          "Allotment No": "ALT/JSHB/2026/11/1305/JSR/194704",
          "Application Date": "15-10-2026",
          "Allotment Date": "05-11-2026"
        }
      },
      {
        "title": "2. PERSONAL DATA",
        "icon": "fa-solid fa-user-check",
        "items": {
          "Email ID": "pooja.pandey.jshb@mailinator.com",
          "Date of Birth": "08-08-1992",
          "Gender": "Female",
          "Marital Status": "Married",
          "Relation": "Husband (Alok Pandey)",
          "PAN No": "ABCI1238F",
          "Aadhar No": "654321098718"
        }
      },
      {
        "title": "3. ASSET & FINANCE",
        "icon": "fa-solid fa-building-columns",
        "items": {
          "Property No": "N-7352",
          "Payment Option": "N/A",
          "Total Paid": "₹0.00",
          "Category": "Residential",
          "Division": "Jamshedpur Division"
        }
      },
      {
        "title": "4. APPLICATIONS & PROCESSING STAGE",
        "icon": "fa-solid fa-file-signature",
        "items": {
          "Application No": "ALT-JSRBGB-4311VX054126CV",
          "Type": "Allotment",
          "Current Stage": "Dealing Assistant Review",
          "Due Date": "20 Nov 2026",
          "Pending With": "Ritik Kumar",
          "Status": "In Progress",
          "Action / Tracks": "Live Movement & Audit Tracking"
        }
      }
    ]
  },
  "workflow_roles": [
    {
      "step": 1,
      "stage_name": "Dealing Assistant Review",
      "role": "Dealing Assistant",
      "actions": ["Forward", "Send Back", "Reject"],
      "description": "Initial scrutiny of auto-created Allotment Application, lottery payment receipt, and applicant KYC."
    },
    {
      "step": 2,
      "stage_name": "Office Superintendent Review",
      "role": "Office Superintendent",
      "actions": ["Forward", "Send Back", "Reject"],
      "description": "Supervisory verification of file noting, document checklist compliance, and division records."
    },
    {
      "step": 3,
      "stage_name": "Estate Officer Review",
      "role": "Estate Officer",
      "actions": ["Forward", "Send Back", "Reject"],
      "description": "Administrative review, issuance of Office Orders (OO) / Letters (LT), and forwarding for sanction."
    },
    {
      "step": 4,
      "stage_name": "Executive Engineer Review",
      "role": "Executive Engineer",
      "actions": ["Approve", "Send Back", "Reject"],
      "description": "Final authority approval of the Allotment Application triggering the 24-hour allotment letter notice and 01:00 AM batch generation."
    }
  ],
  "officer_dashboard": {
    "login_info": {
      "Current Login IP": "127.0.0.1",
      "Login Since": "2 seconds ago",
      "Current Time": "3:13 PM",
      "Day": "Thursday",
      "Date": "05-Nov-2026",
      "Security Mail Alert": "Officer Login Alert & Failed Attempt Alerts CC'd to system@adms.jshb.computered.co.in"
    },
    "summary_counters": [
      { "label": "Total Received", "count": 1, "theme": "blue" },
      { "label": "Pending Applications", "count": 1, "theme": "amber" },
      { "label": "Processed Applications", "count": 0, "theme": "emerald" },
      { "label": "Sent Back", "count": 0, "theme": "rose" },
      { "label": "Rejected", "count": 0, "theme": "rose" }
    ],
    "pending_table": [
      {
        "application_no": "ALT-JSRBGB-4311VX054126CV",
        "type": "Allotment",
        "allottee_name": "Mrs. Pooja Pandey",
        "property_no": "N-7352",
        "created_date": "05-Nov-2026 15:10",
        "pending_duration": "0 days pending",
        "priority": "Normal",
        "due_date": "20-Nov-2026",
        "action": "Review"
      }
    ],
    "calendar_meta": {
      "months": "January to December (12 Months)",
      "years": "2021 to 2031 (11-Year Span)",
      "weekdays": "Sunday, Monday, Tuesday, Wednesday, Thursday, Friday, Saturday",
      "active_entry": "Date 5 Nov 2026 → 1 App(s) Scheduled | Visible Grid Dates: 1 to 14",
      "recent_notices": "No recent notices available."
    }
  },
  "application_view_workspace": {
    "action_banner": "ACTION REQUIRED (DEALING ASSISTANT REVIEW) — Please review the details below and take appropriate action. | Available Actions: Forward | Send Back | Reject",
    "application_details": {
      "Application Type": "Allotment",
      "Current Status": "Completed",
      "Priority Level": "Normal",
      "Date Created": "22-Oct-2026 11:07 AM",
      "Due Date": "06-Nov-2026"
    },
    "allottee_information": {
      "Applicant Name": "Shri Vikash Kumar Yadav",
      "Hindi Name (KrutiDev)": "Jh fodk'k dqekj ;kno",
      "Property Number": "E-7612",
      "Username": "JSRHI2026DND40576",
      "Email": "user1@adms.jshb.computered.co.in",
      "Allotment No": "ALT/JSHB/2026/10/8722/JSR/265746"
    },
    "attached_documents": [
      {
        "doc_title": "Document 1: Allotment Letter",
        "file_type": "allotment_letter_ALT-JSHB...",
        "uploaded_by": "Srivastav Pandey (System)",
        "action_tag": "ALLOTMENT LETTER",
        "button": "View"
      },
      {
        "doc_title": "Document 2: Lottery Receipt",
        "file_type": "lottery-payment-receipt-2...",
        "uploaded_by": "Super Admin (Staff)",
        "action_tag": "lottery receipt",
        "button": "View"
      }
    ],
    "application_notes": [
      {
        "note_no": "Note 1",
        "officer_name": "Srivastav Pandey",
        "designation": "Estate Officer (Jamshedpur Division)",
        "date_time": "22-Oct-2026 01:03 PM",
        "verification": "OTP Verified",
        "attachment": "Image & Official Signature",
        "content": "आवंटी के आवेदन पत्र को आवश्यक कार्यवाही हेतु अग्रेषित करने के संबंध में।\nपत्रांक: __________  |  दिनांक: __________\n\nआवंटी श्री/श्रीमती __________, संपत्ति संख्या __________ द्वारा प्रस्तुत आवेदन पत्र एवं संलग्न अभिलेखों का परीक्षण किया गया।\nप्रकरण में आवश्यक अभिलेख उपलब्ध हैं तथा आवेदन पत्र नियमानुसार आवश्यक कार्यवाही हेतु प्रस्तुत है।\nअतः उक्त प्रकरण को संपदा पदाधिकारी महोदय के समक्ष परीक्षण एवं आवश्यक कार्यवाही हेतु अग्रेषित किया जाता है।\nकृपया प्रकरण का परीक्षण कर नियमानुसार आवश्यक कार्यवाही करने का कष्ट करें।\n\nआदेशानुसार।\nहस्ताक्षर: __________  |  नाम: __________"
      },
      {
        "note_no": "Note 2",
        "officer_name": "Sunil Kumar",
        "designation": "Dealing Assistant (Jamshedpur Division)",
        "date_time": "22-Oct-2026 12:02 PM",
        "verification": "OTP Verified",
        "attachment": "Digital Noting Record",
        "content": "विषय: आवेदन पत्र अग्रेषित किए जाने के संबंध में।\nमहोदय,\nआवंटी द्वारा प्रस्तुत अभिलेखों एवं लॉटरी भुगतान रसीद का प्रारंभिक सत्यापन पूर्ण कर अग्रेषित किया जाता है।"
      }
    ],
    "office_correspondences": [
      {
        "reference_no": "OO-JSR-22102026-0SWON",
        "type": "Office Order",
        "subject": "आवंटी के आवेदन पत्र को आवश्यक कार्यवाही...",
        "generated_by": "Srivastav Pandey",
        "date": "22 Oct, 2026",
        "status": "Published",
        "action": "View"
      }
    ],
    "document_upload_status": [
      {
        "sno": 1,
        "document_name": "Aadhar Card (Required)",
        "file_name": "lottery-payment-receipt3.png",
        "type": "SVG",
        "uploaded_by": "Allottee",
        "remarks": "Uploaded as per engineer request."
      },
      {
        "sno": 2,
        "document_name": "Income Certificate (Required)",
        "file_name": "lottery-payment-receipt2.png",
        "type": "SVG",
        "uploaded_by": "Allottee",
        "remarks": "Uploaded as per engineer request."
      }
    ],
    "movement_history": [
      {
        "step": 1,
        "date_time": "22-Oct-2026 11:07 AM",
        "action": "CREATED",
        "transferred_from": "Super Admin",
        "transferred_to": "Super Admin",
        "remarks": "Application created by system."
      },
      {
        "step": 2,
        "date_time": "22-Oct-2026 11:07 AM",
        "action": "FORWARDED",
        "transferred_from": "Super Admin",
        "transferred_to": "Sunil Kumar (Dealing Assistant)",
        "remarks": "Automatically forwarded to Dealing Assistant Review."
      },
      {
        "step": 3,
        "date_time": "22-Oct-2026 12:02 PM",
        "action": "FORWARDED",
        "transferred_from": "Sunil Kumar",
        "transferred_to": "Srivastav Pandey (Estate Officer)",
        "remarks": "Application forwarded to Estate Officer."
      },
      {
        "step": 4,
        "date_time": "22-Oct-2026 01:03 PM",
        "action": "APPROVED",
        "transferred_from": "Srivastav Pandey",
        "transferred_to": "Estate Officer",
        "remarks": "Application approved."
      }
    ]
  },
  "officer_action_modal": {
    "unified_design_note": "Forward, Send Back, Reject, and Approve share the exact same unified interface (Approve records final noting without forwarding to a next role).",
    "example_application_no": "ALT-JSRBGB-4311VX054126CV",
    "features": [
      {
        "step": "1. Live Image Upload (Mobile QR Camera Scan & Reusable Assets)",
        "icon": "fa-solid fa-qrcode",
        "details": "Opens a 'Scan to Capture' QR popup. Pointing the phone's camera at the QR opens a mobile capture & crop page. Uploaded photos reflect in real time on the desktop screen, attach to the noting text, and save into Officer Assets for direct 1-click reuse in future files without re-scanning."
      },
      {
        "step": "2. Official Green-Sheet Legal Note",
        "icon": "fa-solid fa-file-shield",
        "details": "The official noting is permanently recorded in the immutable file history and carries the exact same governance validity as signing and stamping a physical government green noting sheet."
      },
      {
        "step": "3. Forward To (Next Officer Routing)",
        "icon": "fa-solid fa-share-from-square",
        "details": "Automatically identifies the Immediate Next Step (e.g., Office Superintendent Review | Designation: Office Superintendent | Officer Name: Shivasya Kumari)."
      },
      {
        "step": "4. Request Workflow Bypass (Skip Level — 24-Hr Auto Approval)",
        "icon": "fa-solid fa-forward-fast",
        "details": "Allows forwarding directly to a higher authority if the immediate officer is unavailable. Requires Admin Approval with a 24-Hour Waiting Period — pending requests are automatically approved by the system after 24 hours."
      },
      {
        "step": "5. Multi-Language Noting Editor & Signature Insertion",
        "icon": "fa-solid fa-language",
        "details": "Mandatory official remarks supporting 3 typing languages: (1) English (Arial), (2) Hindi (KrutiDev), and (3) Normal Hindi (Unicode). Includes tools to attach official signatures/stamps from saved Officer Assets or computer file explorer."
      },
      {
        "step": "6. Mandatory Email OTP Security Verification",
        "icon": "fa-solid fa-key",
        "details": "Before submitting the noting and executing Forward, Send Back, Reject, or Approve, the officer must verify their identity via live Email OTP."
      }
    ]
  },
  "automation_and_storage": {
    "email_notifications": [
      {
        "trigger": "On Application Forward / Send Back / Reject",
        "recipient": "Next Assigned Officer + CC: system@adms.jshb.computered.co.in",
        "message": "Sends 'New Application Forwarded to You' email alert and real-time in-portal notification."
      },
      {
        "trigger": "On Final Application Approval",
        "recipient": "Allottee Email + CC: system@adms.jshb.computered.co.in",
        "message": "Notifies the allottee that their application is approved and their official Allotment Letter will be uploaded within 24 hours."
      },
      {
        "trigger": "On Night Batch Allotment Letter Generation (01:00 AM)",
        "recipient": "Allottee Email + CC: system@adms.jshb.computered.co.in",
        "message": "Informs the allottee and organization that the final Allotment Letter has been generated and published to their portal."
      }
    ],
    "night_scheduler": {
      "Execution Time": "01:00 AM Daily (24-Hour Format — 01:00)",
      "Engine Architecture": "Laravel Queue Job + Batch Program + Artisan Console Command",
      "Process Flow": "Runs automatically at 01:00 AM on the server, generates Allotment Letter PDFs for newly approved applications, updates the generated documents table, and dispatches email/portal notifications with CC to system@adms.jshb.computered.co.in."
    },
    "subdomain_storage": {
      "objective": "Keep all documents across the complete allottee lifecycle on a separate dedicated subdomain independent from the main application server — ensuring clean organization, centralized management, easier maintenance, and zero main-server storage bloat.",
      "document_categories": [
        "1. Allottee Creation & KYC Documents",
        "2. Application Submission & Approval Request Documents",
        "3. Application Approval Letters",
        "4. Official Generated Allotment Letters",
        "5. e-Lottery Payment Receipts & Screenshots",
        "6. Any Other Documents Generated or Uploaded During the Allotment Lifecycle"
      ]
    }
  },
  "custom_sections": []
}
</script>

@endsection

@push('scripts')
<script>
    // =========================================================================
    // 1. LOAD MASTER JSON & DYNAMICALLY RENDER ALL SECTIONS
    // =========================================================================
    let officerData = JSON.parse(document.getElementById('officerWorkflowJsonData').textContent);

    function renderAllFromJson() {
        renderHeroFromJson();
        renderCanvasTabsFromJson();
        renderDynamicSectionsFromJson();
        setupOfficerCanvas();
    }

    // --- A. Render Hero Section ---
    function renderHeroFromJson() {
        const m = officerData.meta;
        const kpisHtml = (m.kpis || []).map(k => `
            <div class="ow-kpi-box ${k.theme || 'blue'}">
                <div class="ow-kpi-val">${k.value}</div>
                <div class="ow-kpi-lbl">${k.label}</div>
            </div>
        `).join('');

        document.getElementById('owHeroContainer').innerHTML = `
            <div>
                <div class="live-badge">
                    <span class="ping">
                        <span class="ping-circle"></span>
                        <span class="ping-dot"></span>
                    </span>
                    ${m.badge}
                </div>
                <h1 class="ow-hero-title">
                    ${m.title_line_1} <br>
                    <span>${m.title_highlight}</span>
                </h1>
                <p class="ow-hero-desc">${m.description}</p>
                <div class="ow-hero-btns">
                    <a href="#sec-canvas" class="btn-large btn-dark">
                        Explore Interactive Flow <i class="fa-solid fa-diagram-project"></i>
                    </a>
                    <button type="button" onclick="openJsonEditorModal()" class="btn-large btn-outline" style="cursor: pointer;">
                        <i class="fa-solid fa-code"></i> Edit / Add via JSON
                    </button>
                    <button type="button" onclick="downloadJsonSpecFile()" class="btn-large btn-outline" style="cursor: pointer;">
                        <i class="fa-solid fa-download"></i> Download JSON
                    </button>
                </div>
            </div>

            <div>
                <div class="ow-summary-card">
                    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:0.5rem; padding-bottom:0.85rem; margin-bottom:1rem; border-bottom:1px solid #e2e8f0;">
                        <div>
                            <span style="font-size:0.72rem; font-weight:800; text-transform:uppercase; color:var(--primary-dark); display:block;">100% JSON-Driven UI</span>
                            <h3 style="font-size:1.15rem; font-weight:800; color:var(--secondary); margin:0;">Admin & Officer Flow Summary</h3>
                        </div>
                        <span class="ow-pill green"><i class="fa-solid fa-check-double"></i> Live Spec</span>
                    </div>

                    <div class="ow-kpi-grid">${kpisHtml}</div>

                    <div style="margin-top:0.9rem; padding:0.75rem 0.9rem; border-radius:0.85rem; background:#fffbeb; border:1px solid #fde68a; font-size:0.78rem; color:#92400e; line-height:1.45;">
                        <strong><i class="fa-solid fa-envelope-circle-check"></i> Organization Audit Mail CC:</strong><br>
                        <code>${m.system_audit_email}</code> — ${m.system_audit_rule}
                    </div>
                </div>
            </div>
        `;
    }

    // --- B. Render All 8 Core Sections + Any Future Custom JSON Sections ---
    function renderDynamicSectionsFromJson() {
        const root = document.getElementById('owDynamicSectionsRoot');
        const ms = officerData.masters_and_schemes;
        const ac = officerData.allottee_creation;
        const al = officerData.allottee_list_sample;
        const wr = officerData.workflow_roles;
        const od = officerData.officer_dashboard;
        const av = officerData.application_view_workspace;
        const am = officerData.officer_action_modal;
        const as = officerData.automation_and_storage;

        // 1. Masters & Scheme Setup HTML
        const divisionsHtml = ms.divisions.map(d => `
            <div class="ow-kv-row">
                <span class="ow-kv-key"><i class="fa-solid fa-location-dot" style="color:var(--secondary);"></i> ${d.name}</span>
                <span class="ow-kv-val">${d.sub_divisions.join(' • ')}</span>
            </div>
        `).join('');

        const propTypesHtml = ms.property_types.map(pt => `
            <div class="ow-kv-row">
                <span class="ow-kv-key">${pt.type}</span>
                <span class="ow-kv-val">${pt.sub_types.join(', ')}</span>
            </div>
        `).join('');

        const schemeAttrHtml = ms.scheme_attributes.map(sa => `
            <div class="ow-kv-row">
                <span class="ow-kv-key">${sa.field}</span>
                <span class="ow-kv-val">${sa.rule}</span>
            </div>
        `).join('');

        const schemeFinHtml = ms.scheme_financials.map(sf => `
            <div class="ow-kv-row">
                <span class="ow-kv-key">${sf.parameter}</span>
                <span class="ow-kv-val" style="color:#166534;">${sf.value}</span>
            </div>
        `).join('');

        // 2. Allottee Creation Methods & 4-Step Wizard HTML
        const methodsHtml = ac.methods.map(m => `
            <div class="ow-card" style="border-left: 5px solid var(--secondary);">
                <div class="ow-card-head">
                    <h3 class="ow-card-title"><span class="ow-icon-badge"><i class="fa-solid fa-bolt"></i></span> ${m.title}</h3>
                    <span class="ow-pill">${m.id}</span>
                </div>
                <p style="font-size:0.9rem; color:var(--text-muted); margin:0; line-height:1.6;">${m.description}</p>
            </div>
        `).join('');

        const wizardStepsHtml = ac.steps.map(st => {
            const groupsHtml = st.groups.map(g => `
                <div style="margin-bottom:0.85rem;">
                    <div style="font-size:0.8rem; font-weight:800; color:var(--secondary); margin-bottom:0.4rem;">
                        <i class="fa-solid fa-Layer-group"></i> ${g.group_title}
                    </div>
                    <div class="ow-field-tags">
                        ${g.fields.map(f => `<span class="ow-field-tag ${f.required ? 'req' : ''}">${f.name}${f.required ? ' *' : ''}</span>`).join('')}
                    </div>
                </div>
            `).join('');

            return `
                <div class="ow-card" style="border-top: 4px solid var(--secondary);">
                    <div class="ow-card-head">
                        <h3 class="ow-card-title">${st.step_no}: ${st.title}</h3>
                        <span class="ow-pill green">Button: ${st.button_label}</span>
                    </div>
                    ${groupsHtml}
                </div>
            `;
        }).join('');

        // 3. Allottee List Sample Record HTML
        const headerBadgesHtml = Object.entries(al.record_header).map(([k, v]) => `
            <div style="background:#f8fafc; padding:0.55rem 0.85rem; border-radius:0.75rem; border:1px solid #e2e8f0;">
                <div style="font-size:0.7rem; color:var(--text-muted); font-weight:700; text-transform:uppercase;">${k}</div>
                <div style="font-size:0.9rem; color:var(--secondary); font-weight:800;">${v}</div>
            </div>
        `).join('');

        const allotteePanelsHtml = al.panels.map(p => {
            const rows = Object.entries(p.items).map(([k, v]) => `
                <div class="ow-kv-row">
                    <span class="ow-kv-key">${k}</span>
                    <span class="ow-kv-val">${v}</span>
                </div>
            `).join('');
            return `
                <div class="ow-card">
                    <div class="ow-card-head">
                        <h3 class="ow-card-title"><span class="ow-icon-badge"><i class="${p.icon}"></i></span> ${p.title}</h3>
                    </div>
                    <div class="ow-kv-list">${rows}</div>
                </div>
            `;
        }).join('');

        // 4. 4-Role Officer Workflow Chain HTML
        const workflowRolesHtml = wr.map(r => `
            <div class="ow-card" style="border-top: 4px solid ${r.step === 4 ? '#16a34a' : '#1e3a8a'};">
                <div class="ow-card-head">
                    <span class="ow-pill ${r.step === 4 ? 'green' : ''}">Step (${r.step})</span>
                    <span class="ow-pill maroon">${r.role}</span>
                </div>
                <h3 style="font-family:'Outfit',sans-serif; font-size:1.15rem; font-weight:800; color:var(--secondary); margin-bottom:0.4rem;">
                    ${r.stage_name}
                </h3>
                <p style="font-size:0.85rem; color:var(--text-muted); line-height:1.5; margin-bottom:0.9rem;">
                    ${r.description}
                </p>
                <div style="display:flex; flex-wrap:wrap; gap:0.4rem;">
                    ${r.actions.map(act => `<span class="ow-field-tag req"><i class="fa-solid fa-check"></i> ${act}</span>`).join('')}
                </div>
            </div>
        `).join('');

        // 5. Officer Dashboard Preview HTML
        const loginRowsHtml = Object.entries(od.login_info).map(([k, v]) => `
            <div class="ow-kv-row">
                <span class="ow-kv-key">${k}</span>
                <span class="ow-kv-val">${v}</span>
            </div>
        `).join('');

        const dashCountersHtml = od.summary_counters.map(c => `
            <div class="ow-kpi-box ${c.theme}">
                <div class="ow-kpi-val">${c.count}</div>
                <div class="ow-kpi-lbl">${c.label}</div>
            </div>
        `).join('');

        const pendingRowsHtml = od.pending_table.map(row => `
            <tr>
                <td><strong>${row.application_no}</strong></td>
                <td><span class="ow-pill">${row.type}</span></td>
                <td><strong>${row.allottee_name}</strong></td>
                <td>${row.property_no}</td>
                <td>${row.created_date}</td>
                <td>${row.pending_duration}</td>
                <td><span class="ow-pill green">${row.priority}</span></td>
                <td>${row.due_date}</td>
                <td><span class="ow-pill maroon">${row.action}</span></td>
            </tr>
        `).join('');

        const calMetaHtml = Object.entries(od.calendar_meta).map(([k, v]) => `
            <div class="ow-kv-row">
                <span class="ow-kv-key" style="text-transform:capitalize;">${k.replace('_', ' ')}</span>
                <span class="ow-kv-val">${v}</span>
            </div>
        `).join('');

        // 6. Officer Application View Workspace HTML
        const appDetailsHtml = Object.entries(av.application_details).map(([k, v]) => `
            <div class="ow-kv-row"><span class="ow-kv-key">${k}</span><span class="ow-kv-val">${v}</span></div>
        `).join('');

        const allotteeInfoHtml = Object.entries(av.allottee_information).map(([k, v]) => `
            <div class="ow-kv-row"><span class="ow-kv-key">${k}</span><span class="ow-kv-val">${v}</span></div>
        `).join('');

        const attachedDocsHtml = av.attached_documents.map(doc => `
            <div class="ow-kv-row" style="align-items:center;">
                <div>
                    <strong style="color:var(--secondary); display:block;">${doc.doc_title}</strong>
                    <span style="font-size:0.76rem; color:var(--text-muted);">File: ${doc.file_type} • Uploaded By: ${doc.uploaded_by}</span>
                </div>
                <div style="display:flex; gap:0.4rem; align-items:center;">
                    <span class="ow-pill">${doc.action_tag}</span>
                    <span class="ow-pill green">${doc.button}</span>
                </div>
            </div>
        `).join('');

        const notesHtml = av.application_notes.map(n => `
            <div class="ow-greensheet">
                <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:0.5rem;">
                    <div>
                        <strong style="color:#065f46; font-size:0.98rem;">${n.note_no}: ${n.officer_name}</strong>
                        <span style="font-size:0.8rem; color:#047857; display:block;">${n.designation} • ${n.date_time}</span>
                    </div>
                    <div style="display:flex; gap:0.4rem;">
                        <span class="ow-pill green"><i class="fa-solid fa-shield-check"></i> ${n.verification}</span>
                        <span class="ow-pill">${n.attachment}</span>
                    </div>
                </div>
                <div class="ow-greensheet-body">${n.content}</div>
            </div>
        `).join('');

        const corrRowsHtml = av.office_correspondences.map(c => `
            <tr>
                <td><strong>${c.reference_no}</strong></td>
                <td><span class="ow-pill">${c.type}</span></td>
                <td>${c.subject}</td>
                <td>${c.generated_by}</td>
                <td>${c.date}</td>
                <td><span class="ow-pill green">${c.status}</span></td>
                <td><span class="ow-pill maroon">${c.action}</span></td>
            </tr>
        `).join('');

        const docUploadRowsHtml = av.document_upload_status.map(d => `
            <tr>
                <td>${d.sno}</td>
                <td><strong>${d.document_name}</strong></td>
                <td><code>${d.file_name}</code></td>
                <td><span class="ow-pill">${d.type}</span></td>
                <td>${d.uploaded_by}</td>
                <td>${d.remarks}</td>
            </tr>
        `).join('');

        const movementRowsHtml = av.movement_history.map(m => `
            <tr>
                <td><strong>Step ${m.step}</strong></td>
                <td>${m.date_time}</td>
                <td><span class="ow-pill ${m.action === 'APPROVED' ? 'green' : ''}">${m.action}</span></td>
                <td>${m.transferred_from}</td>
                <td><strong>${m.transferred_to}</strong></td>
                <td>${m.remarks}</td>
            </tr>
        `).join('');

        // 7. Officer Action Modal Features HTML
        const actionModalCardsHtml = am.features.map(f => `
            <div class="ow-card">
                <div class="ow-card-head">
                    <h3 class="ow-card-title"><span class="ow-icon-badge"><i class="${f.icon}"></i></span> ${f.step}</h3>
                </div>
                <p style="font-size:0.88rem; color:var(--text-muted); line-height:1.6; margin:0;">${f.details}</p>
            </div>
        `).join('');

        // 8. Email Notifications, 01:00 AM Night Scheduler & Subdomain Storage HTML
        const emailRulesHtml = as.email_notifications.map(e => `
            <div class="ow-kv-row" style="flex-direction:column; gap:0.25rem;">
                <div style="display:flex; justify-content:space-between; width:100%; flex-wrap:wrap;">
                    <strong style="color:var(--secondary);">${e.trigger}</strong>
                    <span class="ow-pill maroon">${e.recipient}</span>
                </div>
                <div style="font-size:0.83rem; color:var(--text-muted);">${e.message}</div>
            </div>
        `).join('');

        const nightCronHtml = Object.entries(as.night_scheduler).map(([k, v]) => `
            <div class="ow-kv-row"><span class="ow-kv-key">${k}</span><span class="ow-kv-val">${v}</span></div>
        `).join('');

        const subdomainDocsHtml = as.subdomain_storage.document_categories.map(cat => `
            <div class="ow-kv-row">
                <span class="ow-kv-key"><i class="fa-solid fa-cloud-arrow-up" style="color:#16a34a; margin-right:6px;"></i> ${cat}</span>
                <span class="ow-pill green">Subdomain Isolated</span>
            </div>
        `).join('');

        // 9. Any Future Custom Sections Added to JSON (`custom_sections: []`)
        const customSectionsHtml = (officerData.custom_sections || []).map((cs, i) => `
            <section class="section-padding bg-subtle-pattern" style="padding-top:1rem;">
                <div class="container">
                    <div class="ow-card">
                        <div class="ow-card-head">
                            <h3 class="ow-card-title"><span class="ow-icon-badge"><i class="fa-solid fa-star"></i></span> ${cs.title || 'Additional Workflow Section ' + (i + 1)}</h3>
                            <span class="ow-pill green">Added via JSON</span>
                        </div>
                        <p style="font-size:0.92rem; color:var(--text-muted);">${cs.description || ''}</p>
                    </div>
                </div>
            </section>
        `).join('');

        root.innerHTML = `
            <!-- SECTION 1: MASTERS & SCHEME FINANCIALS -->
            <section id="sec-masters" class="section-padding bg-subtle-pattern" style="padding-top: 2rem; padding-bottom: 3rem;">
                <div class="container">
                    <div class="section-title-wrapper" style="margin-bottom: 2rem;">
                        <span class="section-subtitle" style="color: var(--primary-dark);">Stage 01 • Project Foundation</span>
                        <h2 class="section-title">Master Components & Scheme Financial Setup</h2>
                    </div>

                    <div class="ow-grid-2">
                        <div class="ow-card">
                            <div class="ow-card-head">
                                <h3 class="ow-card-title"><span class="ow-icon-badge"><i class="fa-solid fa-sitemap"></i></span> Divisions, Sub-Divisions & Property Types</h3>
                                <span class="ow-pill">Master Data</span>
                            </div>
                            <div class="ow-kv-list">
                                ${divisionsHtml}
                                <div class="ow-kv-row">
                                    <span class="ow-kv-key">Property Categories</span>
                                    <span class="ow-kv-val">${ms.property_categories.join(' • ')}</span>
                                </div>
                                ${propTypesHtml}
                            </div>
                        </div>

                        <div class="ow-card">
                            <div class="ow-card-head">
                                <h3 class="ow-card-title"><span class="ow-icon-badge"><i class="fa-solid fa-coins"></i></span> Scheme Creation & Financial Formula</h3>
                                <span class="ow-pill green">10% / 15% / 60 EMIs</span>
                            </div>
                            <div class="ow-kv-list">
                                ${schemeAttrHtml}
                                ${schemeFinHtml}
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- SECTION 2: 2 ONBOARDING METHODS & 4-STEP ALLOTTEE WIZARD -->
            <section id="sec-onboarding" class="section-padding" style="background: #ffffff; padding-top: 3.5rem; padding-bottom: 3.5rem;">
                <div class="container">
                    <div class="section-title-wrapper" style="margin-bottom: 2rem;">
                        <span class="section-subtitle" style="color: var(--primary-dark);">Stage 02 • Allottee Creation & Third-Portal Sync</span>
                        <h2 class="section-title">Two Onboarding Methods & 4-Step Creation Flow</h2>
                    </div>

                    <div class="ow-grid-2" style="margin-bottom: 2rem;">
                        ${methodsHtml}
                    </div>

                    <div class="ow-grid-2">
                        ${wizardStepsHtml}
                    </div>
                </div>
            </section>

            <!-- SECTION 3: ALLOTTEE LIST 360° EXPANDABLE RECORD -->
            <section id="sec-allottee-list" class="section-padding bg-subtle-pattern" style="padding-top: 3.5rem; padding-bottom: 3.5rem;">
                <div class="container">
                    <div class="section-title-wrapper" style="margin-bottom: 2rem;">
                        <span class="section-subtitle" style="color: var(--primary-dark);">Stage 03 • Allottee Registry View</span>
                        <h2 class="section-title">Allottee List — Complete 4-Panel Record Structure</h2>
                    </div>

                    <div class="ow-card" style="margin-bottom: 1.5rem;">
                        <div class="ow-card-head">
                            <h3 class="ow-card-title"><span class="ow-icon-badge"><i class="fa-solid fa-table-list"></i></span> ALLOTTEE DETAILS — LIVE RECORD SUMMARY</h3>
                            <span class="ow-pill green">Sync Status: Synced</span>
                        </div>
                        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(175px, 1fr)); gap:0.75rem;">
                            ${headerBadgesHtml}
                        </div>
                    </div>

                    <div class="ow-grid-2">
                        ${allotteePanelsHtml}
                    </div>
                </div>
            </section>

            <!-- SECTION 4: 4-ROLE WORKFLOW PIPELINE -->
            <section id="sec-workflow-roles" class="section-padding" style="background:#ffffff; padding-top: 3.5rem; padding-bottom: 3.5rem;">
                <div class="container">
                    <div class="section-title-wrapper" style="margin-bottom: 2rem;">
                        <span class="section-subtitle" style="color: var(--primary-dark);">Stage 04 • Application_for = 'allotment'</span>
                        <h2 class="section-title">4-Stage Officer Approval Workflow Chain</h2>
                    </div>

                    <div class="ow-grid-4">
                        ${workflowRolesHtml}
                    </div>
                </div>
            </section>

            <!-- SECTION 5: OFFICER PORTAL DASHBOARD & WORKLOAD CALENDAR -->
            <section id="sec-officer-dashboard" class="section-padding bg-subtle-pattern" style="padding-top: 3.5rem; padding-bottom: 3.5rem;">
                <div class="container">
                    <div class="section-title-wrapper" style="margin-bottom: 2rem;">
                        <span class="section-subtitle" style="color: var(--primary-dark);">Stage 05 • Dealing Assistant Portal Login</span>
                        <h2 class="section-title">Official Quick View — Officer Dashboard & Calendar</h2>
                    </div>

                    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(170px, 1fr)); gap:0.85rem; margin-bottom:1.5rem;">
                        ${dashCountersHtml}
                    </div>

                    <div class="ow-grid-2" style="margin-bottom: 1.5rem;">
                        <div class="ow-card">
                            <div class="ow-card-head">
                                <h3 class="ow-card-title"><span class="ow-icon-badge"><i class="fa-solid fa-user-Astronaut"></i><i class="fa-solid fa-user-lock"></i></span> Officer Session & Login Telemetry</h3>
                                <span class="ow-pill green">Active Session</span>
                            </div>
                            <div class="ow-kv-list">${loginRowsHtml}</div>
                        </div>

                        <div class="ow-card">
                            <div class="ow-card-head">
                                <h3 class="ow-card-title"><span class="ow-icon-badge"><i class="fa-solid fa-calendar-days"></i></span> Officer Calendar (2021–2031) & Notices</h3>
                                <span class="ow-pill">1 App(s) on 5 Nov</span>
                            </div>
                            <div class="ow-kv-list">${calMetaHtml}</div>
                        </div>
                    </div>

                    <div class="ow-card">
                        <div class="ow-card-head">
                            <h3 class="ow-card-title"><span class="ow-icon-badge"><i class="fa-solid fa-inbox"></i></span> Pending Applications Queue (Awaiting Officer Review)</h3>
                            <span class="ow-pill amber">View All Available</span>
                        </div>
                        <div class="ow-table-wrap">
                            <table class="ow-table">
                                <thead>
                                    <tr>
                                        <th>Application No</th>
                                        <th>Type</th>
                                        <th>Allottee Name</th>
                                        <th>Property No</th>
                                        <th>Created Date</th>
                                        <th>Pending Duration</th>
                                        <th>Priority</th>
                                        <th>Due Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>${pendingRowsHtml}</tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>

            <!-- SECTION 6: OFFICER APPLICATION REVIEW WORKSPACE & HINDI GREEN-SHEET NOTING -->
            <section id="sec-application-view" class="section-padding" style="background:#ffffff; padding-top: 3.5rem; padding-bottom: 3.5rem;">
                <div class="container">
                    <div class="section-title-wrapper" style="margin-bottom: 2rem;">
                        <span class="section-subtitle" style="color: var(--primary-dark);">Stage 06 • Officer File Inspection Screen</span>
                        <h2 class="section-title">Application View, Hindi Green-Sheet Noting & Audit Trail</h2>
                    </div>

                    <div style="background:#fef2f2; border:1.5px solid #fecaca; border-left:6px solid #7f1d1d; padding:1rem 1.25rem; border-radius:1rem; margin-bottom:1.5rem; color:#7f1d1d; font-weight:800; font-size:0.9rem;">
                        <i class="fa-solid fa-bell"></i> ${av.action_banner}
                    </div>

                    <div class="ow-grid-2" style="margin-bottom: 1.5rem;">
                        <div class="ow-card">
                            <div class="ow-card-head">
                                <h3 class="ow-card-title"><span class="ow-icon-badge"><i class="fa-solid fa-file-lines"></i></span> APPLICATION DETAILS</h3>
                            </div>
                            <div class="ow-kv-list">${appDetailsHtml}</div>
                        </div>

                        <div class="ow-card">
                            <div class="ow-card-head">
                                <h3 class="ow-card-title"><span class="ow-icon-badge"><i class="fa-solid fa-id-card"></i></span> ALLOTTEE INFORMATION (BILINGUAL)</h3>
                            </div>
                            <div class="ow-kv-list">${allotteeInfoHtml}</div>
                        </div>
                    </div>

                    <div class="ow-grid-2" style="margin-bottom: 1.5rem;">
                        <div class="ow-card">
                            <div class="ow-card-head">
                                <h3 class="ow-card-title"><span class="ow-icon-badge"><i class="fa-solid fa-paperclip"></i></span> ATTACHED DOCUMENTS</h3>
                            </div>
                            <div class="ow-kv-list">${attachedDocsHtml}</div>

                            <div class="ow-card-head" style="margin-top:1.5rem;">
                                <h3 class="ow-card-title"><span class="ow-icon-badge"><i class="fa-solid fa-envelope-open-text"></i></span> OFFICE CORRESPONDENCES (LT/OO/OD)</h3>
                            </div>
                            <div class="ow-table-wrap">
                                <table class="ow-table">
                                    <thead>
                                        <tr>
                                            <th>Reference No</th>
                                            <th>Type</th>
                                            <th>Subject</th>
                                            <th>Generated By</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>${corrRowsHtml}</tbody>
                                </table>
                            </div>
                        </div>

                        <div class="ow-card">
                            <div class="ow-card-head">
                                <h3 class="ow-card-title"><span class="ow-icon-badge"><i class="fa-solid fa-pen-nib"></i></span> APPLICATION NOTES (OFFICIAL GREEN-SHEET)</h3>
                                <span class="ow-pill green">OTP Verified</span>
                            </div>
                            ${notesHtml}
                        </div>
                    </div>

                    <div class="ow-card" style="margin-bottom: 1.5rem;">
                        <div class="ow-card-head">
                            <h3 class="ow-card-title"><span class="ow-icon-badge"><i class="fa-solid fa-file-arrow-up"></i></span> DOCUMENT UPLOAD STATUS</h3>
                            <span class="ow-pill maroon">Action: Request Additional Document</span>
                        </div>
                        <div class="ow-table-wrap">
                            <table class="ow-table">
                                <thead>
                                    <tr>
                                        <th>S.No.</th>
                                        <th>Document Name</th>
                                        <th>Upload Status / File</th>
                                        <th>Type</th>
                                        <th>Uploaded By</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>${docUploadRowsHtml}</tbody>
                            </table>
                        </div>
                    </div>

                    <div class="ow-card">
                        <div class="ow-card-head">
                            <h3 class="ow-card-title"><span class="ow-icon-badge"><i class="fa-solid fa-clock-rotate-left"></i></span> APPLICATION MOVEMENT HISTORY — 4 STEPS</h3>
                            <span class="ow-pill green">Complete Audit Trail</span>
                        </div>
                        <div class="ow-table-wrap">
                            <table class="ow-table">
                                <thead>
                                    <tr>
                                        <th>Step</th>
                                        <th>Date & Time</th>
                                        <th>Action</th>
                                        <th>Transferred From</th>
                                        <th>Transferred To</th>
                                        <th>Remarks / Details</th>
                                    </tr>
                                </thead>
                                <tbody>${movementRowsHtml}</tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>

            <!-- SECTION 7: UNIFIED OFFICER ACTION MODAL (QR CAMERA, BYPASS, HINDI & OTP) -->
            <section id="sec-action-modal" class="section-padding bg-subtle-pattern" style="padding-top: 3.5rem; padding-bottom: 3.5rem;">
                <div class="container">
                    <div class="section-title-wrapper" style="margin-bottom: 2rem;">
                        <span class="section-subtitle" style="color: var(--primary-dark);">Stage 07 • Forward / Send Back / Reject / Approve</span>
                        <h2 class="section-title">Unified Officer Action Modal & Live QR Camera Upload</h2>
                        <p style="color: var(--text-muted); max-width: 720px; margin: 0.5rem auto 0; font-size: 0.94rem;">
                            ${am.unified_design_note} (Reference File: <strong>${am.example_application_no}</strong>)
                        </p>
                    </div>

                    <div class="ow-grid-3">
                        ${actionModalCardsHtml}
                    </div>
                </div>
            </section>

            <!-- SECTION 8: EMAIL CC RULES, 01:00 AM NIGHT SCHEDULER & SUBDOMAIN STORAGE -->
            <section id="sec-night-batch" class="ow-maroon-section">
                <div class="container">
                    <div style="max-width: 760px; margin-bottom: 2.5rem;">
                        <span class="live-badge" style="background: rgba(255,255,255,0.14); color: var(--primary);">
                            <i class="fa-solid fa-server"></i> Stage 08 • Background Automation & Storage Isolation
                        </span>
                        <h2 class="section-title" style="color: #ffffff; margin-bottom: 0.75rem;">
                            Email CC Policy, 01:00 AM Night Batch Queue & Subdomain Storage
                        </h2>
                        <p style="color: #f1f5f9; font-size: 1rem; line-height: 1.65;">
                            ${as.subdomain_storage.objective}
                        </p>
                    </div>

                    <div class="ow-grid-3">
                        <div class="ow-card">
                            <div class="ow-card-head">
                                <h3 class="ow-card-title"><span class="ow-icon-badge"><i class="fa-solid fa-envelope"></i></span> Movement & Approval Mail Drops</h3>
                            </div>
                            <div class="ow-kv-list">${emailRulesHtml}</div>
                        </div>

                        <div class="ow-card">
                            <div class="ow-card-head">
                                <h3 class="ow-card-title"><span class="ow-icon-badge"><i class="fa-solid fa-clock"></i></span> 01:00 AM Night Scheduler Batch</h3>
                                <span class="ow-pill amber">01:00 Hrs</span>
                            </div>
                            <div class="ow-kv-list">${nightCronHtml}</div>
                        </div>

                        <div class="ow-card">
                            <div class="ow-card-head">
                                <h3 class="ow-card-title"><span class="ow-icon-badge"><i class="fa-solid fa-hard-drive"></i></span> Separate Subdomain Document Storage</h3>
                            </div>
                            <div class="ow-kv-list">${subdomainDocsHtml}</div>
                        </div>
                    </div>
                </div>
            </section>

            ${customSectionsHtml}
        `;
    }

    // =========================================================================
    // 2. INTERACTIVE HTML5 CANVAS ENGINE (100% DRIVEN BY officerData.canvas_flows)
    // =========================================================================
    const canvas = document.getElementById('officerFlowCanvas');
    const ctx = canvas.getContext('2d');
    let activeFlowIdx = 0;
    let cam = { x: 0, y: 0, zoom: 1 };
    let isPanning = false;
    let panStart = { x: 0, y: 0 };
    let nodes = [];
    let edges = [];
    let selectedNodeIdx = 0;
    let hoveredNodeIdx = -1;
    let particles = [];

    function renderCanvasTabsFromJson() {
        const tabWrap = document.getElementById('owCanvasTabs');
        tabWrap.innerHTML = officerData.canvas_flows.map((f, idx) => `
            <button type="button" class="ow-canvas-tab ${idx === activeFlowIdx ? 'active' : ''}" onclick="switchOfficerCanvasFlow(${idx})">
                <i class="${f.icon || 'fa-solid fa-diagram-project'}"></i> ${f.label}
            </button>
        `).join('');
    }

    function switchOfficerCanvasFlow(idx) {
        activeFlowIdx = idx;
        selectedNodeIdx = 0;
        renderCanvasTabsFromJson();
        layoutOfficerNodes();
        fitOfficerCanvas();
        updateCanvasInfoBar(nodes[0]);
    }

    function setupOfficerCanvas() {
        const wrap = document.getElementById('officerCanvasWrap');
        const dpr = window.devicePixelRatio || 1;
        canvas.width = wrap.clientWidth * dpr;
        canvas.height = wrap.clientHeight * dpr;
        ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
        layoutOfficerNodes();
        fitOfficerCanvas();
        updateCanvasInfoBar(nodes[0]);
    }

    function layoutOfficerNodes() {
        const flow = officerData.canvas_flows[activeFlowIdx] || officerData.canvas_flows[0];
        const isMobile = canvas.clientWidth < 768;
        nodes = [];
        edges = flow.edges || [];
        particles = [];

        if (isMobile) {
            const cardW = 300;
            const cardH = 92;
            flow.nodes.forEach((n, i) => {
                nodes.push({ ...n, idx: i, x: 180, y: 65 + i * 112, w: cardW, h: cardH });
            });
        } else {
            const cardW = 305;
            const cardH = 104;
            const cols = 4;
            flow.nodes.forEach((n, i) => {
                const row = Math.floor(i / cols);
                const colInRow = i % cols;
                // Snake layout (Left-to-Right on Row 0, Right-to-Left on Row 1)
                const effectiveCol = (row % 2 === 1) ? (Math.min(flow.nodes.length - row * cols, cols) - 1 - colInRow) : colInRow;
                nodes.push({
                    ...n,
                    idx: i,
                    x: 190 + effectiveCol * 345,
                    y: 155 + row * 210,
                    w: cardW,
                    h: cardH
                });
            });
        }

        edges.forEach(e => {
            particles.push({ from: e.from, to: e.to, t: Math.random(), speed: 0.0045 });
        });
    }

    function fitOfficerCanvas() {
        if (!nodes.length) return;
        const cw = canvas.clientWidth;
        const ch = canvas.clientHeight;
        let minX = Infinity, maxX = -Infinity, minY = Infinity, maxY = -Infinity;
        nodes.forEach(n => {
            minX = Math.min(minX, n.x - n.w / 2);
            maxX = Math.max(maxX, n.x + n.w / 2);
            minY = Math.min(minY, n.y - n.h / 2);
            maxY = Math.max(maxY, n.y + n.h / 2);
        });
        const gw = (maxX - minX) + 90;
        const gh = (maxY - minY) + 90;
        cam.zoom = Math.min(cw / gw, ch / gh, 1.04);
        cam.zoom = Math.max(cam.zoom, 0.52);
        cam.x = cw / 2 - ((minX + maxX) / 2) * cam.zoom;
        cam.y = ch / 2 - ((minY + maxY) / 2) * cam.zoom;
    }

    function updateCanvasInfoBar(node) {
        if (!node) return;
        document.getElementById('owCanvasNodeTitle').textContent = `${node.badge}: ${node.title}`;
        document.getElementById('owCanvasNodeDesc').textContent = node.desc;
        document.getElementById('owCanvasNodeBadge').textContent = node.badge;
    }

    function renderCanvasFrame() {
        const w = canvas.clientWidth;
        const h = canvas.clientHeight;
        ctx.clearRect(0, 0, w, h);

        const spacing = 24 * cam.zoom;
        if (spacing > 10) {
            ctx.fillStyle = 'rgba(30, 58, 138, 0.10)';
            for (let x = cam.x % spacing; x < w; x += spacing) {
                for (let y = cam.y % spacing; y < h; y += spacing) {
                    ctx.beginPath();
                    ctx.arc(x, y, 1.2, 0, Math.PI * 2);
                    ctx.fill();
                }
            }
        }

        ctx.save();
        ctx.translate(cam.x, cam.y);
        ctx.scale(cam.zoom, cam.zoom);

        edges.forEach(e => {
            const n1 = nodes[e.from];
            const n2 = nodes[e.to];
            if (!n1 || !n2) return;
            ctx.save();
            ctx.beginPath();
            ctx.moveTo(n1.x, n1.y);
            ctx.bezierCurveTo((n1.x + n2.x) / 2, n1.y, (n1.x + n2.x) / 2, n2.y, n2.x, n2.y);
            ctx.strokeStyle = '#94a3b8';
            ctx.lineWidth = 2.5;
            ctx.setLineDash([6, 4]);
            ctx.stroke();
            ctx.setLineDash([]);

            if (e.label) {
                const mx = (n1.x + n2.x) / 2;
                const my = (n1.y + n2.y) / 2;
                ctx.font = '700 10px Inter, sans-serif';
                const tw = ctx.measureText(e.label).width + 16;
                ctx.fillStyle = '#e0e7ff';
                roundRect(ctx, mx - tw / 2, my - 10, tw, 20, 10);
                ctx.fill();
                ctx.fillStyle = '#1e3a8a';
                ctx.textAlign = 'center';
                ctx.fillText(e.label, mx, my + 3.5);
            }
            ctx.restore();
        });

        particles.forEach(p => {
            p.t += p.speed;
            if (p.t > 1) p.t = 0;
            const n1 = nodes[p.from];
            const n2 = nodes[p.to];
            if (!n1 || !n2) return;
            const pt = bezierPt(n1.x, n1.y, (n1.x + n2.x) / 2, n1.y, (n1.x + n2.x) / 2, n2.y, n2.x, n2.y, p.t);
            ctx.save();
            ctx.beginPath();
            ctx.arc(pt.x, pt.y, 5, 0, Math.PI * 2);
            ctx.fillStyle = '#1e3a8a';
            ctx.fill();
            ctx.lineWidth = 2;
            ctx.strokeStyle = '#facc15';
            ctx.stroke();
            ctx.restore();
        });

        nodes.forEach(n => {
            const x = n.x - n.w / 2;
            const y = n.y - n.h / 2;
            const isSel = n.idx === selectedNodeIdx;
            const isHov = n.idx === hoveredNodeIdx;

            ctx.save();
            ctx.shadowColor = isSel ? 'rgba(30, 58, 138, 0.22)' : 'rgba(15, 23, 42, 0.08)';
            ctx.shadowBlur = isSel ? 22 : 12;
            ctx.shadowOffsetY = 5;

            ctx.fillStyle = '#ffffff';
            roundRect(ctx, x, y, n.w, n.h, 16);
            ctx.fill();
            ctx.shadowBlur = 0;

            ctx.fillStyle = n.color || '#1e3a8a';
            roundRect(ctx, x, y, n.w, 8, { tl: 16, tr: 16, br: 0, bl: 0 });
            ctx.fill();

            ctx.lineWidth = isSel ? 2.5 : (isHov ? 2 : 1.2);
            ctx.strokeStyle = isSel ? (n.color || '#1e3a8a') : '#cbd5e1';
            roundRect(ctx, x, y, n.w, n.h, 16);
            ctx.stroke();

            ctx.fillStyle = isSel ? '#facc15' : '#eff6ff';
            roundRect(ctx, x + 14, y + 17, 74, 21, 10);
            ctx.fill();

            ctx.font = '800 9.5px Inter, sans-serif';
            ctx.fillStyle = '#1e3a8a';
            ctx.textAlign = 'left';
            ctx.fillText(n.badge, x + 22, y + 31);

            ctx.fillStyle = '#dcfce7';
            ctx.beginPath();
            ctx.arc(x + n.w - 22, y + 27, 9, 0, Math.PI * 2);
            ctx.fill();
            ctx.fillStyle = '#166534';
            ctx.textAlign = 'center';
            ctx.fillText('✓', x + n.w - 22, y + 31);

            ctx.textAlign = 'left';
            ctx.font = '800 13.5px Outfit, Inter, sans-serif';
            ctx.fillStyle = '#1e3a8a';
            ctx.fillText(n.title, x + 14, y + 59);

            ctx.font = '500 11px Inter, sans-serif';
            ctx.fillStyle = '#475569';
            ctx.fillText(n.sub, x + 14, y + 80);

            ctx.restore();
        });

        ctx.restore();
        requestAnimationFrame(renderCanvasFrame);
    }

    function roundRect(ctx, x, y, w, h, r) {
        if (typeof r === 'number') r = { tl: r, tr: r, br: r, bl: r };
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

    function bezierPt(x0, y0, x1, y1, x2, y2, x3, y3, t) {
        const cx = 3 * (x1 - x0), bx = 3 * (x2 - x1) - cx, ax = x3 - x0 - cx - bx;
        const cy = 3 * (y1 - y0), by = 3 * (y2 - y1) - cy, ay = y3 - y0 - cy - by;
        return { x: ax * t ** 3 + bx * t ** 2 + cx * t + x0, y: ay * t ** 3 + by * t ** 2 + cy * t + y0 };
    }

    function hitNode(sx, sy) {
        const wx = (sx - cam.x) / cam.zoom;
        const wy = (sy - cam.y) / cam.zoom;
        return nodes.find(n => wx >= n.x - n.w / 2 && wx <= n.x + n.w / 2 && wy >= n.y - n.h / 2 && wy <= n.y + n.h / 2);
    }

    function bindOfficerCanvasEvents() {
        canvas.addEventListener('mousedown', e => {
            const hit = hitNode(e.offsetX, e.offsetY);
            if (hit) {
                selectedNodeIdx = hit.idx;
                updateCanvasInfoBar(hit);
                return;
            }
            isPanning = true;
            panStart = { x: e.clientX - cam.x, y: e.clientY - cam.y };
        });

        canvas.addEventListener('mousemove', e => {
            if (isPanning) {
                cam.x = e.clientX - panStart.x;
                cam.y = e.clientY - panStart.y;
                return;
            }
            const hit = hitNode(e.offsetX, e.offsetY);
            hoveredNodeIdx = hit ? hit.idx : -1;
            canvas.style.cursor = hit ? 'pointer' : 'grab';
        });

        window.addEventListener('mouseup', () => { isPanning = false; });
    }

    function zoomOfficerCanvas(f) {
        cam.zoom = Math.min(1.5, Math.max(0.45, cam.zoom * f));
    }

    function downloadOfficerCanvasPng() {
        const a = document.createElement('a');
        a.download = 'jshb-officer-workflow.png';
        a.href = canvas.toDataURL('image/png');
        a.click();
    }

    // =========================================================================
    // 3. LIVE JSON EDITOR MODAL FUNCTIONS
    // =========================================================================
    function openJsonEditorModal() {
        document.getElementById('owJsonEditorTextarea').value = JSON.stringify(officerData, null, 2);
        document.getElementById('owJsonModal').classList.add('open');
    }

    function closeJsonEditorModal() {
        document.getElementById('owJsonModal').classList.remove('open');
    }

    function applyLiveJsonChanges() {
        try {
            const updated = JSON.parse(document.getElementById('owJsonEditorTextarea').value);
            officerData = updated;
            renderAllFromJson();
            closeJsonEditorModal();
        } catch (err) {
            alert('Invalid JSON syntax: ' + err.message);
        }
    }

    function downloadJsonSpecFile() {
        const blob = new Blob([JSON.stringify(officerData, null, 2)], { type: 'application/json' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'jshb-officer-and-admin-workflow-spec.json';
        a.click();
        URL.revokeObjectURL(url);
    }

    document.addEventListener('DOMContentLoaded', () => {
        renderAllFromJson();
        bindOfficerCanvasEvents();
        requestAnimationFrame(renderCanvasFrame);
        window.addEventListener('resize', setupOfficerCanvas);
    });
</script>
@endpush

