<?php
/**
 * @license MIT
 *
 * Modified by gravityview on 28-November-2022 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace GravityKit\GravityView\Foundation\ThirdParty\Illuminate\Support\Facades;

/**
 * @see \Illuminate\View\Compilers\BladeCompiler
 */
class Blade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return static::$app['view']->getEngineResolver()->resolve('blade')->getCompiler();
    }
}
