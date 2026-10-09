<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('category_id')
                    ->relationship('category', 'name')
                    ->required(),
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('sku')
                    ->label('SKU')
                    ->default(fn () => 'PRD-' . strtoupper(\Illuminate\Support\Str::random(8)))
                    ->readOnly()
                    ->maxLength(255),
                Forms\Components\TextInput::make('collection')
                    ->maxLength(255),
                Forms\Components\TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                Forms\Components\TextInput::make('original_price')
                    ->numeric(),
                Forms\Components\TextInput::make('discount')
                    ->maxLength(255),
                Forms\Components\TextInput::make('rating')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                Forms\Components\TextInput::make('review_count')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('sizes'),
                Forms\Components\TextInput::make('colors'),
                Forms\Components\TextInput::make('badge')
                    ->maxLength(255),
                Forms\Components\Textarea::make('short_desc')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('long_desc')
                    ->label('Deskripsi Lengkap')
                    ->rows(6)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('weight')
                    ->label('Berat (gram)')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->default(250),
                Forms\Components\TextInput::make('length')
                    ->label('Panjang (cm)')
                    ->numeric()
                    ->minValue(1),
                Forms\Components\TextInput::make('width')
                    ->label('Lebar (cm)')
                    ->numeric()
                    ->minValue(1),
                Forms\Components\TextInput::make('height')
                    ->label('Tinggi (cm)')
                    ->numeric()
                    ->minValue(1),
                Forms\Components\FileUpload::make('image')
                    ->image(),
                Forms\Components\TextInput::make('sales_count')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('stock')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('weight')
                    ->label('Berat (gram)')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->default(250),
                Forms\Components\Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('category.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable(),
                Tables\Columns\TextColumn::make('collection')
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->money()
                    ->sortable(),
                Tables\Columns\TextColumn::make('original_price')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('discount')
                    ->searchable(),
                Tables\Columns\TextColumn::make('rating')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('review_count')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('badge')
                    ->searchable(),

                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\TextColumn::make('sales_count')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('stock')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('weight')
                    ->label('Berat (gr)')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Active')
                    ->sortable(),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
