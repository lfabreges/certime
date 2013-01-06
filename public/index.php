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

$controllerName = filter_input(
    INPUT_GET,
    'controller',
    FILTER_CALLBACK,
    array('options' => 'basename')
);
$controllerName = '\\Certime\\Controller\\'
    . ucfirst(strtolower($controllerName ?: 'index'))
;

$actionName = filter_input(
    INPUT_GET,
    'action',
    FILTER_CALLBACK,
    array('options' => 'basename')
);
$actionName = strtolower($actionName ?: 'index') . 'Action';

$controller = new $controllerName(
    new \Certime\View\View(__DIR__ . '/../view')
);

if (is_callable(array($controller, $actionName))) {
    call_user_func(array($controller, $actionName));
}
