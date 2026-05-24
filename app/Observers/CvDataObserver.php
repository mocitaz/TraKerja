<?php

namespace App\Observers;

use App\Services\ActivityLogger;

class CvDataObserver
{
    /**
     * Get the descriptive name of the model based on its class name.
     */
    private function getModelName($model): string
    {
        $className = class_basename($model);
        
        return match ($className) {
            'UserExperience' => 'Pengalaman Kerja',
            'UserEducation' => 'Pendidikan',
            'UserSkill' => 'Skill/Keahlian',
            'UserProject' => 'Proyek',
            'UserOrganization' => 'Organisasi',
            'UserAchievement' => 'Penghargaan/Prestasi',
            default => 'Data CV',
        };
    }

    /**
     * Handle the "created" event.
     */
    public function created($model): void
    {
        $modelName = $this->getModelName($model);
        
        ActivityLogger::log(
            'cv_data_update',
            "User menambahkan data {$modelName} ke dalam CV",
            'success',
            ['type' => 'create', 'model' => class_basename($model)],
            $model->user_id
        );
    }

    /**
     * Handle the "updated" event.
     */
    public function updated($model): void
    {
        // Don't log if only display_order was changed (prevent spam when reordering)
        if (count($model->getDirty()) === 1 && $model->isDirty('display_order')) {
            return;
        }

        $modelName = $this->getModelName($model);
        
        ActivityLogger::log(
            'cv_data_update',
            "User memperbarui data {$modelName} di CV",
            'success',
            ['type' => 'update', 'model' => class_basename($model)],
            $model->user_id
        );
    }

    /**
     * Handle the "deleted" event.
     */
    public function deleted($model): void
    {
        $modelName = $this->getModelName($model);
        
        ActivityLogger::log(
            'cv_data_update',
            "User menghapus data {$modelName} dari CV",
            'success',
            ['type' => 'delete', 'model' => class_basename($model)],
            $model->user_id
        );
    }
}
