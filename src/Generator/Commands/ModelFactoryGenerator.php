<?php

namespace Nucleus\Generator\Commands;

use Illuminate\Support\Str;
use Nucleus\Generator\GeneratorCommand;
use Nucleus\Generator\Interfaces\ComponentsGenerator;
use Symfony\Component\Console\Input\InputOption;

class ModelFactoryGenerator extends GeneratorCommand implements ComponentsGenerator
{
    /**
     * User required/optional inputs expected to be passed while calling the command.
     * This is a replacement of the `getArguments` function "which reads whenever it's called".
     *
     * @var  array
     */
    public array $inputs = [
        ['model', null, InputOption::VALUE_OPTIONAL, 'The model to generate this Factory for'],
    ];
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'nucleus:generate:factory';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Model Factory class for a given Model';
    /**
     * The type of class being generated.
     */
    protected string $fileType = 'Factory';
    /**
     * The structure of the file path.
     */
    protected string $pathStructure = '{section-name}/{container-name}/Data/Factory/*';
    /**
     * The structure of the file name.
     */
    protected string $nameStructure = '{file-name}';
    /**
     * The name of the stub file.
     */
    protected string $stubName = 'factory.stub';

    public function getUserInputs(): ?array
    {
        $model = $this->checkParameterOrAsk('model', 'Enter the name of the Model to generate this Factory for');

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
