@extends('layouts.main')

@section('title', 'Send Back Application | JSHB')

@section('content')
@include('components.partials.compact-css')
<div class="compact-wrapper">
    <div class="compact-card col-span-12">
        <div class="compact-card-header header-yellow" style="display: flex; justify-content: space-between; align-items: center;">
            <span><i class="fa-solid fa-reply" style="margin-right: 8px;"></i> Send Back Application <span style="opacity: 0.7; font-size: 14px; font-weight: 500; margin-left: 5px;">| No: {{ $application->application_no }}</span></span>
            <div>
                <button type="button" onclick="openQrModal()" style="font-weight: 600; font-size: 13px; padding: 6px 12px; border-radius: 6px; border: none; color: #856404; background: #fff3cd; cursor: pointer; margin-right: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);"><i class="fa-solid fa-mobile-screen-button" style="margin-right: 5px;"></i> Live Image Upload</button>
                <a href="{{ route('engineer.applications.show', $application) }}" class="btn btn-outline-warning btn-sm" style="background: rgba(255,255,255,0.7); font-weight: 600; color: #856404; border-color: #856404;"><i class="fa-solid fa-arrow-left"></i> Back to Review</a>
            </div>
        </div>
        <div class="compact-card-body">

            <style>
                .forward-card-radio {
                    display: none;
                }

                .forward-card-label {
                    display: flex;
                    align-items: center;
                    padding: 12px 15px;
                    border: 2px solid #e2e8f0;
                    border-radius: 8px;
                    cursor: pointer;
                    transition: all 0.2s;
                    background: #fff;
                    margin-bottom: 10px;
                    position: relative;
                    overflow: hidden;
                }

                .forward-card-label:hover {
                    border-color: #cbd5e1;
                    background: #f8fafc;
                }

                .forward-card-radio:checked+.forward-card-label {
                    border-color: #856404;
                    background: #fff3cd;
                    box-shadow: 0 4px 6px -1px rgba(133, 100, 4, 0.1);
                }

                .forward-card-radio:checked+.forward-card-label::before {
                    content: '\f058';
                    font-family: 'Font Awesome 6 Free';
                    font-weight: 900;
                    color: #856404;
                    position: absolute;
                    right: 15px;
                    top: 50%;
                    transform: translateY(-50%);
                    font-size: 20px;
                }

                .forward-avatar {
                    width: 40px;
                    height: 40px;
                    border-radius: 50%;
                    background: #e2e8f0;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    margin-right: 15px;
                    color: #64748b;
                    font-weight: bold;
                    font-size: 16px;
                    transition: all 0.2s;
                }

                .forward-card-radio:checked+.forward-card-label .forward-avatar {
                    background: #856404;
                    color: #fff;
                }

                .forward-details h6 {
                    margin: 0 0 3px 0;
                    font-size: 15px;
                    color: #1e293b;
                    font-weight: 600;
                }

                .forward-details p {
                    margin: 0;
                    font-size: 12px;
                    color: #64748b;
                }

                .forward-group-title {
                    font-size: 12px;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                    color: #64748b;
                    font-weight: 700;
                    margin: 15px 0 10px;
                    border-bottom: 1px solid #e2e8f0;
                    padding-bottom: 5px;
                }

                .forward-cards-container::-webkit-scrollbar {
                    width: 6px;
                }

                .forward-cards-container::-webkit-scrollbar-track {
                    background: #f1f1f1;
                    border-radius: 4px;
                }

                .forward-cards-container::-webkit-scrollbar-thumb {
                    background: #cbd5e1;
                    border-radius: 4px;
                }

                .forward-cards-container::-webkit-scrollbar-thumb:hover {
                    background: #94a3b8;
                }
            </style>

            <div style="background: #fff3cd; padding: 15px; border-radius: 6px; border-left: 5px solid #ffeeba; margin-bottom: 20px; color: #856404;">
                <i class="fa-solid fa-info-circle"></i> <strong>Note:</strong> Provide detailed objections or reasons for sending back this application. Your noting is digitally recorded as part of the official file.
            </div>

            <form action="{{ route('engineer.applications.action', $application) }}" method="POST">
                @csrf
                <input type="hidden" name="action_type" value="send_back">

                <div class="row">
                    <div class="col-md-12 mb-4">
                        <label style="font-weight: 600; font-size: 15px; margin-bottom: 8px; display: block; color: #333;">Send Back To <span class="text-danger">*</span></label>
                        @if(!empty($sendBackOptions))
                        <div class="forward-cards-container" style="max-height: 280px; overflow-y: auto; padding-right: 5px;">
                            @foreach($sendBackOptions as $index => $option)
                            <div class="forward-group-title" @if($index==0) style="margin-top: 0;" @endif>
                                <i class="fa-solid fa-sitemap" style="margin-right: 5px;"></i> {{ $option['step']->step_name }} ({{ $option['step']->role->name ?? 'Role' }})
                            </div>
                            <div style="display: flex; flex-wrap: wrap; gap: 15px; margin-bottom: 20px;">
                                @foreach($option['engineers'] as $engineer)
                                <div class="forward-card-wrapper" style="flex: 0 1 320px; max-width: 100%;">
                                    <input type="radio" name="send_back_to_user" id="fwd_{{ $engineer->id }}_{{ $option['step']->id }}" value="{{ $engineer->id }}|{{ $option['step']->id }}" class="forward-card-radio" required>
                                    <label for="fwd_{{ $engineer->id }}_{{ $option['step']->id }}" class="forward-card-label" style="height: 100%; margin-bottom: 0;">
                                        <div class="forward-avatar">
                                            {{ substr($engineer->name, 0, 1) }}
                                        </div>
                                        <div class="forward-details">
                                            <h6>{{ $engineer->name }}</h6>
                                            <p><i class="fa-regular fa-id-badge" style="margin-right: 4px;"></i>{{ $option['step']->role->name ?? '' }}</p>
                                        </div>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="alert alert-danger" style="margin-bottom: 0; padding: 10px 15px;">
                            <i class="fa-solid fa-triangle-exclamation"></i> Cannot send back. No eligible engineers found in previous steps.
                        </div>
                        @endif
                    </div>
                </div>

                <div class="form-group mb-4 summernote-wrapper">
                    <label style="font-weight: 600; font-size: 16px; margin-bottom: 10px; display: block; color: #333;"><i class="fa-solid fa-pen-fancy" style="color: #856404;"></i> Objection Noting / Remarks <span class="text-danger">*</span></label>

                    <!-- Font Family Selection -->
                    <div class="mb-3 p-2" style="background: #f8f9fa; border-radius: 6px; border: 1px solid #e9ecef;">
                        <label class="me-3" style="font-weight: 600; font-size: 14px; color: #495057;">Typing Language:</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input font-family-selector" type="radio" name="font_family" id="font_english_note" value="english" checked>
                            <label class="form-check-label" for="font_english_note" style="margin:0px;">English (Arial)</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input font-family-selector" type="radio" name="font_family" id="font_hindi_note" value="krutidev">
                            <label class="form-check-label" for="font_hindi_note" style="margin:0px;">Hindi (Kruti Dev)</label>
                        </div>
                    </div>

                    <textarea id="summernote" name="remarks" required></textarea>
                </div>

                <hr style="margin: 20px 0; border-top: 1px solid #eaeaea;">

                <div style="text-align: right;">
                    <button type="submit" class="btn btn-warning text-dark" style="font-size: 15px; padding: 8px 20px; font-weight: 600;"><i class="fa-solid fa-reply"></i> Submit Noting & Send Back</button>
                </div>
            </form>
        </div>
    </div>

    @include('components.partials.summernote-editor')
    @include('components.partials.qr-scanner-modal')
    @endsection
