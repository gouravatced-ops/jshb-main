@extends('layouts.allottee-dashboard')

@section('title', 'View Allottee | JSHB')

@section('content')
<div class="card border-0 shadow-sm" style="padding:0;">
    <div class="d-flex align-items-center justify-content-between px-4 py-3 border-bottom bg-white">
        <div>
            <h5 class="mb-1">Allottee Dashboard</h5>
            <div class="text-mute fw-bold">Applicant Account</div>
        </div>
        <a
            class="btn btn-outline-secondary"
            href="javascript:void(0)"
            onclick="window.close();">
            Back to List
        </a>
    </div>

    <div style="display:grid;grid-template-columns:360px 1fr;min-height:640px;">
        <aside class="allottee-sidebar">
            <div class="px-3 py-3 border-bottom">
                <div class="small text-uppercase text-secondary fw-bold mb-1">Menu</div>
                <div class="small text-muted">Step-wise allottee process</div>
            </div>

            <div class="px-3 pt-3">
                <div class="progress" style="height:8px;">
                    <div class="progress-bar bg-success" style="width: {{ $progressPercent }}%"></div>
                </div>
                <div class="small text-muted mt-1">Progress: {{ $progressPercent }}%</div>
            </div>

            <a href="#" class="allottee-tab active" data-overview="1"><i class="fa-solid fa-gauge me-2"></i>Quick Overview</a>
            @foreach($steps as $step)
            <a href="#"
                class="allottee-tab process-tab {{ $step->status === 'locked' ? 'locked' : '' }}"
                data-step="{{ $step->step_no }}">
                <span class="badge-step {{ $step->status }}">{{ $step->step_no }}</span>
                {{ $step->title }}
            </a>
            @endforeach
        </aside>

        <section class="p-3 p-md-4 bg-white">
            <div id="allotteeSectionContainer">
                @include('admin.allottee.sections.overview', ['allottee' => $allottee])
            </div>
        </section>
    </div>
</div>

<style>
    .allottee-sidebar {
        border-right: 1px solid #e5e7eb;
        background: linear-gradient(180deg, #f8fafc 0%, #eff6ff 100%);
    }

    .allottee-tab {
        display: block;
        padding: 11px 14px;
        margin: 8px;
        border-radius: 10px;
        color: #1e293b;
        text-decoration: none;
        font-weight: 600;
        border: 1px solid transparent;
    }

    .allottee-tab:hover {
        background: #e2e8f0;
        border-color: #cbd5e1;
    }

    .allottee-tab.active {
        background: #0f766e;
        color: #fff;
        box-shadow: 0 8px 18px rgba(15, 118, 110, .25);
    }

    .allottee-tab.locked {
        opacity: .55;
        cursor: not-allowed;
        pointer-events: none;
    }

    .badge-step {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        font-size: 11px;
        margin-right: 8px;
        font-weight: 700
    }

    .badge-step.completed {
        background: #16a34a;
        color: #fff
    }

    .badge-step.pending {
        background: #f59e0b;
        color: #fff
    }

    .badge-step.locked {
        background: #94a3b8;
        color: #fff
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabs = document.querySelectorAll('.allottee-tab');
        const container = document.getElementById('allotteeSectionContainer');
        const processBaseUrl = @json(route('admin.allottees.process.step', ['allottee' => $allottee, 'stepNo' => '__STEP__']));
        const overviewUrl = @json(route('admin.allottees.section', ['allottee' => $allottee, 'section' => 'overview']));

        tabs.forEach((tab) => {
            tab.addEventListener('click', async function(e) {
                e.preventDefault();
                tabs.forEach((item) => item.classList.remove('active'));
                this.classList.add('active');
                container.innerHTML = '<div style="padding:20px;">Loading...</div>';

                try {
                    const url = this.dataset.overview ? overviewUrl : processBaseUrl.replace('__STEP__', this.dataset.step);
                    const response = await fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    if (!response.ok) throw new Error('Unable to load section');
                    container.innerHTML = await response.text();
                } catch (error) {
                    container.innerHTML = '<div class="alert alert-danger">Failed to load section.</div>';
                }
            });
        });

        document.addEventListener('click', async function(e) {
            const completeBtn = e.target.closest('[data-complete-step]');
            if (!completeBtn) return;
            e.preventDefault();
            const stepNo = completeBtn.getAttribute('data-complete-step');
            const completeUrl = @json(route('admin.allottees.process.complete', ['allottee' => $allottee, 'stepNo' => '__STEP__'])).replace('__STEP__', stepNo);
            const token = document.querySelector('meta[name="csrf-token"]')?.content;

            const response = await fetch(completeUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            if (response.ok) {
                window.location.reload();
            }
        });
    });
</script>
@endsection