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

require __DIR__ . '/../src/Certime/Loader/Loader.php';
$loader = new \Certime\Loader\Loader(array('Certime' => __DIR__ . '/../src/Certime'));
$loader->register();

$controllerName = \Certime\Filter\File::getSanitizedBasename(INPUT_GET, 'controller');
$controllerName = '\\Certime\\Controller\\' . ucfirst(strtolower($controllerName ?: 'repository'));

$actionName = \Certime\Filter\File::getSanitizedBasename(INPUT_GET, 'action');
$actionName = strtolower($actionName ?: 'index') . 'Action';

if (class_exists($controllerName, true)) {
    $controller = new $controllerName(
        new \Certime\View\Simple(__DIR__ . '/../view'),
        __DIR__ . '/../data'
    );
    if (is_callable(array($controller, $actionName))) {
        call_user_func(array($controller, $actionName));
    }
}
