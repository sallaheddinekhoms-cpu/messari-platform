<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>استمارة ترشح ووصل إيداع - {{ $application->application_number }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800;900&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Tajawal', sans-serif;
        }
        body {
            background-color: #f8fafc;
            color: #0f172a;
            padding: 20px;
        }
        .sheet {
            background: #ffffff;
            max-width: 820px;
            margin: 0 auto;
            padding: 30px 40px;
            border: 2px solid #0f5132;
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            position: relative;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #cbd5e1;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header-text {
            text-align: center;
            flex-grow: 1;
        }
        .header-text h3 {
            font-size: 13px;
            font-weight: 700;
            color: #334155;
            margin-bottom: 3px;
        }
        .header-text h2 {
            font-size: 15px;
            font-weight: 800;
            color: #0f5132;
            margin-bottom: 4px;
        }
        .header-text h1 {
            font-size: 18px;
            font-weight: 900;
            color: #1e293b;
        }
        .logo-img {
            width: 75px;
            height: 75px;
            object-fit: contain;
        }
        .badge-box {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f1f5f9;
            border: 1px dashed #94a3b8;
            padding: 10px 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        .badge-box .app-num {
            font-size: 16px;
            font-weight: 900;
            color: #0f5132;
            direction: ltr;
        }
        .badge-box .app-status {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 700;
        }
        .status-approved { background: #dcfce7; color: #166534; }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-reserved { background: #fee2e2; color: #991b1b; }
        .status-rejected { background: #f1f5f9; color: #475569; }

        .section-title {
            font-size: 14px;
            font-weight: 800;
            color: #0f5132;
            background: #f0fdf4;
            padding: 6px 12px;
            border-right: 4px solid #0f5132;
            margin: 15px 0 10px 0;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px 20px;
            font-size: 13px;
        }
        .info-item {
            display: flex;
            align-items: baseline;
        }
        .info-label {
            font-weight: 700;
            color: #64748b;
            min-width: 140px;
        }
        .info-value {
            font-weight: 600;
            color: #0f172a;
        }
        .checklist {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 6px 15px;
            font-size: 12px;
            margin-top: 8px;
        }
        .check-item {
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .check-box {
            width: 16px;
            height: 16px;
            border: 1.5px solid #0f5132;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 900;
            color: #0f5132;
            border-radius: 3px;
        }
        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #e2e8f0;
        }
        .sig-block {
            text-align: center;
            width: 30%;
        }
        .sig-title {
            font-size: 13px;
            font-weight: 700;
            color: #334155;
            margin-bottom: 45px;
        }
        .sig-space {
            font-size: 11px;
            color: #94a3b8;
        }
        .print-btn-bar {
            text-align: center;
            margin-bottom: 20px;
        }
        .btn-print {
            background: #0f5132;
            color: #ffffff;
            padding: 10px 25px;
            border: none;
            border-radius: 6px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        @media print {
            body {
                background: #ffffff;
                padding: 0;
            }
            .sheet {
                border: 1.5px solid #0f5132;
                box-shadow: none;
                margin: 0;
                width: 100%;
                max-width: 100%;
            }
            .print-btn-bar {
                display: none;
            }
        }
    </style>
</head>
<body>

    <div class="print-btn-bar">
        <button onclick="window.print()" class="btn-print">🖨️ طباعة الاستمارة الرسمية أو الحفظ كـ PDF</button>
    </div>

    <div class="sheet">
        <!-- Header -->
        <div class="header">
            <img src="{{ asset('images/league-logo.png') }}" alt="شعار الرابطة" class="logo-img">
            <div class="header-text">
                <h3>الجمهورية الجزائرية الديمقراطية الشعبية</h3>
                <h3>الاتحاد الجزائري لكرة القدم (FAF) - الرابطة الجهوية لكرة القدم بالبليدة</h3>
                <h2>المديرية واللجنة التقنية الجهوية (DTR)</h2>
                <h1>استمارة ترشح ووصل إيداع ملف تكوين المدربين</h1>
            </div>
            <img src="{{ asset('images/masari-logo.jpg') }}" alt="مساري" class="logo-img">
        </div>

        <!-- File ID & Status -->
        <div class="badge-box">
            <div>
                <span style="font-size: 13px; color: #64748b; font-weight: 600;">رقم ملف الترشح:</span>
                <span class="app-num">{{ $application->application_number }}</span>
            </div>
            <div>
                <span style="font-size: 13px; color: #64748b; font-weight: 600;">تاريخ الإيداع:</span>
                <span style="font-weight: 700;">{{ $application->created_at->format('Y/m/d - H:i') }}</span>
            </div>
            <div>
                <span class="app-status {{ 'status-' . $application->status }}">
                    @if($application->status === 'approved') ✅ مصادق ومقبول نهائياً
                    @elseif($application->status === 'pending') ⏳ قيد تدقيق DTR
                    @elseif($application->status === 'reserved') ⚠️ ملف به تحفظ
                    @else ❌ ملف مرفوض
                    @endif
                </span>
            </div>
        </div>

        <!-- Course Information -->
        <div class="section-title">1. الدورة التكوينية والتخصص المستهدف</div>
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">مسمى الدورة:</span>
                <span class="info-value" style="color: #0f5132; font-weight: 800;">{{ $application->course->title ?? 'تكوين تدريبي' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">رمز الدورة:</span>
                <span class="info-value">{{ $application->course->code ?? 'DTR-2026' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">التخصص التدريبي:</span>
                <span class="info-value">{{ $application->course->course_type_label ?? 'مدربي كرة القدم' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">مقر وتاريخ التربص:</span>
                <span class="info-value">{{ $application->course->location ?? 'البليدة' }} (انطلاق: {{ $application->course->start_date?->format('Y/m/d') ?? 'لاحقاً' }})</span>
            </div>
        </div>

        <!-- Candidate Personal Info -->
        <div class="section-title">2. الهوية والمعلومات الشخصية للمترشح</div>
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">الاسم واللقب (عربي):</span>
                <span class="info-value" style="font-size: 15px; font-weight: 800;">{{ $application->full_name_ar }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Nom et Prénom:</span>
                <span class="info-value">{{ $application->first_name_fr }} {{ $application->last_name_fr }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">تاريخ ومكان الميلاد:</span>
                <span class="info-value">{{ $application->birth_date?->format('Y/m/d') }} بـ {{ $application->birth_place }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">رقم التعريف الوطني (NIN):</span>
                <span class="info-value">{{ $application->national_id_number ?: 'غير مسجل' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">ولاية الإقامة:</span>
                <span class="info-value">{{ $application->wilaya->name_ar ?? 'ولاية البليدة' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">العنوان الشخصي:</span>
                <span class="info-value">{{ $application->address ?: 'مقر الإقامة المعتمد' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">رقم الهاتف المحمول:</span>
                <span class="info-value" style="direction: ltr; font-weight: 700;">{{ $application->phone }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">البريد الإلكتروني:</span>
                <span class="info-value">{{ $application->email }}</span>
            </div>
        </div>

        <!-- Sporting Experience -->
        <div class="section-title">3. المسار الرياضي والصفة والمستوى الدراسي</div>
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">المستوى الدراسي:</span>
                <span class="info-value">{{ $application->education_level }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">النادي الحالي الممارس به:</span>
                <span class="info-value">{{ $application->current_club ?: 'بدون نادٍ حالياً' }}</span>
            </div>
            <div class="info-item" style="grid-column: span 2;">
                <span class="info-label">المسار الكروي السابق:</span>
                <span class="info-value">{{ $application->sporting_experience ?: 'حارس مرمى / لاعب كرة قدم سابق' }}</span>
            </div>
            @if($application->previous_diplomas)
            <div class="info-item" style="grid-column: span 2;">
                <span class="info-label">الشهادات والدبلومات السابقة:</span>
                <span class="info-value">{{ $application->previous_diplomas }}</span>
            </div>
            @endif
        </div>

        <!-- 9 Documents Audit Checklist -->
        <div class="section-title">4. بطاقة مراجعة وتدقيق الملف الإداري الرسمي (الوثائق الـ 9)</div>
        <div class="checklist">
            <div class="check-item">
                <div class="check-box">{{ $application->doc_handwritten_request ? '✓' : '' }}</div>
                <span>1. طلب خطي للمشاركة في التكوين</span>
            </div>
            <div class="check-item">
                <div class="check-box">{{ $application->doc_photo ? '✓' : '' }}</div>
                <span>2. صورتان شمسيتان حديثتان</span>
            </div>
            <div class="check-item">
                <div class="check-box">{{ $application->doc_id_card ? '✓' : '' }}</div>
                <span>3. نسخة من بطاقة التعريف الوطنية</span>
            </div>
            <div class="check-item">
                <div class="check-box">{{ $application->doc_former_license ? '✓' : '' }}</div>
                <span>4. رخصة حارس سابق / إثبات الممارسة</span>
            </div>
            <div class="check-item">
                <div class="check-box">{{ $application->doc_medical_certificate ? '✓' : '' }}</div>
                <span>5. شهادة طبية عامة وصدرية</span>
            </div>
            <div class="check-item">
                <div class="check-box">{{ $application->doc_judicial_record ? '✓' : '' }}</div>
                <span>6. شهادة السوابق العدلية (صحيفة 03)</span>
            </div>
            <div class="check-item">
                <div class="check-box">{{ $application->doc_residence_certificate ? '✓' : '' }}</div>
                <span>7. شهادة الإقامة</span>
            </div>
            <div class="check-item">
                <div class="check-box">{{ $application->doc_birth_certificate ? '✓' : '' }}</div>
                <span>8. شهادة الميلاد</span>
            </div>
            <div class="check-item">
                <div class="check-box">{{ $application->doc_school_certificate ? '✓' : '' }}</div>
                <span>9. شهادة مدرسية (أولى ثانوي فأكثر)</span>
            </div>
            <div class="check-item">
                <div class="check-box">{{ $application->doc_diplomas ? '✓' : '' }}</div>
                <span>10. نسخ الشهادات السابقة (FAF / CAF)</span>
            </div>
        </div>

        <!-- Review notes if any -->
        @if($application->review_notes)
        <div style="margin-top: 15px; padding: 10px 15px; background: #fffbeb; border: 1px solid #fde68a; border-radius: 6px; font-size: 13px;">
            <strong>ملاحظات المديرية التقنية الجهوية:</strong> {{ $application->review_notes }}
        </div>
        @endif

        <!-- Signatures & Stamp -->
        <div class="signatures">
            <div class="sig-block">
                <div class="sig-title">توقيع المترشح المعني</div>
                <div class="sig-space">أصرح بصحة الوثائق المرفقة</div>
            </div>
            <div class="sig-block">
                <div class="sig-title">المدير الفني الجهوي (DTR)</div>
                <div class="sig-space">تأشيرة ومصادقة اللجنة</div>
            </div>
            <div class="sig-block">
                <div class="sig-title">الرابطة الجهوية لكرة القدم بالبليدة</div>
                <div class="sig-space">ختم وإمضاء الإدارة</div>
            </div>
        </div>
    </div>

</body>
</html>
