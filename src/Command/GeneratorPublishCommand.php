<?php
/**
 * This file is part of zblogphp-image.
 *
 * @author  chrishyze <chrishyze@gmail.com>
 * @link    https://github.com/chrishyze/zblogphp-image
 * @license https://github.com/chrishyze/zblogphp-image/blob/master/LICENSE
 */

declare(strict_types=1);

namespace ZblogPhpImage\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use ZblogPhpImage\Enum\PhpExtension;
use ZblogPhpImage\Enum\PhpExtensionSuite;

#[AsCommand(
    name: 'generator:publish',
    description: 'Generate dockerfiles for Docker Hub publishing.',
    hidden: false,
    aliases: ['gen:pub']
)]
class GeneratorPublishCommand extends Command
{
    protected string $projectDir;

    protected array $publishConfig;

    protected array $extensionSuite;

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $output->writeln(['', 'Z-BlogPHP Image Generator', 'Generating from preset config...', '']);

        $this->projectDir = dirname(__DIR__, 2);
        $fs = new Filesystem();
        if ($fs->exists($this->projectDir.'/dockerfile')) {
            $fs->remove($this->projectDir.'/dockerfile');
        }
        $fs->mkdir($this->projectDir.'/dockerfile', 0777);

        $this->extensionSuite = [
            PhpExtensionSuite::MySql->value => PhpExtensionSuite::MySql->extensions(),
            PhpExtensionSuite::PgSql->value => PhpExtensionSuite::PgSql->extensions(),
            PhpExtensionSuite::Dev->value => PhpExtensionSuite::Dev->extensions(),
        ];
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $result = $this->initializeConfig($input, $output);
        if ($result !== Command::SUCCESS) {
            return $result;
        }

        $loader = new \Twig\Loader\FilesystemLoader($this->projectDir.'/template');
        $twig = new \Twig\Environment($loader);

        $workflows = $this->generateDockerfile($output, $twig);

        $this->generateWorkflow($output, $twig, $workflows);

        $output->writeln(['', 'Done.']);

        return Command::SUCCESS;
    }

    protected function initializeConfig(InputInterface $input, OutputInterface $output): int
    {
        $configPath = $this->projectDir.'/config/generate-publish.json';
        if (!is_readable($configPath)) {
            $output->writeln('Config file '.$configPath.' is not readable');

            return Command::FAILURE;
        }

        try {
            $configRaw = file_get_contents($configPath);
            if (empty($configRaw)) {
                throw new \Exception();
            }
            $this->publishConfig = json_decode($configRaw, true);
        } catch (\Throwable $e) {
            $output->writeln('Invalid config');

            return Command::FAILURE;
        }

        foreach (array_keys($this->extensionSuite) as $key) {
            if (!isset($this->publishConfig[$key]) || !is_array($this->publishConfig[$key])) {
                continue;
            }
            if (empty($this->publishConfig[$key])) {
                $output->writeln("Extension in config can not be empty: [{$key}]");

                return Command::FAILURE;
            }
            $this->extensionSuite[$key] = $this->publishConfig[$key];
        }
        if (!isset($this->publishConfig['packages']['debian']) || !is_array($this->publishConfig['packages']['debian'])) {
            $this->publishConfig['packages']['debian'] = [];
        }
        if (!isset($this->publishConfig['packages']['alpine']) || !is_array($this->publishConfig['packages']['alpine'])) {
            $this->publishConfig['packages']['alpine'] = [];
        }

        return Command::SUCCESS;
    }

    protected function generateDockerfile(OutputInterface $output, \Twig\Environment $twig): array
    {
        $workflows = [];
        $platforms = ['linux/amd64', 'linux/arm64/v8', 'linux/arm/v7'];
        $excludeArmv7Tags = ['8.2-fpm', '8.2-cli', '8.1-fpm', '8.1-cli'];

        foreach ($this->publishConfig['php']['versions'] as $phpVer) {
            foreach ($this->publishConfig['php']['tags'] as $phpTag) {
                $phpImageTag = "{$phpVer}-{$phpTag}";
                $platformsTemp = $platforms;
                if (in_array($phpImageTag, $excludeArmv7Tags)) {
                    $platformsTemp = array_filter($platformsTemp, function ($platform): bool {
                        return strpos($platform, 'arm/v7') === false;
                    });
                }
                $workflow = [
                    'file' => str_replace('.', '', "publish-{$phpImageTag}"),
                    'name' => "Publish {$phpImageTag}",
                    'platforms' => implode(',', $platformsTemp),
                    'jobs' => [],
                ];

                foreach ($this->extensionSuite as $suite => $extensions) {
                    $dir = "{$this->projectDir}/dockerfile/{$phpVer}/{$phpTag}/{$suite}";
                    $os = strpos($phpTag, 'alpine') !== false ? 'alpine' : 'debian';
                    $packages = '';

                    if (strpos($phpTag, 'alpine') !== false) {
                        if (!empty($this->publishConfig['packages']['alpine'])) {
                            $packages = implode(' ', $this->publishConfig['packages']['alpine']);
                        }
                    } else {
                        if (!empty($this->publishConfig['packages']['debian'])) {
                            $packages = implode(' ', $this->publishConfig['packages']['debian']);
                        }
                    }

                    $extraRunCommands = ['cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini'];
                    if ($suite === 'dev') {
                        $extraRunCommands[0] = 'cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini';
                    }

                    $extensionEnableConf = 'echo -e \'';
                    foreach (PhpExtension::cases() as $extCase) {
                        if (in_array($extCase->value, $extensions)) {
                            $extensionEnableConf .= '\\n'.$extCase->enableConf().'\\n';
                        }
                    }
                    $extensionEnableConf .= '\' >> /usr/local/etc/php/php.ini';
                    $extraRunCommands[] = $extensionEnableConf;

                    $content = $twig->render("Dockerfile.{$os}.twig", [
                        'tag' => $phpImageTag,
                        'packages' => $packages,
                        'extra_run_cmds' => $extraRunCommands,
                    ]);
                    $path = $dir.'/Dockerfile';
                    if (!file_exists($dir)) {
                        mkdir($dir, 0777, true);
                    }
                    file_put_contents($path, $content);
                    $output->writeln('output: '.$path);

                    $thisImageTags = '${{ secrets.DOCKERHUB_USERNAME }}/zblogphp:'.str_replace('-mysql', '', "{$phpImageTag}-{$suite}");
                    if ("{$phpImageTag}" === '8.1-fpm-alpine') {
                        $thisImageTags .= ',${{ secrets.DOCKERHUB_USERNAME }}/zblogphp:latest';
                    }
                    $workflow['jobs'][] = [
                        'name' => str_replace(['.', '-'], ['', '_'], "publish_{$phpImageTag}_{$suite}"),
                        'tags' => $thisImageTags,
                        'dockerfile' => "dockerfile/{$phpVer}/{$phpTag}/{$suite}/Dockerfile",
                    ];
                }

                $workflows[] = $workflow;
            }
        }

        return $workflows;
    }

    protected function generateWorkflow(OutputInterface $output, \Twig\Environment $twig, array $workflows): void
    {
        $output->writeln(['', 'Generating Github Workflow files...']);
        foreach ($workflows as $workflow) {
            $content = $twig->render('publish-docker-hub.twig', $workflow);
            $path = "{$this->projectDir}/.github/workflows/{$workflow['file']}.yml";
            file_put_contents($path, $content);
            $output->writeln('output: '.$path);
        }
    }
}
