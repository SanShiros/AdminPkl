<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Builder::macro('incrementMany', function (string $column, array $values) {
            if (empty($values)) return;

            $cases = '';
            $ids = [];
            $affected = 0;
            $model = $this->getModel();
            $table = (string)$model->getTable();
            $modelPk = (string)$model->getKeyName();
            // $biddings = [];

            foreach ($values as $id => $amount) {
                $id = (int) $id;
                $amount = (int) $amount;

                $cases .= "WHEN $modelPk = $id THEN $column + $amount ";
                $ids[] = $id;
                // $biddings[] = $id;
                // $biddings[] = $amount;
            }
            $ids = implode(',', $ids);

            $now = now();
            $query = "UPDATE $table SET $column = CASE $cases END" . ($model->timestamps ? " updated_at = '$now'" : '') . " WHERE $modelPk IN ($ids)";
            // $biddings = [...$biddings, ...$ids];
            // DB::statement($query);
            return $model->getConnection()->affectingStatement($query);
        });

        Builder::macro('decrementMany', function (string $column, array $values) {
            if (empty($values)) return;

            $cases = '';
            $ids = [];
            $affected = 0;
            $model = $this->getModel();
            $table = (string)$model->getTable();
            $modelPk = (string)$model->getKeyName();
            // $biddings = [];

            foreach ($values as $id => $amount) {
                $id = (int) $id;
                $amount = (int) $amount;

                $cases .= "WHEN $modelPk = $id THEN $column - $amount ";
                $ids[] = $id;
                // $biddings[] = $id;
                // $biddings[] = $amount;
            }
            // $biddings = [...$biddings, ...$ids];
            $ids = implode(',', $ids);
            $query = "UPDATE $table SET $column = CASE $cases END WHERE $modelPk IN ($ids)";
            // if ($model->timestamps) {
            //     $now = now();
            //     $query .= "UPDATE $table SET updated_at = $now WHERE $modelPk IN ($ids);\n";
            // }
            // DB::statement($query);
            // return DB::statement($query);
            // return [$query, $biddings];

            return $model->getConnection()->affectingStatement($query);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
