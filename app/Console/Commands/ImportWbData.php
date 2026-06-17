<?php

namespace App\Console\Commands;

use App\Models\Income;
use App\Models\Order;
use App\Models\Sale;
use App\Models\Stock;
use App\Services\WbApiService;
use Illuminate\Console\Command;

class ImportWbData extends Command
{
    protected $signature = 'wb:import {entity? : sales, orders, stocks, incomes or all} {--date-from=2020-01-01} {--date-to=2026-12-31} {--start-page=1}';
    protected $description = 'Import data from WB API into the database';

    private array $entityMap = [
        'sales' => Sale::class,
        'orders' => Order::class,
        'stocks' => Stock::class,
        'incomes' => Income::class,
    ];

    public function handle(WbApiService $api): int
    {
        $entity = $this->argument('entity') ?? 'all';
        $entities = $entity === 'all' ? array_keys($this->entityMap) : [$entity];

        foreach ($entities as $name) {
            if (!isset($this->entityMap[$name])) {
                $this->error("Unknown entity: {$name}");
                return self::FAILURE;
            }

            $this->importEntity($api, $name);
        }

        $this->info('Import completed.');
        return self::SUCCESS;
    }

    private function importEntity(WbApiService $api, string $entity): void
    {
        $startPage = (int) $this->option('start-page');
        $this->info("Importing {$entity} (from page {$startPage})...");

        $model = $this->entityMap[$entity];
        $params = ['dateFrom' => $this->option('date-from')];

        if ($entity === 'stocks') {
            $params = ['dateFrom' => now()->format('Y-m-d')];
        } else {
            $params['dateTo'] = $this->option('date-to');
        }

        $totalInserted = 0;

        foreach ($api->fetchAll($entity, $params, $startPage) as $batch) {
            $rows = array_map(function ($item) {
                $item['created_at'] = now();
                $item['updated_at'] = now();
                return $item;
            }, $batch['data']);

            $model::insert($rows);
            $totalInserted += count($rows);

            $this->output->write("\r  Page {$batch['page']}/{$batch['lastPage']} — {$totalInserted} records");
        }

        $this->newLine();
        $this->info("  Done: {$totalInserted} records imported into {$entity}.");
    }
}
