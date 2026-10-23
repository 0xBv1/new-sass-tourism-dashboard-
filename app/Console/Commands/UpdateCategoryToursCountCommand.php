<?php

namespace App\Console\Commands;

use App\Models\Category;
use Illuminate\Console\Command;

class UpdateCategoryToursCountCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'categories:update-tours-count';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update tours_count for all categories';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Updating tours count for all categories...');
        
        $categories = Category::all();
        $bar = $this->output->createProgressBar($categories->count());
        $bar->start();

        $updated = 0;
        foreach ($categories as $category) {
            $category->setToursCount();
            $updated++;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Successfully updated tours count for {$updated} categories.");
        
        return Command::SUCCESS;
    }
}
