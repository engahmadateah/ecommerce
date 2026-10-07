<?php

namespace App\Filament\Resources\Categories;

use App\Filament\Resources\Categories\Pages\CreateCategory;
use App\Filament\Resources\Categories\Pages\EditCategory;
use App\Filament\Resources\Categories\Pages\ListCategories;
use App\Filament\Resources\Categories\Schemas\CategoryForm;
use App\Filament\Resources\Categories\Tables\CategoriesTable;
use App\Models\Category;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Tables;

use Filament\Forms\Components\TextInput;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                
TextInput::make('name')
->required()
->live(onBlur: true)
->afterStateUpdated(fn ($state, callable $set) =>
    $set('slug', \Str::slug($state))
),

TextInput::make('slug')
->required()
->disabled(),

\App\Filament\Support\TranslationFields::section(false),
            ]);
    }

    public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('name')
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->since(),
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
            'index' => ListCategories::route('/'),
            'create' => CreateCategory::route('/create'),
            'edit' => EditCategory::route('/{record}/edit'),
        ];
    }
}
