<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MediaGalleryResource\Pages;
use App\Models\MediaGallery;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MediaGalleryResource extends Resource
{
    protected static ?string $model = MediaGallery::class;

    protected static ?string $navigationIcon = 'heroicon-o-camera';

    protected static ?string $navigationGroup = 'المركز الإعلامي';

    protected static ?string $modelLabel = 'عنصر وسائط (صور / فيديو)';

    protected static ?string $pluralModelLabel = 'معرض الصور والفيديوهات';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('معلومات الوسائط')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('عنوان الصورة أو مقطع الفيديو')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Forms\Components\Select::make('type')
                            ->label('نوع المحتوى')
                            ->options([
                                'photo' => '📷 ألبوم صور فوتوغرافية',
                                'video' => '🎬 مقطع فيديو / ملخص أهداف',
                            ])
                            ->default('photo')
                            ->required(),

                        Forms\Components\Select::make('category')
                            ->label('الفئة والمنافسة المرتبطة')
                            ->options([
                                'elite' => 'بطولة النخبة (U20, U17, U15)',
                                'level_1' => 'المستوى الأول (Niveau 1)',
                                'regional' => 'المستوى الجهوي (Régional)',
                                'camps' => 'تجمعات وانتقاءات DTR',
                                'all' => 'عام / كل الفئات',
                            ])
                            ->required()
                            ->default('all'),

                        Forms\Components\TextInput::make('media_url')
                            ->label('رابط الصورة أو رابط الفيديو (YouTube/MP4/Unsplash)')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('thumbnail_url')
                            ->label('رابط الصورة المصغرة (Thumbnail)')
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Forms\Components\DatePicker::make('event_date')
                            ->label('تاريخ الفعالية / المباراة')
                            ->default(now()),

                        Forms\Components\Toggle::make('is_featured')
                            ->label('تثبيت كعنصر رئيسي بارز (Featured)')
                            ->default(false),

                        Forms\Components\Textarea::make('description')
                            ->label('وصف وتفاصيل الحدث أو التجمع')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail_url')
                    ->label('المعاينة')
                    ->circular(),
                Tables\Columns\TextColumn::make('title')
                    ->label('العنوان')
                    ->searchable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('type')
                    ->label('النوع')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state === 'video' ? '🎬 فيديو' : '📷 صورة')
                    ->color(fn ($state) => $state === 'video' ? 'danger' : 'info'),
                Tables\Columns\TextColumn::make('category')
                    ->label('الفئة')
                    ->badge(),
                Tables\Columns\TextColumn::make('event_date')
                    ->label('التاريخ')
                    ->date('Y-m-d')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_featured')
                    ->label('مميز')
                    ->boolean(),
                Tables\Columns\TextColumn::make('views_count')
                    ->label('المشاهدات')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('النوع')
                    ->options([
                        'photo' => 'صور',
                        'video' => 'فيديوهات',
                    ]),
                Tables\Filters\SelectFilter::make('category')
                    ->label('الفئة')
                    ->options([
                        'elite' => 'النخبة',
                        'level_1' => 'المستوى الأول',
                        'regional' => 'المستوى الجهوي',
                        'camps' => 'تجمعات DTR',
                    ]),
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
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMediaGalleries::route('/'),
            'create' => Pages\CreateMediaGallery::route('/create'),
            'edit' => Pages\EditMediaGallery::route('/{record}/edit'),
        ];
    }
}
