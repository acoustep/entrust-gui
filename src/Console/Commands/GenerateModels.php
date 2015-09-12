<?php namespace Acoustep\EntrustGui\Console\Commands;

use Illuminate\Console\Command;

class GenerateModels extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'entrust-gui:models {model=All} {--path=} {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Models';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $path = (($this->option('path')) ? $this->option('path') : app_path()) . '/';
        $model = $this->argument('model');
        $files = $this->getFiles($model);
        foreach ($files as $file) {
            $file_path = $path.$file;
            if ($this->writeable($file_path)) {
                $this->write($file, $file_path);
                $this->info($file.' saved to '.$file_path);
            }
        }
    }

    /**
     * return the files specified
     *
     * @param string
     *
     * @return array
     */
    protected function getFiles($model = 'All')
    {
        $files = [
            'Permission' => 'Permission.php',
            'Role' => 'Role.php',
            'User' => 'User.php',
        ];
        if ($model === 'All') {
            return $files;
        } else {
            return [$model => $files[$model]];
        }

    }
    /**
     * return if the file path is writable
     *
     * @param string
     *
     * @return boolean
     */
    protected function writeable($file_path)
    {
        if ($this->option('force')) {
            return true;
        }
        return  ( ! file_exists($file_path) || $this->confirmable($file_path));
    }

    protected function confirmable($file_path)
    {
        return (file_exists($file_path) && $this->confirm('Overwrite '.$file_path.'? [y|N]'));
    }
    
    /**
     * write the file to the file path
     *
     * @param string
     * @param string
     *
     * @return void
     */
    protected function write($file, $file_path)
    {
        if (! is_dir(dirname($file_path))) {
            mkdir(dirname($file_path), 0755, true);
        }
        file_put_contents(
            $file_path,
            file_get_contents(
                dirname(__FILE__)
                .DIRECTORY_SEPARATOR
                .'..'.DIRECTORY_SEPARATOR
                .'..'.DIRECTORY_SEPARATOR
                .'..'.DIRECTORY_SEPARATOR
                .'templates'
                .DIRECTORY_SEPARATOR
                .$file
                .'.txt'
            )
        );
    }
}
