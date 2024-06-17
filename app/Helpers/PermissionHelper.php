<?php

namespace App\Helpers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PermissionHelper
{
    const SEPARATOR =  ".";

    const TYPE_CREATE = "create";
    const TYPE_READ = "read";
    const TYPE_UPDATE = "update";
    const TYPE_DELETE = "delete";
    const TYPE_ALL = [self::TYPE_CREATE, self::TYPE_READ, self::TYPE_UPDATE, self::TYPE_DELETE];
    const TRANSLATE_TYPE = [
        self::TYPE_CREATE => "Buat",
        self::TYPE_READ => "Lihat",
        self::TYPE_UPDATE => "Edit",
        self::TYPE_DELETE => "Hapus",
    ];

    const ROUTE_TYPE_CREATE = ['create', 'store'];
    const ROUTE_TYPE_READ = ['index', 'show', 'print', 'export', 'find'];
    const ROUTE_TYPE_UPDATE = ['edit', 'update'];
    const ROUTE_TYPE_DELETE = ['destroy'];

    const ACCESS_DASHBOARD = "dashboard";
    const ACCESS_USER = "user";
    const ACCESS_PERMISSION = "permission";
    const ACCESS_ROLE = "role";
    
    // ADMIN
    const ACCESS_MONTHLY_HOTSPOT = "monthly_hotspot";
    const ACCESS_PRODUCT = "product";
    const ACCESS_PAYMENT_METHOD = "payment_method";
    const ACCESS_TRANSACTION = "transaction";
    const ACCESS_HOTSPOT_MEMBER = "hotspot_member";
    
    // MEMBER
    const ACCESS_BILL = "bill";
    const ACCESS_ALL = [
        // ADMIN
        self::ACCESS_DASHBOARD,
        self::ACCESS_USER,
        self::ACCESS_PERMISSION,
        self::ACCESS_ROLE,
        self::ACCESS_MONTHLY_HOTSPOT,
        self::ACCESS_PRODUCT,
        self::ACCESS_PAYMENT_METHOD,
        self::ACCESS_TRANSACTION,
        self::ACCESS_HOTSPOT_MEMBER,
        self::ACCESS_HOTSPOT_MEMBER,
        
        // MEMBER
        self::ACCESS_BILL,
    ];

    const TRANSLATE_ACCESS = [
        // ADMIN
        self::ACCESS_DASHBOARD => "Dashboard",
        self::ACCESS_USER => "Pengguna",
        self::ACCESS_PERMISSION => "Akses",
        self::ACCESS_ROLE => "Jabatan",
        self::ACCESS_MONTHLY_HOTSPOT => "Hotspot Bulanan",
        self::ACCESS_PRODUCT => "Produk",
        self::ACCESS_PAYMENT_METHOD => "Metode Pembayaran",
        self::ACCESS_TRANSACTION => "Transaksi",
        self::ACCESS_HOTSPOT_MEMBER => "Member Hotspot",

        // MEMBER
        self::ACCESS_BILL => "Tagihan Saya",
    ];



    /*
    | Parameters
    | permission (string) : merupakan nama dari permission
    */
    public static function translate($permission)
    {
        $explode = explode(self::SEPARATOR, $permission);
        $access = $explode[0];
        $type = $explode[1];

        $translateAccess = isset(self::TRANSLATE_ACCESS[$access]) ? self::TRANSLATE_ACCESS[$access] : $access;
        $translateType = isset(self::TRANSLATE_TYPE[$type]) ? self::TRANSLATE_TYPE[$type] : $type;

        return $translateAccess . " - " . $translateType;
    }

    /*
    | Parameters
    | access (string) : merupakan access yang tersedia pada helper ini
    | type (string) : merupakan type yang tersedia pada helper ini
    */
    public static function transform($access, $type)
    {
        return $access . self::SEPARATOR . $type;
    }

    /*
    | Parameters
    | permission (string) : merupakan nama dari permission
    */
    public static function getAccess($permission)
    {
        return explode(self::SEPARATOR, $permission)[0];
    }


    /*
    | Parameters
    | permission (string) : merupakan nama dari permission
    */
    public static function getTranslatedAccess($permission)
    {
        return self::TRANSLATE_ACCESS[self::getAccess($permission)];
    }


    /*
    | Parameters
    | permission (string) : merupakan nama dari permission
    */
    public static function getType($permission)
    {
        return explode(self::SEPARATOR, $permission)[1];
    }

    /*
    | Parameters
    | permission (string) : merupakan nama dari permission
    */
    public static function getTranslatedType($permission)
    {
        return self::TRANSLATE_TYPE[self::getType($permission)];
    }

    /*
    | Parameters
    | route_name (string) : Nama Route
    */
    public static function isRoutePermitted($route_name, $user = null)
    {
        // Identifikasi Route
        $exploded_route_names = explode(".", $route_name);
        $access = $exploded_route_names[0];
        $route_type = $exploded_route_names[1];

        if (in_array($route_type, self::ROUTE_TYPE_CREATE)) {
            $type = self::TYPE_CREATE;
        } else if (in_array($route_type, self::ROUTE_TYPE_READ)) {
            $type = self::TYPE_READ;
        } else if (in_array($route_type, self::ROUTE_TYPE_UPDATE)) {
            $type = self::TYPE_UPDATE;
        } else {
            $type = self::TYPE_DELETE;
        }

        // Pemeriksaan Hak Akses
        $user = $user == null ? User::find(Auth::id()) : $user;
        return $user->hasPermissionTo(self::transform($access, $type));
    }
}
