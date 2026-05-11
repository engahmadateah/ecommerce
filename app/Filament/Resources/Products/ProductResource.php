<?php

namespace App\Filament\Resources\Products;

use App\Filament\Resources\Products\Pages\CreateProduct;
use App\Filament\Resources\Products\Pages\EditProduct;
use App\Filament\Resources\Products\Pages\ListProducts;
use App\Filament\Resources\Products\Schemas\ProductForm;
use App\Filament\Resources\Products\Tables\ProductsTable;
use App\Models\Product;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Forms;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Tables;
use Filament\Schemas\Components\Section;


class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Product';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
    
                Section::make('Product Info')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
    
                        Textarea::make('description')
                            ->columnSpanFull(),
    
                        TextInput::make('price')
                            ->numeric()
                            ->required(),
    
                        TextInput::make('stock')
                            ->numeric()
                            ->default(0),
                    ]),
    
                Section::make('Category & Image')
                    ->schema([
                        Select::make('category_id')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->label('Category'),
    
                        FileUpload::make('image')
                            ->image()
                            ->directory('products')
                            ->disk('public')
                            ->imagePreviewHeight('150')
                            ->loadingIndicatorPosition('left'),


                     TextInput::make('discount_price')
                            ->label('Discount Price')
                            ->numeric()
                            ->nullable()
                    ]),
            ]);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Image'),
    
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
    
                Tables\Columns\TextColumn::make('category.name')
                    ->badge()
                    ->color('success'),
    
                Tables\Columns\TextColumn::make('price')
                    ->money('USD')
                    ->sortable(),
    
                Tables\Columns\TextColumn::make('stock')
                    ->badge()
                    ->color(fn ($state) => $state > 0 ? 'success' : 'danger')
                    ->formatStateUsing(fn ($state) =>
                        $state > 0 ? "In Stock ($state)" : "Out of Stock"
                    ),
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
            'index' => ListProducts::route('/'),
            'create' => CreateProduct::route('/create'),
            'edit' => EditProduct::route('/{record}/edit'),
        ];
    }
}
