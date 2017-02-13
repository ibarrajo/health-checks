<?php


use Orchestra\Testbench\TestCase;
use Vistik\Checks\DebugModeOffCheck;
use Vistik\Checks\QueueCheck;
use Vistik\HealthCheckServiceProvider;

class HealthCheckRouteTest extends TestCase
{

    protected function getPackageProviders($app)
    {
        return [HealthCheckServiceProvider::class];
    }

    /**
     * @test
     * @group url
     *
     */
    public function can_hit_health_check_url()
    {
        // Given
        $this->app['config']->set('app.debug', false);
        $this->app['config']->set('health.checks', [new DebugModeOffCheck()]);

        // When
        $this->get('_health')->assertJson(['health' => 'ok']);

        // Then
    }

    /**
     * @test
     * @group url
     *
     */
    public function return_500_if_health_checks_failed()
    {
        // Given
        $this->app['config']->set('queue.default', 'database');
        $this->app['config']->set('health.checks', [new QueueCheck()]);

        // When
        $this->get('_health')->assertJson(['health' => 'failed']);

        // Then
    }
}