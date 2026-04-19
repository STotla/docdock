<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
        font-family: 'DejaVu Sans', sans-serif;
        font-size: 11px;
        color: #1a1a2e;
        background: #fff;
    }

    /* ── HEADER ── */
    .header {
        background: #0f3460;
        padding: 14px 24px;
    }
    .header-left {
        display: inline-block;
        vertical-align: middle;
        width: 68%;
    }
    .header-right {
        display: inline-block;
        vertical-align: middle;
        width: 30%;
        text-align: right;
    }
    .site-name {
        color: #fff;
        font-size: 20px;
        font-weight: 700;
    }
    .site-tagline {
        color: rgba(255,255,255,0.6);
        font-size: 8px;
        letter-spacing: 2px;
        text-transform: uppercase;
        margin-top: 2px;
    }
    .slip-label {
        background: #e94560;
        color: #fff;
        font-size: 9px;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        padding: 4px 12px;
        border-radius: 20px;
    }

    /* ── REF STRIP ── */
    .ref-strip {
        background: #f0f4ff;
        border-left: 4px solid #e94560;
        padding: 7px 24px;
    }
    .ref-left  { display: inline-block; vertical-align: middle; width: 60%; }
    .ref-right { display: inline-block; vertical-align: middle; width: 39%; text-align: right; }
    .ref-label { font-size: 8px; color: #999; text-transform: uppercase; letter-spacing: 1px; }
    .ref-value { font-size: 13px; font-weight: 700; color: #0f3460; letter-spacing: 1px; }

    .status-badge {
        display: inline-block;
        padding: 3px 12px;
        border-radius: 12px;
        font-size: 9px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .confirmed { background: #d4f8e8; color: #1a7a4a; }
    .pending   { background: #fff3cd; color: #856404; }
    .cancelled { background: #f8d7da; color: #842029; }
    .completed { background: #d1ecf1; color: #0c5460; }

    /* ── BODY ── */
    .body { padding: 12px 24px; }

    .section-title {
        font-size: 8px;
        font-weight: 700;
        color: #e94560;
        text-transform: uppercase;
        letter-spacing: 2px;
        border-bottom: 1px solid #f0f0f0;
        padding-bottom: 3px;
        margin-bottom: 7px;
    }

    /* ── TWO COLUMN ── */
    .two-col { width: 100%; margin-bottom: 10px; }
    .col-left  { display: inline-block; vertical-align: top; width: 55%; padding-right: 2%; }
    .col-right { display: inline-block; vertical-align: top; width: 43%; }

    /* ── DOCTOR CARD ── */
    .doctor-card {
        background: #f8f9ff;
        border: 1px solid #e0e4f5;
        border-radius: 6px;
        padding: 10px 12px;
    }
    .doctor-initial {
        display: inline-block;
        vertical-align: middle;
        width: 38px;
        height: 38px;
        background: #0f3460;
        border-radius: 50%;
        color: #fff;
        font-size: 16px;
        font-weight: 700;
        text-align: center;
        line-height: 38px;
        margin-right: 10px;
    }
    .doctor-details { display: inline-block; vertical-align: middle; }
    .doctor-name    { font-size: 12px; font-weight: 700; color: #1a1a2e; }
    .doctor-spec    { font-size: 10px; color: #e94560; font-weight: 600; margin-top: 1px; }
    .doctor-qual    { font-size: 9px; color: #666; margin-top: 1px; }

    /* ── CLINIC BOX ── */
    .clinic-box {
        background: #fff;
        border: 1px solid #e0e4f5;
        border-radius: 6px;
        padding: 10px 12px;
    }
    .clinic-name    { font-size: 12px; font-weight: 700; color: #0f3460; }
    .clinic-address { font-size: 9px; color: #555; margin-top: 3px; line-height: 1.5; }
    .clinic-contact { font-size: 9px; color: #888; margin-top: 4px; }

    /* ── APPOINTMENT GRID (3 columns) ── */
    .appt-grid { margin-bottom: 10px; }
    .appt-row  { width: 100%; }
    .appt-cell {
        display: inline-block;
        vertical-align: top;
        width: 30%;
        margin-right: 3%;
    }
    .appt-cell:last-child { margin-right: 0; }
    .appt-item {
        background: #f8f9ff;
        border: 1px solid #e0e4f5;
        border-radius: 6px;
        padding: 7px 10px;
    }
    .appt-item-label { font-size: 8px; color: #999; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 2px; }
    .appt-item-value { font-size: 12px; font-weight: 700; color: #1a1a2e; }
    .appt-item-sub   { font-size: 8px; color: #888; margin-top: 1px; }

    /* ── FEE BOX ── */
    .fee-box {
        background: #0f3460;
        border-radius: 6px;
        padding: 10px 14px;
        color: #fff;
    }
    .fee-left  { display: inline-block; vertical-align: middle; width: 65%; }
    .fee-right { display: inline-block; vertical-align: middle; width: 34%; text-align: right; }
    .fee-label  { font-size: 8px; color: rgba(255,255,255,0.6); text-transform: uppercase; letter-spacing: 1px; }
    .fee-amount { font-size: 18px; font-weight: 700; }
    .fee-paid   {
        background: #e94560;
        color: #fff;
        font-size: 9px;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        padding: 4px 12px;
        border-radius: 20px;
    }

    /* ── PATIENT BOX ── */
    .patient-box {
        background: #fff;
        border: 1px solid #e0e4f5;
        border-radius: 6px;
        padding: 10px 12px;
    }
    .p-row  { width: 100%; margin-bottom: 5px; }
    .p-cell { display: inline-block; vertical-align: top; width: 48%; }
    .p-label { font-size: 8px; color: #999; text-transform: uppercase; letter-spacing: 1px; }
    .p-value { font-size: 10px; font-weight: 600; color: #1a1a2e; margin-top: 1px; }

    /* ── DIVIDER ── */
    .divider {
        border: none;
        border-top: 1px dashed #e0e4f5;
        margin: 8px 0;
    }

    /* ── QR + INSTRUCTIONS ── */
    .bottom-row { width: 100%; }
    .qr-box {
        display: inline-block;
        vertical-align: top;
        width: 18%;
        text-align: center;
    }
    .qr-box img { width: 75px; height: 75px; }
    .qr-hint { font-size: 7px; color: #999; margin-top: 3px; }

    .instructions-box {
        display: inline-block;
        vertical-align: top;
        width: 79%;
        margin-left: 2%;
    }
    .instruction-item {
        font-size: 9px;
        color: #555;
        padding: 3px 0;
        border-bottom: 1px dashed #eee;
        line-height: 1.4;
    }
    .instruction-item:last-child { border-bottom: none; }
    .bullet { color: #e94560; font-weight: 700; margin-right: 4px; }

    /* ── FOOTER ── */
    .footer {
        background: #f8f9ff;
        border-top: 2px solid #e0e4f5;
        padding: 7px 24px;
        text-align: center;
        margin-top: 10px;
    }
    .footer-text { font-size: 8px; color: #aaa; }
    .footer-site { font-size: 9px; color: #0f3460; font-weight: 700; margin-top: 2px; }
</style>
</head>
<body>

{{-- ══ HEADER ══ --}}
<div class="header">
    <div class="header-left">
        <div class="site-name">{{ config('app.name', 'DocDock') }}</div>
        <div class="site-tagline">Your Health, Our Priority</div>
    </div>
    <div class="header-right">
        <span class="slip-label">Appointment Slip</span>
    </div>
</div>

{{-- ══ REF STRIP ══ --}}
<div class="ref-strip">
    <div class="ref-left">
        <div class="ref-label">Appointment Reference</div>
        <div class="ref-value"># {{ strtoupper($appointment->appointment_id) }}</div>
    </div>
    <div class="ref-right">
        @php
            $statusClass = match($appointment->status) {
                'confirmed' => 'confirmed',
                'pending'   => 'pending',
                'cancelled' => 'cancelled',
                'completed' => 'completed',
                default     => 'pending',
            };
        @endphp
        <span class="status-badge {{ $statusClass }}">{{ ucfirst($appointment->status) }}</span>
    </div>
</div>

{{-- ══ BODY ══ --}}
<div class="body">

    {{-- ── DOCTOR + CLINIC SIDE BY SIDE ── --}}
    <div class="two-col">
        <div class="col-left">
            <div class="section-title">Doctor Information</div>
            <div class="doctor-card">
                <span class="doctor-initial">
                    {{ strtoupper(substr($appointment->doctor->user->name, 0, 1)) }}
                </span>
                <div class="doctor-details">
                    <div class="doctor-name">{{ $appointment->doctor->user->name }}</div>
                    <div class="doctor-spec">{{ $appointment->doctor->specialization->name }}</div>
                    <div class="doctor-qual">{{ $appointment->doctor->qualification ?? '-' }}</div>
                </div>
            </div>
        </div>
        <div class="col-right">
            <div class="section-title">Clinic Details</div>
            <div class="clinic-box">
                <div class="clinic-name">{{ $appointment->doctor->clinic_name }}</div>
                <div class="clinic-address">
                    {{ $appointment->doctor->clinic_address }},
                    {{ $appointment->doctor->city }},
                    {{ $appointment->doctor->state }}
                </div>
                <div class="clinic-contact">
                    📞 {{ $appointment->doctor->phone }}
                    &nbsp;|&nbsp;
                    ✉ {{ $appointment->doctor->user->email }}
                </div>
            </div>
        </div>
    </div>

    {{-- ── APPOINTMENT DETAILS (3 columns in one row) ── --}}
    <div class="section-title">Appointment Details</div>
    <div class="appt-grid">
        <div class="appt-row">
            <div class="appt-cell">
                <div class="appt-item">
                    <div class="appt-item-label">📅 Date</div>
                    <div class="appt-item-value">
                        {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d M Y') }}
                    </div>
                    <div class="appt-item-sub">
                        {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('l') }}
                    </div>
                </div>
            </div>
            <div class="appt-cell">
                <div class="appt-item">
                    <div class="appt-item-label">🕐 Start Time</div>
                    <div class="appt-item-value">
                        {{ \Carbon\Carbon::parse($appointment->appointment_start_time)->format('h:i A') }}
                    </div>
                    <div class="appt-item-sub">Arrive 10 mins early</div>
                </div>
            </div>
            <div class="appt-cell">
                <div class="appt-item">
                    <div class="appt-item-label">🕐 End Time</div>
                    <div class="appt-item-value">
                        {{ \Carbon\Carbon::parse($appointment->appointment_end_time)->format('h:i A') }}
                    </div>
                    <div class="appt-item-sub">Estimated end</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── FEE + PATIENT SIDE BY SIDE ── --}}
    <div class="two-col">
        <div class="col-left">
            <div class="section-title">Consultation Fee</div>
            <div class="fee-box">
                <div class="fee-left">
                    <div class="fee-label">Amount Paid</div>
                    <div class="fee-amount">
                        {{ Number::currency($appointment->amount, $appointment->currency) }}
                    </div>
                </div>
                <div class="fee-right">
                    <span class="fee-paid">✓ Paid</span>
                </div>
            </div>
        </div>
        <div class="col-right">
            <div class="section-title">Patient Information</div>
            <div class="patient-box">
                <div class="p-row">
                    <div class="p-cell">
                        <div class="p-label">Full Name</div>
                        <div class="p-value">{{ $appointment->name }}</div>
                    </div>
                    <div class="p-cell">
                        <div class="p-label">Phone</div>
                        <div class="p-value">{{ $appointment->phone ?? 'N/A' }}</div>
                    </div>
                </div>
                <div class="p-row">
                    <div class="p-cell">
                        <div class="p-label">Email</div>
                        <div class="p-value">{{ $appointment->email }}</div>
                    </div>
                    <div class="p-cell">
                        <div class="p-label">Booked On</div>
                        <div class="p-value">
                            {{ \Carbon\Carbon::parse($appointment->created_at)->format('d M Y') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <hr class="divider">

    {{-- ── QR CODE + INSTRUCTIONS ── --}}
    <div class="bottom-row">
        <div class="qr-box">
            {{-- ✅ Base64 PNG — works correctly in DomPDF --}}
<img src="data:image/svg+xml;base64,{{ $qrCode }}" width="120" height="120">            <div class="qr-hint">Scan to verify appointment</div>
        </div>
        <div class="instructions-box">
            <div class="section-title" style="margin-bottom:5px;">Important Instructions</div>
            <div class="instruction-item"><span class="bullet">›</span> Carry a valid government-issued photo ID</div>
            <div class="instruction-item"><span class="bullet">›</span> Bring previous prescriptions & medical reports if any</div>
            <div class="instruction-item"><span class="bullet">›</span> Arrive at least 10 minutes before your slot</div>
            <div class="instruction-item"><span class="bullet">›</span> Cancel 24 hours in advance to avoid charges</div>
            <div class="instruction-item"><span class="bullet">›</span> This slip is valid for the date and time mentioned only</div>
        </div>
    </div>

</div>

{{-- ══ FOOTER ══ --}}
<div class="footer">
    <div class="footer-text">
        System-generated slip — no signature required.
        Support: support@{{ strtolower(str_replace(' ', '', config('app.name'))) }}.com
    </div>
    <div class="footer-site">{{ config('app.name') }} &nbsp;•&nbsp; {{ config('app.url') }}</div>
</div>

</body>
</html>