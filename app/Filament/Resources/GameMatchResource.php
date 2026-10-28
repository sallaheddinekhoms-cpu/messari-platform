<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GameMatchResource\Pages;
use App\Models\AgeCategory;
use App\Models\ClubTeam;
use App\Models\Competition;
use App\Models\GameMatch;
use App\Models\Player;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class GameMatchResource extends Resource
{
    protected static ?string $model = GameMatch::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationGroup = 'مركز البطولات والنتائج';

    protected static ?string $modelLabel = 'مباراة شبانية';

    protected static ?string $pluralModelLabel = 'برمجة المباريات والنتائج (ورقة اللقاء)';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('معلومات وبرمجة اللقاء')
                    ->description('تحديد الجولة، الفريقين، الملعب وموعد الانطلاق')
                    ->columns(3)
                    ->schema([
                        Forms\Components\Select::make('round_id')
                            ->label('الجولة / المجموعة / البطولة')
                            ->relationship('round', 'name_ar')
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->group?->competition?->name_ar} ({$record->group?->competition?->ageCategory?->code}) - {$record->group?->name} - {$record->name_ar}")
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpan(3),

                        Forms\Components\Select::make('home_team_id')
                            ->label('الفريق المضيف (Domicile)')
                            ->relationship('homeTeam', 'id')
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->display_name)
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\Select::make('away_team_id')
                            ->label('الفريق الضيف (Extérieur)')
                            ->relationship('awayTeam', 'id')
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->display_name)
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\Select::make('stadium_id')
                            ->label('ملعب اللقاء')
                            ->relationship('stadium', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable(),

                        Forms\Components\DatePicker::make('match_date')
                            ->label('تاريخ المباراة')
                            ->required()
                            ->native(false)
                            ->default(now()),

                        Forms\Components\TimePicker::make('kickoff_time')
                            ->label('توقيت انطلاق اللقاء')
                            ->default('10:00:00')
                            ->required(),
                    ]),

                Forms\Components\Section::make('النتيجة وحالة اللقاء (الحساب التلقائي للترتيب)')
                    ->description('تسجيل الأهداف والنتيجة النهائية مع تحديث جدول الترتيب تلقائياً بمجرد الاعتماد')
                    ->columns(4)
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('حالة اللقاء')
                            ->options([
                                'scheduled' => 'مجدولة (Programmé)',
                                'live' => 'جارية حالياً (En Direct)',
                                'finished' => 'منتهية (Terminé - تحديث الترتيب تلقائياً)',
                                'postponed' => 'مؤجلة (Reporté)',
                                'cancelled' => 'ملغاة (Annulé)',
                            ])
                            ->default('scheduled')
                            ->required(),

                        Forms\Components\TextInput::make('home_score')
                            ->label('أهداف المضيف')
                            ->numeric()
                            ->minValue(0)
                            ->default(0),

                        Forms\Components\TextInput::make('away_score')
                            ->label('أهداف الضيف')
                            ->numeric()
                            ->minValue(0)
                            ->default(0),

                        Forms\Components\Toggle::make('is_validated')
                            ->label('مصادقة واعتماد لجنة الرابطة')
                            ->helperText('عند التفعيل يتم اعتماد النتيجة رسمياً في جدول ترتيب الفوج')
                            ->default(true),

                        Forms\Components\TextInput::make('home_penalty_score')
                            ->label('ركلات الترجيح (المضيف)')
                            ->numeric()
                            ->minValue(0)
                            ->nullable(),

                        Forms\Components\TextInput::make('away_penalty_score')
                            ->label('ركلات الترجيح (الضيف)')
                            ->numeric()
                            ->minValue(0)
                            ->nullable(),
                    ]),

                Forms\Components\Section::make('الطاقم التحكيمي ومحافظ المباراة')
                    ->description('تسجيل حكام ومندوب اللقاء وتحميل ورقة المباراة الرسمية')
                    ->columns(4)
                    ->schema([
                        Forms\Components\TextInput::make('referee_name')
                            ->label('حكم الساحة الرئيسي'),

                        Forms\Components\TextInput::make('assistant1_name')
                            ->label('الحكم المساعد الأول'),

                        Forms\Components\TextInput::make('assistant2_name')
                            ->label('الحكم المساعد الثاني'),

                        Forms\Components\TextInput::make('delegate_name')
                            ->label('مندوب / محافظ المباراة'),

                        Forms\Components\FileUpload::make('match_sheet_path')
                            ->label('ورقة اللقاء الرسمية الممسوحة ضوئياً (Feuille de Match - PDF / Image)')
                            ->directory('match-sheets')
                            ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png', 'image/webp'])
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('أحداث وأهداف اللقاء (ورقة التحكيم الرقمية)')
                    ->description('تسجيل الأهداف، الهدافين، البطاقات الصفراء والحمراء، والتغييرات')
                    ->schema([
                        Forms\Components\Repeater::make('events')
                            ->relationship('events')
                            ->schema([
                                Forms\Components\Grid::make(6)
                                    ->schema([
                                        Forms\Components\Select::make('club_team_id')
                                            ->label('الفريق')
                                            ->options(function ($record, $get) {
                                                $homeTeamId = $get('../../home_team_id') ?? $record?->home_team_id;
                                                $awayTeamId = $get('../../away_team_id') ?? $record?->away_team_id;
                                                $options = [];
                                                if ($homeTeamId) {
                                                    $team = ClubTeam::with('club')->find($homeTeamId);
                                                    if ($team) $options[$team->id] = 'المضيف: ' . ($team->club?->short_name ?: $team->club?->name_ar);
                                                }
                                                if ($awayTeamId) {
                                                    $team = ClubTeam::with('club')->find($awayTeamId);
                                                    if ($team) $options[$team->id] = 'الضيف: ' . ($team->club?->short_name ?: $team->club?->name_ar);
                                                }
                                                if (empty($options)) {
                                                    return ClubTeam::with('club')->take(50)->get()->pluck('display_name', 'id');
                                                }
                                                return $options;
                                            })
                                            ->required()
                                            ->columnSpan(2),

                                        Forms\Components\Select::make('event_type')
                                            ->label('نوع الحدث')
                                            ->options([
                                                'goal' => '⚽ هدف مسجل (Goal)',
                                                'penalty_goal' => '⚽ هدف من ضربة جزاء (Penalty)',
                                                'own_goal' => '⚽ هدف ضد مرماه (CSC)',
                                                'yellow_card' => '🟨 بطاقة صفراء (Avertissement)',
                                                'red_card' => '🟥 بطاقة حمراء (Expulsion)',
                                                'sub_in' => '🔄 دخول بديل (Remplacement In)',
                                                'sub_out' => '🔄 خروج لاعب (Remplacement Out)',
                                                'penalty_saved' => '🧤 ركلة جزاء ضائعة/مصدودة',
                                            ])
                                            ->default('goal')
                                            ->required()
                                            ->columnSpan(2),

                                        Forms\Components\TextInput::make('minute')
                                            ->label('الدقيقة')
                                            ->numeric()
                                            ->minValue(1)
                                            ->maxValue(130)
                                            ->default(45)
                                            ->required()
                                            ->columnSpan(1),

                                        Forms\Components\TextInput::make('extra_minute')
                                            ->label('وقت إضافي (+)')
                                            ->numeric()
                                            ->nullable()
                                            ->columnSpan(1),

                                        Forms\Components\Select::make('player_id')
                                            ->label('اللاعب المعني (الهداف / المنذر / المستبدل)')
                                            ->searchable()
                                            ->getSearchResultsUsing(function (string $search) {
                                                return Player::where('first_name_ar', 'like', "%{$search}%")
                                                    ->orWhere('last_name_ar', 'like', "%{$search}%")
                                                    ->orWhere('passport_number', 'like', "%{$search}%")
                                                    ->limit(30)
                                                    ->get()
                                                    ->mapWithKeys(fn ($p) => [
                                                        $p->id => "{$p->full_name_ar} ({$p->passport_number}) - " . ($p->currentLicense?->club?->short_name ?? '')
                                                    ]);
                                            })
                                            ->getOptionLabelUsing(fn ($value) => Player::find($value)?->full_name_ar)
                                            ->required()
                                            ->columnSpan(3),

                                        Forms\Components\Select::make('assist_player_id')
                                            ->label('صاحب التمريرة الحاسمة (إن وُجد)')
                                            ->searchable()
                                            ->getSearchResultsUsing(function (string $search) {
                                                return Player::where('first_name_ar', 'like', "%{$search}%")
                                                    ->orWhere('last_name_ar', 'like', "%{$search}%")
                                                    ->limit(20)
                                                    ->get()
                                                    ->mapWithKeys(fn ($p) => [
                                                        $p->id => "{$p->full_name_ar} ({$p->passport_number})"
                                                    ]);
                                            })
                                            ->getOptionLabelUsing(fn ($value) => Player::find($value)?->full_name_ar)
                                            ->nullable()
                                            ->columnSpan(3),

                                        Forms\Components\TextInput::make('description')
                                            ->label('تفاصيل أو سبب الإنذار / الطرد / الملاحظة')
                                            ->columnSpanFull(),
                                    ])
                            ])
                            ->itemLabel(fn (array $state): ?string => 
                                (isset($state['event_type']) ? match($state['event_type']) {
                                    'goal' => '⚽ هدف',
                                    'penalty_goal' => '⚽ ركلة جزاء مسجلة',
                                    'own_goal' => '⚽ هدف في مرماه',
                                    'yellow_card' => '🟨 بطاقة صفراء',
                                    'red_card' => '🟥 بطاقة حمراء',
                                    'sub_in' => '🔄 دخول بديل',
                                    'sub_out' => '🔄 خروج لاعب',
                                    'penalty_saved' => '🧤 ركلة جزاء ضائعة',
                                    default => 'حدث',
                                } : 'حدث كروي') . (isset($state['minute']) ? " (د {$state['minute']})" : '')
                            )
                            ->collapsible()
                            ->defaultItems(0)
                            ->reorderableWithButtons()
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('match_date')
                    ->label('التاريخ')
                    ->date('Y/m/d')
                    ->sortable(),

                Tables\Columns\TextColumn::make('category_badge')
                    ->label('الفئة')
                    ->state(fn (GameMatch $record) => $record->round?->group?->competition?->ageCategory?->code ?? '-')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'U20' => 'danger',
                        'U17' => 'success',
                        'U15' => 'warning',
                        default => 'info',
                    }),

                Tables\Columns\TextColumn::make('round.name_ar')
                    ->label('الجولة والمنافسة')
                    ->description(fn (GameMatch $record) => $record->round?->group?->competition?->name_ar)
                    ->searchable(),

                Tables\Columns\TextColumn::make('homeTeam.club.short_name')
                    ->label('المضيف')
                    ->weight('bold')
                    ->searchable(),

                Tables\Columns\TextColumn::make('score')
                    ->label('النتيجة')
                    ->state(function (GameMatch $record): string {
                        if ($record->status === 'finished') {
                            return "{$record->home_score} - {$record->away_score}";
                        }
                        if ($record->status === 'live') {
                            return "مباشر ({$record->home_score} - {$record->away_score})";
                        }
                        return substr($record->kickoff_time, 0, 5);
                    })
                    ->badge()
                    ->color(fn (GameMatch $record) => match ($record->status) {
                        'finished' => 'success',
                        'live' => 'danger',
                        'scheduled' => 'primary',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('awayTeam.club.short_name')
                    ->label('الضيف')
                    ->weight('bold')
                    ->searchable(),

                Tables\Columns\TextColumn::make('stadium.name')
                    ->label('الملعب')
                    ->default('-')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('الحالة')
                    ->badge()
                    ->colors([
                        'success' => 'finished',
                        'danger' => 'live',
                        'info' => 'scheduled',
                        'gray' => 'postponed',
                        'warning' => 'cancelled',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'finished' => 'منتهية',
                        'live' => 'مباشر',
                        'scheduled' => 'مجدولة',
                        'postponed' => 'مؤجلة',
                        'cancelled' => 'ملغاة',
                        default => $state,
                    }),

                Tables\Columns\IconColumn::make('is_validated')
                    ->label('مصادقة')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('events_count')
                    ->label('الأحداث')
                    ->counts('events')
                    ->badge()
                    ->color('gray')
                    ->toggleable(),
            ])
            ->defaultSort('match_date', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('age_category')
                    ->label('الفئة العمرية')
                    ->options(fn () => AgeCategory::pluck('name_ar', 'id')->toArray())
                    ->query(function ($query, array $data) {
                        if (!empty($data['value'])) {
                            $query->whereHas('round.group.competition', fn ($q) => $q->where('age_category_id', $data['value']));
                        }
                    }),

                Tables\Filters\SelectFilter::make('competition')
                    ->label('البطولة')
                    ->options(fn () => Competition::pluck('name_ar', 'id')->toArray())
                    ->query(function ($query, array $data) {
                        if (!empty($data['value'])) {
                            $query->whereHas('round.group', fn ($q) => $q->where('competition_id', $data['value']));
                        }
                    }),

                Tables\Filters\SelectFilter::make('status')
                    ->label('حالة اللقاء')
                    ->options([
                        'finished' => 'منتهية',
                        'scheduled' => 'مجدولة',
                        'live' => 'مباشر',
                        'postponed' => 'مؤجلة',
                        'cancelled' => 'ملغاة',
                    ]),

                Tables\Filters\TernaryFilter::make('is_validated')
                    ->label('حالة الاعتماد والمصادقة')
                    ->placeholder('الكل')
                    ->trueLabel('مصادق عليها')
                    ->falseLabel('غير مصادق عليها'),
            ])
            ->actions([
                // Quick Score Entry Modal
                Tables\Actions\Action::make('record_score')
                    ->label('تسجيل النتيجة')
                    ->icon('heroicon-o-pencil-square')
                    ->color('success')
                    ->modalHeading(fn (GameMatch $record) => "تسجيل نتيجة: {$record->homeTeam?->club?->short_name} ضد {$record->awayTeam?->club?->short_name}")
                    ->modalSubmitActionLabel('حفظ واعتماد النتيجة')
                    ->form([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('home_score')
                                    ->label(fn (GameMatch $record) => 'أهداف: ' . ($record->homeTeam?->club?->short_name ?? 'المضيف'))
                                    ->numeric()
                                    ->minValue(0)
                                    ->default(fn (GameMatch $record) => $record->home_score ?? 0)
                                    ->required(),

                                Forms\Components\TextInput::make('away_score')
                                    ->label(fn (GameMatch $record) => 'أهداف: ' . ($record->awayTeam?->club?->short_name ?? 'الضيف'))
                                    ->numeric()
                                    ->minValue(0)
                                    ->default(fn (GameMatch $record) => $record->away_score ?? 0)
                                    ->required(),
                            ]),

                        Forms\Components\Select::make('status')
                            ->label('حالة اللقاء')
                            ->options([
                                'finished' => 'منتهية (اعتماد وتحديث الترتيب فوراً)',
                                'live' => 'جارية حالياً (مباشر)',
                                'scheduled' => 'مجدولة',
                                'postponed' => 'مؤجلة',
                            ])
                            ->default('finished')
                            ->required(),

                        Forms\Components\Toggle::make('is_validated')
                            ->label('مصادقة رسمية فورية من الرابطة')
                            ->default(true),
                    ])
                    ->action(function (GameMatch $record, array $data): void {
                        $record->update([
                            'home_score' => $data['home_score'],
                            'away_score' => $data['away_score'],
                            'status' => $data['status'],
                            'is_validated' => $data['is_validated'],
                        ]);

                        Notification::make()
                            ->title('تم تسجيل النتيجة وتحديث الترتيب بنجاح')
                            ->success()
                            ->send();
                    }),

                // One-click Validate/Invalidate Toggle
                Tables\Actions\Action::make('toggle_validation')
                    ->label(fn (GameMatch $record) => $record->is_validated ? 'إلغاء الاعتماد' : 'اعتماد رسمي')
                    ->icon(fn (GameMatch $record) => $record->is_validated ? 'heroicon-o-x-circle' : 'heroicon-o-check-badge')
                    ->color(fn (GameMatch $record) => $record->is_validated ? 'gray' : 'warning')
                    ->requiresConfirmation()
                    ->modalHeading('تغيير حالة المصادقة على اللقاء')
                    ->modalDescription(fn (GameMatch $record) => $record->is_validated 
                        ? 'هل أنت متأكد من إلغاء اعتماد نتيجة اللقاء؟ سيتم إعادة حساب الترتيب تلقائياً.' 
                        : 'هل تريد اعتماد النتيجة رسمياً؟ سيتم احتساب النقاط وتحديث الترتيب.')
                    ->action(function (GameMatch $record): void {
                        $newStatus = ! $record->is_validated;
                        $record->update([
                            'is_validated' => $newStatus,
                            'status' => $newStatus ? 'finished' : $record->status,
                        ]);

                        Notification::make()
                            ->title($newStatus ? 'تم اعتماد نتيجة اللقاء رسمياً' : 'تم تعليق اعتماد اللقاء')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\EditAction::make()
                    ->label('تعديل والأحداث'),
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
            'index' => Pages\ListGameMatches::route('/'),
            'create' => Pages\CreateGameMatch::route('/create'),
            'edit' => Pages\EditGameMatch::route('/{record}/edit'),
        ];
    }
}
