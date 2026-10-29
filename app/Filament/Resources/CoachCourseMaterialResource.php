<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CoachCourseMaterialResource\Pages;
use App\Models\CoachCourse;
use App\Models\CoachCourseMaterial;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CoachCourseMaterialResource extends Resource
{
    protected static ?string $model = CoachCourseMaterial::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationGroup = 'المديرية الفنية الجهوية';

    protected static ?string $modelLabel = 'درس / محاضرة تكوينية';

    protected static ?string $pluralModelLabel = 'الدروس والمحتوى البيداغوجي للتربصات';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('معلومات المحاضرة والدورة التكوينية')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('coach_course_id')
                            ->label('الدورة التكوينية المستهدفة')
                            ->relationship('course', 'title')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('module_number')
                            ->label('المحور / الوحدة التدريبية')
                            ->placeholder('مثال: المحور الأول (نظري وميداني)')
                            ->required(),

                        Forms\Components\TextInput::make('title')
                            ->label('عنوان الدرس أو المحاضرة')
                            ->placeholder('مثال: التمركز الصحيح والتصدي للكرات الهوائية')
                            ->required(),

                        Forms\Components\Textarea::make('description')
                            ->label('ملخص المحاضرة والمحتوى التعليمي')
                            ->columnSpanFull(),

                        Forms\Components\FileUpload::make('file_path')
                            ->label('ملف المحاضرة (PDF / Presentation / Word)')
                            ->directory('materials')
                            ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation', 'application/zip'])
                            ->openable()
                            ->downloadable()
                            ->columnSpan(2),

                        Forms\Components\TextInput::make('video_url')
                            ->label('رابط فيديو تدريبي خارجي (YouTube / Drive - إن وُجد)')
                            ->url()
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('التحكم في مدة الإتاحة والعرض (بالأيام)')
                    ->description('تحديد مدة صلاحية عرض الدرس للمترشحين المقبولين (مثال: 15 يوماً)')
                    ->columns(3)
                    ->schema([
                        Forms\Components\DateTimePicker::make('published_at')
                            ->label('تاريخ وتوقيت النشر')
                            ->default(now())
                            ->required(),

                        Forms\Components\TextInput::make('duration_days')
                            ->label('مدة العرض والإتاحة (بالأيام)')
                            ->numeric()
                            ->default(15)
                            ->minValue(1)
                            ->maxValue(90)
                            ->helperText('مثال: 15 يوماً من تاريخ النشر')
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                $published = $get('published_at') ? \Carbon\Carbon::parse($get('published_at')) : now();
                                $set('expires_at', $published->copy()->addDays((int)$state));
                            }),

                        Forms\Components\DateTimePicker::make('expires_at')
                            ->label('تاريخ انتهاء صلاحية العرض')
                            ->helperText('يتم حسابه آلياً أو تخصيصه يدوياً')
                            ->default(now()->addDays(15)),

                        Forms\Components\Toggle::make('is_active')
                            ->label('تفعيل وإظهار الدرس للمترشحين')
                            ->default(true),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('module_number')
                    ->label('المحور')
                    ->badge()
                    ->color('warning'),

                Tables\Columns\TextColumn::make('title')
                    ->label('عنوان الدرس')
                    ->weight('bold')
                    ->searchable(),

                Tables\Columns\TextColumn::make('course.title')
                    ->label('الدورة التكوينية')
                    ->limit(35),

                Tables\Columns\TextColumn::make('duration_days')
                    ->label('مدة العرض')
                    ->suffix(' يوم')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('expires_at')
                    ->label('تاريخ الانتهاء')
                    ->date('Y/m/d')
                    ->description(fn (CoachCourseMaterial $record) => $record->isAvailable() ? "متبقي {$record->remaining_days} يوم" : 'منتهي الصلاحية')
                    ->color(fn (CoachCourseMaterial $record) => $record->isAvailable() ? 'success' : 'danger')
                    ->sortable(),

                Tables\Columns\TextColumn::make('views_count')
                    ->label('الاطلاع والتحميل')
                    ->counts('views')
                    ->badge()
                    ->color('info')
                    ->suffix(' مترشح'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('نشط')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('coach_course_id')
                    ->label('الدورة التكوينية')
                    ->relationship('course', 'title'),
            ])
            ->actions([
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
            'index' => Pages\ListCoachCourseMaterials::route('/'),
            'create' => Pages\CreateCoachCourseMaterial::route('/create'),
            'edit' => Pages\EditCoachCourseMaterial::route('/{record}/edit'),
        ];
    }
}
