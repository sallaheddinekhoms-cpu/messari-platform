<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PlayerResource\Pages;
use App\Models\Player;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PlayerResource extends Resource
{
    protected static ?string $model = Player::class;

    protected static ?string $navigationIcon = 'heroicon-o-identification';

    protected static ?string $navigationGroup = 'الملف الرياضي والمواهب';

    protected static ?string $modelLabel = 'لاعب ناشئ';

    protected static ?string $pluralModelLabel = 'سجل اللاعبين الشبان (الملف الرياضي)';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('البيانات الشخصية والمدنية')
                    ->description('المعلومات الأساسية الموثقة للاعب الشاب')
                    ->columns(3)
                    ->schema([
                        Forms\Components\TextInput::make('passport_number')
                            ->label('رقم الجواز الرياضي (Passport ID)')
                            ->disabled()
                            ->dehydrated(false)
                            ->placeholder('يتم توليده تلقائياً (MESSARI-YYYY-XXXXX)'),

                        Forms\Components\TextInput::make('first_name_ar')
                            ->label('الاسم (بالعربية)')
                            ->required()
                            ->maxLength(100),

                        Forms\Components\TextInput::make('last_name_ar')
                            ->label('اللقب (بالعربية)')
                            ->required()
                            ->maxLength(100),

                        Forms\Components\TextInput::make('first_name_fr')
                            ->label('Prénom (Français)')
                            ->required()
                            ->maxLength(100),

                        Forms\Components\TextInput::make('last_name_fr')
                            ->label('Nom (Français)')
                            ->required()
                            ->maxLength(100),

                        Forms\Components\DatePicker::make('birth_date')
                            ->label('تاريخ الميلاد')
                            ->required()
                            ->native(false),

                        Forms\Components\TextInput::make('birth_place')
                            ->label('مكان الميلاد')
                            ->required(),

                        Forms\Components\Select::make('wilaya_id')
                            ->label('الولاية التابعة')
                            ->relationship('wilaya', 'name_ar')
                            ->searchable()
                            ->preload(),

                        Forms\Components\TextInput::make('national_id_number')
                            ->label('رقم التعريف الوطني (NIN)')
                            ->maxLength(30),
                    ]),

                Forms\Components\Section::make('المواصفات الفنية والبدنية')
                    ->description('المركز، القدم المفضلة، والمقاييس البدنية')
                    ->columns(4)
                    ->schema([
                        Forms\Components\Select::make('primary_position')
                            ->label('المركز الأساسي')
                            ->options([
                                'GK' => 'حارس مرمى (GK)',
                                'CB' => 'مدافع محوري (CB)',
                                'LB' => 'ظهير أيسر (LB)',
                                'RB' => 'ظهير أيمن (RB)',
                                'DM' => 'وسط دفاعي (DM)',
                                'CM' => 'وسط ميدان (CM)',
                                'AM' => 'صانع ألعاب (AM)',
                                'LW' => 'جناح أيسر (LW)',
                                'RW' => 'جناح أيمن (RW)',
                                'ST' => 'قلب هجوم (ST)',
                            ])
                            ->required(),

                        Forms\Components\TextInput::make('secondary_position')
                            ->label('المركز الثانوي')
                            ->maxLength(10),

                        Forms\Components\Select::make('dominant_foot')
                            ->label('القدم المفضلة')
                            ->options([
                                'right' => 'اليمنى',
                                'left' => 'اليسرى',
                                'both' => 'كلتا القدمين',
                            ])
                            ->default('right')
                            ->required(),

                        Forms\Components\Select::make('blood_type')
                            ->label('فصيلة الدم')
                            ->options([
                                'A+' => 'A+', 'A-' => 'A-',
                                'B+' => 'B+', 'B-' => 'B-',
                                'AB+' => 'AB+', 'AB-' => 'AB-',
                                'O+' => 'O+', 'O-' => 'O-',
                            ]),

                        Forms\Components\TextInput::make('height_cm')
                            ->label('الطول (سم)')
                            ->numeric()
                            ->suffix('سم'),

                        Forms\Components\TextInput::make('weight_kg')
                            ->label('الوزن (كلغ)')
                            ->numeric()
                            ->suffix('كلغ'),

                        Forms\Components\Select::make('status')
                            ->label('الحالة الرياضية')
                            ->options([
                                'active' => 'مؤهل ونشط',
                                'injured' => 'مصاب',
                                'suspended' => 'معاقب / موقوف',
                                'transferred' => 'تم تحويله',
                                'archived' => 'مؤرشف',
                            ])
                            ->default('active')
                            ->required(),

                        Forms\Components\FileUpload::make('photo_path')
                            ->label('الصورة الشخصية للاعب')
                            ->image()
                            ->directory('players-photos')
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('bio')
                            ->label('الملاحظات الفنية ونبذة المسار')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('photo_path')
                    ->label('الصورة')
                    ->circular()
                    ->defaultImageUrl(fn () => 'https://ui-avatars.com/api/?background=059669&color=fff&name=Player'),

                Tables\Columns\TextColumn::make('passport_number')
                    ->label('رقم الجواز / الإجازة')
                    ->searchable(['passport_number', 'national_id_number'])
                    ->copyable()
                    ->weight('bold')
                    ->color('primary'),

                Tables\Columns\TextColumn::make('full_name_ar')
                    ->label('الاسم واللقب')
                    ->searchable(['first_name_ar', 'last_name_ar', 'first_name_fr', 'last_name_fr'])
                    ->description(fn (Player $record) => $record->full_name_fr),

                Tables\Columns\TextColumn::make('currentLicense.club.short_name')
                    ->label('النادي الحالي')
                    ->badge()
                    ->color('success')
                    ->default('غير مسجل حالياً'),

                Tables\Columns\TextColumn::make('currentLicense.ageCategory.code')
                    ->label('الفئة')
                    ->badge()
                    ->color('info')
                    ->default('-'),

                Tables\Columns\TextColumn::make('primary_position')
                    ->label('المركز')
                    ->badge()
                    ->color('warning'),

                Tables\Columns\TextColumn::make('birth_date')
                    ->label('تاريخ الميلاد')
                    ->date('Y/m/d')
                    ->description(fn (Player $record) => $record->age . ' سنة'),

                Tables\Columns\TextColumn::make('status')
                    ->label('الحالة')
                    ->badge()
                    ->colors([
                        'success' => 'active',
                        'danger' => 'suspended',
                        'warning' => 'injured',
                        'gray' => 'archived',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'active' => 'نشط',
                        'injured' => 'مصاب',
                        'suspended' => 'موقوف',
                        'transferred' => 'محول',
                        'archived' => 'مؤرشف',
                        default => $state,
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('club')
                    ->label('النادي المنخرط')
                    ->relationship('currentLicense.club', 'name_ar')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('category')
                    ->label('الفئة الشبانية')
                    ->relationship('currentLicense.ageCategory', 'code'),
                Tables\Filters\SelectFilter::make('primary_position')
                    ->label('المركز التكتيكي')
                    ->options([
                        'GK' => 'حارس مرمى',
                        'CB' => 'مدافع محوري',
                        'LB' => 'ظهير أيسر',
                        'RB' => 'ظهير أيمن',
                        'DM' => 'وسط دفاعي',
                        'CM' => 'وسط ميدان',
                        'AM' => 'صانع ألعاب',
                        'LW' => 'جناح أيسر',
                        'RW' => 'جناح أيمن',
                        'ST' => 'مهاجم',
                    ]),
                Tables\Filters\SelectFilter::make('wilaya_id')
                    ->label('الولاية')
                    ->relationship('wilaya', 'name_ar'),
            ])
            ->actions([
                Tables\Actions\Action::make('view_passport')
                    ->label('الجواز الرقمي')
                    ->icon('heroicon-o-qr-code')
                    ->color('success')
                    ->url(fn (Player $record) => route('passport.show', $record->uuid))
                    ->openUrlInNewTab(),
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
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPlayers::route('/'),
            'create' => Pages\CreatePlayer::route('/create'),
            'edit' => Pages\EditPlayer::route('/{record}/edit'),
        ];
    }
}
