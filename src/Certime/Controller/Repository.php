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
use Certime\Service\Repository as RepositoryService;

/**
 * @category Certime
 * @package  Certime_Controller
 * @author   Ludovic Fabrèges
 */
class Repository extends AbstractController
{
    public function indexAction()
    {
        $repositoryService = new RepositoryService("{$this->dataDirectory}/repository");
        $this->view->themes = $repositoryService->getThemes();
        $this->view->isEmpty = $repositoryService->isEmpty();
        $this->view->page = 'repository';
        $this->view->render('repository');
    }

    public function showSnippetAction()
    {
        $themeName = FilterFile::getSanitizedBasename(INPUT_GET, 'theme');
        $snippetName = FilterFile::getSanitizedBasename(INPUT_GET, 'snippet');

        $repositoryService = new RepositoryService("{$this->dataDirectory}/repository");
        $snippet = $repositoryService->getSnippet($themeName, $snippetName);

        if (false === $snippet) {
            $this->view->content = "Le snippet demandé n'existe pas.";
            $this->view->render('content', null);
        } else {
            $this->view->theme = $themeName;
            $this->view->snippet = $snippetName;
            $this->view->code = highlight_file($snippet->path, true);
            $this->view->render('snippet', null);
        }
    }

    public function deleteSnippetAction()
    {
        $themeName = FilterFile::getSanitizedBasename(INPUT_GET, 'theme');
        $snippetName = FilterFile::getSanitizedBasename(INPUT_GET, 'snippet');
        $repositoryService = new RepositoryService("{$this->dataDirectory}/repository");
        try {
            $repositoryService->deleteSnippet($themeName, $snippetName);
            $this->view->render('success');
        } catch (\Exception $e) {
            $this->view->errors = array(rtrim($e->getMessage(), '.') . '.');
            $this->view->render('error');
        }
    }
}
