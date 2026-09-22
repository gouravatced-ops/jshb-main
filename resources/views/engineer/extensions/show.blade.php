@extends('layouts.main')

@section('title', 'View Extension Request | Engineer')

@section('content')
<div class="compact-wrapper">
    <div class="compact-card col-span-12">
        <div class="compact-card-header header-blue" style="display: flex; justify-content: space-between; align-items: center;">
            <span>
                <i class="fa-solid fa-eye" style="margin-right: 8px; color: #0d47a1;"></i> View Extension Request 
                <span class="badge" style="background: #0d47a1; color: white; margin-left: 10px;">{{ $extension->application->application_no ?? 'N/A' }}</span>
            </span>
            <div>
                <a href="{{ route('engineer.extensions.index') }}" class="btn btn-sm btn-outline-primary" style="font-weight: 600;"><i class="fa-solid fa-arrow-left"></i> Back to My Extensions</a>
            </div>
        </div>
        
        <div class="compact-card-body">
            <!-- Request Details -->
            <h5 style="font-size: 16px; font-weight: 600; color: #334155; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 1px solid #e2e8f0;">
                <i class="fa-solid fa-circle-info" style="color: #64748b; margin-right: 8px;"></i> Request Information
            </h5>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div style="background: #f8fafc; padding: 15px; border-radius: 6px; border: 1px solid #e2e8f0;">
                    <div style="font-size: 12px; color: #64748b; font-weight: 600; text-transform: uppercase;">Requested By (You)</div>
                    <div style="font-size: 15px; color: #1e293b; font-weight: 600; margin-top: 4px;">{{ $extension->requestedBy->name ?? 'Unknown' }}</div>
                </div>
                <div style="background: #f8fafc; padding: 15px; border-radius: 6px; border: 1px solid #e2e8f0;">
                    <div style="font-size: 12px; color: #64748b; font-weight: 600; text-transform: uppercase;">Submitted At</div>
                    <div style="font-size: 15px; color: #1e293b; font-weight: 600; margin-top: 4px;">{{ $extension->created_at->format('d M Y, h:i A') }}</div>
                </div>
            </div>

            <div style="background: #f1f5f9; padding: 20px; border-radius: 6px; border-left: 4px solid #3b82f6; margin-bottom: 35px;">
                <div style="font-size: 13px; color: #64748b; font-weight: 600; text-transform: uppercase; margin-bottom: 10px;">Your Justification</div>
                <div style="font-size: 15px; color: #334155; line-height: 1.6; background: white; padding: 15px; border-radius: 4px; border: 1px solid #e2e8f0;">
                    {!! $extension->request_reason !!}
                </div>
            </div>

            <h5 style="font-size: 16px; font-weight: 600; color: #334155; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 1px solid #e2e8f0;">
                <i class="fa-solid fa-gavel" style="color: #64748b; margin-right: 8px;"></i> Admin Decision
            </h5>

            @if($extension->status === 'pending')
                <div style="background: #fffbeb; border: 1px solid #fef3c7; padding: 20px; border-radius: 6px; text-align: center;">
                    <i class="fa-solid fa-clock-rotate-left fa-2x" style="color: #d97706; margin-bottom: 10px;"></i>
                    <h6 style="color: #92400e; font-weight: 600; font-size: 16px; margin: 0;">Pending Review</h6>
                    <p style="color: #b45309; font-size: 14px; margin-top: 5px; margin-bottom: 0;">Your request is currently being reviewed by an admin.</p>
                </div>
            @else
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <div style="background: #f8fafc; padding: 15px; border-radius: 6px; border: 1px solid #e2e8f0;">
                        <div style="font-size: 12px; color: #64748b; font-weight: 600; text-transform: uppercase;">Status</div>
                        <div style="font-size: 15px; font-weight: 600; margin-top: 4px;">
                            @if($extension->status === 'approved')
                                <span style="color: #10b981;"><i class="fa-solid fa-circle-check"></i> Approved</span>
                            @elseif($extension->status === 'rejected')
                                <span style="color: #ef4444;"><i class="fa-solid fa-circle-xmark"></i> Rejected</span>
                            @endif
                        </div>
                    </div>
                    <div style="background: #f8fafc; padding: 15px; border-radius: 6px; border: 1px solid #e2e8f0;">
                        <div style="font-size: 12px; color: #64748b; font-weight: 600; text-transform: uppercase;">Action Date</div>
                        <div style="font-size: 15px; color: #1e293b; font-weight: 600; margin-top: 4px;">
                            {{ $extension->approved_at ? $extension->approved_at->format('d M Y, h:i A') : $extension->updated_at->format('d M Y, h:i A') }}
                        </div>
                    </div>
                </div>

                @if($extension->status === 'approved' && $extension->extension_days)
                <div style="background: #ecfdf5; border: 1px solid #a7f3d0; padding: 15px; border-radius: 6px; margin-bottom: 20px;">
                    <span style="color: #065f46; font-weight: 600;"><i class="fa-solid fa-calendar-plus" style="margin-right: 8px;"></i> Extended By: {{ $extension->extension_days }} Day(s)</span>
                </div>
                @endif

                <div style="background: #f8fafc; padding: 20px; border-radius: 6px; border: 1px solid #e2e8f0; margin-bottom: 20px;">
                    <div style="font-size: 13px; color: #64748b; font-weight: 600; text-transform: uppercase; margin-bottom: 10px;">Admin Remarks</div>
                    <div style="font-size: 15px; color: #334155; line-height: 1.6;">
                        {!! $extension->remarks !!}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
