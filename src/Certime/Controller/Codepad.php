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

use Certime\Filter\File as FilterFile;
use Certime\Service\Codepad as CodepadService;
use Certime\Service\Repository as RepositoryService;

/**
 * @category Certime
 * @package  Certime_Controller
 * @author   Ludovic Fabrèges
 */
class Codepad extends AbstractController
{
    public function indexAction()
    {
        $repositoryService = new RepositoryService("{$this->dataDirectory}/repository");
        $this->view->themes = $repositoryService->getThemes();
        $this->view->page = 'codepad';
        $this->view->render('codepad');
    }

    public function editAction()
    {
        $themeName = FilterFile::getSanitizedBasename(INPUT_GET, 'theme');
        $snippetName = FilterFile::getSanitizedBasename(INPUT_GET, 'snippet');

        $repositoryService = new RepositoryService("{$this->dataDirectory}/repository");
        $snippet = $repositoryService->getSnippet($themeName, $snippetName);

        if (false !== $snippet) {
            $codepad = new CodepadService("{$this->dataDirectory}/tmp");
            $this->view->theme = $themeName;
            $this->view->snippet = $snippetName;
            $this->view->code = file_get_contents($snippet->path);
            $this->view->result = $codepad->evalCode($this->view->code);
        }

        $this->view->themes = $repositoryService->getThemes();
        $this->view->page = 'codepad';
        $this->view->render('codepad');
    }

    public function evalAction()
    {
        $codepadService = new CodepadService("{$this->dataDirectory}/tmp");
        $this->view->content = $codepadService->evalCode(filter_input(INPUT_GET, 'code'));
        $this->view->render('content', null);
    }

    public function saveAction()
    {
        $errors = array();

        $theme = FilterFile::getSanitizedBasename(INPUT_GET, 'theme');
        $snippet = FilterFile::getSanitizedBasename(INPUT_GET, 'snippet');

        if (empty($snippet)) {
            $errors[] = 'Le nom du snippet doit être renseigné.';
        }
        if (empty($theme)) {
            $errors[] = 'Le thème doit être renseigné.';
        }

        $code = filter_input(INPUT_GET, 'code');

        try {
            $repositoryService = new RepositoryService("{$this->dataDirectory}/repository");
            $repositoryService->saveSnippet($theme, $snippet, $code);
        } catch (\Exception $e) {
            $errors[] = rtrim($e->getMessage(), '.') . '.';
        }

        if (!empty($errors)) {
            $this->view->errors = $errors;
            $this->view->render('error', null);
        }
    }
}
