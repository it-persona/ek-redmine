<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class RedmineController extends Controller
{
    /**
     * @var array
     */
    private $issues;

    /**
     * @Route("/", name="homepage")
     * @Template(":default:index.html.twig")
     */
    public function indexAction(): array
    {
        return [];
    }

    /**
     * @Route("/projects", name="projects")
     * @Template(":redmine:projects.html.twig")
     */
    public function projectsAction(): array
    {
        $data = $this->getClient()->project->all();

        return [
            'projects' => $data['projects']
        ];
    }

    /**
     * @Route("/issues", name="all_issues")
     * @Template(":redmine:all_issues.html.twig")
     */
    public function allIssuesAction(): array
    {
        $issues = $this->getClient()->issue->all();

        return [
            'issues'      => $issues['issues'],
            'total_count' => $issues['total_count']
        ];
    }

    /**
     * @Route("/project/{id}/issues", name="issues")
     * @Template(":redmine:issues.html.twig")
     */
    public function issuesAction($id): array
    {
        $issues = $this->getIssuesByProjectId($id);

        return [
            'issues' => $issues
        ];
    }

    /**
     * Helper method for get a redmine client from service container
     *
     * @return \Redmine\Client
     */
    private function getClient(): \Redmine\Client
    {
        return $this->container->get('johnstyle.redmine_client')->client();
    }

    /**
     * Get a issues list by project_id
     *
     * @param $id
     * @return array
     */
    private function getIssuesByProjectId($id): array
    {
        if ($id) {
            $this->issues = $this->getClient()->issue->all(['project_id' => $id]);
        }

        return $this->issues;
    }
}
