<?php

namespace App\Filament\Resources;

use App\Enums\HeroIconType;
use App\Filament\Resources\AdvertisementResource\Pages;
use App\Filament\Resources\AdvertisementResource\RelationManagers;
use App\Models\Advertisement;
use App\Models\CompanyInformation;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AdvertisementResource extends Resource
{
    protected static ?string $model = Advertisement::class;

    protected static ?string $navigationIcon = HeroIconType::RECTANGLE_GROUP->value;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Advertisement & Descriptions')
                ->description(false)
                    ->schema([
                    Forms\Components\TextInput::make('ad_title')
                        ->label('Title')
                        ->helperText('Title of Advertisement')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\Select::make('company_information_id')
                        ->label('Company Name')
                        ->default(fn () => CompanyInformation::latest()->value('id'))
                        ->options(fn () => CompanyInformation::pluck('company_name','id'))
                        ->required(),
                    Forms\Components\MarkdownEditor::make('ad_description')
                        ->label('Description')
                        ->columnSpanFull()
                        ->required()
                        ->helperText('This is also appear in the Advertisement Page'),
                    Forms\Components\FileUpload::make('ad_image')
                        ->multiple()
                        ->disk('public')
                        ->directory('advertisement-pictures')
                        ->columnSpanFull()
                        ->image(),
                    ])
                ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('company_information_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ad_title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ad_description')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('ad_image'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAdvertisements::route('/'),
            'create' => Pages\CreateAdvertisement::route('/create'),
            'edit' => Pages\EditAdvertisement::route('/{record}/edit'),
        ];
    }
}
