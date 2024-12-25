<?php

namespace App\Filament\Resources;

use App\Enums\HeroIconType;
use App\Filament\Resources\PatronResource\Pages;
use App\Filament\Resources\PatronResource\RelationManagers;
use App\Models\CompanyInformation;
use App\Models\Patron;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PatronResource extends Resource
{
    protected static ?string $model = Patron::class;

    protected static ?string $navigationIcon = HeroIconType::USERS->value;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

            Section::make('Advertisement & Descriptions')
            ->description(false)
                ->schema([
                    Forms\Components\Select::make('company_information_id')
                        ->label('Company Name')
                        ->default(fn () => CompanyInformation::latest()->value('id'))
                        ->options(fn () => CompanyInformation::pluck('company_name','id'))
                        ->required(),
                    Forms\Components\TextInput::make('patron_name')
                        ->label('Fullname')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('patron_email')
                        ->email()
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('patron_phone')
                        ->label('Mobile Number')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\MarkdownEditor::make('patron_description')
                        ->label('Patrong Information')
                        ->required()
                        ->columnSpanFull(),
                    Forms\Components\TextInput::make('twitter_link')
                        ->label('Twitter Link')
                        ->required()
                        ->url()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('fb_link')
                        ->label('Facebook Link')
                        ->required()
                        ->url()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('penterest_link')
                        ->label('Penterest Link')
                        ->required()
                        ->url()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('youtube_link')
                        ->label('Youtube Link')
                        ->required()
                        ->url()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('linkedin_link')
                        ->label('Linkedin Link')
                        ->required()
                        ->url()
                        ->maxLength(255),
                    Forms\Components\FileUpload::make('patron_images')
                        ->multiple()
                        ->disk('public')
                        ->directory('patron-pictures')
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
                Tables\Columns\TextColumn::make('patron_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('patron_images')
                    ->searchable(),
                Tables\Columns\TextColumn::make('patron_email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('patron_phone')
                    ->searchable(),
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
            'index' => Pages\ListPatrons::route('/'),
            'create' => Pages\CreatePatron::route('/create'),
            'edit' => Pages\EditPatron::route('/{record}/edit'),
        ];
    }
}
