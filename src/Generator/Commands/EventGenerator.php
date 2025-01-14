<?php

declare(strict_types=1);

namespace Nucleus\Generator\Commands;

use Illuminate\Support\Str;
use Nucleus\Generator\GeneratorCommand;
use Nucleus\Generator\Interfaces\ComponentsGenerator;
use Symfony\Component\Console\Input\InputOption;

class EventGenerator extends GeneratorCommand implements ComponentsGenerator
{
    /**
     * User required/optional inputs expected to be passed while calling the command.
     * This is a replacement of the `getArguments` function "which reads whenever it's called".
     *
     * @var  array
     */
    public array $inputs = [
        ['model', null, InputOption::VALUE_OPTIONAL, 'The model to generate this Event for'],
        ['stub', null, InputOption::VALUE_OPTIONAL, 'The stub file to load for this generator.'],
        ['listener', null, InputOption::VALUE_OPTIONAL, 'Generate a Listener for this Event?'],
    ];
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'nucleus:generate:event';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Event class and its corresponding Listener';
    /**
     * The type of class being generated.
     */
    protected string $fileType = 'Event';
    /**
     * The structure of the file path.
     */
    protected string $pathStructure = '{section-name}/{container-name}/Event/*';
    /**
     * The structure of the file name.
     */
    protected string $nameStructure = '{file-name}';
    /**
     * The name of the stub file.
     */
    protected string $stubName = 'events/generic.stub';

    public function getUserInputs(): ?array
    {
        $model = $this->checkParameterOrAsk(
            'model',
            'Enter the name of the Model to generate this Event for',
            Str::ucfirst($this->containerName)
        );

        $listener = $this->checkParameterOrConfirm(
            'listener',
            'Do you want to generate a Listener for this Event?',
            false
        );
        if ($listener) {
            // We need to generate a corresponding listener
            // so call the other command
            $status = $this->call('nucleus:generate:listener', [
                '--section' => $this->sectionName,
                '--container' => $this->containerName,
                '--file' => $this->fileName . 'Listener',
                '--event' => $this->fileName,
            ]);

            if ($status == 0) {
                $this->printInfoMessage('The Listener for Event was successfully generated');
            } else {
                $this->printErrorMessage('Could not generate the corresponding Listener!');
            }
        }

        $this->printInfoMessage('!!! Do not forget to register the Event and/or EventListener !!!');

        $stub = Str::lower($this->option('stub')) ?: 'generic';

        // Load a new stub-file based on the users choice
        $this->stubName = 'events/' . $stub . '.stub';

        return [
            'path-parameters' => [
                'section-name' => $this->sectionName,
                'container-name' => $this->containerName,
            ],
            'stub-parameters' => [
                '_section-name' => Str::lower($this->sectionName),
                'section-name' => $this->sectionName,
                '_container-name' => Str::lower($this->containerName),
                'container-name' => $this->containerName,
                'class-name' => $this->fileName,
                'model' => $model,
                '_model' => Str::lower($model),
            ],
            'file-parameters' => [
                'file-name' => $this->fileName,
            ],
        ];
    }
}
