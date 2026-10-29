<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CoachApplicationResource\Pages;
use App\Models\CoachApplication;
use App\Models\CoachCourse;
use App\Models\Wilaya;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CoachApplicationResource extends Resource
{
    protected static ?string $model = CoachApplication::class;

    protected static ?string $navigationIcon = 'heroicon-o-identification';

    protected static ?string $navigationGroup = 'المديرية الفنية الجهوية';

    protected static ?string $modelLabel = 'ملف ترشح مدرب';

    protected static ?string $pluralModelLabel = 'طلبات وملفات تكوين المدربين';

    protected static ?int $navigationSort = 2;

    public static function getNavigationBadge(): ?string
    {
        $count = CoachApplication::where('status', 'pending')->count();
        return $count > 0 ? (string)$count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('معلومات الدورة والملف الإداري')
                    ->columns(3)
                    ->schema([
                        Forms\Components\TextInput::make('application_number')
                            ->label('رقم ملف الترشح الرسمي')
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\Select::make('coach_course_id')
                            ->label('الدورة التكوينية المستهدفة')
                            ->relationship('course', 'title')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpan(2),

                        Forms\Components\TextInput::make('first_name_ar')
                            ->label('الاسم (بالعربية)')
                            ->required(),

                        Forms\Components\TextInput::make('last_name_ar')
                            ->label('اللقب (بالعربية)')
                            ->required(),

                        Forms\Components\TextInput::make('first_name_fr')
                            ->label('Prénom (Français)'),

                        Forms\Components\TextInput::make('last_name_fr')
                            ->label('Nom (Français)'),

                        Forms\Components\DatePicker::make('birth_date')
                            ->label('تاريخ الميلاد')
                            ->required(),

                        Forms\Components\TextInput::make('birth_place')
                            ->label('مكان الميلاد')
                            ->required(),

                        Forms\Components\TextInput::make('national_id_number')
                            ->label('رقم التعريف الوطني (NIN)'),

                        Forms\Components\Select::make('wilaya_id')
                            ->label('ولاية الإقامة')
                            ->relationship('wilaya', 'name_ar')
                            ->searchable()
                            ->preload(),

                        Forms\Components\TextInput::make('phone')
                            ->label('رقم الهاتف المحمول')
                            ->tel()
                            ->required(),

                        Forms\Components\TextInput::make('email')
                            ->label('البريد الإلكتروني')
                            ->email()
                            ->required(),

                        Forms\Components\TextInput::make('address')
                            ->label('العنوان الشخصي')
                            ->columnSpan(2),

                        Forms\Components\TextInput::make('education_level')
                            ->label('المستوى الدراسي')
                            ->required(),

                        Forms\Components\TextInput::make('sporting_experience')
                            ->label('المسار الرياضي والصفة السابقة (حارس/لاعب)')
                            ->columnSpan(2),

                        Forms\Components\TextInput::make('current_club')
                            ->label('النادي الحالي (إن وُجد)'),

                        Forms\Components\TextInput::make('previous_diplomas')
                            ->label('الشهادات السابقة')
                            ->columnSpan(2),
                    ]),

                Forms\Components\Section::make('وثائق ملف الترشح الممسوحة ضوئياً (الملف الإداري الرسمي)')
                    ->description('معاينة وتحميل الوثائق المرفوعة من طرف المترشح للتدقيق في استيفاء الشروط')
                    ->columns(2)
                    ->schema([
                        Forms\Components\FileUpload::make('doc_handwritten_request')
                            ->label('1. طلب خطي للمشاركة في التكوين')
                            ->directory('coach-applications')
                            ->downloadable()
                            ->openable(),

                        Forms\Components\FileUpload::make('doc_photo')
                            ->label('2. صورة شمسية')
                            ->directory('coach-applications')
                            ->downloadable()
                            ->openable(),

                        Forms\Components\FileUpload::make('doc_id_card')
                            ->label('3. نسخة بطاقة التعريف الوطنية')
                            ->directory('coach-applications')
                            ->downloadable()
                            ->openable(),

                        Forms\Components\FileUpload::make('doc_player_license')
                            ->label('4. رخصة حارس مرمى / لاعب سابق')
                            ->directory('coach-applications')
                            ->downloadable()
                            ->openable(),

                        Forms\Components\FileUpload::make('doc_medical_certificate')
                            ->label('5. شهادة طبية عامة وأمراض الصدر')
                            ->directory('coach-applications')
                            ->downloadable()
                            ->openable(),

                        Forms\Components\FileUpload::make('doc_police_record')
                            ->label('6. شهادة السوابق العدلية (صحيفة رقم 03)')
                            ->directory('coach-applications')
                            ->downloadable()
                            ->openable(),

                        Forms\Components\FileUpload::make('doc_residence_certificate')
                            ->label('7. شهادة الإقامة')
                            ->directory('coach-applications')
                            ->downloadable()
                            ->openable(),

                        Forms\Components\FileUpload::make('doc_birth_certificate')
                            ->label('8. شهادة الميلاد')
                            ->directory('coach-applications')
                            ->downloadable()
                            ->openable(),

                        Forms\Components\FileUpload::make('doc_school_certificate')
                            ->label('9. شهادة مدرسية (أولى ثانوي على الأقل)')
                            ->directory('coach-applications')
                            ->downloadable()
                            ->openable(),

                        Forms\Components\FileUpload::make('doc_diplomas')
                            ->label('10. شهادات التدريب السابقة (إن وُجدت)')
                            ->directory('coach-applications')
                            ->downloadable()
                            ->openable(),
                    ]),

                Forms\Components\Section::make('قرار المعاينة والمصادقة (المديرية واللجنة التقنية الجهوية)')
                    ->description('تحديد حالة قبول الملف أو إبداء تحفظ لاستدراك وثائق أو الرفض')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('حالة دراسة الملف')
                            ->options([
                                'pending' => '⏳ قيد المعاينة والتدقيق',
                                'approved' => '✓ مقبول ومؤهل للتربص (فتح الدروس)',
                                'reserved' => '⚠️ تحفظ (ملف غير مكتمل - إتاحة الاستدراك)',
                                'rejected' => '✕ مرفوض',
                            ])
                            ->default('pending')
                            ->required(),

                        Forms\Components\Textarea::make('review_notes')
                            ->label('ملاحظات اللجنة التقنية / سبب التحفظ أو الرفض')
                            ->helperText('هذه الملاحظة تظهر للمترشح في حسابه لمعرفة الوثائق المطلوب استدراكها.')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('application_number')
                    ->label('رقم الملف')
                    ->fontFamily('mono')
                    ->weight('bold')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('candidate_name')
                    ->label('المترشح')
                    ->state(fn (CoachApplication $record) => "{$record->first_name_ar} {$record->last_name_ar}")
                    ->description(fn (CoachApplication $record) => $record->phone)
                    ->searchable(['first_name_ar', 'last_name_ar', 'phone']),

                Tables\Columns\TextColumn::make('course.title')
                    ->label('مسلك التكوين')
                    ->badge()
                    ->color(fn (CoachApplication $record) => match ($record->course?->course_type) {
                        'goalkeeper' => 'warning',
                        'faf1', 'faf2', 'faf3' => 'success',
                        'caf_c', 'caf_b' => 'danger',
                        default => 'info',
                    })
                    ->wrap(),

                Tables\Columns\TextColumn::make('education_level')
                    ->label('المستوى الدراسي')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('wilaya.name_ar')
                    ->label('الولاية')
                    ->default('-')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإيداع')
                    ->dateTime('Y/m/d - H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('حالة الملف')
                    ->badge()
                    ->colors([
                        'info' => 'pending',
                        'success' => 'approved',
                        'warning' => 'reserved',
                        'danger' => 'rejected',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'قيد المعاينة',
                        'approved' => 'مقبول للتربص',
                        'reserved' => 'تحفظ',
                        'rejected' => 'مرفوض',
                        default => $state,
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                // Course Type Filter
                Tables\Filters\SelectFilter::make('course_type')
                    ->label('نوع التكوين المستهدف')
                    ->options([
                        'goalkeeper' => 'مدربي حراس المرمى',
                        'faf1' => 'المستوى الأول (FAF 1)',
                        'faf2' => 'المستوى الثاني (FAF 2)',
                        'faf3' => 'المستوى الثالث (FAF 3)',
                        'caf_c' => 'إجازة التدريب الإفريقية (CAF C)',
                        'caf_b' => 'إجازة التدريب الإفريقية (CAF B)',
                    ])
                    ->query(function ($query, array $data) {
                        if (!empty($data['value'])) {
                            $query->whereHas('course', fn ($q) => $q->where('course_type', $data['value']));
                        }
                    }),

                // Status Filter
                Tables\Filters\SelectFilter::make('status')
                    ->label('حالة المعاينة والقبول')
                    ->options([
                        'pending' => 'قيد المعاينة والتدقيق',
                        'approved' => 'مقبول ومؤهل للتربص',
                        'reserved' => 'تحفظ (وثائق ناقصة)',
                        'rejected' => 'مرفوض',
                    ]),

                // Wilaya Filter
                Tables\Filters\SelectFilter::make('wilaya_id')
                    ->label('الولاية')
                    ->options(fn () => Wilaya::pluck('name_ar', 'id')->toArray()),
            ])
            ->actions([
                // Quick Action: Approve Candidate
                Tables\Actions\Action::make('approve_candidate')
                    ->label('قبول ومصادقة')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('المصادقة على قبول المترشح في التربص')
                    ->modalDescription(fn (CoachApplication $record) => "هل تريد تأكيد قبول ملف المترشح ({$record->full_name_ar}) في دورة {$record->course?->title}؟ سيتم إشعاره وفتح فضاء الدروس البيداغوجية له.")
                    ->action(function (CoachApplication $record): void {
                        $record->update([
                            'status' => 'approved',
                            'reviewed_by' => auth()->id(),
                            'reviewed_at' => now(),
                            'review_notes' => 'تمت المصادقة وقبول ملف الترشح رسمياً من طرف المديرية التقنية الجهوية.',
                        ]);

                        Notification::make()
                            ->title("تمت المصادقة وقبول ملف المترشح ({$record->full_name_ar}) بنجاح")
                            ->success()
                            ->send();
                    }),

                // Quick Action: Issue Reservation (تحفظ)
                Tables\Actions\Action::make('reserve_candidate')
                    ->label('إبداء تحفظ')
                    ->icon('heroicon-o-exclamation-triangle')
                    ->color('warning')
                    ->form([
                        Forms\Components\Textarea::make('review_notes')
                            ->label('سبب التحفظ والوثائق المطلوب استدراكها')
                            ->placeholder('مثال: الشهادة الطبية غير واضحة، يرجى إعادة رفع شهادة طبية عامة وصدرية حديثة.')
                            ->required(),
                    ])
                    ->action(function (CoachApplication $record, array $data): void {
                        $record->update([
                            'status' => 'reserved',
                            'reviewed_by' => auth()->id(),
                            'reviewed_at' => now(),
                            'review_notes' => $data['review_notes'],
                        ]);

                        Notification::make()
                            ->title("تم تسجيل التحفظ وإشعار المترشح ({$record->full_name_ar})")
                            ->warning()
                            ->send();
                    }),

                // Quick Action: Reject Candidate
                Tables\Actions\Action::make('reject_candidate')
                    ->label('رفض الملف')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->form([
                        Forms\Components\Textarea::make('review_notes')
                            ->label('سبب الرفض')
                            ->placeholder('مثال: عدم بلوغ السن القانوني أو عدم توفر المستوى الدراسي المطلوب.')
                            ->required(),
                    ])
                    ->action(function (CoachApplication $record, array $data): void {
                        $record->update([
                            'status' => 'rejected',
                            'reviewed_by' => auth()->id(),
                            'reviewed_at' => now(),
                            'review_notes' => $data['review_notes'],
                        ]);

                        Notification::make()
                            ->title("تم رفض ملف الترشح")
                            ->danger()
                            ->send();
                    }),

                Tables\Actions\Action::make('print_dossier')
                    ->label('استمارة ووصل الإيداع')
                    ->icon('heroicon-o-printer')
                    ->color('gray')
                    ->tooltip('طباعة استمارة ووصل إيداع الملف الرسمي للمترشح')
                    ->url(fn (CoachApplication $record) => route('dtr.candidate.print-dossier', $record->id))
                    ->openUrlInNewTab(),

                Tables\Actions\EditAction::make()
                    ->label('معاينة كاملة وتعديل'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('bulk_approve')
                        ->label('قبول ومصادقة جماعية للمحددين')
                        ->icon('heroicon-o-check-badge')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function ($records): void {
                            foreach ($records as $record) {
                                $record->update([
                                    'status' => 'approved',
                                    'reviewed_by' => auth()->id(),
                                    'reviewed_at' => now(),
                                    'review_notes' => 'تمت المصادقة وقبول ملف الترشح رسمياً من طرف المديرية التقنية الجهوية.',
                                ]);
                            }
                            Notification::make()
                                ->title('تمت المصادقة وقبول الدفعة المحددة بنجاح')
                                ->success()
                                ->send();
                        }),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCoachApplications::route('/'),
            'create' => Pages\CreateCoachApplication::route('/create'),
            'edit' => Pages\EditCoachApplication::route('/{record}/edit'),
        ];
    }
}
