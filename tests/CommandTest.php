<?php

class CommandTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @test
	 */
	public function testSyntax()
	{
		$command = new Jumilla\Erb2Blade\Console\Erb2BladeCommand();
	}
}
