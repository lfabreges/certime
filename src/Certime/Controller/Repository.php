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

namespace Certime\Controller;

use Certime\Repository\Snippet as SnippetRepository;
use Certime\View\ViewInterface;

/**
 * @category Certime
 * @package  Certime_Controller
 * @author   Ludovic Fabrèges
 */
class Repository extends AbstractController
{
    public function indexAction()
    {
        $snippetRepository = new SnippetRepository($this->directory);
        $this->view->tree = $snippetRepository->getTree();
        $this->view->page = 'repository';
        $this->view->render('repository');
    }

    public function snippetAction()
    {
        $path = filter_input(INPUT_GET, 'path', FILTER_CALLBACK, array('options' => 'urldecode'));
        $this->view->setLayout(null);
        $this->view->content = highlight_file($path, true);
        $this->view->render('content');
    }
}