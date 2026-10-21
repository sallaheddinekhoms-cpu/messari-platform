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
                            ->label('شعار النادي')
                            ->image()
                            ->directory('clubs-logos')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('logo_path')
                    ->label('الشعار')
                    ->circular()
                    ->defaultImageUrl(fn (Club $record) => 'https://ui-avatars.com/api/?background=059669&color=fff&name=' . urlencode($record->acronym)),
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
                Tables\Filters\SelectFilter::make('wilaya_id')
                    ->label('الولاية')
                    ->relationship('wilaya', 'name_ar'),
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
            'index' => Pages\ListClubs::route('/'),
            'create' => Pages\CreateClub::route('/create'),
            'edit' => Pages\EditClub::route('/{record}/edit'),
        ];
    }
}
