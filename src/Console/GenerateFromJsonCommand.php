<?php

declare(strict_types=1);

namespace MediaMonks\Muban\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class GenerateFromJsonCommand extends Command
{
    protected function configure()
    {
        $this->setName('muban:from-json');
        $this->addOption('raw', 'r', InputOption::VALUE_NONE, 'The json to generate the component from');
        $this->addOption('file', 'f', InputOption::VALUE_OPTIONAL, 'The json to generate the component from');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        if ($input->hasOption('file') && $input->getOption('file') !== null) {
            if (!file_exists($input->getOption('file'))) {
                $output->writeln('File does not exist.');
                return Command::INVALID;
            }

            $json = json_decode(file_get_contents($input->getOption('file')), true);
        } else if ($input->hasOption('raw')) {
            $helper = $this->getHelper('question');
            $question = new Question('Paste your json ');

            $json = json_decode($helper->ask($input, $output, $question), true);

        } else {
            $output->writeln('Missing json, use the option file or raw see: bin/conosle muban:from-json --help.');
            return Command::INVALID;
        }

        $componentName = $json['component'];
        $parameters = $json['parameters'];
        $nameTokens = explode('-', $componentName);
        $className = sprintf('%s%s%s', strtoupper($nameTokens[0]), strtoupper($nameTokens[1]), ucfirst($nameTokens[2]));

        $classTemplate = file_get_contents(__DIR__ . '/../../templates/component.php.tpl');

        $componentClass = str_replace([
            '[classname]',
            '[componentname]',
            '[properties]',
            '[property_initializers]',
            '[property_accessors]',
        ], [
            $className,
            $componentName,
            $this->generateProperties($parameters),
            $this->generatePropertyInitializers($parameters),
            $this->generatePropertyAccessors($parameters),
        ], $classTemplate);

        $template = file_get_contents(__DIR__ . '/../../templates/component.html.twig.tpl');

        $templateContent = str_replace([
            '[classname]',
            '[component]',
        ], [
            $className,
            (array_key_exists('as', $parameters) ? $this->generateComponent($componentName, $parameters) : ''),
        ], $template);

        $files = [
            __DIR__ . '/../Component/Library/' . $className . '.php' => $componentClass,
            __DIR__ . '/../../templates/component/' . $componentName . '.html.twig' => $templateContent,
        ];

        foreach ($files as $file => $content) {
            file_put_contents($file, $content);
        }

        $output->writeln('');
        $output->writeln('<comment>The following files where created:</comment>');
        foreach ($files as $file => $content) {
            $output->writeln(realpath($file));
        }

        $output->writeln('');
        $output->writeln('<info>You can now further customize the component.</info>');

        return Command::SUCCESS;
    }

    private function generateProperties(array $properties): string
    {
        $str = '';

        $i = 0;
        $defaultProps = ['className', 'id'];

        foreach ($properties as $property => $value) {
            if (in_array($property, $defaultProps)) continue;

            $tab = '';

            if ($i > 0) {
                $tab = '    ';
            }

            $str .= $tab . 'private ' . gettype($value) . ' $' . $property . ';' . PHP_EOL;
            $i++;
        }

        return $str;
    }

    private function generatePropertyInitializers(array $properties): string
    {
        $str = '';

        $i = 0;
        $defaultProps = ['className', 'id'];

        foreach ($properties as $property => $value) {
            if (in_array($property, $defaultProps)) continue;

            $tab = '';

            if ($i > 0) {
                $tab = str_repeat('    ', 2);
            }

            $str .= $tab . '$component->' . $property . ' = $params[' . "'" . $property . "']" . ';' . PHP_EOL;

            $i++;
        }

        return $str;
    }

    private function generatePropertyAccessors(array $properties): string
    {
        $str = '';

        $i = 0;
        $defaultProps = ['className', 'id'];

        foreach ($properties as $property => $value) {
            if (in_array($property, $defaultProps)) continue;

            $tab = '';

            if ($i > 0) {
                $tab = str_repeat('    ', 1);
            }

            $str .= $tab . 'public function get' . ucfirst($property) . '(): ' . gettype($value) . ' {' . PHP_EOL;
            $str .= ($i === 0 ? str_repeat('    ', 2) : '') . $tab . $tab . 'return $this->' . $property . ';' . PHP_EOL;
            $str .= ($i === 0 ? str_repeat('    ', 1) : '') . $tab . '}' . PHP_EOL;
            $str .= PHP_EOL;

            $i++;
        }

        return $str;
    }

    private function generateComponent(string $name, array $properties): string
    {
        $component = '';

        if (array_key_exists('as', $properties)) {
            $component .= '<' . $properties['as'] . ' data-component="' . $name . '"></' . $properties['as'] . '>';
        }

        return $component;
    }
}