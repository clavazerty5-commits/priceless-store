<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Models\User;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\BulkActionGroup;

class CustomerResource extends Resource
{
    protected static ?string $model = User::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-users';
    protected static ?int $navigationSort = 6;

    public static function getNavigationLabel(): string
    {
        return app()->getLocale() === 'fr' ? 'Utilisateurs & Clients' : 'العملاء والحسابات';
    }

    public static function getModelLabel(): string
    {
        return app()->getLocale() === 'fr' ? 'Utilisateur' : 'مستخدم / عميل';
    }

    public static function getPluralModelLabel(): string
    {
        return app()->getLocale() === 'fr' ? 'Utilisateurs & Clients' : 'العملاء والحسابات';
    }

    public static function form(Schema $schema): Schema
    {
        $isFr = app()->getLocale() === 'fr';

        return $schema
            ->components([
                Section::make($isFr ? 'Informations du Compte' : 'بيانات الحساب الشخصي')
                    ->schema([
                        TextInput::make('name')
                            ->label($isFr ? 'Nom complet' : 'الاسم الكامل')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label($isFr ? 'Adresse Email' : 'البريد الإلكتروني')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        TextInput::make('password')
                            ->label($isFr ? 'Nouveau mot de passe' : 'كلمة السر الجديدة')
                            ->password()
                            ->revealable()
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->helperText($isFr 
                                ? 'Laissez vide si vous ne souhaitez pas modifier le mot de passe actuel.' 
                                : 'اترك هذا الحقل فارغاً إذا كنت لا ترغب في تغيير كلمة السر الحالية.')
                            ->maxLength(255),
                        Toggle::make('is_admin')
                            ->label($isFr ? 'Accès Administrateur (Admin)' : 'صلاحية مسؤول / مدير المتجر')
                            ->helperText($isFr 
                                ? 'Permet d\'accéder au panneau d\'administration.' 
                                : 'يمنح هذا المستخدم حق الدخول إلى لوحة تحكم المتجر.')
                            ->default(false),
                    ])->columns(2),

                Section::make($isFr ? 'Coordonnées de Livraison' : 'بيانات الاتصال والعنوان')
                    ->schema([
                        TextInput::make('phone')
                            ->label($isFr ? 'Numéro de téléphone' : 'رقم الهاتف')
                            ->tel()
                            ->placeholder('+216 23 456 789'),
                        TextInput::make('city')
                            ->label($isFr ? 'Ville' : 'المدينة')
                            ->placeholder($isFr ? 'Tunis, Sousse, Sfax...' : 'تونس، سوسة، صفاقس...'),
                        Textarea::make('address')
                            ->label($isFr ? 'Adresse complète' : 'العنوان بالتفصيل')
                            ->rows(3)
                            ->placeholder($isFr ? 'Rue, bâtiment, appartement...' : 'الشارع، رقم العمارة / المنزل...')
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        $isFr = app()->getLocale() === 'fr';

        return $table
            ->columns([
                TextColumn::make('name')
                    ->label($isFr ? 'Nom' : 'الاسم')
                    ->searchable()
                    ->weight('bold'),
                TextColumn::make('email')
                    ->label($isFr ? 'Email' : 'البريد الإلكتروني')
                    ->searchable()
                    ->copyable(),
                TextColumn::make('phone')
                    ->label($isFr ? 'Téléphone' : 'الهاتف')
                    ->searchable()
                    ->placeholder('-'),
                TextColumn::make('city')
                    ->label($isFr ? 'Ville' : 'المدينة')
                    ->searchable()
                    ->placeholder('-'),
                IconColumn::make('is_admin')
                    ->label($isFr ? 'Rôle' : 'الرتبة')
                    ->boolean()
                    ->trueIcon('heroicon-o-shield-check')
                    ->falseIcon('heroicon-o-user')
                    ->trueColor('warning')
                    ->falseColor('gray')
                    ->sortable(),
                TextColumn::make('orders_count')
                    ->label($isFr ? 'Commandes' : 'الطلبات')
                    ->counts('orders')
                    ->badge()
                    ->color('info')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label($isFr ? 'Date d\'inscription' : 'تاريخ التسجيل')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('is_admin')
                    ->label($isFr ? 'Type de compte' : 'نوع الحساب')
                    ->options([
                        '0' => $isFr ? 'Clients' : 'العملاء',
                        '1' => $isFr ? 'Administrateurs' : 'المدراء',
                    ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageCustomers::route('/'),
        ];
    }
}
