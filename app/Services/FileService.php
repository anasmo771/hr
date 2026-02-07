<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\File;

class FileService
{
    public function deleteFiles($procedureId, $type)
    {
        DB::beginTransaction();
        try {
            $files = File::where('procedure_id', $procedureId)
                         ->where('type', $type)
                         ->get();

            foreach ($files as $file) {
                if (Storage::disk('public')->exists($file->path)) {
                    Storage::disk('public')->delete($file->path);
                }
            }

            File::where('procedure_id', $procedureId)
                ->where('type', $type)
                ->delete();

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }
}
