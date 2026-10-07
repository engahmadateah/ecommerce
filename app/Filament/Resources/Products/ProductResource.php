<?php

namespace App\Filament\Resources\Products;

use App\Filament\Resources\Products\Pages\CreateProduct;
use App\Filament\Resources\Products\RelationManagers\VariantsRelationManager;
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
use Filament\Forms\Components\Toggle;
use App\Models\Product as ProductModel;
use Illuminate\Support\Str;
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
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, $set, $operation) {
                                if ($operation === 'create' && filled($state)) {
                                    $set('slug', ProductModel::uniqueSlug((string) $state));
                                }
                            }),

                        TextInput::make('slug')
                            ->label('URL slug')
                            ->helperText('The product page address, e.g. /products/blue-shirt. Fixed after creation.')
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->disabledOn('edit'),

                        TextInput::make('sku')
                            ->label('SKU')
                            ->maxLength(64)
                            ->unique(ignoreRecord: true),

                        Toggle::make('is_published')
                            ->label('Published (visible in the shop)')
                            ->default(true),
    
                        Textarea::make('description')
                            ->columnSpanFull(),
    
                        TextInput::make('price')
                            ->numeric()
                            ->required(),
    
                        TextInput::make('stock')
                            ->numeric()
                            ->default(0)
                            ->helperText('Ignored when the product has variants: it becomes the sum of their stock.'),
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

                        FileUpload::make('gallery')
                            ->label('More images')
                            ->image()
                            ->multiple()
                            ->reorderable()
                            ->maxFiles(8)
                            ->directory('products')
                            ->disk('public'),


                     TextInput::make('discount_price')
                            ->label('Discount Price')
                            ->numeric()
                            ->nullable()
                    ]),

                \App\Filament\Support\TranslationFields::section(),
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

                Tables\Columns\TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\IconColumn::make('is_published')
                    ->label('Published')
                    ->boolean(),
    
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
            VariantsRelationManager::class,
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
