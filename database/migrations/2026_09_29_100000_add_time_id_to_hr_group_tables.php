<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hr_group_definitions', function (Blueprint $table) {
            $table->foreignId('time_id')->nullable()->after('project_id')->constrained('hr_times')->cascadeOnDelete();
        });

        Schema::table('hr_groups', function (Blueprint $table) {
            $table->foreignId('time_id')->nullable()->after('project_id')->constrained('hr_times')->cascadeOnDelete();
        });

        Schema::table('hr_group_definitions', function (Blueprint $table) {
            $table->dropUnique(['project_id', 'name']);
        });

        $this->migrateLegacyGroupDataToTimeSlots();

        Schema::table('hr_group_definitions', function (Blueprint $table) {
            $table->unique(['project_id', 'time_id', 'name']);
        });

        Schema::table('hr_groups', function (Blueprint $table) {
            $table->unique(['project_id', 'user_id', 'time_id']);
        });
    }

    public function down(): void
    {
        Schema::table('hr_groups', function (Blueprint $table) {
            $table->dropUnique(['project_id', 'user_id', 'time_id']);
            $table->dropConstrainedForeignId('time_id');
        });

        Schema::table('hr_group_definitions', function (Blueprint $table) {
            $table->dropUnique(['project_id', 'time_id', 'name']);
            $table->dropConstrainedForeignId('time_id');
            $table->unique(['project_id', 'name']);
        });
    }

    private function migrateLegacyGroupDataToTimeSlots(): void
    {
        $projectIds = DB::table('hr_group_definitions')
            ->whereNull('time_id')
            ->distinct()
            ->pluck('project_id')
            ->merge(
                DB::table('hr_groups')->whereNull('time_id')->distinct()->pluck('project_id')
            )
            ->unique()
            ->values();

        foreach ($projectIds as $projectId) {
            $timeIds = DB::table('hr_times')
                ->join('hr_dates', 'hr_dates.id', '=', 'hr_times.date_id')
                ->where('hr_dates.project_id', $projectId)
                ->where('hr_dates.date_delete', false)
                ->where('hr_times.time_delete', false)
                ->orderBy('hr_dates.date_datetime')
                ->orderBy('hr_times.time_start')
                ->pluck('hr_times.id');

            if ($timeIds->isEmpty()) {
                continue;
            }

            $legacyDefinitions = DB::table('hr_group_definitions')
                ->where('project_id', $projectId)
                ->whereNull('time_id')
                ->get();

            foreach ($legacyDefinitions as $definition) {
                $firstTimeId = $timeIds->first();
                DB::table('hr_group_definitions')
                    ->where('id', $definition->id)
                    ->update(['time_id' => $firstTimeId]);

                foreach ($timeIds->slice(1) as $timeId) {
                    $exists = DB::table('hr_group_definitions')
                        ->where('project_id', $projectId)
                        ->where('time_id', $timeId)
                        ->where('name', $definition->name)
                        ->exists();

                    if ($exists) {
                        continue;
                    }

                    DB::table('hr_group_definitions')->insert([
                        'project_id'  => $projectId,
                        'time_id'     => $timeId,
                        'name'        => $definition->name,
                        'max_members' => $definition->max_members,
                        'sort_order'  => $definition->sort_order,
                        'created_at'  => $definition->created_at,
                        'updated_at'  => now(),
                    ]);
                }
            }

            $legacyGroups = DB::table('hr_groups')
                ->where('project_id', $projectId)
                ->whereNull('time_id')
                ->get();

            foreach ($legacyGroups as $group) {
                $attendTimeIds = DB::table('hr_attends')
                    ->where('project_id', $projectId)
                    ->where('user_id', $group->user_id)
                    ->where('attend_delete', false)
                    ->pluck('time_id')
                    ->unique()
                    ->values();

                $targetTimeIds = $attendTimeIds->isNotEmpty() ? $attendTimeIds : collect([$timeIds->first()]);

                $isFirst = true;
                foreach ($targetTimeIds as $timeId) {
                    if ($isFirst) {
                        DB::table('hr_groups')
                            ->where('id', $group->id)
                            ->update(['time_id' => $timeId]);
                        $isFirst = false;

                        continue;
                    }

                    $exists = DB::table('hr_groups')
                        ->where('project_id', $projectId)
                        ->where('user_id', $group->user_id)
                        ->where('time_id', $timeId)
                        ->exists();

                    if ($exists) {
                        continue;
                    }

                    DB::table('hr_groups')->insert([
                        'project_id'      => $projectId,
                        'time_id'         => $timeId,
                        'user_id'         => $group->user_id,
                        'group'           => $group->group,
                        'active_datetime' => $group->active_datetime,
                        'created_at'      => $group->created_at,
                        'updated_at'      => now(),
                    ]);
                }
            }
        }
    }
};
