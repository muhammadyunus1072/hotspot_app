<?php

namespace App\Repositories\Account;

use App\Models\User;
use App\Helpers\MenuHelper;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use App\Repositories\MasterDataRepository;
use App\Repositories\Transaction\HotspotMemberRepository;

class UserRepository extends MasterDataRepository
{
    protected static function className(): string
    {
        return User::class;
    }

    public static function update($id, $data)
    {
        $obj = self::find($id);

        MenuHelper::resetCacheByUser($id);

        return $obj->update($data);
    }

    public static function authenticatedUser(): User
    {
        return self::find(Auth::id());
    }

    public static function getByRole($roleId)
    {
        return User::whereHas('roles', function ($query) use ($roleId) {
            $query->whereId($roleId);
        })
            ->get();
    }

    public static function findByEmail($email)
    {
        return User::whereEmail($email)->first();
    }

    public static function datatable($roleId)
    {
        return User::with('roles')
            ->when($roleId, function ($query) use ($roleId) {
                $query->whereHas('roles', function ($query) use ($roleId) {
                    $query->whereId($roleId);
                });
            });
    }

    public static function search($search)
    {
        $data = User::select(
                'id',
                'name',
            )
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'LIKE', "%$search%");
                });
            })
            ->role('Member')
            ->orderBy('name', 'ASC')
            ->limit(100)
            ->get();

        $response = array();

        foreach ($data as $res) {
            array_push($response, ['id' => $res->id, 'text' => "$res->name"]);
        }
        return $response;
    }
    public static function search_member($search)
    {
        $hotspot_members = HotspotMemberRepository::get()->pluck('user_id');
        $data = User::select(
                'id',
                'name',
            )
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'LIKE', "%$search%");
                });
            })
            ->whereNotIn('id', $hotspot_members)
            ->role('Member')
            ->orderBy('name', 'ASC')
            ->limit(100)
            ->get();

        $response = array();

        foreach ($data as $res) {
            array_push($response, ['id' => $res->id, 'text' => "$res->name"]);
        }
        return $response;
    }
}
