<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PGJKontrakResource\Pages;
use App\Filament\Resources\PGJKontrakResource\RelationManagers;
use App\Mail\ContractActiveMail;
use App\Models\Client;
use App\Models\Employe;
use App\Models\Jabatan;
use App\Models\PGJ_Kontrak;
use App\Models\PGJKontrak;
use App\Models\UserAbsensi;
use App\Notifications\ContractActive;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Filament\Tables\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class PGJKontrakResource extends Resource
{
    protected static ?string $model = PGJ_Kontrak::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Master Data';

    public static function getModelLabel(): string
    {
        return 'Pengajuan Kontrak';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Pengajuan Kontrak';
    }


    public static function getNavigationBadge(): ?string
    {
        return \App\Models\PGJ_Kontrak::query()
            ->where('tgl_selesai_kontrak', '<', now())
            ->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger'; // warna merah biar jelas
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Filter Mitra')
                    ->schema([
                        Select::make('mitra_id')
                            ->label('Pilih Mitra')
                            ->options(Client::pluck('name', 'id')->toArray()) // ambil dari tabel Client
                            ->searchable()
                            ->reactive(),
                    ]),

                // SECTION SURAT
                Section::make('Section Surat')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('no_srt')
                                ->label('Masukkan No Surat')
                                ->placeholder(function () {
                                    $last = PGJ_Kontrak::orderBy('id', 'desc')->first();
                                    if ($last) {
                                        // Pisahkan nomor surat berdasarkan "/"
                                        $parts = explode('/', $last->no_srt);

                                        // Ambil angka depannya (misal "450")
                                        $lastNumber = (int) $parts[0];

                                        // Naikkan 1
                                        $nextNumber = $lastNumber + 1;

                                        // Gabungkan kembali (pakai format lama persis)
                                        $parts[0] = $nextNumber;

                                        return implode('/', $parts);
                                    }

                                    // Kalau belum ada data, mulai dari 1
                                    return '001/SAC/' . strtoupper(now()->format('m')) . '/' . now()->year;
                                })
                                ->required(),

                            TextInput::make('nama_pk_ptm')
                                ->label('Nama Pihak Pertama')
                                ->placeholder('Masukan Nama Pihak Pertama')
                                ->required(),
                        ]),

                        Grid::make(2)->schema([
                            DatePicker::make('tgl_dibuat')
                                ->label('Tgl Surat Dibuat/disepakati')
                                ->placeholder('99/12/9999')
                                ->native(false)
                                ->required(),

                            TextInput::make('alamat_pk_ptm')
                                ->placeholder('Ponorogo')
                                ->label('Alamat Pihak Pertama'),
                        ]),

                        Grid::make(2)->schema([
                            DatePicker::make('tgl_mulai_kontrak')
                                ->label('Tgl Mulai Kontrak')
                                ->placeholder('99/12/9999')
                                ->native(false)
                                ->required(),

                            TextInput::make('jabatan_pk_ptm')
                                ->label('Jabatan Pihak Pertama')
                                ->placeholder('Jabatan Pihak Pertama')
                                ->required(),
                        ]),

                        Grid::make(2)->schema([
                            DatePicker::make('tgl_selesai_kontrak')
                                ->native(false)
                                ->placeholder('99/12/9999')
                                ->label('Tgl Selesai Kontrak')
                                ->required(),
                        ]),
                    ]),

                // PIHAK KEDUA
                Section::make('Pihak Kedua')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('nama_pk_kda')
                                ->label('Nama Pihak Kedua')
                                ->options(function (callable $get) {
                                    $mitraId = $get('mitra_id');

                                    $userAbsensi = UserAbsensi::where('nama_lengkap', '!=', 'admin')
                                        ->whereIn('nama_lengkap', function($query) {
                                            $query->select('nama_pk_kda')
                                                ->from('sacpocom_edata.p_g_j__kontraks')
                                                ->whereNull('deleted_at')
                                                ->whereDate('tgl_selesai_kontrak', '<=', Carbon::today()->toDateString()); // expired kontrak
                                        })
                                        ->whereNotIn('nama_lengkap', function($query) {
                                            $query->select('nama_pk_kda')
                                                ->from('sacpocom_edata.p_g_j__kontraks')
                                                ->whereNull('deleted_at')
                                                ->whereDate('tgl_selesai_kontrak', '>', Carbon::today()->toDateString()); // masih aktif
                                        })
                                        ->when($mitraId, function ($q) use ($mitraId) {
                                            $q->whereHas('kerjasama', function ($query) use ($mitraId) {
                                                $query->where('client_id', $mitraId);
                                            });
                                        })
                                        ->pluck('nama_lengkap', 'nama_lengkap')
                                        ->toArray();

                                    $employees = Employe::when($mitraId, function ($q) use ($mitraId) {
                                            $q->where('client_id', $mitraId);
                                        })
                                        ->whereNotIn('name', function($query) {
                                            $query->select('nama_pk_kda')
                                                ->from('sacpocom_edata.p_g_j__kontraks')
                                                ->whereNull('deleted_at');
                                        })
                                        ->pluck('name', 'name')
                                        ->toArray();

                                    return array_merge($userAbsensi, $employees);
                                })

                                ->searchable()
                                ->required()
                                ->reactive()
                                ->afterStateUpdated(function ($state, callable $set) {
                                    // Ambil data user
                                    $selectedUser = UserAbsensi::where('nama_lengkap', $state)->first();
                                    $selectEmploye = Employe::where('name', $state)->first();

                                    if ($selectedUser && $selectEmploye) {
                                        // Misal TTL disimpan "PONOROGO, 1990-01-20"
                                        $ttl = explode(',', $selectEmploye->ttl ?? '');
                                        $tempat = trim($ttl[0] ?? '');
                                        $tanggal = isset($ttl[1]) ? Carbon::parse(trim($ttl[1])) : null;

                                        $set('jabatan_pk_kda', $selectedUser->jabatan->name_jabatan ?? '');
                                        $set('unit_pk_kda', $selectedUser->client->name ?? '');
                                        $set('nik_pk_kda', $selectEmploye->no_ktp ?? '');
                                        $set('tempat_lahir_pk_kda', $tempat);
                                        $set('tgl_lahir_pk_kda', $tanggal);
                                        $set('status_pk_kda', $selectedUser->status ?? '');
                                        $set('alamat_pk_kda', $selectEmploye->alamat ?? '');
                                    }
                                }),

                            Grid::make(2)->schema([
                                TextInput::make('tempat_lahir_pk_kda')
                                    ->label('Tempat')
                                    ->placeholder('Tempat Lahir Pihak Kedua')
                                    ->required(),

                                DatePicker::make('tgl_lahir_pk_kda')
                                    ->native(false)
                                    ->label('Tanggal Lahir')
                                    ->placeholder('99/12/9999')
                                    ->required(),
                            ]),
                        ]),

                        Grid::make(2)->schema([
                            TextInput::make('nik_pk_kda')
                                ->label('NIK')
                                ->placeholder('123456789')
                                ->required(),

                            TextInput::make('alamat_pk_kda')
                                ->label('Alamat')
                                ->placeholder('Ponorogo')
                                ->required(),
                        ]),

                        Grid::make(2)->schema([
                            Select::make('jabatan_pk_kda')
                                ->label('Jabatan')
                                ->placeholder('Pilih Jabatan')
                                ->options(
                                    Jabatan::pluck('name_jabatan', 'name_jabatan')
                                )
                                ->required(),

                            Select::make('status_pk_kda')
                                ->label('Status')
                                ->options([
                                    'Karyawan Kontrak' => 'Karyawan Kontrak',
                                    'Karyawan Tetap' => 'Karyawan Tetap'
                                ])
                                ->native(false)
                                ->placeholder('Pilih Status')
                                ->required(),
                        ]),

                        TextInput::make('unit_pk_kda')
                            ->label('Unit Kerja')
                            ->required(),
                    ]),

                // GAJI DAN TUNJANGAN
                Section::make('Gaji Dan Tunjangan')
                    ->schema([
                        TextInput::make('g_pok')
                            ->label('Gaji Pokok')
                            ->placeholder('9000000')
                            ->numeric(),

                        TextInput::make('tj_hadir')
                            ->label('Tunjangan Kehadiran')
                            ->placeholder('9000000')
                            ->numeric(),

                        TextInput::make('kinerja')
                            ->label('Kinerja')
                            ->placeholder('9000000')
                            ->numeric(),

                        TextInput::make('lain_lain')
                            ->label('Lain Lain')
                            ->placeholder('9000000')
                            ->numeric(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('no')
                    ->label('No')
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('no_srt')->label('No Surat')->sortable()->searchable()->alignCenter(),
                Tables\Columns\TextColumn::make('nama_pk_kda')->label('Nama Pihak Kedua')->sortable()->searchable()->alignCenter()->color(
                    fn($record) =>
                    \Carbon\Carbon::now()->greaterThan(\Carbon\Carbon::parse($record->tgl_selesai_kontrak))
                        ? 'danger'
                        : ''
                ),
                Tables\Columns\TextColumn::make('nik_pk_kda')->label('NIK')->sortable()->searchable()->alignCenter(),
                Tables\Columns\TextColumn::make('jabatan_pk_kda')->label('Jabatan')->alignCenter(),
                Tables\Columns\TextColumn::make('unit_pk_kda')->label('Unit Kerja')->alignCenter(),
                Tables\Columns\TextColumn::make('status_pk_kda')->label('Status')->badge()->alignCenter(),
                Tables\Columns\TextColumn::make('tgl_mulai_kontrak')
                    ->label('Tanggal Kontrak')
                    ->date('d/m/Y')
                    ->suffix(fn($record) => ' - ' . \Carbon\Carbon::parse($record->tgl_selesai_kontrak)->format('d/m/Y'))
                    ->color(
                        fn($record) =>
                        \Carbon\Carbon::now()->greaterThan(\Carbon\Carbon::parse($record->tgl_selesai_kontrak))
                            ? 'danger'   // merah kalau sudah lewat
                            : 'success'  // hijau kalau masih aktif
                    )
                    ->icon(
                        fn($record) =>
                        \Carbon\Carbon::now()->greaterThan(\Carbon\Carbon::parse($record->tgl_selesai_kontrak))
                            ? 'heroicon-o-exclamation-circle' // expired
                            : 'heroicon-o-check-circle'       // masih aktif
                    ),

                Tables\Columns\TextColumn::make('ttd')
                    ->label('TTD')
                    ->alignCenter()
                    ->badge()
                    ->color(fn($record) => $record->ttd != null ? 'success' : 'danger')
                    ->icon(fn($record) => $record->ttd != null ? 'heroicon-o-check-badge' : 'heroicon-o-x-circle')
                    ->getStateUsing(function ($record) {
                        if ($record->ttd) {
                            return 'Pihak 2';
                        } elseif ($record->ttd_atasan) {
                            return 'Pihak 1';
                        } elseif ($record->ttd && $record->ttd_atasan) {
                            return 'Pihak 1 & 2';
                        } else {
                            return 'Belum TTD';
                        }
                    }),
                Tables\Columns\IconColumn::make('send_to_operator')
                    ->boolean()
                    ->label('Sent'),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('status_pk_kda')
                    ->label('Status Karyawan')
                    ->options([
                        'Karyawan Kontrak' => 'Karyawan Kontrak',
                        'Tetap' => 'Tetap',
                    ]),
                Tables\Filters\SelectFilter::make('jabatan_pk_kda')
                    ->label('Jabatan')
                    ->options(
                        fn() => \App\Models\PGJ_Kontrak::query()
                            ->select('jabatan_pk_kda')
                            ->distinct()
                            ->pluck('jabatan_pk_kda', 'jabatan_pk_kda')
                            ->toArray()
                    ),
                Tables\Filters\SelectFilter::make('unit_pk_kda')
                    ->label('Unit Kerja')
                    ->options(
                        fn() => \App\Models\PGJ_Kontrak::query()
                            ->select('unit_pk_kda')
                            ->distinct()
                            ->pluck('unit_pk_kda', 'unit_pk_kda')
                            ->toArray()
                    ),
                Tables\Filters\SelectFilter::make('kontrak_habis') // UNIQUE NAME
                    ->label('Kontrak Habis')
                    ->options(function () {
                        return PGJ_Kontrak::query()
                            ->whereNull('deleted_at')
                            ->whereDate('tgl_selesai_kontrak', '<=', now())
                            ->distinct()
                            ->pluck('unit_pk_kda', 'unit_pk_kda')
                            ->toArray();
                    })
                    ->query(function ($query, $data) {
                        if ($data['value']) {
                            $query->where('unit_pk_kda', $data['value'])
                                ->whereDate('tgl_selesai_kontrak', '<=', now());
                        }
                    })
                    ->placeholder('Pilih Unit'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('download_pdf')
                ->label('Download PDF')
                ->icon('heroicon-o-document-arrow-down')
                ->color('success')
                // This is the updated part for Filament 3
                ->action(function ($record) {
                    $pdf = Pdf::loadView('filament.pages.kontrak-page', ['kontrak' => $record])
                        ->setPaper('a4', 'potrait');

                    $filename = 'kontrak-karyawan-' . $record->nama_pk_kda . date('Y-m-d') . '.pdf';

                    return response()->streamDownload(
                        fn () => print($pdf->output()),
                        $filename
                    );
                }),
                Tables\Actions\Action::make('preview_pdf')
                ->label('Preview PDF')
                ->icon('heroicon-o-eye')
                ->color('info')
                ->modalHeading('Preview Kontrak PDF')
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('Close')
                ->modalContent(function ($record) {
                    $pdf = Pdf::loadView('filament.pages.kontrak-page', [
                        'kontrak' => $record,
                    ])->setPaper('a4', 'portrait');

                    $pdfContent = base64_encode($pdf->output());

                    // Embed PDF in an iframe
                    return view('components.pdf-preview', [
                        'pdfContent' => $pdfContent,
                    ]);
                }),
                Action::make('sendToOperator')
                    ->label('Send to Operator')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('info')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $isEmailUser = UserAbsensi::where('nama_lengkap', $record->nama_pk_kda)->first();
                        if ($record->send_to_operator == 0) {
                            $record->update([
                                'send_to_operator' => 1,
                            ]);

                            $isEmailUser->notify(new ContractActive());

                            Notification::make()
                                ->title('Berhasil diverifikasi & Notif Ke Email User')
                                ->success()
                                ->send();
                        }
                    })
                    ->visible(fn(Model $record): bool => $record->send_to_operator != 1),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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
            'index' => Pages\ListPGJKontraks::route('/'),
            'create' => Pages\CreatePGJKontrak::route('/create'),
            'edit' => Pages\EditPGJKontrak::route('/{record}/edit'),
            'view' => Pages\ViewPGJKontraks::route('/{record}/view'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
