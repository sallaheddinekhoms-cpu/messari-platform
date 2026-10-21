<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GameMatchResource\Pages;
use App\Models\GameMatch;
use Filament\Forms;
use Filament\Forms\Form;
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

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('معلومات وبرمجة اللقاء')
                    ->columns(3)
                    ->schema([
                        Forms\Components\Select::make('round_id')
                            ->label('الجولة / المجموعة')
                            ->relationship('round', 'name_ar')
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->group?->competition?->name_ar} - {$record->group?->name} - {$record->name_ar}")
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\Select::make('home_team_id')
                            ->label('الفريق المضيف')
                            ->relationship('homeTeam', 'id')
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->display_name)
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\Select::make('away_team_id')
                            ->label('الفريق الضيف')
                            ->relationship('awayTeam', 'id')
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->display_name)
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\Select::make('stadium_id')
                            ->label('ملعب اللقاء')
                            ->relationship('stadium', 'name')
                            ->searchable()
                            ->preload(),

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
                    ->columns(4)
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('حالة اللقاء')
                            ->options([
                                'scheduled' => 'مجدولة',
                                'live' => 'جارية حالياً',
                                'finished' => 'منتهية (تحديث الترتيب تلقائياً)',
                                'postponed' => 'مؤجلة',
                                'cancelled' => 'ملغاة',
                            ])
                            ->default('scheduled')
                            ->required(),

                        Forms\Components\TextInput::make('home_score')
                            ->label('أهداف المضيف')
                            ->numeric()
                            ->minValue(0),

                        Forms\Components\TextInput::make('away_score')
                            ->label('أهداف الضيف')
                            ->numeric()
                            ->minValue(0),

                        Forms\Components\Toggle::make('is_validated')
                            ->label('مصادقة لجنة الرابطة')
                            ->default(true),
                    ]),

                Forms\Components\Section::make('الطاقم التحكيمي والمندوب')
                    ->columns(4)
                    ->schema([
                        Forms\Components\TextInput::make('referee_name')
                            ->label('حكم الساحة الرئيسي'),

                        Forms\Components\TextInput::make('assistant1_name')
                            ->label('المساعد الأول'),

                        Forms\Components\TextInput::make('assistant2_name')
                            ->label('المساعد الثاني'),

                        Forms\Components\TextInput::make('delegate_name')
                            ->label('مندوب المباراة'),

                        Forms\Components\FileUpload::make('match_sheet_path')
                            ->label('ورقة اللقاء الرسمية الممسوحة (PDF / Image)')
                            ->directory('match-sheets')
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

                Tables\Columns\TextColumn::make('round.name_ar')
                    ->label('الجولة')
                    ->description(fn (GameMatch $record) => $record->round?->group?->competition?->ageCategory?->code),

                Tables\Columns\TextColumn::make('homeTeam.club.short_name')
                    ->label('المضيف')
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('score')
                    ->label('النتيجة')
                    ->state(function (GameMatch $record): string {
                        if ($record->status === 'finished') {
                            return "{$record->home_score} - {$record->away_score}";
                        }
                        return substr($record->kickoff_time, 0, 5);
                    })
                    ->badge()
                    ->color(fn (GameMatch $record) => $record->status === 'finished' ? 'success' : 'primary'),

                Tables\Columns\TextColumn::make('awayTeam.club.short_name')
                    ->label('الضيف')
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('stadium.name')
                    ->label('الملعب')
                    ->default('-'),

                Tables\Columns\TextColumn::make('status')
                    ->label('الحالة')
                    ->badge()
                    ->colors([
                        'success' => 'finished',
                        'warning' => 'live',
                        'info' => 'scheduled',
                        'danger' => 'cancelled',
                        'gray' => 'postponed',
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
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('حالة اللقاء')
                    ->options([
                        'finished' => 'منتهية',
                        'scheduled' => 'مجدولة',
                        'live' => 'مباشر',
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
            'index' => Pages\ListGameMatches::route('/'),
            'create' => Pages\CreateGameMatch::route('/create'),
            'edit' => Pages\EditGameMatch::route('/{record}/edit'),
        ];
    }
}
