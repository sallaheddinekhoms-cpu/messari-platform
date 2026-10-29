<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CoachCourseResource\Pages;
use App\Models\CoachCourse;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CoachCourseResource extends Resource
{
    protected static ?string $model = CoachCourse::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationGroup = 'المديرية الفنية الجهوية';

    protected static ?string $modelLabel = 'دورة وتربص تكويني';

    protected static ?string $pluralModelLabel = 'دورات وتربصات المدربين (DTR)';

    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        $count = CoachCourse::where('is_registration_open', true)->count();
        return $count > 0 ? "{$count} مفتوحة" : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('معلومات الدورة والشهادة المستهدفة')
                    ->description('تحديد نوع التكوين ومسمى الدورة وشروط الالتحاق بها')
                    ->columns(3)
                    ->schema([
                        Forms\Components\Select::make('course_type')
                            ->label('نوع التكوين والتخصص')
                            ->options([
                                'goalkeeper' => 'مدربي حراس المرمى (Entraîneur des Gardiens)',
                                'faf1' => 'المستوى الأول الفيدرالي (FAF 1)',
                                'faf2' => 'المستوى الثاني الفيدرالي (FAF 2)',
                                'faf3' => 'المستوى الثالث الفيدرالي (FAF 3)',
                                'caf_c' => 'إجازة التدريب الإفريقية (CAF C)',
                                'caf_b' => 'إجازة التدريب الإفريقية (CAF B)',
                            ])
                            ->required()
                            ->native(false),

                        Forms\Components\TextInput::make('title')
                            ->label('عنوان الدورة / الفوج')
                            ->placeholder('مثال: دورة مدربي حراس المرمى - الفوج الأول 2026')
                            ->required()
                            ->columnSpan(2),

                        Forms\Components\TextInput::make('code')
                            ->label('الرمز التعريفي للدورة')
                            ->placeholder('مثال: DTR-GK-2026-01')
                            ->required()
                            ->unique(ignoreRecord: true),

                        Forms\Components\TextInput::make('location')
                            ->label('مكان إقامة التربص (المقر / الملعب)')
                            ->placeholder('مثال: مقر الرابطة الجهوية بالبليدة / ملعب مصطفى تشاكر')
                            ->required()
                            ->columnSpan(2),

                        Forms\Components\TextInput::make('max_candidates')
                            ->label('العدد الأقصى للمترشحين المقبولين')
                            ->numeric()
                            ->default(30)
                            ->minValue(5)
                            ->maxValue(100),

                        Forms\Components\TextInput::make('fee_amount')
                            ->label('حقوق المشاركة (دج)')
                            ->numeric()
                            ->prefix('DZD')
                            ->default(25000),

                        Forms\Components\Toggle::make('is_registration_open')
                            ->label('فتح استقبال ملفات الترشح عبر المنصة')
                            ->default(true),

                        Forms\Components\Toggle::make('is_active')
                            ->label('تفعيل الدورة وإظهارها في المنصة')
                            ->default(true),
                    ]),

                Forms\Components\Section::make('الجدول الزمني ومواعيد التسجيل')
                    ->columns(3)
                    ->schema([
                        Forms\Components\DatePicker::make('registration_deadline')
                            ->label('آخر أجل لإيداع الملفات')
                            ->required()
                            ->native(false),

                        Forms\Components\DatePicker::make('start_date')
                            ->label('تاريخ انطلاق التربص البيداغوجي')
                            ->required()
                            ->native(false),

                        Forms\Components\DatePicker::make('end_date')
                            ->label('تاريخ اختتام التربص والامتحانات')
                            ->required()
                            ->native(false),
                    ]),

                Forms\Components\Section::make('التفاصيل البيداغوجية وشروط القبول')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Textarea::make('description')
                            ->label('الأهداف البيداغوجية ومحتوى الدورة')
                            ->rows(4)
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('prerequisites')
                            ->label('شروط ومعايير القبول المسبقة')
                            ->rows(4)
                            ->placeholder("1- رخصة حارس مرمى سابق أو ممارسة فعلية\n2- مستوى أولى ثانوي على الأقل\n3- شهادة طبية للقدرة البدنية"),

                        Forms\Components\Textarea::make('required_documents')
                            ->label('الملف الإداري الإجباري المطلوب')
                            ->rows(4)
                            ->placeholder("1- طلب خطي\n2- صورتان شمسيتان\n3- بطاقة التعريف الوطنية\n4- رخصة حارس مرمى سابق..."),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('عنوان الدورة التكوينية')
                    ->description(fn (CoachCourse $record) => "رمز: {$record->code} | {$record->course_type_label}")
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\BadgeColumn::make('course_type')
                    ->label('التخصص')
                    ->colors([
                        'primary' => 'goalkeeper',
                        'success' => 'faf1',
                        'warning' => 'faf2',
                        'danger' => 'faf3',
                        'info' => fn ($state) => in_array($state, ['caf_c', 'caf_b']),
                    ])
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'goalkeeper' => 'حراس المرمى',
                        'faf1' => 'FAF 1',
                        'faf2' => 'FAF 2',
                        'faf3' => 'FAF 3',
                        'caf_c' => 'CAF C',
                        'caf_b' => 'CAF B',
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('applications_count')
                    ->label('الملفات الواردة')
                    ->counts('applications')
                    ->sortable()
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('registration_deadline')
                    ->label('آخر أجل للتسجيل')
                    ->date('Y/m/d')
                    ->sortable()
                    ->color(fn ($state) => \Carbon\Carbon::parse($state)->isPast() ? 'danger' : 'success'),

                Tables\Columns\TextColumn::make('start_date')
                    ->label('تاريخ الانطلاق')
                    ->date('Y/m/d')
                    ->sortable(),

                Tables\Columns\ToggleColumn::make('is_registration_open')
                    ->label('التسجيل مفتوح')
                    ->afterStateUpdated(function () {
                        Notification::make()
                            ->title('تم تحديث حالة فتح التسجيل للدورة بنجاح')
                            ->success()
                            ->send();
                    }),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('الحالة')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('course_type')
                    ->label('تصفية حسب التخصص')
                    ->options([
                        'goalkeeper' => 'مدربي حراس المرمى',
                        'faf1' => 'FAF 1',
                        'faf2' => 'FAF 2',
                        'faf3' => 'FAF 3',
                        'caf_c' => 'CAF C',
                        'caf_b' => 'CAF B',
                    ]),

                Tables\Filters\TernaryFilter::make('is_registration_open')
                    ->label('حالة التسجيل')
                    ->trueLabel('التسجيل مفتوح فقط')
                    ->falseLabel('التسجيل مغلق'),
            ])
            ->actions([
                Tables\Actions\Action::make('view_candidates')
                    ->label('ملفات المترشحين')
                    ->icon('heroicon-o-users')
                    ->color('primary')
                    ->url(fn (CoachCourse $record) => CoachApplicationResource::getUrl('index', [
                        'tableFilters' => ['coach_course_id' => ['value' => $record->id]],
                    ])),

                Tables\Actions\Action::make('print_roster')
                    ->label('طباعة قائمة المقبولين')
                    ->icon('heroicon-o-printer')
                    ->color('gray')
                    ->url(fn (CoachCourse $record) => route('dtr.course.print-roster', $record->id))
                    ->openUrlInNewTab(),

                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
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
            'index' => Pages\ListCoachCourses::route('/'),
            'create' => Pages\CreateCoachCourse::route('/create'),
            'edit' => Pages\EditCoachCourse::route('/{record}/edit'),
        ];
    }
}
