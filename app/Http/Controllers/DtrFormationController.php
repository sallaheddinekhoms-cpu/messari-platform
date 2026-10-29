<?php

namespace App\Http\Controllers;

use App\Models\CoachApplication;
use App\Models\CoachCourse;
use App\Models\CoachCourseMaterial;
use App\Models\CoachMaterialView;
use App\Models\User;
use App\Models\Wilaya;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DtrFormationController extends Controller
{
    /**
     * DTR Coaching Portal Home Page
     */
    public function index()
    {
        $courses = CoachCourse::where('is_active', true)
            ->withCount(['applications', 'materials'])
            ->orderBy('id', 'asc')
            ->get();

        $stats = [
            'total_courses' => $courses->count(),
            'open_registrations' => $courses->where('is_registration_open', true)->count(),
            'total_candidates' => CoachApplication::count(),
            'approved_candidates' => CoachApplication::where('status', 'approved')->count(),
        ];

        return view('masari.dtr.index', compact('courses', 'stats'));
    }

    /**
     * Candidacy Application Form (إيداع ملف ترشح جديد)
     */
    public function apply(Request $request)
    {
        $selectedCourseId = $request->query('course_id');
        $selectedCourse = $selectedCourseId ? CoachCourse::find($selectedCourseId) : null;
        
        $courses = CoachCourse::where('is_active', true)
            ->where('is_registration_open', true)
            ->get();
            
        $wilayas = Wilaya::orderBy('code')->get();

        return view('masari.dtr.apply', compact('courses', 'selectedCourse', 'wilayas'));
    }

    /**
     * Submit Candidate Application
     */
    public function submitApplication(Request $request)
    {
        $request->validate([
            'coach_course_id' => 'required|exists:coach_courses,id',
            'first_name_ar' => 'required|string|max:100',
            'last_name_ar' => 'required|string|max:100',
            'first_name_fr' => 'nullable|string|max:100',
            'last_name_fr' => 'nullable|string|max:100',
            'birth_date' => 'required|date',
            'birth_place' => 'required|string|max:150',
            'national_id_number' => 'nullable|string|max:50',
            'wilaya_id' => 'nullable|exists:wilayas,id',
            'address' => 'nullable|string|max:255',
            'phone' => 'required|string|max:30',
            'email' => 'required|email|max:150',
            'password' => 'required|string|min:6|confirmed',
            'education_level' => 'required|string|max:150',
            'sporting_experience' => 'nullable|string|max:255',
            'current_club' => 'nullable|string|max:150',
            // File Uploads
            'doc_handwritten_request' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
            'doc_photo' => 'nullable|file|mimes:jpg,jpeg,png|max:3072',
            'doc_id_card' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
            'doc_player_license' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
            'doc_medical_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
            'doc_police_record' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
            'doc_residence_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
            'doc_birth_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
            'doc_school_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
            'doc_diplomas' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
        ]);

        // Find or create User account for candidate
        $user = User::where('email', $request->email)->first();
        if (! $user) {
            $user = User::create([
                'name' => "{$request->first_name_ar} {$request->last_name_ar}",
                'email' => $request->email,
                'phone' => $request->phone,
                'role' => 'coach',
                'password' => Hash::make($request->password),
                'is_active' => true,
            ]);
        }

        // Generate Application Number e.g. DTR-GK-2026-0001
        $course = CoachCourse::findOrFail($request->coach_course_id);
        $count = CoachApplication::where('coach_course_id', $course->id)->count() + 1;
        $appNumber = "DTR-" . strtoupper(substr($course->course_type, 0, 4)) . "-" . date('Y') . "-" . str_pad($count, 4, '0', STR_PAD_LEFT);

        // Upload documents
        $docFields = [
            'doc_handwritten_request',
            'doc_photo',
            'doc_id_card',
            'doc_player_license',
            'doc_medical_certificate',
            'doc_police_record',
            'doc_residence_certificate',
            'doc_birth_certificate',
            'doc_school_certificate',
            'doc_diplomas',
        ];

        $uploadedPaths = [];
        foreach ($docFields as $doc) {
            if ($request->hasFile($doc)) {
                $file = $request->file($doc);
                $ext = $file->getClientOriginalExtension();
                $filename = "{$appNumber}_{$doc}_" . time() . ".{$ext}";
                $path = $file->storeAs('coach-applications', $filename, 'public');
                $uploadedPaths[$doc] = $path;
            }
        }

        $applicationData = array_merge($request->only([
            'coach_course_id',
            'first_name_ar',
            'last_name_ar',
            'first_name_fr',
            'last_name_fr',
            'birth_date',
            'birth_place',
            'national_id_number',
            'wilaya_id',
            'address',
            'phone',
            'email',
            'education_level',
            'sporting_experience',
            'current_club',
            'previous_diplomas',
        ]), $uploadedPaths, [
            'user_id' => $user->id,
            'application_number' => $appNumber,
            'status' => 'pending',
        ]);

        $application = CoachApplication::create($applicationData);

        // Auto login candidate
        Auth::login($user);

        return redirect()->route('dtr.dashboard')->with('success', "تم تسجيل ملف ترشحكم بنجاح برقم إيداع رسمي: {$appNumber}. يرجى الاحتفاظ بهذا الرقم لمتابعة المعاينة والقبول.");
    }

    /**
     * Show Candidate Login Form
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dtr.dashboard');
        }
        return view('masari.dtr.login');
    }

    /**
     * Authenticate Candidate
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('dtr.dashboard'));
        }

        return back()->withErrors([
            'email' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة.',
        ])->onlyInput('email');
    }

    /**
     * Candidate Dashboard (متابعة الملف والدروس البيداغوجية)
     */
    public function candidateDashboard()
    {
        $user = Auth::user();
        if (! $user) {
            return redirect()->route('dtr.login');
        }

        // If user is Admin or DTR, redirect to Filament admin panel or show applications
        if ($user->isAdmin() || $user->isDtr()) {
            return redirect('/admin/coach-applications');
        }

        $applications = CoachApplication::with(['course.materials'])
            ->where('user_id', $user->id)
            ->orWhere('email', $user->email)
            ->latest()
            ->get();

        return view('masari.dtr.dashboard', compact('user', 'applications'));
    }

    /**
     * Resolve Reservation / Upload corrected document
     */
    public function updateReservation(Request $request, $id)
    {
        $user = Auth::user();
        $application = CoachApplication::where('id', $id)
            ->where(fn($q) => $q->where('user_id', $user->id)->orWhere('email', $user->email))
            ->firstOrFail();

        $docFields = [
            'doc_handwritten_request',
            'doc_photo',
            'doc_id_card',
            'doc_player_license',
            'doc_medical_certificate',
            'doc_police_record',
            'doc_residence_certificate',
            'doc_birth_certificate',
            'doc_school_certificate',
            'doc_diplomas',
        ];

        $updated = false;
        foreach ($docFields as $doc) {
            if ($request->hasFile($doc)) {
                $file = $request->file($doc);
                $ext = $file->getClientOriginalExtension();
                $filename = "{$application->application_number}_{$doc}_updated_" . time() . ".{$ext}";
                $path = $file->storeAs('coach-applications', $filename, 'public');
                $application->{$doc} = $path;
                $updated = true;
            }
        }

        if ($updated) {
            $application->status = 'pending';
            $application->review_notes = 'تم استدراك الوثائق من طرف المترشح، بانتظار إعادة المعاينة من طرف المديرية التقنية.';
            $application->save();

            return back()->with('success', 'تم رفع الوثائق المستدركة بنجاح، وستتم إعادة دراسة ملفك من طرف المديرية التقنية.');
        }

        return back()->with('error', 'يرجى اختيار الوثيقة المطلوبة لإعادة رفعها.');
    }

    /**
     * Download or Access Course Material
     */
    public function downloadMaterial($materialId, $applicationId)
    {
        $user = Auth::user();
        $application = CoachApplication::where('id', $applicationId)
            ->where(fn($q) => $q->where('user_id', $user->id)->orWhere('email', $user->email))
            ->firstOrFail();

        if ($application->status !== 'approved') {
            abort(403, 'غير مخول: الدروس والمحاضرات متاحة حصرياً للمترشحين المقبولين رسمياً في التربص.');
        }

        $material = CoachCourseMaterial::where('id', $materialId)
            ->where('coach_course_id', $application->coach_course_id)
            ->firstOrFail();

        if (! $material->isAvailable()) {
            return back()->with('error', 'عذراً، انتهت صلاحية عرض وتحميل هذا الدرس (تجاوز مدة الـ ' . $material->duration_days . ' يوماً).');
        }

        // Record or update view/download
        CoachMaterialView::updateOrCreate(
            [
                'coach_course_material_id' => $material->id,
                'coach_application_id' => $application->id,
            ],
            [
                'downloaded_at' => now(),
            ]
        );

        if ($material->file_path && Storage::disk('public')->exists($material->file_path)) {
            return Storage::disk('public')->download($material->file_path, "{$material->title}.pdf");
        }

        // If it's a sample path that doesn't exist on disk yet, serve a friendly receipt response or inline preview
        return back()->with('info', "المحاضرة: {$material->title} (متاحة للعرض والتحميل لمدة {$material->remaining_days} يوماً قادمة).");
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('dtr.index');
    }
}
