<?php

namespace Morasoft\ArtisanUI\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class ArtisanGuiController extends \App\Http\Controllers\Controller
{
    public function index()
    {
        return view('artisan-ui::artisan-ui');
    }

    public function execute(Request $request)
    {
        $type = $request->input('type');

        try {
            switch ($type) {
                case 'model':
                    return $this->createModel($request);

                case 'controller':
                    return $this->createController($request);

                case 'migration':
                    return $this->createMigration($request);

                case 'seeder':
                    return $this->runSeeder($request);

                case 'validation':
                    return $this->runRequest($request);

                case 'artisan':
                    return $this->runArtisan($request);

                default:
                    return back()->with('output', '❌ Unsupported command type.');
            }
        } catch (\Throwable $e) {
            return back()->with('output', '❌ An error occurred: ' . $e->getMessage());
        }
    }

    protected function createModel(Request $request)
    {
        $name = trim($request->input('model_name'));
        if (empty($name)) {
            return back()->with('output', '❌ Please enter a model name.');
        }

        $command = "make:model $name";

        $with = $request->input('with', []);
        if (in_array('migration', $with)) {
            $command .= ' --migration';
        }
        if (in_array('controller', $with)) {
            $command .= ' --controller';
        }

        Artisan::call($command);
        return back()->with('output', Artisan::output());
    }

    protected function createController(Request $request)
    {
        $name = trim($request->input('controller_name'));
        if (empty($name)) {
            return back()->with('output', '❌ Please enter a controller name.');
        }

        Artisan::call("make:controller $name");
        return back()->with('output', Artisan::output());
    }

    protected function createMigration(Request $request)
    {
        $name = trim($request->input('migration_name'));
        if (empty($name)) {
            return back()->with('output', '❌ Please enter a migration name.');
        }

        Artisan::call("make:migration $name");
        return back()->with('output', Artisan::output());
    }

    protected function runSeeder(Request $request)
    {
        $name = trim($request->input('seeder_name'));
        if (empty($name)) {
            return back()->with('output', '❌ Please enter a seeder name.');
        }

        Artisan::call("make:seeder $name");
        return back()->with('output', Artisan::output());
    }

    protected function runRequest(Request $request)
    {
        $name = trim($request->input('request_name'));
        if (empty($name)) {
            return back()->with('output', '❌ Please enter a validation request name.');
        }

        Artisan::call("make:request $name");
        return back()->with('output', Artisan::output());
    }

    protected function runArtisan(Request $request)
    {
        $name = trim($request->input('artisan_command'));

        if (empty($name)) {
            return back()->with('output', '❌ Please enter an artisan command.');
        }

        try {
            Artisan::call($name);
            $output = Artisan::output();

            if (empty(trim($output))) {
                $output = "✅ Artisan command '$name' executed successfully, but no output was returned.";
            }

        } catch (\Throwable $e) {
            $output = "❌ An error occurred:\n" . $e->getMessage();
        }

        return back()->with('output', $output);
    }
}
