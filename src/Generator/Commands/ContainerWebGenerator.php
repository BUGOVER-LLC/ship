<?php

declare(strict_types=1);

namespace Nucleus\Generator\Commands;

use Illuminate\Support\Pluralizer;
use Illuminate\Support\Str;
use Nucleus\Generator\GeneratorCommand;
use Nucleus\Generator\Interfaces\ComponentsGenerator;
use Symfony\Component\Console\Input\InputOption;

class ContainerWebGenerator extends GeneratorCommand implements ComponentsGenerator
{
    /**
     * User required/optional inputs expected to be passed while calling the command.
     * This is a replacement of the `getArguments` function "which reads whenever it's called".
     */
    public array $inputs = [
        ['url', null, InputOption::VALUE_OPTIONAL, 'The base URI of all endpoints (/stores, /cars, ...)'],
        ['controllertype', null, InputOption::VALUE_OPTIONAL, 'The controller type (SAC, MAC)'],
        ['maincalled', false, InputOption::VALUE_NONE],
    ];

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'nucleus:generate:container:web';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a Container for a nucleus from scratch (WEB Part)';

    /**
     * The type of class being generated.
     */
    protected string $fileType = 'Container';

    /**
     * The structure of the file path.
     */
    protected string $pathStructure = '{section-name}/{container-name}/*';

    /**
     * The structure of the file name.
     */
    protected string $nameStructure = '{file-name}';

    /**
     * The name of the stub file.
     */
    protected string $stubName = 'composer.stub';

    public function getUserInputs(): ?array
    {
        $ui = 'web';

        // container name as inputted and lower
        $sectionName = $this->sectionName;

        // container name as inputted and lower
        $containerName = $this->containerName;

        // name of the model (singular and plural)
        $model = $this->containerName;
        $models = Pluralizer::plural($model);

        // add the README file
        $this->printInfoMessage('Generating README File');
        $this->call('nucleus:generate:readme', [
            '--section' => $sectionName,
            '--container' => $containerName,
            '--file' => 'README',
        ]);

        // create the configuration file
        $this->printInfoMessage('Generating Configuration File');
        $this->call('nucleus:generate:configuration', [
            '--section' => $sectionName,
            '--container' => $containerName,
            '--file' => Str::camel($this->sectionName) . '-' . Str::camel($this->containerName),
        ]);

        // create the MainServiceProvider for the container
        $this->printInfoMessage('Generating MainServiceProvider');
        $this->call('nucleus:generate:provider', [
            '--section' => $sectionName,
            '--container' => $containerName,
            '--file' => 'MainServiceProvider',
            '--stub' => 'mainserviceprovider',
        ]);

        // create the model and repository for this container
        $this->printInfoMessage('Generating Model and Repository');
        $this->call('nucleus:generate:model', [
            '--section' => $sectionName,
            '--container' => $containerName,
            '--file' => $model,
            '--repository' => true,
        ]);

        // create the migration file for the model
        $this->printInfoMessage('Generating a basic Migration file');
        $this->call('nucleus:generate:migration', [
            '--section' => $sectionName,
            '--container' => $containerName,
            '--file' => 'create_' . Str::snake($models) . '_table',
            '--tablename' => Str::snake($models),
        ]);

        // create the default routes for this container
        $this->printInfoMessage('Generating Default Routes');
        $version = 1;
        $doctype = 'private';

        // get the URI and remove the first trailing slash
        $url = Str::lower(
            $this->checkParameterOrAsk(
                'url',
                'Enter the base URI for all WEB endpoints (foo/bar/{id})',
                Str::kebab($models)
            )
        );
        $url = ltrim($url, '/');

        $controllertype = Str::lower(
            $this->checkParameterOrChoice(
                'controllertype',
                'Select the controller type (Single or Multi Action Controller)',
                ['SAC', 'MAC'],
                0
            )
        );

        $this->printInfoMessage('Generating Requests for Routes');
        $this->printInfoMessage('Generating Default Actions');
        $this->printInfoMessage('Generating Default Tasks');
        $this->printInfoMessage('Generating Default Controller/s');

        $routes = [
            [
                'stub' => 'GetAll',
                'name' => 'GetAll' . $models,
                'operation' => 'index',
                'verb' => 'GET',
                'url' => $url,
                'action' => 'GetAll' . $models . 'Action',
                'request' => 'GetAll' . $models . 'Request',
                'task' => 'GetAll' . $models . 'Task',
                'controller' => 'GetAll' . $models . 'Controller',
            ],
            [
                'stub' => 'Find',
                'name' => 'Find' . $model . 'ById',
                'operation' => 'show',
                'verb' => 'GET',
                'url' => $url . '/{id}',
                'action' => 'Find' . $model . 'ById' . 'Action',
                'request' => 'Find' . $model . 'ById' . 'Request',
                'task' => 'Find' . $model . 'ById' . 'Task',
                'controller' => 'Find' . $model . 'ById' . 'Controller',
            ],
            [
                'stub' => 'Create',
                'name' => 'Store' . $model,
                'operation' => 'store',
                'verb' => 'POST',
                'url' => $url . '/store',
                'action' => 'Create' . $model . 'Action',
                'request' => 'Store' . $model . 'Request',
                'task' => 'Create' . $model . 'Task',
                'controller' => 'Create' . $model . 'Controller',
            ],
            [
                'stub' => 'Store',
                'name' => 'Create' . $model,
                'operation' => 'create',
                'verb' => 'GET',
                'url' => $url . '/create',
                'action' => null,
                'request' => 'Create' . $model . 'Request',
                'task' => null,
                'controller' => 'Create' . $model . 'Controller',
            ],
            [
                'stub' => 'Update',
                'name' => 'Update' . $model,
                'operation' => 'update',
                'verb' => 'PATCH',
                'url' => $url . '/{id}',
                'action' => 'Update' . $model . 'Action',
                'request' => 'Update' . $model . 'Request',
                'task' => 'Update' . $model . 'Task',
                'controller' => 'Update' . $model . 'Controller',
            ],
            [
                'stub' => 'Edit',
                'name' => 'Edit' . $model,
                'operation' => 'edit',
                'verb' => 'GET',
                'url' => $url . '/{id}/edit',
                'action' => null,
                'request' => 'Edit' . $model . 'Request',
                'task' => null,
                'controller' => 'Update' . $model . 'Controller',
            ],
            [
                'stub' => 'Delete',
                'name' => 'Delete' . $model,
                'operation' => 'destroy',
                'verb' => 'DELETE',
                'url' => $url . '/{id}',
                'action' => 'Delete' . $model . 'Action',
                'request' => 'Delete' . $model . 'Request',
                'task' => 'Delete' . $model . 'Task',
                'controller' => 'Delete' . $model . 'Controller',
            ],
        ];

        foreach ($routes as $route) {
            $this->call('nucleus:generate:request', [
                '--section' => $sectionName,
                '--container' => $containerName,
                '--file' => $route['request'],
                '--ui' => $ui,
                '--stub' => $route['stub'],
            ]);

            if (null != $route['action']) {
                $this->call('nucleus:generate:action', [
                    '--section' => $sectionName,
                    '--container' => $containerName,
                    '--file' => $route['action'],
                    '--ui' => $ui,
                    '--model' => $model,
                    '--stub' => $route['stub'],
                ]);
            }

            if (null != $route['task']) {
                $this->call('nucleus:generate:task', [
                    '--section' => $sectionName,
                    '--container' => $containerName,
                    '--file' => $route['task'],
                    '--model' => $model,
                    '--stub' => $route['stub'],
                ]);
            }

            if ('sac' === $controllertype) {
                $this->call('nucleus:generate:route', [
                    '--section' => $sectionName,
                    '--container' => $containerName,
                    '--file' => $route['name'],
                    '--ui' => $ui,
                    '--operation' => $route['operation'],
                    '--doctype' => $doctype,
                    '--docversion' => $version,
                    '--url' => $route['url'],
                    '--verb' => $route['verb'],
                    '--controller' => $route['controller'],
                ]);

                $this->call('nucleus:generate:controller', [
                    '--section' => $sectionName,
                    '--container' => $containerName,
                    '--file' => $route['controller'],
                    '--ui' => $ui,
                    '--stub' => $route['stub'],
                ]);
            } else {
                $this->call('nucleus:generate:route', [
                    '--section' => $sectionName,
                    '--container' => $containerName,
                    '--file' => $route['name'],
                    '--ui' => $ui,
                    '--operation' => $route['operation'],
                    '--doctype' => $doctype,
                    '--docversion' => $version,
                    '--url' => $route['url'],
                    '--verb' => $route['verb'],
                    '--controller' => 'Controller',
                ]);
            }
        }

        if ('mac' === $controllertype) {
            $this->printInfoMessage('Generating a Controller to wire everything together');
            $this->call('nucleus:generate:controller', [
                '--section' => $sectionName,
                '--container' => $containerName,
                '--file' => 'Controller',
                '--ui' => $ui,
                '--stub' => 'crud',
            ]);
        }

        $this->call('nucleus:generate:composer', [
            '--section' => $sectionName,
            '--container' => $containerName,
            '--file' => 'composer',
        ]);
        $this->printInfoMessage('Generating Composer File');

        $this->printInfoMessage('Generating .gitignore File');
        $this->call('nucleus:generate:gitignore', [
            '--section' => $sectionName,
            '--container' => $containerName,
            '--file' => 'Gitignore',
        ]);

        return null;
    }

    /**
     * Get the default file name for this component to be generated
     */
    public function getDefaultFileName(): string
    {
        return 'composer';
    }

    /**
     * @return string
     */
    public function getDefaultFileExtension(): string
    {
        return 'json';
    }
}
