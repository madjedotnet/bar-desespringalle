<?php

namespace App\Service;

use Twig\Environment;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\RequestStack;

class Paginator {
    // représente l'entité sur laquelle on travaille, pour la pagination (findBy())
    private $entityClass;
    private $limit = 10;
    private $currentPage = 1;
    private $manager;
    private $twig;
    private $route;
    private $templatePath;

    public function __construct(ObjectManager $manager, Environment $twig, RequestStack $request, $templatePath) {
        $this->manager = $manager;
        $this->twig = $twig;
        $this->route = $request->getCurrentRequest()->attributes->get('_route');
        $this->templatePath = $templatePath;
    }

    public function display() {
        $this->twig->display($this->templatePath, [
            'page' => $this->currentPage,
            'pages' => $this->getPages(),
            'route' => $this->route
        ]);
    }

    public function setTemplatePath($templatePath) {
        $this->templatePath = $templatePath;

        return $this;
    }

    public function getTemplatePath() {
        return $this->templatePath;
    }

    public function setRoute($route) {
        $this->route = $route;

        return $this;
    }

    public function getRoute() {
        return $this->route;
    }

    public function getData() {
        // 1 calcul de l'offset - start
        $offset = $this->currentPage * $this->limit - $this->limit;

        // 2 détermination du repository et trouver les éléments
        $repo = $this->manager->getRepository($this->entityClass);
        $data = $repo->findBy([], [], $this->limit, $offset);

        // 3 renvoyer les éléments
        return $data;
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function getPages() {
        // 1 connaitre le nombre total d'enregistrement
        $repo = $this->manager->getRepository($this->entityClass);
        $total = count($repo->findAll());

        // 2 faire la division
        $pages = ceil($total / $this->limit);

        return $pages;
    }

    public function setLimit($limit) {
        $this->limit = $limit;

        return $this;
    }

    public function getLimit() {
        return $this->limit;
    }

    public function setEntityClass($entityClass) {
        $this->entityClass = $entityClass;

        return $this;
    }

    public function getEntityClass() {
        return $this->entityClass;
    }

    public function setPage($page) {
        $this->currentPage = $page;

        return $this;
    }

    public function getPage() {
        return $this->currentPage;
    }
}