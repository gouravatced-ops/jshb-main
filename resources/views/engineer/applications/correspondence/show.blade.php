@extends('layouts.main')

@section('title', 'View Correspondence | JSHB')

@section('content')
<style>
    @media print {
        @page {
            size: A4;
            margin: 10mm; 
        }
        body {
            margin: 0;
            padding: 0;
            background: #fff;
        }
        body * {
            visibility: hidden;
        }
        #printableArea, #printableArea * {
            visibility: visible;
        }
        #printableArea {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            margin: 0;
            padding: 0;
            box-shadow: none !important;
            border: none !important;
            min-height: auto;
        }
        .no-print {
            display: none !important;
        }
    }
    
    .correspondence-paper {
        background: #fff;
        padding: 40px 60px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        border: 1px solid #eaeaea;
        margin: 0 auto;
        max-width: 850px;
        min-height: 1000px;
        font-family: 'Times New Roman', Times, serif;
    }
    
    .letterhead-header {
        text-align: center;
        border-bottom: 2px solid #2c3e50;
        padding-bottom: 20px;
        margin-bottom: 30px;
    }
    
    .letterhead-header h2 {
        font-size: 24px;
        font-weight: bold;
        color: #2c3e50;
        margin-bottom: 5px;
        text-transform: uppercase;
    }
    
    .letterhead-header h4 {
        font-size: 16px;
        color: #555;
        margin: 0;
    }
    
    .ref-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 30px;
        font-size: 15px;
        font-weight: bold;
    }
    
    .subject-row {
        margin-bottom: 30px;
        font-size: 16px;
    }
    
    .subject-row strong {
        text-decoration: underline;
    }
    
    .content-area {
        font-size: 16px;
        line-height: 1.6;
        text-align: justify;
        min-height: 400px;
    }
    
    .signature-area {
        margin-top: 60px;
        display: flex;
        justify-content: flex-end;
    }
    
    .signature-box {
        text-align: center;
        width: 250px;
    }
    
    .signature-name {
        font-weight: bold;
        font-size: 16px;
        border-top: 1px dashed #999;
        padding-top: 10px;
        margin-top: 50px;
    }
</style>

<div class="compact-wrapper">
    <div class="compact-card col-span-12 no-print" style="margin-bottom: 25px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); border-radius: 8px; overflow: hidden; background: #fff; border: 1px solid #e2e8f0;">
        <div class="compact-card-header header-blue" style="display: flex; justify-content: space-between; align-items: center; padding: 15px 20px; background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
            <span style="font-size: 16px; font-weight: 600; color: #1e293b;"><i class="fa-solid fa-file-invoice" style="margin-right: 8px; color: #0d47a1;"></i> View Office Correspondence <span style="opacity: 0.7; font-size: 14px; font-weight: 500; margin-left: 5px;">| Ref: {{ $correspondence->reference_number }}</span></span>
            <div>
                <a href="{{ route('engineer.applications.show', $application) }}" class="btn btn-outline-primary btn-sm" style="background: rgba(255,255,255,0.7); font-weight: 600; color: #0d47a1; border-color: #0d47a1;"><i class="fa-solid fa-arrow-left"></i> Back to Application</a>
                <button onclick="window.print()" class="btn btn-sm" style="background-color: #0d47a1; border-color: #0d47a1; color: white; font-weight: 600; margin-left: 10px;">
                    <i class="fa-solid fa-print"></i> Print Document
                </button>
            </div>
        </div>
    </div>
</div>

<div id="printableArea" class="correspondence-paper">
    <div class="letterhead-header" style="display: flex; align-items: center; justify-content: center; position: relative;">
        <img src="{{ asset(config('panel.logo')) }}" alt="JSHB Logo" style="height: 80px; position: absolute; left: 0;" onerror="this.src='https://placehold.co/80x80/ffffff/1f7b4d?text=JSHB'">
        <div>
            <h2>Jharkhand State Housing Board</h2>
            <h4>Government of Jharkhand</h4>
            <div style="font-size: 12px; margin-top: 5px; color: #777;">Head Office: Harmu Housing Colony, Ranchi, Jharkhand 834002</div>
        </div>
    </div>
    
    <div class="ref-row">
        <div>Ref No: {{ $correspondence->reference_number }}</div>
        <div>Date: {{ $correspondence->created_at->format('d/m/Y') }}</div>
    </div>
    
    <div class="subject-row">
        <strong>Subject:</strong> {{ $correspondence->subject }}
    </div>
    
    @php
    $corrFontFamily = "";
    if (isset($correspondence->font_family) && $correspondence->font_family === 'krutidev') {
        $corrFontFamily = "font-family: 'KrutiDev011', sans-serif;";
    } else if (isset($correspondence->font_family) && $correspondence->font_family === 'normalhindi') {
        $corrFontFamily = "font-family: 'notosansdevanagari', sans-serif;";
    }
    @endphp
    
    <div class="content-area" style="{{ $corrFontFamily }}">
        {!! $correspondence->content !!}
    </div>
    
    <div class="signature-area">
        <div class="signature-box">
            <div class="signature-name">
                {{ $correspondence->generatedBy->name ?? 'Authorized Signatory' }}<br>
                {{ $correspondence->generatedBy->roleRelation->name ?? 'Officer' }}<br>
                Jharkhand State Housing Board
            </div>
        </div>
    </div>
</div>
@endsection
