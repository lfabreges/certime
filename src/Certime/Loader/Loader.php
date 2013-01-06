<?php

/*
 * This file is part of Certime.
 *
 * Certime is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Certime is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Certime. If not, see <http://www.gnu.org/licenses/>.
 */

namespace Certime\Loader;

require_once __DIR__ . '/LoaderInterface.php';

/**
 * @category Certime
 * @package  Certime_Loader
 * @author   Ludovic Fabrèges
 */
class Loader implements LoaderInterface
{
    /**
     * @var array [namespace, directory]
     */
    protected $namespaces = array();

    /**
     * Construit une instance du chargeur.
     *
     * @param array $namespaces
     *
     * @return void
     */
    public function __construct(array $namespaces)
    {
        foreach ($namespaces as $namespace => $directory) {
            $this->registerNamespace($namespace, $directory);
        }
    }

    /**
     * @see LoaderInterface::autoload()
     */
    public function autoload($className)
    {
        foreach ($this->namespaces as $namespace => $directory) {
            if (0 === strpos($className, $namespace)) {
                $fileName = $directory
                    . '/'
                    . str_replace('\\', '/', substr($className, strlen($namespace)))
                    . '.php'
                ;
                if (file_exists($fileName)) {
                    require_once $fileName;
                }
            }
        }
        return false;
    }

    /**
     * @see LoaderInterface::register()
     */
    public function register()
    {
        spl_autoload_register(array($this, 'autoload'));
    }

    /**
     * Enregistre un espace de nommage.
     *
     * @param string $namespace
     * @param string $directory
     *
     * @return self
     */
    protected function registerNamespace($namespace, $directory)
    {
        $namespace = trim($namespace, '\\');
        $this->namespaces[$namespace] = $directory;
        return $this;
    }
}
