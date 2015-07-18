<?php

namespace Jumilla\Erb2Blade\Console;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Finder\Finder;
use Illuminate\Console\Command;

/**
 * Command: convert .erb to .blade.php.
 *
 * @author Fumio Furukawa <fumio@jumill.me>
 */
class Erb2BladeCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'view:erb2blade';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert .erb to .blade.php.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $view_paths = $this->laravel['config']->get('view.paths');

        // load laravel services
        $finder = Finder::create();

        $generated_count = 0;
        foreach ($view_paths as $view_path) {
            $view_path = rtrim($view_path, '/');

            // recursive find files
            $erb_files = iterator_to_array($finder->name('*.erb')->files()->in($view_path), false);
            foreach ($erb_files as $erb_path) {
                // `-v` enabled
                if ($this->output->isVerbose()) {
                    $this->info(substr($erb_path, strlen($view_path) + 1));
                }

                // convert erb string to blade string
                $blade_content = $this->erb2blade(file_get_contents($erb_path));

                // output .blade.php
                $blade_path = dirname($erb_path).'/'.basename($erb_path, '.erb').'.blade.php';
                file_put_contents($blade_path, $blade_content);

                ++$generated_count;
            }
        }

        $this->line("Generate {$generated_count} files.");
    }

    /**
     * Convert content of `.erb` to `.blade.php`.
     *
     * @param  string  $content
     * @return string
     */
    public function erb2blade($content)
    {
        // '<%# ... %>' => '{{-- --}}'
        $content = preg_replace('/<%# (.+?)[[:space:]]*%>/', '{{-- $1 --}}', $content);

        // '<% render ... %>' => '@include (...)'
        $content = preg_replace('/<%[[:space:]]+render +(.+)[[:space:]]*%>/', '@include ($1)', $content);

        // '<%= ... %>' => '{{ }}'
        $content = preg_replace('/<%= (.+?)[[:space:]]*%>/', '{{ $1 }}', $content);

        // '<% if statement %>' => '@if (statement)'
        $content = preg_replace('/<%[[:space:]]if +(.+)[[:space:]]*%>/', '@if ($1)', $content);

        // '<% unless statement %>' => '@if (!(statement))'
        $content = preg_replace('/<%[[:space:]]unless +(.+)[[:space:]]*%>/', '@if (!($1))', $content);

        // '<% while statement do %>' => '@while (statement)'
        $content = preg_replace('/<%[[:space:]]while +(.+) +do[[:space:]]*%>/', '@while ($1)', $content);

        // '<% statement.each do |value| %>' => '@foreach (statement as $value)'
        $content = preg_replace('/<%[[:space:]]+(.+)\.each +do *\\| *([[:alnum:]]+) *\\|[[:space:]]*%>/', '@foreach ($1 as $2)', $content);

        // '<% statement.each_with_index do |value, key| %>' => '@foreach (statement as $key => $value)'
        $content = preg_replace('/<%[[:space:]]+(.+)\.each_with_index +do *\\| *([[:alnum:]]+) *, *([[:alnum:]]+) *\\|[[:space:]]*%>/', '@foreach ($1 as $3 => $2)', $content);

        // '<% case statement %>' => '<?php switch (statement): >'
        $content = preg_replace('/<%[[:space:]]case +(.+)[[:space:]]*%>/', '<?php switch ($1): ?>', $content);

        // '<% when statement %>' => '<?php case statement: >'
        $content = preg_replace('/<%[[:space:]]when +(.+)[[:space:]]*%>/', '<?php case $1: ?>', $content);

        // '<% else %>' => '@else'
        $content = preg_replace('/<%[[:space:]]+else[[:space:]]*%>/', '@else', $content);

        // '<% elsif statement %>' => '@elseif'
        $content = preg_replace('/<%[[:space:]]+elsif +(.+)[[:space:]]*%>/', '@elseif $1', $content);

        // '<% end %>' => '@end?'
        $content = preg_replace('/<%[[:space:]]+end[[:space:]]*%>/', '@end?', $content);

        // '<% ... %>' => '<?php ... >'
        $content = preg_replace('/<%[[:space:]]+(.+)[[:space:]]*%>/', '<?php $1 ?>', $content);

        return $content;
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
        ];
    }
}
