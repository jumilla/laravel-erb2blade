<?php

class CommandTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function testRun_NoParameter()
    {
        $config = [
            'view' => [
                'paths' => [
                    __DIR__.'/data/1',
                ],
            ],
        ];

        $app = $this->createApplication();

        $app['config'] = new Illuminate\Config\Repository($config);

        $command = new Jumilla\Erb2Blade\Console\Erb2BladeCommand();
        $command->setLaravel($app);

        $this->runCommand($command);
    }

    /**
     * @test
     */
    public function testConversion_comment()
    {
        $command = new Jumilla\Erb2Blade\Console\Erb2BladeCommand();

        $source = '!!<%# comment %>!!';
        $destination = '!!{{-- comment --}}!!';

        $this->assertSame($destination, $command->erb2blade($source));
    }

    /**
     * @test
     */
    public function testConversion_render()
    {
        $command = new Jumilla\Erb2Blade\Console\Erb2BladeCommand();

        $source = '!!<% render path %>!!';
        $destination = '!!@include (path )!!';

        $this->assertSame($destination, $command->erb2blade($source));
    }

    /**
     * @test
     */
    public function testConversion_echo()
    {
        $command = new Jumilla\Erb2Blade\Console\Erb2BladeCommand();

        $source = '!!<%= expression %>!!';
        $destination = '!!{{ expression }}!!';

        $this->assertSame($destination, $command->erb2blade($source));
    }

    /**
     * @test
     */
    public function testConversion_if()
    {
        $command = new Jumilla\Erb2Blade\Console\Erb2BladeCommand();

        $source = '!!<% if statement %>!!';
        $destination = '!!@if (statement )!!';

        $this->assertSame($destination, $command->erb2blade($source));
    }

    /**
     * @test
     */
    public function testConversion_unless()
    {
        $command = new Jumilla\Erb2Blade\Console\Erb2BladeCommand();

        $source = '!!<% unless statement %>!!';
        $destination = '!!@if (!(statement ))!!';

        $this->assertSame($destination, $command->erb2blade($source));
    }

    /**
     * @test
     */
    public function testConversion_else()
    {
        $command = new Jumilla\Erb2Blade\Console\Erb2BladeCommand();

        $source = '!!<% else %>!!';
        $destination = '!!@else!!';

        $this->assertSame($destination, $command->erb2blade($source));
    }

    /**
     * @test
     */
    public function testConversion_elsif()
    {
        $command = new Jumilla\Erb2Blade\Console\Erb2BladeCommand();

        $source = '!!<% elsif statement %>!!';
        $destination = '!!@elseif statement !!';

        $this->assertSame($destination, $command->erb2blade($source));
    }

    /**
     * @test
     */
    public function testConversion_end()
    {
        $command = new Jumilla\Erb2Blade\Console\Erb2BladeCommand();

        $source = '!!<% end %>!!';
        $destination = '!!@end?!!';

        $this->assertSame($destination, $command->erb2blade($source));
    }

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    protected function createApplication()
    {
        require_once __DIR__.'/ApplicationStub.php';

        return new ApplicationStub;
    }

    /**
     * @param  \Illuminate\Console\Command $command
     * @param  array $arguments
     * @return int
     */
    protected function runCommand(Illuminate\Console\Command $command, array $arguments = [])
    {
        $input = new Symfony\Component\Console\Input\ArrayInput($arguments);
        $input->setInteractive(false);

        return $command->run($input, new Symfony\Component\Console\Output\NullOutput);
    }
}
