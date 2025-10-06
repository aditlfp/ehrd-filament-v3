<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TempUsersResource\Pages;
use App\Filament\Resources\TempUsersResource\RelationManagers;
use App\Jobs\SendDiscordWebhookJob;
use App\Models\Client;
use App\Models\Employe;
use App\Models\Kerjasama;
use App\Models\TempUsers;
use App\Models\UserAbsensi;
use App\Notifications\UserVerified;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;

class TempUsersResource extends Resource
{
    protected static ?string $model = TempUsers::class;

    protected static ?string $navigationIcon = 'heroicon-o-finger-print';

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?string $pluralLabel = 'User Confirmation';

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::where('status', 0)->count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {

        return $table
            ->defaultSort('status', 'asc')
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\ImageColumn::make('data.image')
                    ->label('Foto Profile')
                    ->getStateUsing(function ($record) {
                        $path = $record->data['image'];


                        if ($path == null) {
                            return null;
                        } else {
                            $path = "https://absensi-sac.sac-po.com/public/storage/user/{$record->data['image']}";
                            return $path;
                        }
                    })
                    ->defaultImageUrl(url('https://placehold.co/400x400/png'))
                    ->circular(),
                Tables\Columns\TextColumn::make('data.nama_lengkap')
                    ->label('Nama Lengkap')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('data.pw')
                    ->label('Password'),
                Tables\Columns\TextColumn::make('data.no_hp')
                    ->label('No HP ( Aktif )'),
                Tables\Columns\TextColumn::make('data.email')
                    ->label('Email'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Input')
                    ->dateTime('d-m-Y H:i')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->formatStateUsing(fn(bool $state): string => $state ? 'Approve' : 'Pending')
                    ->color(fn(bool $state): string => $state ? 'success' : 'warning')
                    ->sortable()


            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('verifikasi')
                    ->label('Verifikasi')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (Model $record) {
                        $kerjasama = Kerjasama::where('client_id', $record->data['client_id'])->first();
                        try {
                            DB::transaction(function () use ($record, $kerjasama) {

                                $record->status = 1;
                                $userAbsensi = UserAbsensi::query();
                                if ($userAbsensi->where('email', $record->data['email'])->first() || $userAbsensi->where('name', $record->data['username'])->first()) {
                                    return Notification::make()
                                        ->title('User Gagal diverifikasi: User / Email sudah terdaftar')
                                        ->danger()
                                        ->send();
                                }

                                if ($userAbsensi->where('nik', $record->data['nik'])->first()) {
                                    if ($userAbsensi->status_id != 1) {
                                        $userAbsensi->status_id = 1;
                                        $userAbsensi->save();
                                    }
                                } else {
                                    $webhookUrl = config('services.discord.webhook_url');
                                    $tempAcc = UserAbsensi::create([
                                        'name' => $record->data['username'],
                                        'nama_lengkap' => $record->data['nama_lengkap'],
                                        'kerjasama_id' => $kerjasama->id,
                                        'email' => $record->data['email'],
                                        'password' => $record->data['password'],
                                        'image' => $record->data['image'],
                                        'devisi_id' => $record->data['devisi_id'],
                                        'jabatan_id' => $record->data['jabatan_id'],
                                        'status_id' => 1,
                                        'nik' => $record->data['nik'],
                                        'no_hp' => $record->data['no_hp'],
                                        'alamat' => $record->data['alamat']
                                    ]);

                                    Http::post($webhookUrl, [
                                        'embeds' => [[
                                            'title' => '✨ New User Registration',
                                            'description' => "Welcome **{$tempAcc->nama_lengkap}** to the Absensi APP!",
                                            'color' => 5793266, // Beautiful purple color
                                            'thumbnail' => [
                                                'url' => asset('assets/logo.png')
                                            ],
                                            'fields' => [
                                                [
                                                    'name' => '👤 Full Name',
                                                    'value' => $tempAcc->nama_lengkap,
                                                    'inline' => false
                                                ],
                                                [
                                                    'name' => '🧩 User Name',
                                                    'value' => $tempAcc->name,
                                                    'inline' => false
                                                ],
                                                [
                                                    'name' => '📧 Email Address',
                                                    'value' => "`{$tempAcc->email}`",
                                                    'inline' => false
                                                ],
                                                [
                                                    'name' => '🆔 User ID',
                                                    'value' => "`{$tempAcc->id}`",
                                                    'inline' => true
                                                ],
                                                [
                                                    'name' => '📊 Status',
                                                    'value' => $tempAcc->status_id == 1 ? '🟢 Active' : '🔴 Inactive',
                                                    'inline' => true
                                                ],
                                                [
                                                    'name' => '📅 Registered At',
                                                    'value' => now()->format('F j, Y - g:i A'),
                                                    'inline' => false
                                                ]
                                            ],
                                            'footer' => [
                                                'text' => 'User Management System',
                                                'icon_url' => 'https://cdn.discordapp.com/embed/avatars/1.png'
                                            ],
                                            'timestamp' => now()->toIso8601String(),
                                        ]]
                                    ]);
                                    if ($tempAcc->status_id != 1) {
                                        throw new \Exception('Gagal mengatur status absensi ke aktif (1)');
                                        return Notification::make()
                                            ->title('User berhasil diverifikasi: Status Akun Non Aktif')
                                            ->danger()
                                            ->send();
                                    }
                                }


                                Employe::create([
                                    'name' => $record->data['nama_lengkap'],
                                    'ttl' => $record->data['ttl'],
                                    'nik' => $record->data['nik'],
                                    'no_kk'  => $record->data['no_kk'],
                                    'client_id' => $record->data['client_id'],
                                    'img'  => $record->data['image'],
                                    'img_ktp_dpn' => $record->data['img_ktp_dpn'],
                                    'no_ktp' => 0,
                                    'alamat' => $record->data['alamat']

                                ]);
                                $record->save();
                                $record->notify(new UserVerified());

                                Notification::make()
                                    ->title('User berhasil diverifikasi')
                                    ->success()
                                    ->send();
                            });
                        } catch (\Throwable $th) {
                            dd($th);
                            DB::rollBack();
                            Notification::make()
                                ->title('User Gagal diverifikasi: ' . $th->getMessage())
                                ->danger()
                                ->send();
                        }
                    })
                    ->visible(fn(Model $record): bool => !$record->status),
                Action::make('delete')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function (Model $record) {
                        $record->delete();
                        Notification::make()
                            ->title('User Has Been Deleted')
                            ->warning()
                            ->send();
                    })
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
            'index' => Pages\ListTempUsers::route('/'),
        ];
    }
}
