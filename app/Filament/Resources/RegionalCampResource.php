<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RegionalCampResource\Pages;
use App\Models\RegionalCamp;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RegionalCampResource extends Resource
{
    protected static ?string $model = RegionalCamp::class;

    protected static ?string $navigationIcon = 'heroicon-o-flag';

    protected static ?string $navigationGroup = 'المديرية الفنية الجهوية DTR';

    protected static ?string $modelLabel = 'تجمع منتخب جهوي';

    protected static ?string $pluralModelLabel = 'تجمعات وانتقاء المنتخبات الجهوية';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('معلومات التجمع الانتقائي')
                    ->columns(3)
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('عنوان التجمع')
                            ->required()
                            ->columnSpan(2),

                        Forms\Components\Select::make('age_category_id')
                            ->label('الفئة المستهدفة')
                            ->relationship('ageCategory', 'name_ar')
                            ->required(),

                        Forms\Components\Select::make('season_id')
                            ->label('الموسم الرياضي')
                            ->relationship('season', 'name')
                            ->required(),

                        Forms\Components\TextInput::make('location')
                            ->label('المقر / المدينة')
                            ->required(),

                        Forms\Components\Select::make('stadium_id')
                            ->label('الملعب المخصص للتدريبات')
                            ->relationship('stadium', 'name')
                            ->searchable()
                            ->preload(),

                        Forms\Components\DatePicker::make('start_date')
                            ->label('تاريخ البداية')
                            ->required()
                            ->native(false),

                        Forms\Components\DatePicker::make('end_date')
                            ->label('تاريخ النهاية')
                            ->required()
                            ->native(false),

                        Forms\Components\TextInput::make('lead_coach')
                            ->label('المدرب المشرف على الانتقاء')
                            ->required(),

                        Forms\Components\Textarea::make('objectives')
                            ->label('أهداف وبرنامج التجمع الفني')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('عنوان التجمع')
                    ->weight('bold')
                    ->searchable(),

                Tables\Columns\TextColumn::make('ageCategory.code')
                    ->label('الفئة')
                    ->badge()
                    ->color('warning'),

                Tables\Columns\TextColumn::make('lead_coach')
                    ->label('المدرب المشرف'),

                Tables\Columns\TextColumn::make('location')
                    ->label('المكان'),

                Tables\Columns\TextColumn::make('start_date')
                    ->label('الفترة')
                    ->formatStateUsing(fn (RegionalCamp $record) => "من {$record->start_date->format('m/d')} إلى {$record->end_date->format('m/d')}"),

                Tables\Columns\TextColumn::make('callups_count')
                    ->label('المستدعون')
                    ->counts('callups')
                    ->badge()
                    ->color('success'),
            ])
            ->filters([
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
            'index' => Pages\ListRegionalCamps::route('/'),
            'create' => Pages\CreateRegionalCamp::route('/create'),
            'edit' => Pages\EditRegionalCamp::route('/{record}/edit'),
        ];
    }
}
