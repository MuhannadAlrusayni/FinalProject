<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use App\Imports\OldUsers;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class OldUsersImport implements WithMultipleSheets 
{
   
    public function sheets(): array
    {
        return [
            // 'البكالوريوس' => new OldUsers(),
            0 => new OldUsers(),
        ];
    }
}
