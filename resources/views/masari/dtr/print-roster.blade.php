<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>القائمة الاسمية للمترشحين - {{ $course->title }}</title>
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
        .page {
            background: #ffffff;
            max-width: 1050px;
            margin: 0 auto;
            padding: 30px;
            border: 2px solid #0f5132;
            border-radius: 8px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #cbd5e1;
            padding-bottom: 15px;
            margin-bottom: 15px;
        }
        .header-text {
            text-align: center;
            flex-grow: 1;
        }
        .header-text h3 {
            font-size: 13px;
            font-weight: 700;
            color: #334155;
        }
        .header-text h2 {
            font-size: 15px;
            font-weight: 800;
            color: #0f5132;
        }
        .header-text h1 {
            font-size: 18px;
            font-weight: 900;
            color: #1e293b;
            margin-top: 5px;
        }
        .logo-img {
            width: 70px;
            height: 70px;
            object-fit: contain;
        }
        .meta-strip {
            display: flex;
            justify-content: space-between;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            padding: 10px 15px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 20px;
            color: #14532d;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            margin-bottom: 25px;
        }
        th {
            background: #0f5132;
            color: #ffffff;
            padding: 8px 10px;
            font-weight: 800;
            border: 1px solid #0f5132;
            text-align: center;
        }
        td {
            padding: 8px 10px;
            border: 1px solid #cbd5e1;
            text-align: center;
            font-weight: 600;
        }
        tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .status-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 700;
        }
        .approved { background: #dcfce7; color: #166534; }
        .pending { background: #fef3c7; color: #92400e; }
        .reserved { background: #fee2e2; color: #991b1b; }
        .rejected { background: #f1f5f9; color: #64748b; }

        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #e2e8f0;
        }
        .sig-block {
            text-align: center;
            width: 40%;
        }
        .sig-title {
            font-size: 14px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 50px;
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
        }
        @media print {
            body {
                background: #ffffff;
                padding: 0;
            }
            .page {
                border: none;
                margin: 0;
                width: 100%;
                max-width: 100%;
                padding: 10px;
            }
            .print-btn-bar {
                display: none;
            }
        }
    </style>
</head>
<body>

    <div class="print-btn-bar">
        <button onclick="window.print()" class="btn-print">🖨️ طباعة القائمة الرسمية للتربص</button>
    </div>

    <div class="page">
        <!-- Header -->
        <div class="header">
            <img src="{{ asset('images/league-logo.png') }}" alt="شعار الرابطة" class="logo-img">
            <div class="header-text">
                <h3>الجمهورية الجزائرية الديمقراطية الشعبية</h3>
                <h3>الاتحاد الجزائري لكرة القدم (FAF) - الرابطة الجهوية لكرة القدم بالبليدة</h3>
                <h2>المديرية واللجنة التقنية الجهوية (DTR)</h2>
                <h1>القائمة الاسمية الرسمية للمترشحين لدورة التكوين</h1>
            </div>
            <img src="{{ asset('images/masari-logo.jpg') }}" alt="مساري" class="logo-img">
        </div>

        <div class="meta-strip">
            <div><span>الدورة: </span> {{ $course->title }} ({{ $course->code }})</div>
            <div><span>التخصص: </span> {{ $course->course_type_label }}</div>
            <div><span>المكان: </span> {{ $course->location }}</div>
            <div><span>تاريخ التربص: </span> {{ $course->start_date?->format('Y/m/d') }} - {{ $course->end_date?->format('Y/m/d') }}</div>
            <div><span>إجمالي المترشحين: </span> {{ $applications->count() }} (مقبولين: {{ $applications->where('status', 'approved')->count() }})</div>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 30px;">#</th>
                    <th style="width: 110px;">رقم الملف</th>
                    <th>الاسم واللقب (عربي)</th>
                    <th>Nom & Prénom</th>
                    <th>تاريخ الميلاد</th>
                    <th>الولاية</th>
                    <th>الهاتف</th>
                    <th>النادي الحالي</th>
                    <th style="width: 90px;">الحالة</th>
                    <th style="width: 120px;">توقيع الحضور / ملاحظات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($applications as $index => $app)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td style="direction: ltr; font-weight: 700; color: #0f5132;">{{ $app->application_number }}</td>
                    <td style="font-weight: 800;">{{ $app->full_name_ar }}</td>
                    <td style="direction: ltr;">{{ $app->first_name_fr }} {{ $app->last_name_fr }}</td>
                    <td>{{ $app->birth_date?->format('Y/m/d') }}</td>
                    <td>{{ $app->wilaya->name_ar ?? 'البليدة' }}</td>
                    <td style="direction: ltr;">{{ $app->phone }}</td>
                    <td>{{ $app->current_club ?: '-' }}</td>
                    <td>
                        <span class="status-badge {{ $app->status }}">
                            @if($app->status === 'approved') مقبول
                            @elseif($app->status === 'pending') قيد التدقيق
                            @elseif($app->status === 'reserved') تحفظ
                            @else مرفوض
                            @endif
                        </span>
                    </td>
                    <td></td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" style="padding: 20px; color: #64748b;">لا يوجد مترشحين مسجلين في هذه الدورة حتى الآن.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Signatures -->
        <div class="signatures">
            <div class="sig-block">
                <div class="sig-title">المدير الفني الجهوي (DTR)</div>
                <div style="font-size: 11px; color: #64748b;">تأشيرة ومصادقة اللجنة التقنية</div>
            </div>
            <div class="sig-block">
                <div class="sig-title">رئيس الرابطة الجهوية لكرة القدم بالبليدة</div>
                <div style="font-size: 11px; color: #64748b;">ختم وإمضاء الإدارة العامة</div>
            </div>
        </div>
    </div>

</body>
</html>
