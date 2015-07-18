<?php

class ServiceProviderTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @test
	 */
	public function testSyntax()
	{
		$app = $this->createApplication();

		$provider = new Jumilla\Erb2Blade\ServiceProvider($app);

		$this->assertNotNull($provider);
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
}
