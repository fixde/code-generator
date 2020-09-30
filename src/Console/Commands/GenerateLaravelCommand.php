<?php

namespace Fixde\CodeGenerator\Console\Commands;

use Carbon\Carbon;
use Schema;

class GenerateLaravelCommand extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:laravel {model} {--field=*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Laravel';

    /**
     * @var array
     */
    protected $types = ['controller', 'migration', 'model', 'route', 'repository', 'request', 'resource'];

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->configSchema();

        if (config('generator.autoload')) {
            exec('composer dump-autoload');
        }
    }

    /**
     * Generate file by type.
     *
     * @param string $type
     * @return void
     */
    public function generate($type)
    {
        switch ($type) {
            case 'controller':
                $dir = 'app/Http/Controllers/';
                $name = $dir . $this->model['STUDLY'] . 'Controller.php';
                break;
            case 'migration':
                $dir = 'database/migrations/';
                $name = $dir . Carbon::now()->format('Y_m_d_u') . '_create_' . $this->model['PLURAL_SNAKE'] . '_table.php';
                break;
            case 'model':
                $dir = 'app/Models/';
                $name = $dir . $this->model['STUDLY'] . '.php';
                break;
            case 'route':
                $name = 'routes/api.php';
                break;
            case 'repository':
                $dir = 'app/Repositories/';
                $name = $dir . $this->model['STUDLY'] . 'Repository.php';
                break;
            case 'request':
                $dir = 'app/Http/Requests/';
                $name = $dir . $this->model['STUDLY'] . 'Request.php';
                break;
            case 'resource':
                $dir = 'app/Http/Resources/';
                $name = $dir . $this->model['STUDLY'] . 'Resource.php';
                break;
            default:
                return false;
                break;
        }

        $content =
            view("laravel-templates::$type")
            ->with([
                'config' => config('generator'),
                'fields' => $this->fields,
                'model' => $this->model,
            ])
            ->render();

        if (isset($dir)) {
            $this->checkDirectory($dir);
            file_put_contents($name, '<?php' . PHP_EOL . PHP_EOL . $content);
        } else {
            file_put_contents($name, PHP_EOL . $content, FILE_APPEND | LOCK_EX);
        }

        $this->info('File "' . $name . '" has been successfully generated');
    }
}
