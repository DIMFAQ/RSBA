<?php

namespace App\Livewire\Settings\Menu;

use App\Models\Menu;
use Livewire\Component;
use App\Enums\MenuGroup;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class MenuTable extends Component implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms;

    public ?Menu $menu;

    static function table(Table $table): Table
    {
        return $table
            ->query(Menu::query())
            ->deferLoading(false)
            ->columns([
                TextColumn::make('id')
                    ->label('Id'),
                IconColumn::make('icon')
                    ->label('Icon')
                    ->getStateUsing(fn(Menu $menu) => $menu->icon ? 'tabler-' . $menu?->icon : '')
                    ->icon(fn(Menu $menu) => $menu->icon ? 'tabler-' . $menu?->icon : '')
                    ->color('primary'),
                TextColumn::make('nama')
                    ->label('Title / Nama ')
                    ->searchable(),
                TextColumn::make('route')
                    ->label('Routing')
                    ->searchable(),
                TextColumn::make('permission')
                    ->label('Permissions')
                    ->searchable(),
                TextColumn::make('parent.nama')
                    ->label('Parent Menu')
                    ->getStateUsing(function (Menu $menu) {
                        if ($menu->parent_id == 0 && $menu->id == 0) {
                            return '<span class="py-0.5 px-1 text-xs bg-red-200/50 text-red-500 rounded-md border border-p-2 border-red-500">Main Menu</span>';
                        } else if ($menu->parent_id == 0) {
                            return '<span class="py-0.5 px-1 text-xs bg-primary-200/50 text-primary-500 rounded-md border border-p-2 border-primary-500">Main Menu</span>';
                        } else {
                            return $menu->parent->nama;
                        }
                    })
                    ->html()
            ])
            ->filters([
                SelectFilter::make('group')
                    ->label('Group')
                    ->options(
                        fn(): array =>
                        collect(MenuGroup::options())
                            ->pluck('label', 'value')
                            ->toArray()
                    ),
            ])
            ->actions([
                Action::make('Edit')
                    ->icon('tabler-edit')
                    ->action(
                        fn(Menu $menu, $livewire) => $livewire->editMenu(modal: 'edit-menu', id: $menu->getKey())

                    ),
            ]);
    }

    function editMenu($modal, $id)
    {
        $this->menu = Menu::findOrFail($id);
        $this->dispatch('open-modal', id: $modal);
    }

    public function render()
    {
        return view('livewire.settings.menu.menu-table');
    }
}
