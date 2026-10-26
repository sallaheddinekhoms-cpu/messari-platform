<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClubResource\Pages;
use App\Models\Club;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ClubResource extends Resource
{
    protected static ?string $model = Club::class;

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';

    protected static ?string $navigationGroup = 'الأندية والملاعب';

    protected static ?string $modelLabel = 'نادي منخرط';

    protected static ?string $pluralModelLabel = 'دليل أندية الرابطة';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('معلومات النادي')
                    ->columns(3)
                    ->schema([
                        Forms\Components\TextInput::make('name_ar')
                            ->label('اسم النادي (بالعربية)')
                            ->required(),
                        Forms\Components\TextInput::make('name_fr')
                            ->label('Nom du Club (Français)')
                            ->required(),
                        Forms\Components\TextInput::make('acronym')
                            ->label('الاختصار الرسمي (Acronym)')
                            ->required()
                            ->maxLength(10),
                        Forms\Components\TextInput::make('short_name')
                            ->label('الاسم المتداول')
                            ->maxLength(100),
                        Forms\Components\Select::make('wilaya_id')
                            ->label('الولاية')
                            ->relationship('wilaya', 'name_ar')
                            ->required(),
                        Forms\Components\Select::make('main_stadium_id')
                            ->label('الملعب الرئيسي')
                            ->relationship('stadium', 'name')
                            ->searchable()
                            ->preload(),
                        Forms\Components\TextInput::make('founded_year')
                            ->label('سنة التأسيس')
                            ->numeric(),
                        Forms\Components\ColorPicker::make('primary_color')
                            ->label('اللون الأساسي'),
                        Forms\Components\ColorPicker::make('secondary_color')
                            ->label('اللون الثانوي'),
                        Forms\Components\FileUpload::make('logo_path')
                            ->label('شعار النادي (بدون خلفية - Transparent PNG / WebP / SVG)')
                            ->image()
                            ->imageEditor()
                            ->directory('images/clubs')
                            ->disk('public')
                            ->visibility('public')
                            ->acceptedFileTypes(['image/png', 'image/webp', 'image/svg+xml', 'image/jpeg'])
                            ->maxSize(5120)
                            ->helperText('يُفضل رفع الشعار مفرغ الخلفية بدقة واضحة وبصيغة PNG شفافة أو WebP أو SVG.')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('logo_url')
                    ->label('الشعار')
                    ->square()
                    ->extraImgAttributes(['class' => 'object-contain max-h-11 bg-transparent p-0.5']),
                Tables\Columns\IconColumn::make('has_logo')
                    ->label('حالة الشعار')
                    ->boolean()
                    ->state(fn (Club $record): bool => !empty($record->logo_path))
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->tooltip(fn (Club $record): string => $record->logo_path ? 'الشعار متوفر' : 'ينقصه شعار'),
                Tables\Columns\TextColumn::make('name_ar')
                    ->label('النادي')
                    ->searchable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('acronym')
                    ->label('الرمز')
                    ->badge()
                    ->color('primary')
                    ->searchable(),
                Tables\Columns\TextColumn::make('wilaya.name_ar')
                    ->label('الولاية')
                    ->badge(),
                Tables\Columns\TextColumn::make('stadium.name')
                    ->label('الملعب')
                    ->default('-'),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('النشاط')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('has_logo')
                    ->label('تصفية حسب توفر الشعار')
                    ->placeholder('جميع الأندية')
                    ->trueLabel('أندية تملك شعاراً')
                    ->falseLabel('أندية بدون شعار (ناقصة)')
                    ->queries(
                        true: fn ($query) => $query->whereNotNull('logo_path'),
                        false: fn ($query) => $query->whereNull('logo_path'),
                    ),
                Tables\Filters\SelectFilter::make('wilaya_id')
                    ->label('الولاية')
                    ->relationship('wilaya', 'name_ar'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('تعديل النادي والشعار'),
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
            'index' => Pages\ListClubs::route('/'),
            'create' => Pages\CreateClub::route('/create'),
            'edit' => Pages\EditClub::route('/{record}/edit'),
        ];
    }
}
