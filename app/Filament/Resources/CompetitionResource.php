<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompetitionResource\Pages;
use App\Models\Competition;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CompetitionResource extends Resource
{
    protected static ?string $model = Competition::class;

    protected static ?string $navigationIcon = 'heroicon-o-trophy';

    protected static ?string $navigationGroup = 'مركز البطولات والنتائج';

    protected static ?string $modelLabel = 'بطولة شبانية';

    protected static ?string $pluralModelLabel = 'بطولات ومنافسات الفئات الشبانية';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('معلومات المنافسة')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('name_ar')
                            ->label('اسم المنافسة (بالعربية)')
                            ->required(),
                        Forms\Components\TextInput::make('name_fr')
                            ->label('Nom de la compétition (Français)')
                            ->required(),
                        Forms\Components\Select::make('season_id')
                            ->label('الموسم الرياضي')
                            ->relationship('season', 'name')
                            ->required(),
                        Forms\Components\Select::make('age_category_id')
                            ->label('الفئة العمرية')
                            ->relationship('ageCategory', 'name_ar')
                            ->required(),
                        Forms\Components\Select::make('division_tier')
                            ->label('القسم / المستوى')
                            ->options([
                                'elite' => 'قسم النخبة (Élite)',
                                'level_1_a' => 'المستوى الأول (أ)',
                                'level_1_b' => 'المستوى الأول (ب)',
                                'regional_a' => 'المستوى الجهوي (أ)',
                                'regional_b' => 'المستوى الجهوي (ب)',
                                'regional_c' => 'المستوى الجهوي (ج / س)',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('division_name_ar')
                            ->label('اسم القسم بالعربية (للعرض)')
                            ->placeholder('مثال: قسم النخبة أو المستوى الأول فوج (أ)'),
                        Forms\Components\Select::make('type')
                            ->label('نوع المنافسة')
                            ->options([
                                'championship' => 'بطولة جهوية (Championship)',
                                'cup' => 'كأس الرابطة (Coupe de Ligue)',
                                'playoff' => 'دورة اللقب / البلاي أوف',
                                'festival' => 'مهرجان البراعم الكروي',
                            ])
                            ->default('championship')
                            ->required(),
                        Forms\Components\Select::make('status')
                            ->label('حالة المنافسة')
                            ->options([
                                'upcoming' => 'قادمة',
                                'in_progress' => 'جارية حالياً',
                                'completed' => 'مكتملة',
                            ])
                            ->default('in_progress')
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name_ar')
                    ->label('اسم البطولة')
                    ->searchable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('division_tier')
                    ->label('القسم')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'elite' => 'قسم النخبة',
                        'level_1_a' => 'المستوى الأول (أ)',
                        'level_1_b' => 'المستوى الأول (ب)',
                        'regional_a' => 'المستوى الجهوي (أ)',
                        'regional_b' => 'المستوى الجهوي (ب)',
                        'regional_c' => 'المستوى الجهوي (ج)',
                        default => $state ?? '—',
                    })
                    ->color(fn ($state) => match ($state) {
                        'elite' => 'warning',
                        'level_1_a', 'level_1_b' => 'info',
                        'regional_a', 'regional_b', 'regional_c' => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('ageCategory.code')
                    ->label('الفئة')
                    ->badge()
                    ->color('warning'),
                Tables\Columns\TextColumn::make('season.name')
                    ->label('الموسم')
                    ->badge(),
                Tables\Columns\TextColumn::make('type')
                    ->label('النوع')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'championship' => 'بطولة',
                        'cup' => 'كأس',
                        'playoff' => 'دورة اللقب',
                        'festival' => 'مهرجان براعم',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('status')
                    ->label('الحالة')
                    ->badge()
                    ->colors([
                        'success' => 'in_progress',
                        'gray' => 'upcoming',
                        'info' => 'completed',
                    ])
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'in_progress' => 'جارية',
                        'upcoming' => 'قادمة',
                        'completed' => 'منتهية',
                        default => $state,
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('division_tier')
                    ->label('القسم / المستوى')
                    ->options([
                        'elite' => 'قسم النخبة',
                        'level_1_a' => 'المستوى الأول (أ)',
                        'level_1_b' => 'المستوى الأول (ب)',
                        'regional_a' => 'المستوى الجهوي (أ)',
                        'regional_b' => 'المستوى الجهوي (ب)',
                        'regional_c' => 'المستوى الجهوي (ج)',
                    ]),
                Tables\Filters\SelectFilter::make('age_category_id')
                    ->label('الفئة')
                    ->relationship('ageCategory', 'code'),
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
            'index' => Pages\ListCompetitions::route('/'),
            'create' => Pages\CreateCompetition::route('/create'),
            'edit' => Pages\EditCompetition::route('/{record}/edit'),
        ];
    }
}
