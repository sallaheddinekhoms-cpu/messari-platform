<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ScoutingReportResource\Pages;
use App\Models\ScoutingReport;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ScoutingReportResource extends Resource
{
    protected static ?string $model = ScoutingReport::class;

    protected static ?string $navigationIcon = 'heroicon-o-magnifying-glass-circle';

    protected static ?string $navigationGroup = 'المديرية الفنية الجهوية DTR';

    protected static ?string $modelLabel = 'تقرير معاينة فنية';

    protected static ?string $pluralModelLabel = 'بنك تقارير المعاينة والمواهب';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('اللاعب ومصدر المعاينة')
                    ->columns(3)
                    ->schema([
                        Forms\Components\Select::make('player_id')
                            ->label('اللاعب المستهدف')
                            ->relationship('player', 'first_name_ar')
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->full_name_ar} ({$record->passport_number})")
                            ->searchable(['first_name_ar', 'last_name_ar', 'passport_number'])
                            ->preload()
                            ->required(),

                        Forms\Components\Select::make('scout_user_id')
                            ->label('الكشاف / الفني المعاين')
                            ->relationship('scoutUser', 'name')
                            ->required()
                            ->default(fn () => auth()->id()),

                        Forms\Components\Select::make('match_id')
                            ->label('المباراة المراقبة (إن وجدت)')
                            ->relationship('match', 'id')
                            ->getOptionLabelFromRecordUsing(fn ($record) => "لقاء بتاريخ {$record->match_date->format('Y-m-d')}")
                            ->searchable(),
                    ]),

                Forms\Components\Section::make('معايير التقييم الفني السبعة المعتمدة (DTR 7-Pillar Rating)')
                    ->description('تقييم ركائز الأداء السبع من 1 إلى 10 بحسب دفتر شروط المتابعة الفنية للرابطة')
                    ->columns(4)
                    ->schema([
                        Forms\Components\TextInput::make('attacking_efficiency')
                            ->label('1. الفعالية الهجومية')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(10)
                            ->step(0.1)
                            ->suffix('/ 10'),

                        Forms\Components\TextInput::make('passing_playmaking')
                            ->label('2. التمريرات وصناعة اللعب')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(10)
                            ->step(0.1)
                            ->suffix('/ 10'),

                        Forms\Components\TextInput::make('dribbling_progression')
                            ->label('3. المراوغات والتقدم بالكرة')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(10)
                            ->step(0.1)
                            ->suffix('/ 10'),

                        Forms\Components\TextInput::make('defensive_performance')
                            ->label('4. الأداء الدفاعي')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(10)
                            ->step(0.1)
                            ->suffix('/ 10'),

                        Forms\Components\TextInput::make('physical_performance')
                            ->label('5. الأداء البدني')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(10)
                            ->step(0.1)
                            ->suffix('/ 10'),

                        Forms\Components\TextInput::make('tactical_intelligence')
                            ->label('6. الذكاء التكتيكي')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(10)
                            ->step(0.1)
                            ->suffix('/ 10'),

                        Forms\Components\TextInput::make('match_impact')
                            ->label('7. التأثير العام في المباراة')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(10)
                            ->step(0.1)
                            ->suffix('/ 10'),

                        Forms\Components\TextInput::make('overall_score')
                            ->label('المعدل التقييمي الإجمالي')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(10)
                            ->step(0.1)
                            ->suffix('/ 10'),
                    ]),

                Forms\Components\Section::make('التصنيف وتوصية الكشاف')
                    ->columns(3)
                    ->schema([
                        Forms\Components\Select::make('potential_score')
                            ->label('مستوى الإمكانات والمستقبل (Potential)')
                            ->options([
                                1 => '⭐ (محدود)',
                                2 => '⭐⭐ (متوسط)',
                                3 => '⭐⭐⭐ (واعد)',
                                4 => '⭐⭐⭐⭐ (ممتاز جداً)',
                                5 => '⭐⭐⭐⭐⭐ (نجم مستقبلي / استثنائي)',
                            ])
                            ->default(3)
                            ->required(),

                        Forms\Components\Select::make('recommendation')
                            ->label('توصية المديرية الفنية (DTR)')
                            ->options([
                                'regional_selection' => 'استدعاء فوري للمنتخب الجهوي',
                                'keep_monitoring' => 'مواصلة المتابعة الدورية',
                                'club_alert' => 'توجيه برنامج تطوير مع ناديه',
                                'elite_prospect' => 'موهبة نخبة وطنية مقترحة للفاف (FAF)',
                            ])
                            ->required(),

                        Forms\Components\Select::make('visibility')
                            ->label('مستوى السرية')
                            ->options([
                                'dtr_only' => 'خاص بالمديرية الفنية فقط',
                                'public_badge' => 'عام (منح شارة الموهبة في الجواز)',
                                'shared_club' => 'مشارك مع الطاقم الفني لناديه',
                            ])
                            ->default('dtr_only'),
                    ]),

                Forms\Components\Section::make('الملاحظات التفصيلية')
                    ->schema([
                        Forms\Components\Textarea::make('strengths')
                            ->label('نقاط القوة البارزة')
                            ->rows(3),
                        Forms\Components\Textarea::make('weaknesses')
                            ->label('نقاط التحسين والتطوير المطلوبة')
                            ->rows(3),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('player.full_name_ar')
                    ->label('اللاعب')
                    ->weight('bold')
                    ->searchable(),
                Tables\Columns\TextColumn::make('player.currentLicense.club.short_name')
                    ->label('النادي')
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('overall_score')
                    ->label('المعدل العام')
                    ->badge()
                    ->color('success')
                    ->suffix(' / 10'),
                Tables\Columns\TextColumn::make('potential_score')
                    ->label('الإمكانات')
                    ->formatStateUsing(fn ($state) => str_repeat('⭐', (int) $state)),
                Tables\Columns\TextColumn::make('recommendation')
                    ->label('التوصية')
                    ->badge()
                    ->colors([
                        'success' => 'regional_selection',
                        'warning' => 'keep_monitoring',
                        'danger' => 'elite_prospect',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'regional_selection' => 'منتخب الرابطة الجهوي',
                        'keep_monitoring' => 'متابعة دورية',
                        'club_alert' => 'تنبيه النادي',
                        'elite_prospect' => 'نخبة وطنية',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('scoutUser.name')
                    ->label('الكشاف / الفني'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ التقرير')
                    ->date('Y/m/d'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('recommendation')
                    ->label('نوع التوصية')
                    ->options([
                        'regional_selection' => 'منتخب الرابطة الجهوي',
                        'keep_monitoring' => 'متابعة دورية',
                        'elite_prospect' => 'نخبة وطنية',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListScoutingReports::route('/'),
            'create' => Pages\CreateScoutingReport::route('/create'),
            'edit' => Pages\EditScoutingReport::route('/{record}/edit'),
        ];
    }
}
