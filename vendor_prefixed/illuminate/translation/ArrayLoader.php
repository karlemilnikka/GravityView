<?php
/**
 * @license MIT
 *
 * Modified by gravityview on 01-December-2022 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace GravityKit\GravityView\Foundation\ThirdParty\Illuminate\Translation;

class ArrayLoader implements LoaderInterface
{
    /**
     * All of the translation messages.
     *
     * @var array
     */
    protected $messages = [];

    /**
     * Load the messages for the given locale.
     *
     * @param  string  $locale
     * @param  string  $group
     * @param  string  $namespace
     * @return array
     */
    public function load($locale, $group, $namespace = null)
    {
        $namespace = $namespace ?: '*';

        if (isset($this->messages[$namespace][$locale][$group])) {
            return $this->messages[$namespace][$locale][$group];
        }

        return [];
    }

    /**
     * Add a new namespace to the loader.
     *
     * @param  string  $namespace
     * @param  string  $hint
     * @return void
     */
    public function addNamespace($namespace, $hint)
    {
        //
    }

    /**
     * Add messages to the loader.
     *
     * @param  string  $locale
     * @param  string  $group
     * @param  array  $messages
     * @param  string|null  $namespace
     * @return $this
     */
    public function addMessages($locale, $group, array $messages, $namespace = null)
    {
        $namespace = $namespace ?: '*';

        $this->messages[$namespace][$locale][$group] = $messages;

        return $this;
    }

    /**
     * Get an array of all the registered namespaces.
     *
     * @return array
     */
    public function namespaces()
    {
        return [];
    }
}
