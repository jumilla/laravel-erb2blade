<?php namespace Jumilla\Erb2Blade\Commands;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Console\Command;
use Symfony\Component\Finder\Finder;

/**
* Command: convert .erb to .blade.php
*
* @author Fumio Furukawa <fumio.furukawa@gmail.com>
*/
class Erb2BladeCommand extends Command {

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
	public function fire()
	{
		$viewPathes = [
			app_path() . '/views',
		];

		// load laravel services
		$files = $this->laravel['files'];
		$finder = Finder::create();

		foreach ($viewPathes as $viewPath) {
			// recursive find files
			$erbFiles = iterator_to_array($finder->name('*.erb')->files()->in($viewPath), false);
			foreach ($erbFiles as $erbPath) {
				$dirname = dirname($erbPath);
				$basename = basename($erbPath);
				$bladePath = $dirname.'/'.preg_replace('/\\..+?\\.erb$/', '.blade.php', $basename);

				// convert erb string to blade string
				$bladeContent = $this->erb2blade(file_get_contents($erbPath));
				if (!$bladeContent) {
					echo 'Error: '.'failed.';
					continue;
				}

				file_put_contents($bladePath, $bladeContent);
//				echo $bladePath, "\n";
			}
		}

		echo 'done', "\n";
	}

	public function erb2blade($content)
	{
		// '<%# ... %>' => '{{-- --}}'
		$content = preg_replace('/<%# (.+?)[[:space:]]*%>/', '{{-- $1 --}}', $content);

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
		$content = preg_replace('/<%[[:space:]]+(else)[[:space:]]*%>/', '@$1', $content);

		// '<% end %>' => '@end'
		$content = preg_replace('/<%[[:space:]]+(end)[[:space:]]*%>/', '@$1', $content);

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
//			['name', InputArgument::REQUIRED, 'Plugin name.'],
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
//			['namespace', null, InputOption::VALUE_OPTIONAL, 'Plugin namespace.', null],
		];
	}

}
