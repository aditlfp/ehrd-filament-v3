<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TempUsersResource\Pages;
use App\Filament\Resources\TempUsersResource\RelationManagers;
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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

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
            ->columns([
                Tables\Columns\ImageColumn::make('data.image')
                    ->label('Foto Profile')
                    ->getStateUsing(function($record) {
                        $path = $record->data['image'];
                        

                        if($path == null) { return null; }
                        else{
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
                    ->label('Tanggal Input'),
                Tables\Columns\BadgeColumn::make('status')
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Approve' : 'Pending')
                    ->color(fn (bool $state): string => $state ? 'success' : 'warning')
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
                            $record->status = 1;
                            $record->save();
                            UserAbsensi::create([
                                'name' => $record->data['username'],
                                'nama_lengkap' => $record->data['nama_lengkap'],
                                'kerjasama_id' => $kerjasama->id,
                                'email' => $record->data['email'],
                                'password' => $record->data['password'],
                                'image' => $record->data['image'],
                                'devisi_id' => $record->data['devisi_id'],
                                'status_id' => 1,
                                'nik' => $record->data['nik'],
                                'no_hp' => $record->data['no_hp'],
                                'alamat' => $record->data['alamat']
                            ]);

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

                            $record->notify(new UserVerified());

                            Notification::make()
                                ->title('User berhasil diverifikasi')
                                ->success()
                                ->send();
                        } catch (\Throwable $th) {
                            dd($th);
                            Notification::make()
                                ->title('User Gagal diverifikasi')
                                ->danger()
                                ->send();
                        }
                       


                      
                    })
                    ->visible(fn (Model $record): bool => !$record->status),
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
