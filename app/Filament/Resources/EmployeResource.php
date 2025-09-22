<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeResource\Pages;
use App\Filament\Resources\EmployeResource\RelationManagers;
use App\Models\Client;
use App\Models\Employe;
use App\Models\Jabatan;
use App\Models\UserAbsensi;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Filament\Tables\Actions\Action;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class EmployeResource extends Resource
{
    protected static ?string $model = Employe::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Master Data';

    public static function form(Form $form): Form
    {
        $makeInitials = function (string $name): string {
            // Remove punctuation, collapse spaces
            $clean = preg_replace('/[^\pL\pN\s]+/u', ' ', $name);
            $clean = trim(preg_replace('/\s+/u', ' ', $clean));

            if ($clean === '') return '';

            $parts = explode(' ', $clean);

            $initials = '';
            foreach ($parts as $part) {
                // Take first character of each word, uppercase
                $initials .= mb_strtoupper(mb_substr($part, 0, 1, 'UTF-8'), 'UTF-8');
            }

            return $initials;
        };

        
        return $form
            ->schema([
                 Card::make()
                ->schema([
                    Grid::make(3) // 3 columns layout
                        ->schema([
                            TextInput::make('name')
                                ->label('Masukkan Nama')
                                ->placeholder('Masukkan Nama Karyawan')
                                ->required(),

                            TextInput::make('ttl')
                                ->label('Tempat Tanggal Lahir')
                                ->placeholder('Ponorogo, 99-12-9999')
                                ->columnSpan(2), // spans across next two columns

                           TextInput::make('no_kk')
                                ->label('Masukkan No KK')
                                ->placeholder('1234567890')
                                ->formatStateUsing(function (?string $state): string {
                                    if (!$state) {
                                        return '-';
                                    }

                                    try {
                                        return Crypt::decryptString($state);
                                    } catch (DecryptException $e) {
                                        return $state;
                                    }
                                })
                                ->dehydrateStateUsing(fn (?string $state): ?string =>
                                    $state ? Crypt::encryptString($state) : null
                                )
                                ->required(),

                            TextInput::make('no_ktp')
                                ->label('Masukkan No KTP')
                                ->formatStateUsing(function (?string $state): string {
                                    if (!$state) {
                                        return '-';
                                    }

                                    try {
                                        return Crypt::decryptString($state);
                                    } catch (DecryptException $e) {
                                        return $state;
                                    }
                                })
                                ->dehydrateStateUsing(fn (?string $state): ?string =>
                                    $state ? Crypt::encryptString($state) : null
                                )
                                ->required(),

                            Select::make('client_id')
                                ->label('Pilih Mitra')
                                ->relationship('client', 'name')
                                ->searchable()
                                ->required()
                                ->preload()
                                ->reactive()
                                ->afterStateUpdated(function ($state, callable $set) use ($makeInitials) {
                                    if (! $state) {
                                        $set('initials', null);
                                        $set('numbers', null);
                                        return;
                                    }

                                    if ($client = Client::find($state)) {
                                        $set('initials', $makeInitials($client->name)); 
                                    }

                                    $last = Employe::where('client_id', $state)->max('numbers');
                                    $next = $last ? ((int)$last + 1) : 1;

                                    $set('numbers', str_pad($next, 4, '0', STR_PAD_LEFT));
                                })
                                ->columnSpan(2),

                            FileUpload::make('img_ktp_dpn')
                                ->label('Foto KTP (Depan)')
                                ->disk('public')
                                ->image()
                                ->downloadable()
                                ->maxSize(2048)
                                ->columnSpan(2),

                           Fieldset::make('No Induk Karyawan')
                                ->schema([
                                    Grid::make(3)
                                        ->schema([
                                            TextInput::make('initials')
                                                ->label('Inisial')
                                                ->disabled()
                                                ->dehydrated()
                                                ->required(),

                                            TextInput::make('numbers')
                                                ->label('Nomor Urut')
                                                ->disabled()
                                                ->dehydrated()
                                                ->required()
                                                ->afterStateHydrated(function ($state, callable $set) {
                                                        if ($state !== null) {
                                                            $set('numbers', str_pad((int)$state, 4, '0', STR_PAD_LEFT));
                                                        }
                                                    })
                                                    // When saving, always ensure 4 digits
                                                    ->dehydrateStateUsing(fn ($state) =>
                                                        $state === null ? null : str_pad((int)$state, 4, '0', STR_PAD_LEFT)
                                                ),

                                            DatePicker::make('date_real')
                                                ->label('Tanggal Masuk')
                                                ->reactive()
                                                ->format('Y-m')
                                                ->displayFormat('m-Y')
                                                ->timezone('Asia/Bangkok')
                                                ->native(false)
                                                ->required(),
                                        ]),
                                ])
                                ->columns(3)
                                ->columnSpan(2),

                            FileUpload::make('img')
                                ->label('Foto Profile')
                                ->disk('public')
                                ->directory('images')
                                ->image()
                                ->downloadable()
                                ->maxSize(2048)
                                ->columnSpan(2),

                            TextInput::make('no_bpjs_kesehatan')
                                ->label('No BPJS Kesehatan'),

                            FileUpload::make('file_bpjs_kesehatan')
                                ->label('File BPJS Kesehatan')
                                ->acceptedFileTypes(['application/pdf'])
                                ->disk('public')
                                ->directory('bpjs')
                                ->downloadable()
                                ->columnSpan(2),

                            TextInput::make('no_bpjs_ketenaga')
                                ->label('No BPJS Ketenaga Kerjaan'),

                            FileUpload::make('file_bpjs_ketenaga')
                                ->label('File BPJS Ketenaga Kerjaan')
                                ->acceptedFileTypes(['application/pdf'])
                                ->disk('public')
                                ->directory('bpjs')
                                ->downloadable()
                                ->columnSpan(2),

                            CheckboxList::make('jenis_bpjs')
                                ->label('Jenis BPJS')
                                ->options([
                                    'jkk' => 'JKK',
                                    'jkm' => 'JKM',
                                    'jht' => 'JHT',
                                    'jp'  => 'JP',
                                    'jkp' => 'JKP',
                                ])
                                ->columnSpan(2),
                        ]),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('img')
                    ->label('Foto')
                    ->disk('public')
                    ->getStateUsing(function ($record) {
                        $path = $record->img;

                        if($path == null)
                        {
                            return null;
                        }
                        // If already contains "images/", don’t prepend again
                        if (! str_starts_with($path, 'images/')) {
                            $path = "images/{$path}";
                        }


                        return $path;
                    })
                    ->defaultImageUrl(url('https://placehold.co/400x400/png'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Karyawan')
                    ->placeholder('-')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('posisi')
                   ->getStateUsing(function ($record) {
                        $user = UserAbsensi::whereRaw('LOWER(nama_lengkap) = ?', [strtolower($record->name)])
                            ->with('divisi.jabatan') // sekalian eager load jabatan
                            ->first();

                        return $user?->divisi?->jabatan?->name_jabatan 
                            ?? 'Data NotFound In Absensi';
                    })
                    ->badge()                    
                    ->color(fn ($state) => $state === 'Data NotFound In Absensi' ? 'danger' : 'gray'),
                Tables\Columns\TextColumn::make('ttl')
                    ->label('Tempat Tanggal Lahir')
                    ->placeholder('Kosong')
                    ->alignCenter()
                    ->searchable(),
                Tables\Columns\TextColumn::make('no_kk')
                    ->label('No. KK')
                    ->alignCenter()
                    ->default('-')
                    ->formatStateUsing(function (?string $state): string {
                        if (!$state) {
                            return '-';
                        }

                        try {
                            return Crypt::decryptString($state);
                        } catch (DecryptException $e) {
                            return $state;
                        }
                    })
                    ->searchable(),

                Tables\Columns\TextColumn::make('no_ktp')
                    ->label('No.KTP')
                    ->alignCenter()
                    ->default('-')
                    ->formatStateUsing(function (?string $state): string {
                        if (!$state) {
                            return '-';
                        }

                        try {
                            return Crypt::decryptString($state);
                        } catch (DecryptException $e) {
                            return $state;
                        }
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('client.name')
                    ->label('Mitra')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('no_bpjs_kesehatan')
                    ->label('BPJS Kesehatan')
                    ->alignCenter()
                    ->searchable()
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('no_bpjs_ketenaga')
                    ->label('BPJS Ketenaga')
                    ->alignCenter()
                    ->searchable()
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('psp_format')
                    ->label('No Induk Karyawan')
                    ->getStateUsing(function ($record): string {
                        $initials = $record->initials ?? '';
                        $numbers = $record->numbers ?? '';
                        $dateReal = $record->date_real;

                        $formattedDate = $dateReal;

                        if ($dateReal) {
                            $formattedDate = date('m-Y', strtotime($dateReal));
                        }

                        return trim("{$initials} {$numbers} {$formattedDate}");
                    })
                    // Allow the global search to hit across these DB columns:
                    ->searchable(['initials', 'numbers', 'date_real']),
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
                SelectFilter::make('posisi')
                    ->label('Filter Posisi')
                    ->options(function () {
                        return [
                            'all' => 'Semua Data',
                            'not_in_absensi' => 'Data Tidak Ditemukan di Absensi',
                        ] + Jabatan::on('mysql2connection')
                            ->pluck('name_jabatan', 'name_jabatan')
                            ->toArray();
                    })
                    ->multiple()
                    ->default(['all']) // default array
                    ->query(function (Builder $query, array $data): Builder {
                        $values = $data['values'] ?? [];
                        if (in_array('not_in_absensi', $values)) {
                            $absensiNames = UserAbsensi::on('mysql2connection')
                                ->selectRaw('LOWER(nama_lengkap) as nama')
                                ->pluck('nama')
                                ->all();

                            return $query->whereNotIn(DB::raw('LOWER(name)'), $absensiNames);
                        }

                        if (! in_array('all', $values) && ! empty($values)) {
                            $names = UserAbsensi::on('mysql2connection')
                                ->whereHas('divisi.jabatan', fn($q) => $q->whereIn('name_jabatan', $values))
                                ->selectRaw('LOWER(nama_lengkap) as nama')
                                ->pluck('nama')
                                ->all();

                            return $query->whereIn(DB::raw('LOWER(name)'), $names);
                        }

                        return $query;
                    }),


                SelectFilter::make('client_id')
                    ->label('Filter Mitra')
                    ->relationship('client', 'name')
                    ->query(function (Builder $query, array $data): Builder {
                        // Get the selected client ID from the filter
                        $selectedClientId = $data['value'];

                        // If no client is selected, don't change the query
                        if (empty($selectedClientId)) {
                            return $query;
                        }

                        // Apply the filter to the main Employe query
                        return $query->where('client_id', $selectedClientId);
                    })
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
               Action::make('download_pdf')
                ->label('Download PDF')
                ->icon('heroicon-o-document-arrow-down')
                ->color('success')
                // This is the updated part for Filament 3
                ->action(function ($livewire) {
                    $records = $livewire->getFilteredTableQuery()->get();

                    $pdf = Pdf::loadView('pdf.employes', ['employes' => $records])
                        ->setPaper('a4', 'landscape');

                    $filename = 'data-karyawan-' . date('Y-m-d') . '.pdf';

                    return response()->streamDownload(
                        fn () => print($pdf->output()),
                        $filename
                    );
                }),
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
            'index' => Pages\ListEmployes::route('/'),
            'create' => Pages\CreateEmploye::route('/create'),
            'edit' => Pages\EditEmploye::route('/{record}/edit'),
        ];
    }
}
