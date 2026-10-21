<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArticleResource\Pages;
use App\Models\Article;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ArticleResource extends Resource
{
    protected static ?string $model = Article::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $navigationGroup = 'المركز الإعلامي';

    protected static ?string $modelLabel = 'مقال / خبر إعلامي';

    protected static ?string $pluralModelLabel = 'المركز الإعلامي ومواهب الأسبوع';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('المحتوى الإعلامي')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('عنوان المقال أو التقرير')
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\Select::make('category')
                            ->label('التصنيف الإعلامي')
                            ->options([
                                'talent_of_week' => '⭐ موهبة الأسبوع (Talent of the Week)',
                                'tactical_review' => 'تحليل تكتيكي وفني (DTR)',
                                'medical_advice' => 'إرشادات طبية وتغذية رياضية',
                                'academy_focus' => 'ملف خاص عن مدرسة كروية',
                                'announcement' => 'بيان وبلاغ رسمي للرابطة',
                            ])
                            ->default('talent_of_week')
                            ->required(),

                        Forms\Components\Toggle::make('is_published')
                            ->label('نشر مباشر للجمهور')
                            ->default(true),

                        Forms\Components\Textarea::make('excerpt')
                            ->label('موجز الخبر (مقتطف للعرض السريع)')
                            ->rows(2)
                            ->columnSpanFull(),

                        Forms\Components\RichEditor::make('content')
                            ->label('نص المقال الكامل والتفاصيل')
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\FileUpload::make('featured_image')
                            ->label('الصورة البارزة للمقال')
                            ->image()
                            ->directory('articles-covers')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('featured_image')
                    ->label('الصورة')
                    ->square(),

                Tables\Columns\TextColumn::make('title')
                    ->label('العنوان')
                    ->weight('bold')
                    ->searchable()
                    ->limit(50),

                Tables\Columns\TextColumn::make('category')
                    ->label('التصنيف')
                    ->badge()
                    ->colors([
                        'warning' => 'talent_of_week',
                        'primary' => 'tactical_review',
                        'success' => 'medical_advice',
                        'info' => 'announcement',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'talent_of_week' => 'موهبة الأسبوع',
                        'tactical_review' => 'تحليل فني',
                        'medical_advice' => 'طبي وتغذية',
                        'announcement' => 'بيان رسمي',
                        default => $state,
                    }),

                Tables\Columns\IconColumn::make('is_published')
                    ->label('منشور')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->date('Y/m/d'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->label('التصنيف')
                    ->options([
                        'talent_of_week' => 'موهبة الأسبوع',
                        'tactical_review' => 'تحليل فني',
                        'announcement' => 'بيان رسمي',
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
            'index' => Pages\ListArticles::route('/'),
            'create' => Pages\CreateArticle::route('/create'),
            'edit' => Pages\EditArticle::route('/{record}/edit'),
        ];
    }
}
