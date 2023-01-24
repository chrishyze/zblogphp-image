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
use ZblogPhpImage\Enum\PhpExtensionSuite;

#[AsCommand(
    name: 'generator:publish',
    description: 'Generate dockerfiles for Docker Hub publishing.',
    hidden: false,
    aliases: ['gen:pub']
)]
class GeneratorPublishCommand extends Command
{
    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln(['', 'Z-BlogPHP Image Generator', 'Generating from preset config...', '']);

        $projectDir = dirname(__DIR__, 2);
        $fs = new Filesystem();
        if ($fs->exists($projectDir.'/dockerfile')) {
            $fs->remove($projectDir.'/dockerfile');
        }
        $fs->mkdir($projectDir.'/dockerfile', 0777);

        $configPath = $projectDir.'/config/generate-publish.json';
        if (!is_readable($configPath)) {
            $output->writeln('Config file '.$configPath.' is not readable');

            return Command::FAILURE;
        }

        try {
            $configRaw = file_get_contents($configPath);
            if (empty($configRaw)) {
                throw new \Exception();
            }
            $config = json_decode($configRaw, true);
        } catch (\Throwable $e) {
            $output->writeln('Invalid config');

            return Command::FAILURE;
        }

        $presetExtensions = [
            PhpExtensionSuite::MySql->value => PhpExtensionSuite::MySql->extensions(),
            PhpExtensionSuite::PgSql->value => PhpExtensionSuite::PgSql->extensions(),
            PhpExtensionSuite::Dev->value => PhpExtensionSuite::Dev->extensions(),
        ];
        foreach (array_keys($presetExtensions) as $key) {
            if (!isset($config[$key]) || !is_array($config[$key])) {
                continue;
            }
            if (empty($config[$key])) {
                $output->writeln("Extension in config can not be empty: [{$key}]");

                return Command::FAILURE;
            }
            $presetExtensions[$key] = $config[$key];
        }
        if (!isset($config['packages']['debian']) || !is_array($config['packages']['debian'])) {
            $config['packages']['debian'] = [];
        }
        if (!isset($config['packages']['alpine']) || !is_array($config['packages']['alpine'])) {
            $config['packages']['alpine'] = [];
        }

        $loader = new \Twig\Loader\FilesystemLoader($projectDir.'/template');
        $twig = new \Twig\Environment($loader);
        foreach ($config['php']['versions'] as $v) {
            foreach ($config['php']['tags'] as $t) {
                foreach ($presetExtensions as $s => $e) {
                    $this->generateDockerfile($output, $projectDir, $config['packages'], $twig, $v, $t, $s, $e);
                }
            }
        }

        $output->writeln(['', 'Done.']);

        return Command::SUCCESS;
    }

    protected function generateDockerfile(OutputInterface $output, string $projectDir, array $packageConf, \Twig\Environment $twig, string $phpVer, string $phpTag, string $suite, array $extensions): void
    {
        $dir = "{$projectDir}/dockerfile/{$phpVer}/{$phpTag}/{$suite}";
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }

        $os = strpos($phpTag, 'alpine') !== false ? 'alpine' : 'debian';
        $packages = '';
        if (strpos($phpTag, 'alpine') !== false) {
            if (!empty($packageConf['alpine'])) {
                $packages = implode(' ', $packageConf['alpine']);
            }
        } else {
            if (!empty($packageConf['debian'])) {
                $packages = implode(' ', $packageConf['debian']);
            }
        }

        $content = $twig->render("Dockerfile.{$os}.twig", [
            'tag' => "{$phpVer}-{$phpTag}",
            'packages' => $packages,
            'extensions' => implode(' ', $extensions),
        ]);
        $path = $dir.'/Dockerfile';
        file_put_contents($path, $content);

        $output->writeln('output: '.$path);
    }
}
